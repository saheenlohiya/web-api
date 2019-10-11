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

    public function actionViewResponses($user_venue_rating_id, $user_id = 0) {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return UsersVenuesRatingsResponses::create()->viewResponses($user_venue_rating_id, $user_id);
    }
    
     public function actionSubmitResponses($user_id, $venue_rating_id, $user_venue_rating_responding_user_id, $response_comment) {
        if($this->checkAuthorization($user_id)){
            return UsersVenuesRatingsResponses::create()->respond($venue_rating_id, $user_venue_rating_responding_user_id, $response_comment);
        }
    }
    
    
    
    

}
