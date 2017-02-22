<?php
if(YII_ENV_DEV || YII_ENV == 'dev' || YII_ENV == 'test'){
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=tellus_tests',
        'username' => 'root',
        'password' => 'password',
        'charset' => 'utf8',
    ];
}
else{
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=tellus',
        'username' => 'root',
        'password' => 'password',
        'charset' => 'utf8',
    ];
}

