<?php

namespace backend\modules\v1\controllers;

use common\models\User;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;
use yii\rest\ActiveController;
use yii\web\Response;

/**
 * Default controller for the `api` module
 */
class ApiController extends ActiveController
{
    public $enableCsrfValidation = false;

    /**
     * @var string Override in all child controller
     */
    public $modelClass = 'none';

    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD'],
            'view' => ['GET'],
        ];
    }

    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    /**
     * Format response to JSON
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator'] = [
            'class' => 'yii\filters\ContentNegotiator',
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ]
        ];

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];

//        $behaviors['corsFilter'] = [
//            'class' => \yii\filters\Cors::className(),
//            'cors' => [
//                'Origin'                           => ["*"],
//                'Access-Control-Request-Method'    => ['POST', 'GET', 'OPTIONS'],
//                'Access-Control-Allow-Credentials' => false,
//                'Access-Control-Max-Age'           => 3600,
//                'Access-Control-Request-Headers' => ["*"],
//                'Access-Control-Allow-Origin' => ["*"],
//            ],
//        ];

        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Allow: GET, POST, OPTIONS");
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "OPTIONS") {
            die();
        }

        return $behaviors;
    }

    /**
     * @param string $action
     * @param null $model
     * @param array $params
     * @return bool|User|null|\yii\web\IdentityInterface
     * @throws \yii\web\ForbiddenHttpException
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        return $this->validateUser();
    }

    /**
     * Returns an array with request params send via POST, GET or RawBody
     * @return array|mixed|string
     */
    public function getRequestParamsAsArray()
    {
        $params = Yii::$app->request->post(); // Production mode

        if(!isset($params) || empty($params)){
            $params = Yii::$app->request->getQueryParams();  //Postman test mode
        }

        if(!isset($params) || empty($params)){
            $params = Yii::$app->request->getRawBody();  //Postman test mode
            try{
                $params = json_decode($params, true);

            }catch (\Exception $e){
                $params = [];
            }
        }
        if(!isset($params) || empty($params)){
            $params = [];
        }

        return $params;
    }

    /**
     * Return the access token provided by request
     * @return array|mixed|string
     */
    private function getAccessToken()
    {
        $access_token = Yii::$app->request->headers->get('access_token', null);
        if (!isset($access_token) || empty($access_token)) {
            $access_token = Yii::$app->request->headers->get('auth_key', null);
        }

        if (!isset($access_token) || empty($access_token)) {
            $access_token = Yii::$app->request->post('access_token', null);
        }
        if (!isset($access_token) || empty($access_token)) {
            $access_token = Yii::$app->request->post('auth_key', null);
        }
        if (!isset($access_token) || empty($access_token)) {
            $access_token = Yii::$app->request->getQueryParam('access_token', null);
        }
        if (!isset($access_token) || empty($access_token)) {
            $access_token = Yii::$app->request->getQueryParam('auth_key', null);
        }
        if (!isset($access_token) || empty($access_token)) {
            $raw_body = Yii::$app->request->getRawBody();
            $raw_body_array = json_decode($raw_body, true);
            $access_token = ArrayHelper::getValue($raw_body_array, "access_token", null);
            if (!isset($access_token) || empty($access_token)) {
                $access_token = ArrayHelper::getValue($raw_body_array, "auth_key", null);
            }
        }

        return HtmlPurifier::process($access_token);
    }

    /**
     * Return user if access token is valid
     * @return bool|null|User|\yii\web\IdentityInterface
     */
    public function validateUser()
    {
        $access_token = $this->getAccessToken();
        if (isset($access_token) && !empty($access_token)) {
            return User::findIdentityByAccessToken($access_token);
        }

        return false;
    }
    
}
