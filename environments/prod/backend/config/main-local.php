<?php
use common\models\GlobalFunctions;

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
    ],
];

$config['components']['urlManager']['baseUrl'] = GlobalFunctions::BASE_URL;

return $config;

