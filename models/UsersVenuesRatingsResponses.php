<?php

namespace app\models;

use Yii;
use \app\models\base\UsersVenuesRatingsResponses as BaseUsersVenuesRatingsResponses;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "users_venues_ratings_responses".
 */
class UsersVenuesRatingsResponses extends BaseUsersVenuesRatingsResponses
{

    //events
    const EVENT_VENUE_RATING_RESPONSE_SUCCESS = 'userVenueRatingResponseSuccess';

    public static function create()
    {
        return new self;
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
                # custom validation rules
            ]
        );
    }

    public function respond($venue_rating_id, $user_id, $response_comment)
    {
        //make sure params are not empty and are set
        if (!is_null($user_id) && !is_null($venue_rating_id) && !is_null($response_comment) && !empty($response_comment)) {
            $newResponse = self::create();
            $newResponse->user_venue_rating_id = $venue_rating_id;
            $newResponse->user_venue_rating_responding_user_id = $user_id;
            $newResponse->user_venue_rating_response = $response_comment;

            $result = $newResponse->save();

            if ($result) {
                $this->trigger(self::EVENT_VENUE_RATING_RESPONSE_SUCCESS);
                return $result;
            }
        }

        return false;
    }

    public function viewResponses($user_id,$user_venue_rating_id){
        //make sure params are not empty and are set
        if (!is_null($user_id) && !is_null($user_venue_rating_id)) {
                 var_dump(UsersVenuesRatingsResponses::find()
                    ->where(['user_venue_rating_responding_user_id' => $user_id,'user_venue_rating_id'=>$user_venue_rating_id])
                    ->orderBy(['user_venue_rating_response_date' => SORT_DESC])
                    ->asArray(true)
                    ->all());
        }

        return false;
    }
}
