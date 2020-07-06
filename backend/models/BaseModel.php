<?php

namespace backend\models;

use common\models\GlobalFunctions;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use Yii;

abstract class BaseModel extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = "1";
    const STATUS_INACTIVE = "0";

    /** :::::::::::: Start > Util Gets ::::::::::::*/

    /**
     * Returns the attribute name that represents current object model, default to 'name'
     * @return string
     */
    public static function getRepresentativeAttrName()
    {
        return 'name';
    }

    /**
     * Returns the attribute ID that represents current object model, default to 'id'
     * @return string
     */
    public static function getRepresentativeAttrID()
    {
        return 'id';
    }

    /**
     * Return the model ID
     * @return mixed
     */
    public function getId()
    {
        $id = self::getRepresentativeAttrID();
        return $this->$id;
    }

    /**
     * @return bool true if current model has active status
     */
    public function isActive(){
        return $this->status == self::STATUS_ACTIVE;
    }

    /**
     * Save create and update times
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->created_at = date('Y-m-d H:i:s');
            $this->updated_at = date('Y-m-d H:i:s');
        } else {
            $this->updated_at = date('Y-m-d H:i:s');
        }

        return parent::beforeSave($insert);
    }

    /**
     * Returns a mapped array for using on Select widget
     *
     * @param boolean $check_status
     * @return array
     */
    public static function getSelectMap($check_status = false)
    {
        $query = self::find();
        if($check_status)
        {
            $query->where(['status' => self::STATUS_ACTIVE]);
        }

        $models = $query->asArray()->all();

        $results = ( count( $models ) === 0 ) ? [ '' => '' ] : ArrayHelper::map($models, self::getRepresentativeAttrID(), self::getRepresentativeAttrName());

        return $results;
    }

    /*
      Example for translations

    public static function getSelectMap($check_status = true)
    {
        $query = self::find()->select(['table_name.id', 'table_name_lang.name'])
            ->joinWith(['tableNameLangs']);
        if ($check_status) {
            $query->where(['status' => self::STATUS_ACTIVE]);
        }
        $models = $query->andFilterWhere(['table_name_lang.language' => Yii::$app->language])->asArray()->all();

        return ArrayHelper::map($models, 'id', 'name');
    }

    */

    /** :::::::::::: END > Util Gets ::::::::::::*/

    /** :::::::::::: START > Abstract Methods and Overrides ::::::::::::*/

    /**
     * @return string The base name for current model, it must be implemented on each child
     */
    public abstract function getBaseName();

    /**
     * @return string base route to model links, default to '/'
     */
    public abstract function getBaseLink();

    /**
     * Returns a link that represents current object model
     * @return string
     *
     * You can use this template code
     *  {
     *      $id = $this->getRepresentativeAttrID();
     *      if (isset($this->$id)) {
     *          $name = $this->getRepresentativeAttrName();
     *          return Html::a($this->$name, [$this->getBaseLink() . "/view", 'id' => $this->getId()]);
     *      } else {
     *          return GlobalFunctions::getNoValueSpan();
     *      }
     *  }
     *
     */
    public abstract function getIDLinkForThisModel();

    /** :::::::::::: END > Abstract Methods and Overrides ::::::::::::*/

}