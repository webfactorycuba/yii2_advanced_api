<?php
namespace common\models;

use backend\models\settings\Setting;
use mdm\admin\components\UserStatus;
use common\models\User;
use Yii;
use yii\base\Model;

/**
 * @SWG\Definition(
 *   definition="PasswordResetRequest",
 *   type="object",
 *   required={"email"},
 *     allOf={
 *      @SWG\Schema(ref="#/definitions/Success")
 *     }
 * )
 **/

/**
 * Password reset request form
 */
class PasswordResetRequest extends Model
{
    public $email;

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('common','Correo electrónico'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $class = Yii::$app->getUser()->identityClass ? : 'common\models\User';
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => $class,
                'filter' => ['status' => UserStatus::ACTIVE],
                'message' => Yii::t('common','No existe usuario asociado a este correo electrónico'),
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $class = Yii::$app->getUser()->identityClass ? : 'common\models\User';
        $user = $class::findOne([
            'status' => UserStatus::ACTIVE,
            'email' => $this->email,
        ]);

        if ($user)
        {
            $user->scenario = User::SCENARIO_RESET_PASSWORD;

            if (!ResetPassword::isPasswordResetTokenValid($user->password_reset_token))
            {
                $user->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
            }

            if ($user->save())
            {
               $message = Yii::$app->mail->compose(['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'], ['user' => $user])
                ->setTo($user->email)
                ->setFrom(Setting::getEmail())
                ->setSubject(Yii::t('common','Recuperar Contraseña de').' '. Setting::getName());

                try
                {
                    $message->send();
                    return true;
                }
                catch (\Swift_TransportException $e)
                {
                    return false;
                }
            }
        }

        return false;
    }
}
