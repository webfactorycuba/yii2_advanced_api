<?php

namespace backend\modules\v1\controllers;

use common\models\ChangePassword;
use common\models\User;
use Yii;
use yii\helpers\ArrayHelper;


/**
 * User controller for the `v1` module
 */
class UserController extends ApiController
{
    protected function verbs()
    {
        return [
            'profile' => ['GET'],
            'change-own-password' => ['POST']
        ];
    }

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['create'], $actions['index'], $actions['view'], $actions['update']);

        return $actions;
    }

    /**
     * Renders the user profile
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionProfile()
    {
        return [
            "statusCode" => "200",
            "success" => true,
            "message" => Yii::t("backend", "Usuario encontrado"),
            'result' => User::findOne(Yii::$app->user->getId())->getModelAsJson()
        ];
    }

    /**
     * Allow to change own password for authenticated users
     * @return array
     */
    public function actionChangeOwnPassword()
    {
        $params = $this->getRequestParamsAsArray();

        $model = new ChangePassword();

        $model->oldPassword = ArrayHelper::getValue($params, "oldPassword", null);
        $model->newPassword = ArrayHelper::getValue($params, "newPassword", null);
        $model->retypePassword = ArrayHelper::getValue($params, "retypePassword", null);

        if ($model->change()) {
            return [
                "statusCode" => "200",
                "success" => true,
                "message" => Yii::t("backend", "Su contraseña ha sido cambiada correctamente.")
            ];
        } else {
            return [
                "statusCode" => "422",
                "success" => false,
                "errors" => $model->getErrors(),
                "message" => Yii::t("backend", "Ha ocurrido un error cambiando la contraseña.")
            ];
        }
    }
}
