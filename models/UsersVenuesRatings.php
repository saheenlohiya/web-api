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
                [['user_id', 'venue_id'], 'required'],
                [['user_id'], 'hasOpenTicketValidator'],
                [['venue_rating_cat_1', 'venue_rating_cat_2', 'venue_rating_cat_3', 'venue_rating_cat_4', 'venue_rating_cat_4', 'venue_rating_cat_5', 'venue_rating_cat_6'], 'integer', 'min' => 1, 'max' => 5],
            ]
        );
    }

    public function hasOpenTicketValidator()
    {
        if (self::find()->where(['user_id' => $this->user_id, 'venue_id' => $this->venue_id, 'venue_rating_resolved' => 0])->one()) {
            $this->addError('user_id', 'There is an unresolved ticket.');
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
                $this->_setAutoResolution();
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
            $comments .= self::VENUE_RATING_CAT_1.":".$this->venue_rating_cat_1 . "\n" ;
            $comments .= self::VENUE_RATING_CAT_2.":".$this->venue_rating_cat_2 . "\n" ;
            $comments .= self::VENUE_RATING_CAT_3.":".$this->venue_rating_cat_3 . "\n" ;
            $comments .= "Average Rating:".$this->venue_rating_average . "\n\n" ;
            $comments .= "Comments:".$this->venue_rating_comment ;


                UsersVenuesRatingsResponses::create()->respond($this->id, $this->user_id, $comments);
            //send notification fo venue managers
            $this->_notifyVenueManager();
        }

    }

    public function getRatingsByVenue($user_id, $venue_id)
    {
        if (isset($user_id) && isset($venue_id)) {
            return UsersVenuesRatings::find()
                ->where(['user_id' => $user_id, 'venue_id' => $venue_id])
                ->with(['usersVenuesRatingsResponses', 'venue', 'user'])
                ->orderBy(['venue_rating_resolved' => SORT_ASC, 'venue_rating_date' => SORT_DESC])
                ->asArray(true)
                ->all();
        }
    }

    public function getRatingsByUser($user_id)
    {
        if (isset($user_id)) {

            //we will combine both the users own ratings
            //and ratings submitted to the claimed venue

            $unionQuery = "SELECT * FROM (
                                (SELECT * FROM users_venues_ratings uvr1 WHERE uvr1.user_id=:user_id) 
                                 UNION 
                                (SELECT uvr2.* FROM users_venues_ratings uvr2 JOIN venues ON venues.id = uvr2.venue_id WHERE venues.user_id=:user_id )
                            ) u ORDER BY venue_rating_date DESC, venue_rating_resolved";

            //ratings I submitted
            return UsersVenuesRatings::findBySql($unionQuery)
                ->with(['usersVenuesRatingsResponses', 'venue', 'user'])
                ->params(['user_id' => $user_id])
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
    }

    private function _setAutoResolution()
    {
        if (is_null($this->venue_rating_average)) {
            $this->_calcRatingAverage();
        }

        if ($this->venue_rating_average == self::MAX_RATING_AVG) {
            $this->venue_rating_resolved = true;
            $this->venue_rating_date_resolved = date('Y-m-d H:i:s');
        }

        // we need to give 30 days to respond anyway
        $this->venue_rating_resolve_expiration = date('Y-m-d', strtotime(date('Y-m-d') . '+ ' . self::DEFAULT_RESOLUTION_EXP_DAYS . ' days'));

    }

    private function _notifyVenueManager()
    {
        //find the manager
        $venue = Venues::find()->where(['id' => $this->venue_id])->with(['user'])->one();
        if (!is_null($venue)) {
            //see if the venue is claimed
            if (!is_null($venue->user) && count($venue->user) > 0) {
                //send welcome message
                if (!$this->mailer->sendRatingNotification($this, $venue, $venue->user)) {
                    Throw new Exception("Could not send welcome email");
                }
            }
        }
    }

}
