<?php

namespace app\api\modules\v1\controllers;

use app\filters\TuQueryParamAuth;
use app\models\UsersVenuesRatingsResponses;
use yii\web\Response;

class UsersVenuesRatingsResponsesController extends TuBaseApiController {
    // We are using the regular web app modules:
    public $modelClass = 'app\models\UsersVenuesRatingsResponses';

    /**
     * @return array
     */
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => TuQueryParamAuth::className(),
            'except' => [],
            'optional' => []
        ];
        return $behaviors;
    }

    public function actionViewResponses($user_venue_rating_id) {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return UsersVenuesRatingsResponses::create()->viewResponses($user_venue_rating_id);
    }
}
