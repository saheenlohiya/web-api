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

    public static function create(){
        return new self;
    }

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
                [['user_id','venue_name','venue_city','venue_state','venue_zip'],'required']
            ]
        );
    }
}
