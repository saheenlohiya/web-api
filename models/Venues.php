<?php

namespace app\models;

use Yii;
use \app\models\base\Venues as BaseVenues;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "venues".
 */
class Venues extends BaseVenues
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
