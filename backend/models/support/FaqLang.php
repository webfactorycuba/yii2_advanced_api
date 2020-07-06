<?php

namespace backend\models\support;

use Yii;

/**
 * This is the model class for table "faq_lang".
 *
 * @property int $id
 * @property int $faq_id
 * @property string $question
 * @property string $answer
 * @property string $language
 *
 * @property Faq $faq
 */
class FaqLang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'faq_lang';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['faq_id', 'question', 'answer', 'language'], 'required'],
            [['faq_id'], 'integer'],
            [['question', 'answer'], 'string'],
            [['language'], 'string', 'max' => 2],
            [['faq_id'], 'exist', 'skipOnError' => true, 'targetClass' => Faq::className(), 'targetAttribute' => ['faq_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'faq_id' => Yii::t('backend', 'Faq ID'),
            'question' => Yii::t('backend', 'Question'),
            'answer' => Yii::t('backend', 'Answer'),
            'language' => Yii::t('backend', 'Language'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFaq()
    {
        return $this->hasOne(Faq::className(), ['id' => 'faq_id']);
    }
}
