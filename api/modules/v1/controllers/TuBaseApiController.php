<?php
namespace app\api\modules\v1\controllers;


use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

class TuBaseApiController extends ActiveController
{


//    public function behaviors()
//    {
//        $behaviors = parent::behaviors();
//        $behaviors['authenticator'] = [
//            'class' => QueryParamAuth::className(),
//        ];
//        return $behaviors;
//    }

}