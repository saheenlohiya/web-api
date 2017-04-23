<?php

namespace app\api\modules\v1\controllers;

use app\models\Venues;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class VenuesController extends TuBaseApiController
{
    // We are using the regular web app modules:
    public $modelClass = 'app\models\Venues';

    private $guestActions = ['get-nearby-venues'];

    public function actionGetNearbyVenues($lat,$lon,$radius=5){
        return Venues::create()->getNearbyPlaces($lat,$lon,$radius);
    }

}