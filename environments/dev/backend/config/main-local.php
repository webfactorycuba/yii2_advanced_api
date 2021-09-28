<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@webroot/themes/adminlte',
                ],
            ],
        ],
        'mail' => [
            'class' => 'backend\mail\CustomMailer',
            'viewPath' => '@common/mail',
            'enableSwiftMailerLogging' => true,
            'useFileTransport' => false,
        ],
    ],
];

$config['components']['urlManager']['baseUrl'] = \common\models\ConfigServerConstants::BASE_URL_BACKEND_LOCAL; //update using local domain

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];
}

if (YII_ENV_DEV) {
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1'],
        'generators' => [ //here
            'crud' => [
                'class' => 'yii\gii\generators\crud\Generator',
                'templates' => [
                    'custom_adminlte' => '@backend/components/custom_gii/templates/crud/simple',
                ]
            ],
            'model' => [
                'class' => 'yii\gii\generators\model\Generator',
                'templates' => [
                    'custom_model_adminlte' => '@backend/components/custom_gii/templates/model/simple',
                ]
            ],
        ],
    ];
}

return $config;
