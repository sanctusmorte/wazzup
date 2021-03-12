<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'queue'
    ],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
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
        'retail' => [
            'class' => app\components\Retail::class
        ],
        'wazzup' => [
            'class' => app\components\Wazzup::class,
        ],
        'transport' => [
            'class' => app\components\RetailTransportMg::class
        ],
        'retailTransportMgService' => [
            'class' => app\components\RetailTransportMgService::class
        ],
        'queue' => [
            'class' => \yii\queue\file\Queue::class,
            //'path' => '@app/runtime/queue',
            'as log' => \yii\queue\LogBehavior::class,
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
