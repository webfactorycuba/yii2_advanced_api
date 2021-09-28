<?php

use \kartik\datecontrol\Module;
use common\models\ConfigServerConstants;

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'name'=> ConfigServerConstants::SITE_NAME,
    'language' => ConfigServerConstants::DEFAULT_LANGUAGE,
    'sourceLanguage' => ConfigServerConstants::DEFAULT_LANGUAGE,
    'timeZone' => ConfigServerConstants::TIMEZONE,
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
            // enter optional module parameters below - only if you need to
            // use your own export download action or custom translation
            // message source
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ],
        'notifications' => [
            'class' => 'machour\yii2\notifications\NotificationsModule',
            // Point this to your own Notification class
            // See the "Declaring your notifications" section below
            'notificationClass' => 'common\components\Notification',
            // Allow to have notification with same (user_id, key, key_id)
            // Default to FALSE
            'allowDuplicate' => true,
            // Allow custom date formatting in database
            'dbDateFormat' => 'Y-m-d H:i:s',
            // This callable should return your logged in user Id
            'userId' => function () {
                return Yii::$app->user->id;
            },
        ],
        'datecontrol' =>  [
            'class' => 'kartik\datecontrol\Module',

            // format settings for displaying each date attribute (ICU format example)
            'displaySettings' => [
                Module::FORMAT_DATE => 'php:d-M-Y',
                Module::FORMAT_TIME => 'php:h:i A',
                Module::FORMAT_DATETIME => 'php:d-M-Y H:i',
            ],

            // format settings for saving each date attribute (PHP format example)
            'saveSettings' => [
                Module::FORMAT_DATE => 'php:Y-m-d',
                Module::FORMAT_TIME => 'php:H:i:s',
                Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
            ],

            // set your display timezone
            'displayTimezone' => ConfigServerConstants::TIMEZONE,

            // set your timezone for date saved to db
            'saveTimezone' => 'UTC',

            // automatically use kartik\widgets for each of the above formats
            'autoWidget' => true,

            // default settings for each widget from kartik\widgets used when autoWidget is true
            'autoWidgetSettings' => [
                Module::FORMAT_DATE => [
                    'options' => [
                        'placeholder' => Yii::t('backend','Entre la fecha')
                    ],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'php:d-M-Y',
                        'todayHighlight' => true,
                        'todayBtn' => true,
                    ]
                ],
                Module::FORMAT_DATETIME => [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'php:d-M-Y H:i',
                        'todayHighlight' => true,
                        'todayBtn' => true,
                    ]
                ],
                Module::FORMAT_TIME => [],
            ],
        ]
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
	        'class' => 'yii\rbac\DbManager',
        ],
        'i18n' => [
	        'translations' => [
		        'backend*' => [
			        'class' => 'yii\i18n\DbMessageSource',
                    'forceTranslation' => true
		        ],
                'common*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'forceTranslation' => true
                ],
               // 'frontend*' => [
               //     'class' => 'yii\i18n\DbMessageSource',
               //     'forceTranslation' => true
               // ],
	        ],
        ],
        'formatter' => [
	        'defaultTimeZone' => ConfigServerConstants::TIMEZONE,
	        'dateFormat' => 'php:d-M-Y',
	        'datetimeFormat' => 'php:d-M-Y h:i A',
	        'decimalSeparator' => ',',
	        'thousandSeparator' => ' ',
	        'currencyCode' => '$',
        ],
        // Headers security
        'headers' => [
            'class' => 'common\components\HeaderSecurity',
            'xFrameOptions' => 'SAMEORIGIN',
            'xPoweredBy' => 'WebFactory, Cuba',
        ]
    ],
    'controllerMap' => [
        'console' => [
            'class' => 'console\controllers\ConsoleController',
        ],
        'migration' => [
            'class' => 'bizley\migration\controllers\MigrationController',
        ]
    ],
];
