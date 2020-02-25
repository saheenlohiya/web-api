<?php

namespace app\models;

use Yii;
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
            $headers = Yii::$app->response->headers;
            $headers->add('X-Pagination-Current-Page', '');
            $headers->add('X-Pagination-Total-Count', '');
            $headers->add('X-Pagination-Page-Count', '');
            $headers->add('X-Pagination-Per-Page', '');
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
                              uvr1.*
                            FROM
                              users_venues_ratings uvr1
                              LEFT JOIN users_venues_claims ON users_venues_claims.venue_id = uvr1.venue_id
                            WHERE uvr1.user_id = :user_id AND users_venues_claims.venue_claim_status = 'active')
                            UNION
                            (SELECT
                              users_venues_ratings.*
                            FROM
                              users_venues_claims uvc
                              JOIN users_venues_ratings
                                ON users_venues_ratings.venue_id = uvc.venue_id
                            WHERE uvc.user_id = :user_id AND uvc.venue_claim_status = 'active')
                          ) u
                        ORDER BY last_response_date DESC";

            //ratings I submitted
            $headers = Yii::$app->response->headers;
            $headers->add('X-Pagination-Current-Page', '');
            $headers->add('X-Pagination-Total-Count', '');
            $headers->add('X-Pagination-Page-Count', '');
            $headers->add('X-Pagination-Per-Page', '');
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

        /* Get Venue Details*/
        $venue = Venues::find()->where(['id' => $this->venue_id]);

        /* Get Venue Manager */ 
        $get_venue_manager_email_query = "SELECT users.user_email FROM `users_venues_claims`,`users` WHERE users.user_role = 'manager' AND users_venues_claims.user_id = users.id AND `venue_id` = ".$this->venue_id;
        $get_venue_manager_emails = Yii::$app->db->createCommand($get_venue_manager_email_query)->execute();

        if(!empty($get_venue_manager_emails))
        {
            /* if multiple Venue Manager Fatch Manager Email */ 
            for ($i=0; $i <count($get_venue_manager_email) ; $i++) 
            { 
                /* Send Rating Notification*/
                if (!$this->mailer->sendRatingNotification_new($this, $venue, $get_venue_manager_email[$i])) {
                        Throw new Exception("Could not send Rating email to vanue Manager");
                }
            }
        }
        else
        {
            Throw new Exception("Could not Found Vanue Manager");
        }

        //find the manager
        // $venue = Venues::find()->where(['id' => $this->venue_id])->with(['user'])->one();
        // if (!is_null($venue)) {
        //     //see if the venue is claimed
        //     if (!is_null($venue->user) && is_array($venue->user) && count($venue->user) > 0) {
        //         //send welcome message
        //         if (!$this->mailer->sendRatingNotification($this, $venue, $venue->user)) {
        //             Throw new Exception("Could not send welcome email");
        //         }
        //     }
        // }
    }
    public function sendToSupport() {
        $user = \Yii::$app->user->identity;
        if(is_null($user)) {
            $user = array();
            $user['user_firstname'] = 'Anonymous';
            $user['user_email'] = 'Anonymous@anonymous.com';
            $user = (object)$user;
        }
        $attributes = \yii::$app->request->post();
        if (!$this->mailer->sendRatingNotificationSupport($user, $attributes)) {
            Throw new Exception("Could not send welcome email");
            return false;
        }
        return ['success' => true, 'message'=>'Email succesfully sent'];
    }
}
