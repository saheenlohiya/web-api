<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\UsersVenuesRatingsResponses;
use yii\console\Controller;
use Yii;

class FirebaseMessageUpdaterController extends Controller
{

    public function actionIndex()
    {
        $this->_updateAllFirebaseMessages();
    }

    private function _updateAllFirebaseMessages(){

        //get all feedback responses
        $responses = UsersVenuesRatingsResponses::find()->all();

        if(!is_null($responses)){
            foreach($responses as $response){
                $database = Yii::$app->firebase->getDatabase();
                $reference = $database->getReference('/users_venues_ratings_responses/' . $response->user_venue_rating_id . "/" . $response->id);

                $data = $response->getAttributes();
                $data['user_venue_rating_response_date'] = date(DATE_ISO8601,strtotime($response->user_venue_rating_response_date));

                $data['user'] = $response->userVenueRating->user->getAttributes();
                $data['venue'] = $response->userVenueRating->venue->getAttributes();


                unset($data['user']['user_access_token']);
                unset($data['user']['user_password']);
                unset($data['user']['user_facebook_account_id']);
                unset($data['user']['user_ip_address']);
                unset($data['user']['user_auth_key']);
                unset($data['user']['user_device_token']);
                unset($data['user']['user_is_verified']);

                $reference->set($data);
                echo "Saved response ".$response->id.PHP_EOL;
            }
        }

    }
}
