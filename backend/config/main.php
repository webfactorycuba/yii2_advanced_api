<?php

/**
 * @SWG\Swagger(
 *   info={
 *     "title"="REST API",
 *     "version"="0.0.1"
 *   },
 *   host=API_HOST,
 *   basePath="/v1"
 * )
 *
 * @SWG\SecurityScheme(
 *   securityDefinition="jwt",
 *   description="add 'Bearer ' before jwt token",
 *   type="apiKey",
 *   in="header",
 *   name="Authorization"
 * )
 */

use common\models\ConfigServerConstants;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

$rules = require(__DIR__ . '/rules.php');

return [
    'id' => 'advanced-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'language' => 'es',
    'sourceLanguage' => 'es',
    'modules' => [
        'security' => [
            'class' => 'mdm\admin\Module',
            'controllerMap' => [
                'user' => [
                    'class' => 'backend\controllers\UserController',
                ],
                'route' => [
                    'class' => 'backend\controllers\RouteController',
                ],
                'role' => [
                    'class' => 'backend\controllers\RoleController',
                ],
                'permission' => [
                    'class' => 'backend\controllers\PermissionController',
                ],
                //*** disable routes  for default, menu, permission and rule sections of yii2-admin
                'default' => [
                    'class' => 'backend\controllers\DisableRoutesRbacController',
                ],
                'menu' => [
                    'class' => 'backend\controllers\DisableRoutesRbacController',
                ],
                'rule' => [
                    'class' => 'backend\controllers\DisableRoutesRbacController',
                ],
                'assignment' => [
                    'class' => 'backend\controllers\DisableRoutesRbacController',
                ],

            ],

        ],
	    'gridview' =>  [
		    'class' => '\kartik\grid\Module'
		    // enter optional module parameters below - only if you need to
		    // use your own export download action or custom translation
		    // message source
		    // 'downloadAction' => 'gridview/export/download',
		    // 'i18n' => []
	    ],
        'v1' => [
            'basePath' => '@backend/modules/v1',
            'class' => 'backend\modules\v1\ApiModule',
        ],
    ],
    'as access' => [
	    'class' => 'mdm\admin\components\AccessControl',
	    'allowActions' => [
		    'site/*',
		    'v1/*',
		    //'security/*',
            'notifications/*',
            'security/user/request-password-reset',
            'security/user/reset-password',
		    'gii/*',
		    'debug/*'
	    ]
    ],
    'components' => [
	    'view' => [
		    'theme' => [
			    'pathMap' => [
				    '@vendor/mdmsoft/yii2-admin/views/user' => '@app/views/custom_views_yii2_admin/user',
				    '@vendor/mdmsoft/yii2-admin/views/assignment' => '@app/views/custom_views_yii2_admin/assignment',
				    '@vendor/mdmsoft/yii2-admin/views/item' => '@app/views/custom_views_yii2_admin/item',
				    '@vendor/mdmsoft/yii2-admin/views/route' => '@app/views/custom_views_yii2_admin/route',
			    ],
		    ],
	    ],
        'request' => [
            'csrfParam' => '_csrf-backend',
            'baseUrl' => '',
        ],
        'user' => [
	        'identityClass' => 'common\models\User',
	        'loginUrl' => ['security/user/login'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-session',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    //'levels' => ['error', 'warning'],
                    'logVars' => ['_GET', '_POST'],
                    'categories' => ['WebFactory'],  // Use you App Short Name
                    'logFile' => '@runtime/logs/web_factory.log'
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'baseUrl' => ConfigServerConstants::BASE_URL_BACKEND,  //Real domain
            'showScriptName' => false,
            // Disable r= routes
            'enablePrettyUrl' => true,
            //'suffix' => '.html',
            'enableStrictParsing' => false,
            'rules' => [
//                '' => 'site/index',
//                '<action>'=>'site/<action>',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
//                [
//                    'class'=> 'yii\rest\UrlRule',
//                    'controller' => [
//                          '/v1/controller1',   //Route rest controllers
//                    ],
//                    'pluralize' => false,
//                    'except'=>['action1', 'action2']
//                ],
                'docs' => 'site/docs',
                [
                    'pattern' => 'resource',
                    'route' => 'site/resource',
                    'suffix' => '.json'
                ],
                [
                    'class'=> 'yii\rest\UrlRule',
                    //'prefix' => '/api',
                    'controller' => ['/v1/auth', '/v1/user'],
                    'pluralize' => false,
                    'extraPatterns' => $rules,
                ]
            ]
        ],
//        'mail' => [
//            'class' => 'yii\swiftmailer\Mailer',
//            'viewPath' => '@common/mail',
//        ],
        'mail' => [
            'class' => 'backend\mail\CustomMailer',
            'viewPath' => '@common/mail',
            'enableSwiftMailerLogging' => true,
            'useFileTransport' => false,
        ],
    ],
    'as beforeRequest' => [
	    'class' => 'backend\components\CheckIfLoggedIn'
    ],
    'params' => $params,
];
