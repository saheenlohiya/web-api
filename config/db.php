<?php
if(YII_ENV_DEV || YII_ENV == 'dev' || YII_ENV == 'test'){
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=tellus-main-cluster.cluster-cusw3pjt3qpv.us-east-1.rds.amazonaws.com;dbname=tellus_tests',
        'username' => 'tellus',
        'password' => 'Yux4JFTVdw6y7A7ZQs',
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

