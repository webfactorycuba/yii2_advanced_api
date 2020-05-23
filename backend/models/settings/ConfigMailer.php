<?php

namespace backend\models\settings;

use Yii;

/**
 * This is the model class for table "config_mailer".
 *
 * @property int $id
 * @property string $class
 * @property string $host
 * @property string $username
 * @property string $password
 * @property int $port
 * @property string $encryption
 * @property int $timeout
 */
class ConfigMailer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'config_mailer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['class', 'host', 'username', 'password'], 'required'],
            [['port', 'timeout'], 'integer'],
            [['class', 'host', 'username', 'password', 'encryption'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class' => Yii::t('backend', 'Clase'),
            'host' => Yii::t('backend', 'Host'),
            'username' => Yii::t('backend', 'Usuario'),
            'password' => Yii::t('backend', 'Contraseña'),
            'port' => Yii::t('backend', 'Puerto'),
            'encryption' => Yii::t('backend', 'Encriptación'),
            'timeout' => Yii::t('backend', 'Tiempo de espera'),
        ];
    }
}
