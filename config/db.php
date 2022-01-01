<?php

$local = [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=wazzup',
    'username' => 'mysql',
    'password' => 'mysql',
    'charset' => 'utf8',
];

$prod = [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=wazzup',
    'username' => 'userflights',
    'password' => 'Maks_123654789',
    'charset' => 'utf8',
];

return $prod;