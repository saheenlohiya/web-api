<?php

namespace app\api\modules\v1\controllers;

use app\filters\TuQueryParamAuth;
use app\models\Venues;


class VenuesController extends TuBaseApiController
{
    // We are using the regular web app modules:
    public $modelClass = 'app\models\Venues';

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => TuQueryParamAuth::className(),
            'except' => ['get-nearby-venues','search-nearby-venues'],
            'optional' => []
        ];
        return $behaviors;
    }

    /**
     * @param $lat
     * @param $lon
     * @param int $radius
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionGetNearbyVenues($lat,$lon,$radius=5){
        return Venues::create()->getNearbyPlaces($lat,$lon,$radius);
    }

    /**
     * @param $keyword
     * @param $lat
     * @param $lon
     * @param int $radius
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionSearchNearbyVenues($keyword,$lat,$lon,$radius=5){
        return Venues::create()->getSearchPlaces($keyword,$lat,$lon,$radius);
    }

}