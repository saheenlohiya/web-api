<?php
$db = require(__DIR__ . '/../../config/db.php');
$mail = require(__DIR__ . '/../../config/mail.php');
$params = require(__DIR__ . '/../../config/params.php');

$config = [
    'id' => 'basic',
    'name' => 'TimeTracker',
    // Need to get one level up:
    'basePath' => dirname(__DIR__) . '/..',
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'enableCookieValidation' => true,
            'enableCsrfValidation' => true,
            'cookieValidationKey' => 'ae1a11e90a45f0a32c2677af47378c6c',
            // Enable JSON Input:
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    // Create API log in the standard log dir
                    // But in file 'api.log':
                    'logFile' => '@app/runtime/logs/api.log',
                ],
            ],
        ],
        'mailer' => $mail,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule', 'controller' => ['v1/users'],
                    'extraPatterns' => [
                        'GET email-exists' => 'email-exists',
                        'GET me' => 'me',
                        'GET update-device-token' => 'update-device-token',
                        'POST login' => 'login',
                        'POST update-profile' => 'update-profile'
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule', 'controller' => ['v1/venues'],
                    'extraPatterns' => [
                        'GET get-nearby-venues' => 'get-nearby-venues',
                        'GET search-nearby-venues' => 'search-nearby-venues'
                    ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => ['v1/users-venues-follows'],
                    'extraPatterns' => [
                        'GET list-by-user' => 'list-by-user',
                        'POST unfollow' => 'unfollow',
                    ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => ['v1/users-venues-ratings'],
                    'extraPatterns' => [
                        'GET list-by-venue' => 'list-by-venue',
                        'GET list-by-user' => 'list-by-user',
                        'POST acknowledge' =>'acknowledge',
                        'POST user-venue-rating-global' =>'user-venue-rating-global'
                    ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => ['v1/users-venues-ratings-responses'],
                    'extraPatterns' => [
                        'GET view-responses' => 'view-responses'
                    ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => ['v1/users-venues-claims'],
                    'extraPatterns' => [
                        'GET claim' => 'claim'                    ],
                ]
            ],
        ],
        'db' => $db,
        'firebase' => [
            'class'=>'grptx\Firebase\Firebase',
            'credential_file'=>__DIR__ . '/../../config/service_account.json', // (see https://firebase.google.com/docs/admin/setup#add_firebase_to_your_app)
            'database_uri'=>'https://tellus-live.firebaseio.com', // (optional)
        ],
        'fcm' => [
            'class' => 'understeam\fcm\Client',
            'apiKey' => 'AIzaSyDyOtE4I9elAGpAAPrxPOzChqTM6k7Z_do',
        ],
        'user' => [
            'identityClass' => 'app\models\Users',
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null
        ],
    ],
    'modules' => [
        'v1' => [
            'class' => 'app\api\modules\v1\ApiModule',
        ],
    ],
    'params' => $params,
];

return $config;