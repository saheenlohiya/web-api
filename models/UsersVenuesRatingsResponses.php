<?php

namespace app\models;

use app\models\base\UsersVenuesRatingsResponses as BaseUsersVenuesRatingsResponses;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "users_venues_ratings_responses".
 */
class UsersVenuesRatingsResponses extends BaseUsersVenuesRatingsResponses {

    //events
    const EVENT_VENUE_RATING_RESPONSE_SUCCESS = 'userVenueRatingResponseSuccess';

    //response keywords
    const RESPONSE_KEYWORD_CLOSE = '#close';

    public static function create() {
        return new self;
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

    public function viewResponses($user_venue_rating_id) {
        //make sure params are not empty and are set
        if (!is_null($user_venue_rating_id)) {
            return UsersVenuesRatingsResponses::find()
                ->where(['user_venue_rating_id' => $user_venue_rating_id])
                ->orderBy(['user_venue_rating_response_date' => SORT_DESC])
                ->asArray(true)
                ->all();
        }

        return false;
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