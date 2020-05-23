<?php

namespace common\models;


use Yii;
use yii\base\Model;

/**
 *ChangePassword
 */
class ChangePassword extends Model
{
    public $oldPassword;
    public $newPassword;
    public $retypePassword;

    /**
     * rules
     */
    public function rules()
    {
        return [
            [['oldPassword', 'newPassword', 'retypePassword'], 'required'],
            [['oldPassword'], 'validatePassword'],
            [['newPassword'], 'string', 'min' => 6],
            [['retypePassword'], 'compare', 'compareAttribute' => 'newPassword'],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'oldPassword' => Yii::t('common','Contraseña Anterior'),
            'newPassword' => Yii::t('common','Nueva Contraseña'),
            'retypePassword' =>Yii::t('common','Confirmar Contraseña') ,
        ];
    }


    /**
     * Validate password
     */
    public function validatePassword()
    {
        /* @var $user User */
        $user = Yii::$app->user->identity;
        if (!$user || !$user->validatePassword($this->oldPassword)) {
            $this->addError('oldPassword', 'Incorrect old password.');
        }
    }

    /**
     * Change password
     */
    public function change()
    {
        if ($this->validate()) {
            /* @var $user User */
            $user = Yii::$app->user->identity;
            $user->scenario= User::SCENARIO_CHANGE_PASSWORD;
            $user->setPassword($this->newPassword);
            $user->generateAuthKey();
            if ($user->save()) {
                return true;
            }
        }

        return false;
    }
}
