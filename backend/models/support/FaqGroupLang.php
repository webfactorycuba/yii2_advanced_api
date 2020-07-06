<?php

namespace backend\models\support;

use Yii;

/**
 * This is the model class for table "faq_group_lang".
 *
 * @property int $id
 * @property int $faq_group_id
 * @property string $name
 * @property string $description
 * @property string $language
 *
 * @property FaqGroup $faqGroup
 */
class FaqGroupLang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'faq_group_lang';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['faq_group_id', 'name', 'language'], 'required'],
            [['faq_group_id'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['language'], 'string', 'max' => 2],
            [['faq_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => FaqGroup::className(), 'targetAttribute' => ['faq_group_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'faq_group_id' => 'Faq Group ID',
            'name' => Yii::t('backend', 'Nombre'),
            'description' => Yii::t('backend', 'Descripción'),
            'language' => Yii::t('backend', 'Idioma'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFaqGroup()
    {
        return $this->hasOne(FaqGroup::className(), ['id' => 'faq_group_id']);
    }
}
