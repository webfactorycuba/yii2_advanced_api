<?php

namespace backend\models\support;

use Yii;
use backend\models\BaseModel;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use common\models\GlobalFunctions;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%cronjob_task}}".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CronjobLog[] $cronjobLogs

 */
class CronjobTask extends BaseModel
{
    const SYNC_TO_CRM = 'SYNC_TO_CRM';
    const JOB_STATS = 'STATS';

    public static function getTasksNames()
    {
        return [
            self::SYNC_TO_CRM => self::SYNC_TO_CRM,
            self::JOB_STATS => self::JOB_STATS
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cronjob_task}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'filter', 'filter'=>function($value){return strtoupper(str_replace(" ", "_", trim($value)));}],
            [['name'], 'unique'],
            [['name'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'name' => Yii::t('backend', 'Nombre'),
            'status' => Yii::t('backend', 'Estado'),
            'created_at' => Yii::t('backend', 'Fecha de creación'),
            'updated_at' => Yii::t('backend', 'Fecha de actualización'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCronjobLogs()
    {
        return $this->hasMany(CronjobLog::className(), ['cronjob_task_id' => 'id']);
    }

    /** :::::::::::: START > Abstract Methods and Overrides ::::::::::::*/

    /**
    * @return string The base name for current model, it must be implemented on each child
    */
    public function getBaseName()
    {
        return StringHelper::basename(get_class($this));
    }

    /**
    * @return string base route to model links, default to '/'
    */
    public function getBaseLink()
    {
        return "/cronjob-task";
    }

    /**
    * Returns a link that represents current object model
    * @return string
    *
    */
    public function getIDLinkForThisModel()
    {
        $id = $this->getRepresentativeAttrID();
        if (isset($this->$id)) {
            $name = $this->getRepresentativeAttrName();
            return Html::a($this->$name, [$this->getBaseLink() . "/view", 'id' => $this->getId()]);
        } else {
            return GlobalFunctions::getNoValueSpan();
        }
    }

    /** :::::::::::: END > Abstract Methods and Overrides ::::::::::::*/

    public static function getTaskIdByName($name)
    {
        $model = static::findOne(['name'=>$name]);
        return isset($model)? $model->id : 0;
    }

    /**
     * Returns true if current task is not a constant
     * @return bool
     */
    public function canBeDeleted()
    {
        return !ArrayHelper::keyExists($this->name, self::getTasksNames());
    }
}
