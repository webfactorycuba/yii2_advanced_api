<?php

namespace backend\models\support;

use Yii;
use common\models\GlobalFunctions;

/**
 * This is the model class for table "{{%api_request_log}}".
 *
 * @property int $id
 * @property string $action_id
 * @property string $user_agent
 * @property string $method
 * @property string $headers
 * @property string $body
 * @property string $ip
 * @property string $created_at
 */
class ApiRequestLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%api_request_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['headers', 'body'], 'string'],
            [['created_at'], 'safe'],
            [['action_id'], 'string', 'max' => 250],
            [['user_agent'], 'string', 'max' => 255],
            [['method'], 'string', 'max' => 10],
            [['ip'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'action_id' => Yii::t('backend', 'ID de Acción'),
            'method' => Yii::t('backend', 'Método'),
            'headers' => Yii::t('backend', 'Cabeceras'),
            'body' => Yii::t('backend', 'Cuerpo'),
            'ip' => Yii::t('backend', 'IP'),
            'created_at' => Yii::t('backend', 'Fecha de creación'),
        ];
    }

    public function getMethod()
    {
        if(isset($this->method) && !empty($this->method)){
            return $this->method;
        }
        return GlobalFunctions::getNoValueSpan();
    }

    public function getActionId()
    {
        if(isset($this->action_id) && !empty($this->action_id)){
            return $this->action_id;
        }
        return GlobalFunctions::getNoValueSpan();
    }

    public function getUserAgent()
    {
        if(isset($this->user_agent) && !empty($this->user_agent)){
            return $this->user_agent;
        }
        return GlobalFunctions::getNoValueSpan();
    }

    public function getIp()
    {
        if(isset($this->ip) && !empty($this->ip)){
            return $this->ip;
        }
        return GlobalFunctions::getNoValueSpan();
    }

    public function getBody($jsonFormat=True)
    {
        if(isset($this->body) && !empty($this->body)){
           return $jsonFormat? "<code>{$this->body}</code>": $this->body;
        }
        return GlobalFunctions::getNoValueSpan();
    }

    public function getHeaders($jsonFormat=True)
    {
        if(isset($this->headers) && !empty($this->headers)){
            return $jsonFormat? "<code>{$this->headers}</code>": $this->headers;
        }
        return GlobalFunctions::getNoValueSpan();
    }
}
