<?php

namespace backend\models\i18n;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property string $language
 * @property string $translation
 *
 * @property SourceMessage $id0
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'language'], 'required'],
            [['id'], 'integer'],
            [['translation'], 'string'],
            [['language'], 'string', 'max' => 16],
            [['id', 'language'], 'unique', 'targetAttribute' => ['id', 'language']],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => SourceMessage::className(), 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'language' => Yii::t('backend', 'Idioma'),
            'translation' => Yii::t('backend','Traducción'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(SourceMessage::className(), ['id' => 'id']);
    }

	/**
	 * @param integer $id
	 * @param string $language
	 * @return string
	 */
	public static function getMessageBySourceId($id,$language)
	{
		$translation= self::find()->where(['id'=>$id,'language'=>$language])->one();

		if($translation !== null)
		{
			return $translation->translation;
		}
		else
		{
			return '';
		}
	}

	/**
	 * @param integer $id
	 * @param string $language
	 * @param string $translation
	 * @return boolean
	 */
	public static function addMessage($id, $language, $translation)
	{
		$model = new Message();

		$model->id = $id;
		$model->language = $language;
		$model->translation = $translation;

		if($model->save())
			return true;
		else
			return false;
	}

	/**
	 * @param integer $id
	 * @param string $languagetranslation_en
	 * @param string $translation
	 * @return boolean
	 */
	public static function updateMessage($id, $language, $translation)
	{
		$model = Message::find()->where(['id' => $id, 'language' => $language])->one();

		if($model !== null)
		{
			$model->translation = $translation;

			if($model->save())
				return true;
			else
				return false;
		}
		else
		{
			return false;
		}
	}


	/**
	 * @param integer $id
	 * @param string $language
	 * @return Message
	 */
	public static function getModelMessage($id, $language)
	{
		$model = Message::find()->where(['id' => $id, 'language' => $language])->one();

		if($model !== null)
		{
			return $model;
		}
		else
		{
			return false;
		}
	}

    /**
    * Get Messages list.
    * @return Array
    */
    public static function getMessagesList() {
        $models = self::find()
            ->andWhere(['not', ['translation' => null]])
            ->asArray()
            ->all()
        ;

        $value = ( count( $models ) == 0 ) ? [ '' => '' ] : ArrayHelper::map( $models, 'id', 'translation' );

        return $value;
    }

    /**
     * @param $id
     * @param $language
     * @return array|Message|null|\yii\db\ActiveRecord
     */
    public static function getTranslationBySourceId($id,$language)
    {
        $translation= self::find()->where(['id'=>$id,'language'=>$language])->one();

        return $translation;
    }

    /**
     * Function to get array of codes languages with unset languages stored
     *
     * @param integer $source_message_id
     * @param strinng $add_language_to_update
     * @return array
     */
    public static function getTranslationsCodesAvailables($source_message_id, $add_language_to_update=null)
    {
        $array_languages = Language::getLanguagesShortName();
        $array_translations_stored = Message::find()->where(['id'=>$source_message_id])->all();

        unset($array_languages['es']);

        foreach ($array_translations_stored AS $key => $msg)
        {
            if($add_language_to_update === null)
            {
                unset($array_languages[$msg->language]);
            }
            else
            {
                if($add_language_to_update !== $msg->language)
                {
                    unset($array_languages[$msg->language]);
                }
            }
        }

       return $array_languages;
    }

}
