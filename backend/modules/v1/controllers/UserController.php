<?php

namespace backend\modules\v1\controllers;

use common\models\ChangePassword;
use common\models\User;
use Yii;
use yii\helpers\ArrayHelper;


/**
 * Default controller for the `v1` module
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
     * Renders the user profile view for the module
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionProfile()
    {
        return [
            "status" => "200",
            "success" => true,
            "message" => Yii::t("backend", "Usuario encontrado"),
            'user' => User::findOne(Yii::$app->user->getId())->getModelAsJson()
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
                "status" => "200",
                "success" => true,
                "message" => Yii::t("backend", "Su contraseña ha sido cambiada correctamente.")
            ];
        } else {
            return [
                "status" => "422",
                "success" => false,
                "errors" => $model->getErrors(),
                "message" => Yii::t("backend", "Ha ocurrido un error cambiando la contraseña.")
            ];
        }
    }
}
