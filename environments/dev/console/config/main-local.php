<?php

$config = [
    'bootstrap' => ['gii'],
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
];

$config['components']['urlManager']['baseUrl'] = common\models\GlobalFunctions::BASE_URL; //update using local domain
return $config;
