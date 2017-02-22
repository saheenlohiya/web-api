<?php

namespace app\models;

use Yii;
use \app\models\base\VenuesAdmins as BaseVenuesAdmins;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "venues_admins".
 */
class VenuesAdmins extends BaseVenuesAdmins
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
