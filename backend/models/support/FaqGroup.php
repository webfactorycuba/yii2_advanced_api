<?php

namespace backend\models\support;

use Yii;
use backend\models\BaseModel;
use dosamigos\translateable\TranslateableBehavior;
use yii\helpers\StringHelper;
use common\models\GlobalFunctions;
use yii\helpers\Html;

/**
 * This is the model class for table "faq_group".
 *
 * @property int $id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $name
 * @property string $description
 *
 * @property FaqGroupLang[] $faqGroupLangs
 */
class FaqGroup extends BaseModel
{
    public function behaviors()
    {
        return [
            'trans' => [ // name it the way you want
                'class' => TranslateableBehavior::className(),
                // in case you named your relation differently, you can setup its relation name attribute
                'relation' => 'faqGroupLangs',
                // in case you named the language column differently on your translation schema
                //'languageField' => 'language',
                'translationAttributes' => [
                    'name',
                    'description',
                ],
                // use english as fallback for all languages when no translation is available
                'fallbackLanguage' => 'en',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'faq_group';
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
            [['description'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('backend', 'Nombre'),
            'description' => Yii::t('backend', 'Descripción'),
            'status' => Yii::t('backend', 'Estado'),
            'created_at' => Yii::t('backend', 'Fecha de creación'),
            'updated_at' => Yii::t('backend', 'Fecha de actualización'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFaqGroupLangs()
    {
        return $this->hasMany(FaqGroupLang::className(), ['faq_group_id' => 'id']);
    }

    /**
     * Returns a mapped array for using on Select widget
     *
     * @param boolean $check_status
     * @return array
     */
    public static function getSelectMap($check_status=false)
    {
        if(!$check_status)
        {
            $options = ['status' => self::STATUS_ACTIVE];
            $models = self::find()
                ->select(['faq_group.id','faq_group_lang.name'])
                ->innerJoin('faq_group_lang', 'faq_group_lang.faq_group_id = faq_group.id')
                ->where($options)
                ->asArray()
                ->all();
        }
        else
        {
            $models = self::find()
                ->select(['faq_group.id','faq_group_lang.name'])
                ->innerJoin('faq_group_lang', 'faq_group_lang.faq_group_id = faq_group.id')
                ->asArray()
                ->all();
        }

        $array_map = [];

        if(count($models)>0)
        {
            foreach ($models AS $index => $model)
            {
                $array_map[$model['id']] = $model['name'];
            }
        }

        return $array_map;
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
        return "/faq-group";
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

}
