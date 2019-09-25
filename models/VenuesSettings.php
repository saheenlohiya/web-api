<?php

namespace app\models;

use app\models\base\VenuesSettings as BaseVenuesSettings;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "venues_settings".
 */
class VenuesSettings extends BaseVenuesSettings {

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
