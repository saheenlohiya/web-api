<?php

namespace app\models;

use app\components\Mailer;
use app\models\base\UsersVenuesRatings as BaseUsersVenuesRatings;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "users_venues_ratings".
 * @property-read Mailer $mailer
 */
class UsersVenuesRatings extends BaseUsersVenuesRatings
{

    const MAX_RATING_AVG = 5;
    const DEFAULT_RESOLUTION_EXP_DAYS = 30;
    const VENUE_RATING_CAT_1 = 'Service';
    const VENUE_RATING_CAT_2 = 'Staff';
    const VENUE_RATING_CAT_3 = 'Facility';
    const VENUE_RATING_CAT_4 = 'Not Applicable';
    const VENUE_RATING_CAT_5 = 'Not Applicable';


    /**
     * @return Mailer
     * @throws \yii\base\InvalidConfigException
     */
    protected function getMailer()
    {
        return \Yii::$container->get(Mailer::className());
    }

    public static function create()
    {
        //setup event handlers
        return new self;
    }

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                [
                    'class' => TimestampBehavior::className(),
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['venue_rating_date']
                    ],
                    // using datetime instead of UNIX timestamp:
                    'value' => new Expression('NOW()'),
                ]
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['user_id'], 'default', 'value'=> \Yii::$app->params['anonymousUserId']],
                [['user_id', 'venue_id'], 'required'],
                [['user_id'], 'hasOpenTicketValidator'],
                [['venue_rating_cat_1', 'venue_rating_cat_2', 'venue_rating_cat_3', 'venue_rating_cat_4', 'venue_rating_cat_4', 'venue_rating_cat_5', 'venue_rating_cat_6'], 'integer', 'min' => 1, 'max' => 5],
            ]
        );
    }

    public function hasOpenTicketValidator()
    {
        if($this->user_id != \Yii::$app->params['anonymousUserId']){
            if (self::find()->where(['user_id' => $this->user_id, 'venue_id' => $this->venue_id, 'venue_rating_resolved' => 0])->one()) {
                $this->addError('user_id', 'You currently have an open ticket with this company. Please add to your ongoing conversation using the current ticket.');
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($insert) {
                $this->_calcRatingAverage();
//                $this->_setAutoResolution();
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $comments = "";

        if ($insert) {
            //auto follow
            UsersVenuesFollows::create()->follow($this->user_id, $this->venue_id);

            //auto create thread...but add all the ratings to the thread
            $comments .= self::VENUE_RATING_CAT_1 . ": " . $this->venue_rating_cat_1 . "\n";
            $comments .= self::VENUE_RATING_CAT_2 . ": " . $this->venue_rating_cat_2 . "\n";
            $comments .= self::VENUE_RATING_CAT_3 . ": " . $this->venue_rating_cat_3 . "\n";
            $comments .= "Average Rating: " . number_format((float)$this->venue_rating_average, 2, '.', '') . "\n\n";
            $comments .= "Comments: \n" . $this->venue_rating_comment;


            UsersVenuesRatingsResponses::create()->respond($this->id, $this->user_id, $comments);
            //send notification fo venue managers
            $this->_notifyVenueManager();

            if (is_null($this->venue_rating_average)) {
                $this->_calcRatingAverage();
            }

            // we need to give 30 days to respond anyway
            $this->venue_rating_resolve_expiration = date('Y-m-d', strtotime(date('Y-m-d') . '+ ' . self::DEFAULT_RESOLUTION_EXP_DAYS . ' days'));
        }

    }

    public function getRatingsByVenue($user_id = "", $venue_id)
    {

        $relationships = ['usersVenuesRatingsResponses', 'venue'];
        $where = ['venue_id' => $venue_id];

        if (isset($user_id) && $user_id !== "") {
            $relationships[] = 'user';
            $where['user_id'] = $user_id;
        }

        if (isset($venue_id)) {
            $sql = UsersVenuesRatings::find()
                ->where($where)
                ->with($relationships)
                ->orderBy(['venue_rating_resolved' => SORT_ASC, 'venue_rating_date' => SORT_DESC])
                ->asArray(true);

            return $sql->all();
        }
    }

    public function getRatingsByUser($user_id)
    {
        if (isset($user_id)) {

            //we will combine both the users own ratings
            //and ratings submitted to the claimed venue

            $unionQuery = "SELECT
                          *,
                          (SELECT MAX(uvr.user_venue_rating_response_date) FROM users_venues_ratings_responses uvr WHERE uvr.`user_venue_rating_id` = u.id ) AS last_response_date
                        FROM
                          (
                            (SELECT
                              *
                            FROM
                              users_venues_ratings uvr1
                            WHERE uvr1.user_id = :user_id)
                            UNION
                            (SELECT
                              uvr2.*
                            FROM
                              users_venues_ratings uvr2
                              JOIN venues
                                ON venues.id = uvr2.venue_id
                            WHERE venues.user_id = :user_id)
                          ) u
                        ORDER BY last_response_date DESC";

            //ratings I submitted
            return UsersVenuesRatings::findBySql($unionQuery)
                ->with(['usersVenuesRatingsResponses', 'venue', 'user'])
                ->params([':user_id' => $user_id])
                ->orderBy(['user_venue_rating_response_date' => SORT_DESC])
                ->asArray()
                ->all();
        }
    }

    public function getUsersVenuesRatingsResponses()
    {
        return $this->hasMany(UsersVenuesRatingsResponses::className(), ['user_venue_rating_id' => 'id'])->orderBy(['user_venue_rating_response_date' => SORT_ASC]);
    }

    private function _calcRatingAverage()
    {
        $sum = 0;
        $count = 0;

        //we have 6 categories so lets just do a simple loop to check them and update the average
        //we dont want to use any 0 value cats
        for ($i = 1; $i <= 6; $i++) {
            $cat = 'venue_rating_cat_' . $i;
            if ($this->$cat > 0) {
                $sum += $this->$cat;
                $count++;
            }
        }

        //now calculate the average to save
        $this->venue_rating_average = $sum / $count;
        $this->venue_rating_average = number_format((float)$this->venue_rating_average, 2, '.', '');
    }

    public function setToAcknowledged($user_venue_rating_id)
    {
        $rating = self::findOne($user_venue_rating_id);

        if (!is_null($rating)) {
            $rating->venue_rating_acknowledged = 1;
            $rating->venue_rating_acknowledged_date = date('Y-m-d H:i:s');

            if ($rating->save(FALSE)) return $rating->getAttributes();
        }

        return false;
    }

    private function _notifyVenueManager()
    {
        //find the manager
        $venue = Venues::find()->where(['id' => $this->venue_id])->with(['user'])->one();
        if (!is_null($venue)) {
            //see if the venue is claimed
            if (!is_null($venue->user) && is_array($venue->user) && count($venue->user) > 0) {
                //send welcome message
                if (!$this->mailer->sendRatingNotification($this, $venue, $venue->user)) {
                    Throw new Exception("Could not send welcome email");
                }
            }
        }
    }
    public function sendToSupport() {
        $user = \Yii::$app->user->identity;
        if(!$user) {
            $user = new stdObject();
            $user->user_firstname = 'Anonymous';
            $user->user_email = 'Anonymous';
        }
        $attributes = \yii::$app->request->post();
        if (!$this->mailer->sendRatingNotificationSupport($user, $attributes)) {
            Throw new Exception("Could not send welcome email");
            return false;
        }
        return ['success' => true, 'message'=>'Email succesfully sent'];
    }
}
