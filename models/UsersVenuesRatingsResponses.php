<?php

namespace app\models;

use app\components\TUPushNotifications;
use app\components\Twilio;
use app\models\base\UsersVenuesRatingsResponses as BaseUsersVenuesRatingsResponses;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use app\models\Users;
use app\models\UsersVenuesClaims;
use app\models\UsersVenuesRatings;

/**
 * This is the model class for table "users_venues_ratings_responses".
 */
class UsersVenuesRatingsResponses extends BaseUsersVenuesRatingsResponses
{

    //events
    const EVENT_VENUE_RATING_RESPONSE_SUCCESS = 'userVenueRatingResponseSuccess';

    const RESPONSE_KEYWORD_CLOSE = '#close';
    const RESPONSE_NOTIFICATION_APPEND = '';

    public function init()
    {
        parent::init();
        $this->on(self::EVENT_VENUE_RATING_RESPONSE_SUCCESS, [$this, 'notify']);
        $this->on(self::EVENT_VENUE_RATING_RESPONSE_SUCCESS, [$this, 'updateFirebaseDB']);
    }

    public static function create()
    {
        $instance = new self;
        return $instance;
    }

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['user_venue_rating_id', 'user_venue_rating_responding_user_id', 'user_venue_rating_response'], 'required']
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($insert) {
                $this->user_venue_rating_response_read = false;
                $this->user_venue_rating_response_date = date(DATE_ISO8601);
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

        $this->trigger(self::EVENT_VENUE_RATING_RESPONSE_SUCCESS);
        $this->_parseResponseKeywords();
    }


    public function respond($venue_rating_id, $user_id, $response_comment)
    {
        //make sure params are not empty and are set
        if (!is_null($user_id) && !is_null($venue_rating_id) && !is_null($response_comment) && !empty($response_comment)) {
            $newRespond = self::create();
            $newRespond->user_venue_rating_id                 = $venue_rating_id;
            $newRespond->user_venue_rating_responding_user_id = $user_id;
            $newRespond->user_venue_rating_response           = $response_comment;
            if ($newRespond->save()) {
                $newdate = Yii::$app->formatter->asDate($newRespond['user_venue_rating_response_date'],'php:Y-m-d h:i:s');
                ArrayHelper::setValue($newRespond, 'user_venue_rating_response_date', $newdate);
                
                $newresultResponse = Users::find()
                    ->where(['id' => $newRespond['user_venue_rating_responding_user_id']])
                    ->asArray()
                    ->one();
                if(!is_null($newresultResponse)){
                    if($newresultResponse['user_role']== 'user'){
                        $userrole = "Customer";
                    }elseif(($newresultResponse['user_role']== 'manager') && ($newresultResponse['team_manager_id'] > 0) || ($newresultResponse['team_manager_id'] != '')  ){
                        $userrole = "Team Member";
                    }elseif($newresultResponse['user_role']== 'manager'){
                        $userrole = "Manager";
                    }
                }
                
                $getvenuedata = UsersVenuesRatings::find()
                    ->select(['users.user_firstname', 'users.user_email', 'users.user_phone', 'vc.*'])
                    ->leftJoin('users_venues_claims as vc', 'vc.venue_id=users_venues_ratings.venue_id')
                    ->leftJoin('users', 'users.id=vc.user_id')
                    ->where(['users_venues_ratings.id' => $newRespond['user_venue_rating_id'],'vc.venue_claim_status' => 'active'])
                    ->andWhere(['!=', 'users.id', $newRespond['user_venue_rating_responding_user_id']])
                    ->groupBy(['users.id'])
                    ->orderBy(['users_venues_ratings.id' => SORT_DESC])
                    ->asArray(true)
                    ->all();
                
                foreach ($getvenuedata as $reponsedata){
                    Twilio::sendSms($newRespond['user_venue_rating_response'], $reponsedata['user_phone']);
                }
               
                $userdata       = ['user_role'=>$userrole];
                $results        = ArrayHelper::toArray($newRespond);
                $newrecorddata  = ArrayHelper::merge($results, $userdata);
                $newRespond     = (object)$newrecorddata;
                
                return $newRespond;
            }
        }

        return false;
    }

    public function viewResponses($user_venue_rating_id, $user_id) {
        $resultResponse = false;

        //make sure params are not empty and are set
        if (!is_null($user_venue_rating_id)) {
            $ratingdata = UsersVenuesRatings::find()
                    ->select(['users_venues_ratings.id'])
                    ->where(['users_venues_ratings.id' => $user_venue_rating_id, 'users.team_manager_id' => NULL, 'users_venues_claims.venue_claim_status' => 'active'])
                    ->leftJoin('users_venues_claims', 'users_venues_claims.venue_id=users_venues_ratings.venue_id')
                    ->leftJoin('users', 'users.id=users_venues_claims.user_id')
                    ->one();

            if (! empty($ratingdata)) {
                $resultResponse = self::find()
                    ->select(['users_venues_ratings_responses.*', 'IF(users.user_role = "user", "Customer", (IF (users.team_manager_id != "", "Team Member", "Manager"))) as user_role'])
                    ->where(['user_venue_rating_id' => $user_venue_rating_id])
                    ->leftJoin('users', 'users.id=users_venues_ratings_responses.user_venue_rating_responding_user_id')
                    ->orderBy(['id' => SORT_ASC])
                    ->asArray(true)
                    ->all();

                if ($user_id > 0) {
                    $update_query = "update users_venues_ratings_responses set user_venue_rating_response_read='1' where user_venue_rating_responding_user_id !='$user_id' AND user_venue_rating_id ='$user_venue_rating_id'";
                    Yii::$app->db->createCommand($update_query)->execute();
                }
            }
        }

        return $resultResponse;
    }

