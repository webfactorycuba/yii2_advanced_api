<?php

namespace backend\components;

use common\models\GlobalFunctions;
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
		if(\Yii::$app->getRequest()->getCookies()->has(GlobalFunctions::LANGUAGE_COOKIE_KEY))
		{
			\Yii::$app->language = \Yii::$app->getRequest()->getCookies()->getValue(GlobalFunctions::LANGUAGE_COOKIE_KEY);
		}
	}
}