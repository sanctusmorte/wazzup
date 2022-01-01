<?php

use yii\log\FileTarget;

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
            'traceLevel' => 3,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                    'logFile' => '@runtime/logs/console/error.log',
                    'fileMode' => 0777,
                    'dirMode' => 0777,
                ],
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning', 'info'],
                    'logFile' => '@runtime/logs/console/debug.log',
                    'maxLogFiles' => 10,
                    'fileMode' => 0777,
                    'dirMode' => 0777,
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
        'RetailTransportMgServiceComponent' => [
            'class' => app\components\RetailTransportMgServiceComponent::class
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
            'dirMode' => 0777,
            'fileMode' => 0777,
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
