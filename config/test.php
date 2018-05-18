<?php
$params = require(__DIR__ . '/params.php');
$dbParams = require(__DIR__ . '/db.php');
$mail = require(__DIR__ . '/mail.php');


/**
 * Application configuration shared by all test types
 */
return [
    'id' => 'basic-tests',
    'basePath' => dirname(__DIR__),    
    'language' => 'en-US',
    'components' => [
        'db' => $dbParams,
        'mailer' => $mail,
        'assetManager' => [            
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
        'user' => [
            'identityClass' => 'app\models\User',
        ],        
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
            // but if you absolutely need it set cookie domain to localhost
            /*
            'csrfCookie' => [
                'domain' => 'localhost',
            ],
            */
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'logFile' => '@app/runtime/logs/tests.log',
                ],
            ],
        ],
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
];
