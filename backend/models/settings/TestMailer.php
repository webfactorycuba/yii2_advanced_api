<?php

namespace backend\models\settings;

use Yii;
use yii\base\Model;
use common\models\GlobalFunctions;

/**
 * ContactForm is the model behind the contact form.
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
        $email = Setting::getEmail();
        $subject = Yii::t('backend','Mensaje de prueba');
        $body = Yii::t('backend','Mensaje desde probador de configuración');

        $mailer = Yii::$app->mail->compose()
            ->setTo($this->email)
            ->setFrom($email)
            ->setSubject($subject)
            ->setTextBody($body);

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
