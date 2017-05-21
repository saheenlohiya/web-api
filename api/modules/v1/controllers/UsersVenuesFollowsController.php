<?php

namespace app\api\modules\v1\controllers;

use app\models\UsersVenuesFollows;
use yii\web\Response;

class UsersVenuesFollowsController extends TuBaseApiController
{
    // We are using the regular web app modules:
    public $modelClass = 'app\models\UsersVenuesFollows';

    public function actionListByUser($user_id){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return UsersVenuesFollows::create()->getVenueFollowsByUser($user_id);
    }
}
