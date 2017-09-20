<?php

namespace app\api\modules\v1\controllers;


use app\models\Users;
use yii\rest\ActiveController;

class TuBaseApiController extends ActiveController {

    protected function checkAuthorization($user_id){
        if(isset($_GET['user_access_token'])){
            $user = Users::findIdentityByAccessToken($_GET['user_access_token']);

            if($user->id == $user_id){
               return true;
            }
        }

        return false;
    }

}