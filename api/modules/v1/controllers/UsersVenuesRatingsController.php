<?php

namespace app\api\modules\v1\controllers;

use app\models\UsersVenuesRatings;
use yii\web\Response;

class UsersVenuesRatingsController extends TuBaseApiController
{
    // We are using the regular web app modules:
    public $modelClass = 'app\models\UsersVenuesRatings';

    public function actionListByVenue($user_id,$venue_id){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return UsersVenuesRatings::create()->getRatingsByVenue($user_id,$venue_id);
    }
}
