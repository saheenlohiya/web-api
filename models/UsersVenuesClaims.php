<?php

namespace app\models;

use app\models\base\UsersVenuesClaims as BaseUsersVenuesClaims;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "users_venues_claims".
 */
class UsersVenuesClaims extends BaseUsersVenuesClaims {

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
                # custom validation rules
            ]
        );
    }
}
