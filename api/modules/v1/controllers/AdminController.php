<?php

namespace app\api\modules\v1\controllers;


use app\models\UsersVenuesClaims;
use app\models\Venues;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;

class AdminController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\ContentNegotiator',
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ]
            ],
        ];
    }

    public function actionListActiveVenues($only_claimed = false)
    {
        if(!is_numeric($only_claimed)){
            Throw new HttpException(400,"The only_claimed parameter must be a value of either 0 or 1");
        }

        return Venues::create()->listActiveVenues(trim($only_claimed));
    }
}