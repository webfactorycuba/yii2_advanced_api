<?php
namespace common\components;

use Yii;
use machour\yii2\notifications\models\Notification as BaseNotification;
use yii\helpers\Url;

class Notification extends BaseNotification
{

    const KEY_NEW_USER_REGISTRED = 'new_user_registred';
    const KEY_ROLE_USER_UPDATED = 'user_role_updated';

    /**
     * @var array Holds all usable notifications
     */
    public static $keys = [
        self::KEY_NEW_USER_REGISTRED,
        self::KEY_ROLE_USER_UPDATED,
    ];

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        switch ($this->key)
        {
            case self::KEY_NEW_USER_REGISTRED:
                return Yii::t('common', 'Usuario registrado');
            case self::KEY_ROLE_USER_UPDATED:
                return Yii::t('common', 'Rol de usuario actualizado');
        }
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        switch ($this->key)
        {
            case self::KEY_NEW_USER_REGISTRED:
                return Yii::t('common','Nuevo usuario registrado');
            case self::KEY_ROLE_USER_UPDATED:
                return Yii::t('common', 'El rol de usuario ha sido modificado');
        }
    }

    /**
     * @inheritdoc
     */
    public function getRoute()
    {
        switch ($this->key)
        {
            case self::KEY_NEW_USER_REGISTRED:
                return Url::to(['/security/user/update','id'=>$this->key_id]);
            case self::KEY_ROLE_USER_UPDATED:
                return Url::to(['/security/user/update','id'=>$this->key_id]);
        }
    }

}