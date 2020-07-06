<?php
/**
 * Created by PhpStorm.
 * User: jgvaldes
 * Date: 09/07/2018
 * Time: 10:49
 */

namespace backend\modules\v1\controllers;

use common\models\LoginForm;
use common\models\PasswordResetRequest;
use common\models\ResetPassword;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;

use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class AuthController extends ApiController
{
    protected function verbs()
    {
        return [
            'login' => ['POST'],
            'change-own-password' => ['POST'],
            'password-recovery' => ['POST'],
            'password-recovery-receive' => ['POST'],
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'], $actions['index'], $actions['view'], $actions['update']);

        return $actions;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);
        return $behaviors;
    }

    /**
     * Allow to generate access token for users
     * @return array
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionLogin()
    {
        $params = $this->getRequestParamsAsArray();
        return $this->loginUser($params);
    }

    /**
     * Allow to login user using username and password params
     * @param $params
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws NotFoundHttpException
     * @throws BadRequestHttpException
     */
    private function loginUser($params)
    {
        // *********  Check for username:password valid  ********
        if (ArrayHelper::keyExists("username", $params) && ArrayHelper::keyExists("password", $params)) {
            $username = (string)ArrayHelper::getValue($params, 'username');
            $password = (string)ArrayHelper::getValue($params, 'password');

            if (($user = User::findByUsername($username)) !== null) {

                $loginForm = new LoginForm();
                $loginForm->username = $username;
                $loginForm->password = $password;
                if ($loginForm->login()) {

                    $user->generateAuthKey();
                    $user->save(false);
                    Yii::$app->user->logout();

                    return [
                        "statusCode" => "200",
                        "success" => true,
                        "message" => Yii::t('backend', 'Usuario autenticado.'),
                        'result' => $user->getModelAsJson()
                    ];
                } else {
                    return [
                        "statusCode" => "403",
                        "success" => false,
                        "errors" => $loginForm->getFirstErrors(),
                        "message" => Yii::t('backend', 'Credenciales inválidas. Si olvidó sus datos restablezca las credenciales en CocoLeads.')
                    ];
                }

            } else {
                throw new NotFoundHttpException(Yii::t("backend", "Usuario no encontrado."));
            }
        }

        throw new BadRequestHttpException(Yii::t("backend", "Faltan parámetros para ejecutar la consulta."));
    }

    /**
     * Send email for user recovery password
     * @return array
     * @throws NotFoundHttpException
     * @throws BadRequestHttpException
     */
    public function actionPasswordRecovery()
    {
        if (($user = $this->validateUser()) != false) {
            return [
                "statusCode" => "200",
                "success" => false,
                "message" => Yii::t("backend", "Usted ya se encuentra autenticado en el sistema.")
            ];
        }

        $params = $this->getRequestParamsAsArray();

        $model = new PasswordResetRequest();
        $model->email = ArrayHelper::getValue($params, "email", null);

        if (User::findByEmail($model->email) !== null) {
            if ($model->sendEmail()) {
                return [
                    "statusCode" => "200",
                    "success" => true,
                    "message" => Yii::t("backend", "Se enviaron instrucciones a su correo para recuperar la contraseña.")
                ];
            } else {
                if (YII_ENV_DEV) {
                    return [
                        "statusCode" => "400",
                        "success" => false,
                        "token" => User::findByEmail($model->email)->password_reset_token,
                        "message" => Yii::t("backend", "No se pudo enviar el correo.")
                    ];
                } else {
                    throw new BadRequestHttpException(Yii::t("backend", "No se pudo enviar el correo."));
                }

            }
        } else {
            throw new NotFoundHttpException(Yii::t("backend", "No se encontró ningún usuario con ese correo."));
        }
    }

    /**
     * Reset user password using confirmation_token and new credentials
     * @return array
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionPasswordRecoveryReceive()
    {
        if (($user = $this->validateUser()) != false) {
            return [
                "statusCode" => "200",
                "success" => false,
                "message" => Yii::t("backend", "Usted ya se encuentra autenticado en el sistema.")
            ];
        }

        $params = $this->getRequestParamsAsArray();
        $token = ArrayHelper::getValue($params, "token", null);

        $model = new ResetPassword($token);
        $model->password = ArrayHelper::getValue($params, "password", null);
        $model->retypePassword = ArrayHelper::getValue($params, "retypePassword", null);
        $user = User::findByPasswordResetToken($token);
        if ($model->resetPassword()) {
            return $this->loginUser(['username' => $user->username, 'password' => $model->password]);
        } else {
            return [
                "statusCode" => "422",
                "success" => false,
                "errors" => $model->getFirstErrors(),
                "message" => Yii::t("backend", "Ha ocurrido un error cambiando la contraseña.")
            ];
        }
    }

}