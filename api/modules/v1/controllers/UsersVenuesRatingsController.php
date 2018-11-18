<?php

namespace app\api\modules\v1\controllers;

use app\filters\TuQueryParamAuth;
use app\models\UsersVenuesRatings;
use yii\web\Response;

class UsersVenuesRatingsController extends TuBaseApiController {
    // We are using the regular web app modules:
    public $modelClass = 'app\models\UsersVenuesRatings';

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

    /**
     * @param $user_id
     * @param $venue_id
     * @return \app\models\UsersVenuesRatings[]|array
     */
    public function actionListByVenue($user_id="",$venue_id) {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return UsersVenuesRatings::create()->getRatingsByVenue($user_id,$venue_id);
    }

    public function actionListByUser($user_id) {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if($this->checkAuthorization($user_id)) {
            return UsersVenuesRatings::create()->getRatingsByUser($user_id);
        }
    }

    public function actionAcknowledge(){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $params = \Yii::$app->request->post();

        return ['success'=>UsersVenuesRatings::create()->setToAcknowledged($params['user_venue_rating_id'])];
    }
}
