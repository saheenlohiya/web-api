<?php

namespace app\models;

use Yii;
use \app\models\base\Venues as BaseVenues;
use yii\helpers\ArrayHelper;
use app\components\behaviors\GeocodeBehavior;

/**
 * This is the model class for table "venues".
 */
class Venues extends BaseVenues
{

    public static function create()
    {
        return new self;
    }

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                [
                    'class' => GeocodeBehavior::className(),

                    'address' => [
                        'street_address' => $this->venue_address_1,
                        'postal_code' => $this->venue_zip
                    ],
                    'latitudeAttribute' => 'venue_lat',
                    'longitudeAttribute' => 'venue_lon'

                ]
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['user_id', 'venue_name', 'venue_email', 'venue_address_1', 'venue_city', 'venue_state', 'venue_zip', 'venue_type_id'], 'required']
            ]
        );
    }
}
