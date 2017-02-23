<?php

namespace app\models;

use Yii;
use \app\models\base\VenuesImages as BaseVenuesImages;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "venues_images".
 */
class VenuesImages extends BaseVenuesImages
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
