<?php

namespace app\models;

use Yii;
use \app\models\base\VenuesCoupons as BaseVenuesCoupons;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "venues_coupons".
 */
class VenuesCoupons extends BaseVenuesCoupons
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
