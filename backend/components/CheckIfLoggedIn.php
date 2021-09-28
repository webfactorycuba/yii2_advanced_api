<?php

namespace backend\components;

use common\models\ConfigServerConstants;
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
		if(\Yii::$app->getRequest()->getCookies()->has(ConfigServerConstants::LANGUAGE_COOKIE_KEY_BACKEND))
		{
			\Yii::$app->language = \Yii::$app->getRequest()->getCookies()->getValue(ConfigServerConstants::LANGUAGE_COOKIE_KEY_BACKEND);
		}
	}
}