<?php

namespace app\models;

use Yii;
use \app\models\base\VenuesTypes as BaseVenuesTypes;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "venues_types".
 */
class VenuesTypes extends BaseVenuesTypes
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
