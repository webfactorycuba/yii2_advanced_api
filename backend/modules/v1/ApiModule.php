<?php

namespace backend\modules\v1;

/**
 * v1 module definition class
 */
class ApiModule extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\v1\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        // Avoid user session for REST
        \Yii::$app->user->enableSession = false;
    }
}
