<?php

namespace backend\components;

use yii\base\Behavior;
use yii\web\Application;

class CheckIfLoggedIn extends Behavior
{
	public function events()
	{
		return [Application::EVENT_BEFORE_REQUEST => 'checkIfLoggedIn'];
	}

	public function checkIfLoggedIn ()
	{
		if(\Yii::$app->getRequest()->getCookies()->has('lang-farming'))
		{
			\Yii::$app->language = \Yii::$app->getRequest()->getCookies()->getValue('lang-farming');
		}
	}
}