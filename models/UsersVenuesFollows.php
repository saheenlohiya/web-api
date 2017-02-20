<?php

namespace app\models;

use Yii;
use \app\models\base\UsersVenuesFollows as BaseUsersVenuesFollows;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "users_venues_follows".
 */
class UsersVenuesFollows extends BaseUsersVenuesFollows
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
