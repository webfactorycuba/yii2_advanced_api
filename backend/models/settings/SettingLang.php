<?php

namespace backend\models\settings;

use Yii;

/**
 * This is the model class for table "setting_lang".
 *
 * @property int $id
 * @property int $setting_id
 * @property string $language
 * @property string $name
 * @property string $seo_keywords
 * @property string $description
 * @property string $main_logo
 * @property string $header_logo
 *
 * @property Setting $setting
 */
class SettingLang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'setting_lang';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['setting_id', 'language', 'name', 'description'], 'required'],
            [['setting_id'], 'integer'],
            [['description'], 'string'],
            [['language'], 'string', 'max' => 2],
            [['name', 'main_logo', 'header_logo', 'seo_keywords'], 'string', 'max' => 255],
            [['setting_id'], 'exist', 'skipOnError' => true, 'targetClass' => Setting::className(), 'targetAttribute' => ['setting_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'setting_id' => 'Setting ID',
            'language' => Yii::t('backend', 'Idioma'),
            'name' => Yii::t('backend', 'Nombre'),
            'seo_keywords' => Yii::t('backend', 'SEO'),
            'description' => Yii::t('backend', 'Descripción'),
            'main_logo' => Yii::t('backend', 'Logo principal'),
            'header_logo' => Yii::t('backend', 'Logo de cabecera'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSetting()
    {
        return $this->hasOne(Setting::className(), ['id' => 'setting_id']);
    }
}