//    public function setMessagesToRead($user_id,$venue_id,$message_id){
//        if(!is_null($message_id) && !is_null($user_id) && !is_null($venue_id)){
//            //check is the user_id is assigned to the venue
//            $venue = Venues::find()
//                ->where(['id'=>$venue_id,'user_id'=>$user_id])
//                ->one();
//
//            if($venue){
//                //now we can set the message to read
//            }
//        }
//    }

    public function notify()
    {
        //send push notification
        $this->_pushNotify();
    }

    public function updateFirebaseDB()
    {
        $this->_sendResponseToFirebase();
    }

    private function _sendResponseToFirebase()
    {
        $database = Yii::$app->firebase->getDatabase();
        $reference = $database->getReference('/users_venues_ratings_responses/' . $this->user_venue_rating_id . "/" . $this->id);

        $data = $this->getAttributes();
        $data['user'] = $this->userVenueRating->user->getAttributes();
        $data['venue'] = $this->userVenueRating->venue->getAttributes();


        unset($data['user']['user_access_token']);
        unset($data['user']['user_password']);
        unset($data['user']['user_facebook_account_id']);
        unset($data['user']['user_ip_address']);
        unset($data['user']['user_auth_key']);
        unset($data['user']['user_device_token']);
        unset($data['user']['user_is_verified']);

        $reference->set($data);
    }

    private function _pushNotify()
    {
        try {
            //only do stuff if there is an owner
            if ($this->userVenueRating->venue->user_id != null) {
                //get the information for the responding user
                $responding_user = Users::findOne(['id' => $this->user_venue_rating_responding_user_id]);

                //now get the device token
                $owner_device_token = $this->userVenueRating->venue->user->user_device_token;

                //build the append message for the notification
                $append = $responding_user->user_firstname . " responded to a Tell Us thread for " . $this->userVenueRating->venue->venue_name . ", and said: ";

                if (($this->userVenueRating->venue->user_id != $this->user_venue_rating_responding_user_id) && $owner_device_token != null) {
                    TUPushNotifications::create($append . " venue owner: " . $this->user_venue_rating_response, $owner_device_token)
                        ->send();
                } else if ($this->userVenueRating->venue->user_id == $this->user_venue_rating_responding_user_id && ($this->userVenueRating->venue->user_id !== $this->userVenueRating->user_id)) {
                    //lookup the original user who started the thread assuming its not the owner
                    $original_rating_user = Users::findOne(['id' => $this->userVenueRating->user_id]);
                    TUPushNotifications::create($append . $this->user_venue_rating_response, $original_rating_user->user_device_token)
                        ->send();
                }


            }

        } catch (Exception $e) {

        }

    }

    /**
     * will parse any keywords in the response comment
     */
    private function _parseResponseKeywords()
    {
        $comment_string = strtolower($this->user_venue_rating_response);
        $comment_string_array = explode(' ', $comment_string);

        if (in_array(self::RESPONSE_KEYWORD_CLOSE, $comment_string_array)) {
            if ($this->user_venue_rating_responding_user_id == $this->userVenueRating->user_id) {
                $this->userVenueRating->venue_rating_resolved = true;
                $this->userVenueRating->save(FALSE);
            }
        }
    }
    
    
    
    
    
}