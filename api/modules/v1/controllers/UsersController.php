<?php

namespace app\api\modules\v1\controllers;

use yii;
use app\models\Users;
use yii\web\Response;

class UsersController extends TuBaseApiController
{
    // We are using the regular web app modules:
    public $modelClass = 'app\models\Users';

    /**
     * Checks to see if the supplied email address exists in the DB
     * @param $email
     * @return array
     */
    public function actionEmailExists($email)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if (!is_null($email) && !empty($email)) {
            $emailExists = Users::find()
                ->where(['user_email' => $email])
                ->exists();

            return ['exists' => $emailExists];
        }
        
    }

    /**
     * Finds the user by email and password and return their account information if found
     * @return array
     * @throws yii\web\UnauthorizedHttpException
     */
    public function actionLogin()
    {

        $params = Yii::$app->request->post();

        if(array_key_exists('user_email',$params) && array_key_exists('user_password',$params)){
            $user = Users::findByEmail($params['user_email']);

            if($user){
                if (Yii::$app->getSecurity()->validatePassword($params['user_password'], $user['user_password'])) {
                    unset($user['user_password']);
                    return $user;
                }
            }
        }

        throw new yii\web\UnauthorizedHttpException('Invalid username or password');

    }
}
