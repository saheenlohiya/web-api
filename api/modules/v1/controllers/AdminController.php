<?php

namespace app\api\modules\v1\controllers;

use app\filters\TuQueryParamAuth;
use app\models\UsersVenuesClaims;
use app\models\Venues;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;

class AdminController extends Controller
{
    public function behaviors()
    {
        
        $behaviors = parent::behaviors();
        $behaviors[] = [
            'class' => 'yii\filters\ContentNegotiator',
            'formats' => [
                'application/json' => Response::FORMAT_JSON
            ]
        ];
        $behaviors['authenticator'] = [
            'class' => TuQueryParamAuth::className(),
            'except' => ['approve-claim'],
            'optional' => []
        ];
        return $behaviors;
    }

    public function actionListActiveVenues($only_claimed = false)
    {
        if(!is_numeric($only_claimed)){
            Throw new HttpException(400,"The only_claimed parameter must be a value of either 0 or 1");
        }

        return Venues::create()->listActiveVenues(trim($only_claimed));
    }

    public function actionListPendingClaimVenues()
    {
        return Venues::create()->listPendingClaimedVenues();
    }

    public function actionApproveClaim($approved = false, $claim_hash, $claim_code){
        return UsersVenuesClaims::create()->approveClaim($approved, $claim_hash, $claim_code);
    }
}