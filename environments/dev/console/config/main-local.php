<?php

$config = [
    'bootstrap' => ['gii'],
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
];

$config['components']['urlManager']['baseUrl'] = \common\models\ConfigServerConstants::BASE_URL_BACKEND_LOCAL; //update using local domain
return $config;
