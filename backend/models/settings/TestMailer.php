<?php

namespace backend\models\settings;

use Yii;
use yii\base\Model;
use common\models\GlobalFunctions;

/**
 * TestMailer is the model behind the contact form.
 */
class TestMailer extends Model
{
    public $email;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [ 'email', 'required'],
            ['email', 'email'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('backend','Correo electrónico destino'),
        ];
    }

    /**
     * @param $email
     */
    public function sendEmail()
    {
        $subject = Yii::t('backend','Mensaje de prueba');

        $mailer = Yii::$app->mail->compose(['html' => 'test_message-html'])
            ->setTo($this->email)
            ->setFrom([Setting::getEmail() => Setting::getName()])
            ->setSubject($subject);

        try
        {
            if($mailer->send())
            {
                GlobalFunctions::addFlashMessage('success',Yii::t('backend','Mensaje de prueba enviado correctamente'));
                return true;
            }
            else
            {
                GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error enviando mensaje de prueba'));
                return false;
            }
        }
        catch (\Swift_TransportException $e)
        {
            GlobalFunctions::addFlashMessage('danger',Yii::t('common','Ha ocurrido un error. No se ha podido establecer la conexión con el servidor de correo'));
            return false;
        }
    }
}
