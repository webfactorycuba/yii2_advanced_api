<?php

namespace backend\models\support;

use Yii;

/**
 * This is the model class for table "api_doc_lang".
 *
 * @property int $id
 * @property int $api_doc_id
 * @property string $description
 * @property string $language
 *
 * @property ApiDoc $apiDoc
 */
class ApiDocLang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'api_doc_lang';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['api_doc_id', 'language'], 'required'],
            [['api_doc_id'], 'integer'],
            [['description'], 'string'],
            [['language'], 'string', 'max' => 2],
            [['api_doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => ApiDoc::className(), 'targetAttribute' => ['api_doc_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'api_doc_id' => 'Api_Doc ID',
            'description' => Yii::t('backend', 'Descripción'),
            'language' => Yii::t('backend', 'Idioma'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApiDoc()
    {
        return $this->hasOne(ApiDoc::className(), ['id' => 'api_doc_id']);
    }
}
