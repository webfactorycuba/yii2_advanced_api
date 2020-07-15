<?php
use common\models\GlobalFunctions;
$config = [
    'bootstrap' => ['gii'],
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
];

$config['components']['urlManager']['baseUrl'] = GlobalFunctions::BASE_URL;
return $config;
