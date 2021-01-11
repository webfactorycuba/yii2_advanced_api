<?php

namespace backend\modules\v1\controllers;

use backend\models\settings\Setting;
use backend\models\support\ApiRequestLog;
use common\models\User;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;
use yii\rest\ActiveController;
use yii\web\Response;

/**
 * Api controller for the `v1` module
 */
class ApiController extends ActiveController
{
    public $enableCsrfValidation = false;

    /**
     * @var string Override in all child controller
     */
    public $modelClass = 'none';

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

        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
        header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, DELETE");
        header("Allow: GET, POST, PUT, OPTIONS, DELETE");
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
     * @throws \Exception
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

        try{
            $params = array_merge($params, Yii::$app->request->getQueryParams()); //Postman test mode
        }catch (\Exception $exception){
            // Query Params is not an array
        }


        $raw = Yii::$app->request->getRawBody(); //Postman test mode
        try{
            $raw = json_decode($raw, true);
        }catch (\Exception $e){
            $raw = [];
        }
        try{
            $params = array_merge($params, $raw);
        }catch (\Exception $exception){
            // Raw is not an array
        }


        // Filter XSS Attacks
        try{
            foreach ($params as $key=>$value){
                if(is_array($value)){ // Avoid exception for purifier array
                    foreach ($value as $key2=>$value2){
                        if(!is_array($value)){
                            $value[$key2] = HtmlPurifier::process($value2);
                        }
                    }
                    $params[$key] = $value;
                }else{
                    $params[$key] = HtmlPurifier::process($value);
                }

            }
        }catch (\Exception $exception){
            Yii::error($exception->getMessage(), "WebFactory");
        }

        $this->saveRequestLog($params);
        return $params;
    }

    /**
     * Return the access token provided by request
     * @return array|mixed|string
     * @throws \Exception
     */
    public function getAccessToken()
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
     * @throws \Exception
     */
    public function validateUser()
    {
        $access_token = $this->getAccessToken();
        if (isset($access_token) && !empty($access_token)) {
            return User::findIdentityByAccessToken($access_token);
        }

        return false;
    }
    /**
     * Save a log of any request via API
     * @param $params array
     */
    private function saveRequestLog($params)
    {
        if(Setting::getSaveApiLogs()){
            if(!empty($params)){
                $body = json_encode($params);
            }else{
                $body = "{}";
            }

            if(Yii::$app->request->headers->getCount()> 0){
                $headersArray = [];
                foreach (Yii::$app->request->headers->getIterator() as $item=>$value){
                    $headersArray[$item]=$value;
                }
                $headers = json_encode($headersArray);
            }else{
                $headers = "{}";
            }

            $model = new ApiRequestLog();
            $model->headers = $headers;
            $model->body = $body;
            $model->action_id = $this->action->uniqueId;
            $model-> method = Yii::$app->request->getMethod();
            $model->ip = Yii::$app->request->getUserIP();
            $model->user_agent = Yii::$app->request->getUserAgent();
            $model->save();
        }
    }
    
}
