<?php

namespace app\models;

use app\components\TUPushNotifications;
use app\models\base\UsersVenuesRatingsResponses as BaseUsersVenuesRatingsResponses;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "users_venues_ratings_responses".
 */
class UsersVenuesRatingsResponses extends BaseUsersVenuesRatingsResponses {

    //events
    const EVENT_VENUE_RATING_RESPONSE_SUCCESS = 'userVenueRatingResponseSuccess';

    //response keywords
    const RESPONSE_KEYWORD_CLOSE = '#close';

    private $userDeviceToken = null;

    public static function create() {
        $instance = new self;
        $instance->on(self::EVENT_VENUE_RATING_RESPONSE_SUCCESS, [$instance, 'notify']);

        return $instance;
    }

    public function behaviors() {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    public function rules() {
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
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);

        $this->_parseResponseKeywords();
    }


    public function respond($venue_rating_id, $user_id, $response_comment) {
        //make sure params are not empty and are set
        if (!is_null($user_id) && !is_null($venue_rating_id) && !is_null($response_comment) && !empty($response_comment)) {

            $this->user_venue_rating_id = $venue_rating_id;
            $this->user_venue_rating_responding_user_id = $user_id;
            $this->user_venue_rating_response = $response_comment;

            $result = $this->save();

            if ($result) {
                $this->trigger(self::EVENT_VENUE_RATING_RESPONSE_SUCCESS);
                return $result;
            }
        }

        return false;
    }

    public function viewResponses($user_venue_rating_id) {
        //make sure params are not empty and are set
        if (!is_null($user_venue_rating_id)) {
            return self::find()
                ->where(['user_venue_rating_id' => $user_venue_rating_id])
                ->orderBy(['user_venue_rating_response_date' => SORT_DESC])
                ->asArray(true)
                ->all();
        }

        return false;
    }

    public function notify() {
        //send push notification
        $this->_pushNotify();
    }

    private function _pushNotify() {
        try {
            $this->userDeviceToken = $this->userVenueRating->user->user_device_token;
            TUPushNotifications::create($this->user_venue_rating_response, '205FB895CF44611E7D1B24609238355399FA303810804C8D819499C2EEA287ED')
                ->send();
        } catch (Exception $e) {

        }

    }

    /**
     * will parse any keywords in the response comment
     */
    private function _parseResponseKeywords() {
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