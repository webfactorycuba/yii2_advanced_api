<?php
namespace common\controllers;

use Yii;
use yii\web\Controller;

class BaseController extends Controller
{
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            Yii::$app->response->headers->remove('Server');
            Yii::$app->response->headers->add('Server', "");
            return true;
        }

        return false;
    }
}