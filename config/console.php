<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');
$mail = require(__DIR__ . '/mail.php');

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning','info'],
                ],
            ],
        ],
        'mailer' => $mail,
        'db' => $db,
        'firebase' => [
            'class'=>'grptx\Firebase\Firebase',
            'credential_file'=>dirname(__FILE__).'/service_account.json', // (see https://firebase.google.com/docs/admin/setup#add_firebase_to_your_app)
            'database_uri'=>'https://tellus-dev.firebaseio.com', // (optional)
        ],
        'fcm' => [
            'class' => 'understeam\fcm\Client',
            'apiKey' => 'AIzaSyDyOtE4I9elAGpAAPrxPOzChqTM6k7Z_do',
        ],
    ],
    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
