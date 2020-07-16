<?php

namespace common\models;

use mdm\admin\components\UserStatus;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;

/**
 * Password reset form
 */
class ResetPassword extends Model
{
    public $password;
    public $retypePassword;
    /**
     * @var User
     */
    private $_user;

    /**
     * Creates a form model given a token.
     *
     * @param  string $token
     * @param  array $config name-value pairs that will be used to initialize the object properties
     * @throws ForbiddenHttpException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new ForbiddenHttpException(Yii::t('common', 'El token de restablecimiento de contraseña no puede estar en blanco.'));
        }
        // check token
        $class = Yii::$app->getUser()->identityClass ?: 'common\models\User';
        if (static::isPasswordResetTokenValid($token)) {
            $this->_user = $class::findOne([
                    'password_reset_token' => $token,
                    'status' => UserStatus::ACTIVE
            ]);
        }
        if (!$this->_user) {
            throw new ForbiddenHttpException(Yii::t('common','Token de restablecimiento de contraseña incorrecto'));
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'retypePassword'], 'required'],
            ['password', 'string', 'min' => 6],
            ['retypePassword', 'compare', 'compareAttribute' => 'password']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'password' => Yii::t('common','Contraseña'),
            'retypePassword' => Yii::t('common','Repetir Contraseña'),
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        return $user->save(false);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = ArrayHelper::getValue(Yii::$app->params, 'user.passwordResetTokenExpire', 24 * 3600);
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }
}
