<?php

namespace app\api\modules\v1\controllers;

use app\filters\TuQueryParamAuth;
use app\models\Users;
use yii;
use yii\web\Response;

class UsersController extends TuBaseApiController
{
    // We are using the regular web app modules:
    public $modelClass = 'app\models\Users';

    public function actions()
    {
        $actions = parent::actions();
        $actions['options'] = [
            'class' => 'app\components\OptionsAction',
        ];
        return $actions;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => TuQueryParamAuth::className(),
            'except' => ['email-exists', 'login', 'create', 'options'],
            'optional' => []
        ];
        // remove authentication filter
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        // add CORS filter
//        $behaviors['corsFilter'] = [
//            'class' => \yii\filters\Cors::className(),
//        ];

        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)

        return $behaviors;
    }

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
     * @return mixed
     * @throws yii\base\InvalidConfigException
     * @throws yii\web\UnauthorizedHttpException
     */
    public function actionLogin()
    {

        $params = Yii::$app->request->post();

        //var_dump($params);

        if (array_key_exists('user_email', $params) && array_key_exists('user_password', $params)) {

            $user = Users::findByEmail($params['user_email']);

            if ($user) {
                if (Yii::$app->getSecurity()->validatePassword($params['user_password'], $user['user_password'])) {
                    return $this->_getUserObject($user);
                }
            }
        }

        throw new yii\web\UnauthorizedHttpException('Invalid username or password');

    }


    /**
     * Get the user's profile information
     * @param $user_access_token
     * @return mixed
     * @throws yii\base\InvalidConfigException
     */
    function actionMe($user_access_token)
    {
        $user = Users::findIdentityByAccessToken($user_access_token);

        if ($user) {
            $me = Users::me($user->id);
            return $this->_getUserObject($me);
        }
    }

    public function actionUpdateDeviceToken($user_access_token, $device_token)
    {
        $user = Users::findIdentityByAccessToken($user_access_token);

        if ($user) {
            $user->user_device_token = $device_token;
            if ($user->save(FALSE)) {
                $me = Users::me($user->id);
                return $this->_getUserObject($me);
            }
        }

    }


    /**
     * Prepare the user's profile object to send back
     * @param $userData
     * @return mixed
     * @throws yii\base\InvalidConfigException
     */
    private function _getUserObject($userData)
    {
        $userData['user_dob'] = Yii::$app->formatter->asDate($userData['user_dob'], 'MM/dd/yyyy');
        unset($userData['user_password']);
        unset($userData['user_auth_key']);
        return $userData;
    }
}
