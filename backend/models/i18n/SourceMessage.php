<?php

namespace backend\models\i18n;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "source_message".
 *
 * @property int $id
 * @property string $category
 * @property string $message
 *
 * @property Message[] $messages
 */
class SourceMessage extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'source_message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message'], 'string'],
            [['category'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category' => Yii::t('backend','Categoría'),
            'message' => Yii::t('backend','Mensaje original'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['id' => 'id']);
    }

    /**
     * Get label with css to show status of translation
     */
    public static function getIfExistTranslation($id)
    {
        $need_translations = false;
        $list_languages_availables = Language::getLanguagesActives('es');

        foreach ($list_languages_availables AS $key => $value)
        {
            $exist = Message::find()->where(['id'=> $id, 'language'=> $value->code]);
            if(!$exist)
            {
                $need_translations = true;
            }
            else
            {
                $translation = Message::getMessageBySourceId($id,$value->code);
                if($translation === '' ||$translation === null)
                {
                    $need_translations = true;
                }
            }
        }

        if(!$need_translations)
        {
            return Html::tag('span', '<i class="fa fa-exclamation-circle"></i>', ['class'=>'label label-success', 'data-toggle' => 'tooltip', 'data-placement' => 'rigth', 'data-original-title' => Yii::t('backend','Traducido')]);
        }
        else
        {
            return Html::tag('span', '<i class="fa fa-exclamation-triangle"></i>', ['class'=>'label label-danger', 'data-toggle' => 'tooltip', 'data-placement' => 'rigth', 'data-original-title' => Yii::t('backend','Necesita traducciones')]);
        }
    }

    /**
     * Get category to translations.
     * @return Array
     */
    public static function getCategoriesTranslationsList() {

        $value = [
            'backend' => 'backend',
            'common' => 'common',
            'frontend' => 'frontend',
        ];

        return $value;
    }

    /**
     * Function to show list of translation in view
     *
     * @return array
     */
    public function getAllMessagesList()
    {
        $models = Message::find()
            ->where(['id' => $this->id])
            ->andWhere(['not', ['language' => 'es']])
            ->all();

        return $models;
    }
}
