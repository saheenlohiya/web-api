<?php

namespace app\api\modules\v1\controllers;

use app\models\Venues;
use yii\rest\ActiveController;

class VenuesController extends ActiveController
{
    // We are using the regular web app modules:
    public $modelClass = 'app\models\Venues';

    public function actionGetNearbyVenues($lat,$lon,$radius=5){
        return Venues::create()->getNearbyPlaces($lat,$lon,$radius);
    }
}