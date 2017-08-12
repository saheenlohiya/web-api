<?php

namespace app\api\modules\v1\controllers;

use app\filters\TuQueryParamAuth;
use app\models\Venues;


class VenuesController extends TuBaseApiController {
    // We are using the regular web app modules:
    public $modelClass = 'app\models\Venues';

    public function actions() {
        $actions = parent::actions();
        unset($actions['view']);
        return $actions;
    }

    /**
     * @return array
     */
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => TuQueryParamAuth::className(),
            'except' => ['get-nearby-venues', 'search-nearby-venues', 'view'],
            'optional' => []
        ];
        return $behaviors;
    }

    /**
     * @param $lat
     * @param $lon
     * @param int $radius
     * @param int $limit
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionGetNearbyVenues($lat, $lon, $radius = 5, $limit = 20) {
        return Venues::create()->getNearbyPlaces($lat, $lon, $radius,$limit);
    }

    /**
     * @param $keyword
     * @param $lat
     * @param $lon
     * @param int $radius
     * @param int $limit
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionSearchNearbyVenues($keyword, $lat, $lon, $radius = 5, $limit = 20) {
        return Venues::create()->getSearchPlaces($keyword, $lat, $lon, $radius, $limit);
    }

    public function actionView($id) {
        return Venues::create()->venue($id);
    }

}