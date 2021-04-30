<?php

namespace backend\modules\v1\controllers;

use backend\modules\v1\ApiUtilsFunctions;
use common\models\ChangePassword;
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
     * @throws \Throwable
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\ForbiddenHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionProfile()
    {
        $header = Yii::$app->request->headers->get('authorization', null);
        if(isset($header) && !empty($header)){
            $pieces = explode(" ", $header);
            if(count($pieces) == 2){
                $model = Yii::$app->user->getIdentity();
                if($model->getAuthKeyTest() == $pieces[1]){
                    $model->testAccess = true;
                }
                return ApiUtilsFunctions::getResponseType(
                    ApiUtilsFunctions::TYPE_SUCCESS,
                    Yii::t("backend", "Usuario encontrado"),
                    $model->getModelAsJson()
                );
            }
        }

        return ApiUtilsFunctions::getResponseType(
            ApiUtilsFunctions::TYPE_FORBIDDEN
        );
    }

    /**
     * Allow to change own password for authenticated users
     * @return array
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\ForbiddenHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionChangeOwnPassword()
    {
        $params = $this->getRequestParamsAsArray();

        $model = new ChangePassword();

        $model->oldPassword = ArrayHelper::getValue($params, "oldPassword", null);
        $model->newPassword = ArrayHelper::getValue($params, "newPassword", null);
        $model->retypePassword = ArrayHelper::getValue($params, "retypePassword", null);

        if ($model->change()) {
            return ApiUtilsFunctions::getResponseType(
                ApiUtilsFunctions::TYPE_SUCCESS,
                Yii::t("backend", "Su contraseña ha sido cambiada correctamente.")
            );
        } else {
            return ApiUtilsFunctions::getResponseType(
                ApiUtilsFunctions::TYPE_ERROR,
                Yii::t("backend", "Ha ocurrido un error cambiando la contraseña."),
                $model->getFirstErrors()
            );
        }
    }
}
