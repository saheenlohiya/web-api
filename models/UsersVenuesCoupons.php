<?php

namespace app\models;

use Yii;
use \app\models\base\UsersVenuesCoupons as BaseUsersVenuesCoupons;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "users_venues_coupons".
 */
class UsersVenuesCoupons extends BaseUsersVenuesCoupons
{

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
}
