<?php

namespace app\models;

use Yii;
use \app\models\base\Users as BaseUsers;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "users".
 */
class Users extends BaseUsers
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
