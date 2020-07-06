<?php

namespace backend\models\support;

use common\models\GlobalFunctions;
use Yii;

/**
 * This is the model class for table "{{%cronjob_log}}".
 *
 * @property int $id
 * @property int $cronjob_task_id
 * @property string $message
 * @property string $execution_date
 * @property string $created_at
 *
 * @property CronjobTask $cronjobTask
 */
class CronjobLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cronjob_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cronjob_task_id'], 'integer'],
            [['message'], 'string'],
            [['execution_date', 'created_at'], 'safe'],
            [['cronjob_task_id'], 'exist', 'skipOnError' => true, 'targetClass' => CronjobTask::className(), 'targetAttribute' => ['cronjob_task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'cronjob_task_id' => Yii::t('backend', 'Tarea de CronJob'),
            'message' => Yii::t('backend', 'Mensaje'),
            'execution_date' => Yii::t('backend', 'Fecha de ejecución'),
            'created_at' => Yii::t('backend', 'Fecha de creación'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCronjobTask()
    {
        return $this->hasOne(CronjobTask::className(), ['id' => 'cronjob_task_id']);
    }

    public function getCronjobTaskLink()
    {
        if(isset($this->cronjobTask)){
            return $this->cronjobTask->getIDLinkForThisModel();
        }

        return GlobalFunctions::getNoValueSpan();
    }

    public function getMessage()
    {
        if(isset($this->message)){
            return $this->message;
        }

        return GlobalFunctions::getNoValueSpan();
    }

    public static function registerJob($taskId, $execution_date, $message){
        return (new self(['cronjob_task_id'=>$taskId, 'execution_date'=>$execution_date, 'message'=>$message]))->save();
    }
}
