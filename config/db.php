<?php
if(YII_ENV_DEV || YII_ENV == 'dev'){
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=tellus',
        'username' => 'ubuntu',
        'password' => '',
        'charset' => 'utf8',
    ];
}
else if(YII_ENV == 'test'){
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=tellus_tests',
        'username' => 'ubuntu',
        'password' => '',
        'charset' => 'utf8',
    ];
}
else{
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=tellus-main-cluster.cluster-cusw3pjt3qpv.us-east-1.rds.amazonaws.com;dbname=tellus',
        'username' => 'tellus',
        'password' => 'Yux4JFTVdw6y7A7ZQs',
        'charset' => 'utf8',
    ];
}
