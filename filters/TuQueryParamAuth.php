<?php

namespace app\filters;


use yii\filters\auth\QueryParamAuth;

class TuQueryParamAuth extends QueryParamAuth
{
    public $tokenParam = 'user_access_token';

}