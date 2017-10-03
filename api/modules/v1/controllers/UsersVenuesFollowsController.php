<?php

namespace app\api\modules\v1\controllers;

use app\filters\TuQueryParamAuth;
use app\models\UsersVenuesFollows;
use yii\web\Response;

class UsersVenuesFollowsController extends TuBaseApiController {
    // We are using the regular web app modules:
    public $modelClass = 'app\models\UsersVenuesFollows';

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
     * @return bool
     */
    public function actionUnfollow(){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $params = \Yii::$app->request->post();

        if($this->checkAuthorization($params['user_id'])) {
            if (!is_null($params['user_id']) && !is_null($params['venue_id'])) {
                $result = UsersVenuesFollows::create()->unfollow($params['user_id'], $params['venue_id']);

               return $result;

            }
        }
    }

    /**
     * @param $user_id
     * @return \app\models\UsersVenuesFollows[]|array
     */
    public function actionListByUser($user_id) {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if($this->checkAuthorization($user_id)){
            return UsersVenuesFollows::create()->getVenueFollowsByUser($user_id);
        }
    }
}
