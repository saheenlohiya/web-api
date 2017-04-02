<?php

namespace app\api\modules\v1\controllers;

use app\models\Users;
use yii\rest\ActiveController;
use yii\web\Response;

class UsersController extends ActiveController
{
    // We are using the regular web app modules:
    public $modelClass = 'app\models\Users';

    public function actionEmailExists($email){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if(!is_null($email) && !empty($email)){
           $emailExists = Users::find()
                ->where( [ 'user_email' => $email ] )
                ->exists();

            return ['exists'=>$emailExists];
        }
    }
}
