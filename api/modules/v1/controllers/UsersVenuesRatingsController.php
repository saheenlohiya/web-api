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
            'except' => ['create', 'user-venue-rating-global'],
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

        $response = \Yii::$app->response;
        $request = \Yii::$app->request;

        $response->format = Response::FORMAT_JSON;

        $acknowledged = UsersVenuesRatings::create()->setToAcknowledged($request->post('user_venue_rating_id'));

        if(!$acknowledged){
            $response->setStatusCode(304);
            return ['error' => 'Could not modify rating'];
        }
        else{
            return $acknowledged;
        }


    }

    public function actionUserVenueRatingGlobal(){

        $response = \Yii::$app->response;
        $request = \Yii::$app->request;
        $response->format = Response::FORMAT_JSON; 
        $acknowledged = UsersVenuesRatings::create()->sendToSupport();
        if(!$acknowledged){
            $response->setStatusCode(304);
            return ['error' => 'Could not post rating'];
        }
        else{
            $response->setStatusCode(200);
            return $acknowledged;
        }


    }
}
