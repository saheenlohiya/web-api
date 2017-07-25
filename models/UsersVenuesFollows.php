<?php

namespace app\models;

use app\models\base\UsersVenuesFollows as BaseUsersVenuesFollows;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "users_venues_follows".
 */
class UsersVenuesFollows extends BaseUsersVenuesFollows {

    //events
    const EVENT_USER_FOLLOW_SUCCESS = 'userFollowSuccess';

    public function extraFields()
    {
        return ['venue'];
    }

    public static function create() {
        return new self;
    }

    public function behaviors() {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                [
                    'class' => TimestampBehavior::className(),
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['user_venue_follow_date'],
                    ],
                    // using datetime instead of UNIX timestamp:
                    'value' => new Expression('NOW()'),
                ],
            ]
        );
    }

    public function rules() {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['user_id', 'venue_id'], 'required'],
                ['user_id', 'unique', 'targetAttribute' => ['user_id', 'venue_id']]

            ]
        );
    }

    public function follow($user_id, $venue_id) {
        //make sure params are not empty
        if (!is_null($user_id) && !is_null($venue_id)) {
            //make sure user is not already following this venue
            if (!UsersVenuesFollows::find()->where(['user_id' => $user_id, 'venue_id' => $venue_id])->exists()) {
                $newFollow = UsersVenuesFollows::create();
                $newFollow->user_id = $user_id;
                $newFollow->venue_id = $venue_id;

                $result = $newFollow->save();

                if ($result) {
                    $this->trigger(self::EVENT_USER_FOLLOW_SUCCESS);
                    return $result;
                }
            }
        }

        return false;
    }

    public function getVenueFollowsByUser($user_id) {
        return UsersVenuesFollows::find()
            ->where(['user_id' => $user_id])
            ->with(['venue.venuesImages'])
            ->orderBy(['user_venue_follow_date' => 'DESC'])
            ->asArray(true)
            ->all();
    }
}
