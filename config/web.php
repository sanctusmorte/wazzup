<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$routes = require __DIR__ . '/routes.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'name' => 'Wazzup',
    'language' => 'ru-RU',
    'bootstrap' => [
        'log',
        'queue'
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'jfsbkjsbfdtqsmlskmxzkjbgfsdhjgfajds',
            'enableCsrfValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser'
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'sergeymakinen\yii\telegramlog\Target',
                    'token' => '1681007089:AAEUAha-oFHjhXa8qA0sjhDF03FmS93sPtE',
                    'levels' => ['error', 'info', 'warning'],
                    'categories' => ['wazzup_telegram_log'],
                    'chatId' => 90187076,
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => $routes,
        ],
        'retail' => [
            'class' => app\components\Retail::class
        ],
        'wazzup' => [
            'class' => app\components\Wazzup::class,
        ],
        'transport' => [
            'class' => app\components\RetailTransportMg::class
        ],
        'RetailTransportMgServiceComponent' => [
            'class' => app\components\RetailTransportMgServiceComponent::class,
        ],
        'wazzupServiceComponent' => [
            'class' => app\components\WazzupServiceComponent::class
        ],
        'queue' => [
            'class' => \yii\queue\file\Queue::class,
            'path' => '@app/runtime/queue',
            'as log' => \yii\queue\LogBehavior::class,
            'ttr' => 5 * 60, // Max time for job execution
            'attempts' => 10, // Max number of attempts
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
