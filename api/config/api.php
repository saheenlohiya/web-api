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
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule', 'controller' => ['v1/users'],
                    'extraPatterns' => [
                        'GET email-exists' => 'email-exists',
                        'GET me' => 'me',
                        'POST login' => 'login',
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
                    ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => ['v1/users-venues-ratings'],
                    'extraPatterns' => [
                        'GET list-by-venue' => 'list-by-venue',
                        'GET list-by-user' => 'list-by-user',
                    ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => ['v1/users-venues-ratings-responses'],
                    'extraPatterns' => [],
                ],
            ],
        ],
        'db' => $db,
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