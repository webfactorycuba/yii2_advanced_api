<?php

namespace backend\models\settings;

use common\models\GlobalFunctions;
use yii\helpers\Html;
use Yii;
use backend\models\BaseModel;
use dosamigos\translateable\TranslateableBehavior;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * This is the model class for table "setting".
 *
 * @property int $id
 * @property string $phone
 * @property string $seo_keywords
 * @property string $address
 * @property string $email
 * @property string $mini_header_logo
 *
 * @property string $name
 * @property string $description
 * @property string $main_logo
 * @property string $header_logo
 * @property int $save_api_logs
 *
 * @property SettingLang[] $settingLangs
 */
class Setting extends BaseModel
{
    const SETTING_ID = 1;
    const SAVE_API_LOGS = 1;
    const UNSAVE_API_LOGS = 0;

    public $file_main_logo;
    public $file_header_logo;
    public $file_mini_header_logo;

    public function behaviors()
    {
        return [
            'trans' => [ // name it the way you want
                'class' => TranslateableBehavior::className(),
                // in case you named your relation differently, you can setup its relation name attribute
                'relation' => 'settingLangs',
                // in case you named the language column differently on your translation schema
                //'languageField' => 'language',
                'translationAttributes' => [
                    'name',
                    'seo_keywords',
                    'description',
                    'main_logo',
                    'header_logo',
                ],
                // use english as fallback for all languages when no translation is available
                //'fallbackLanguage' => \Yii::$app->language,
                'fallbackLanguage' => 'en',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'setting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone', 'address', 'email', 'name', 'description'], 'required'],
            [['phone', 'address', 'email', 'seo_keywords', 'mini_header_logo', 'name', 'main_logo', 'header_logo'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['email'], 'email'],
            [['save_api_logs'], 'integer'],
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
            'seo_keywords' => Yii::t('backend', 'SEO'),
            'address' => Yii::t('backend', 'Dirección'),
            'email' => Yii::t('backend', 'Correo electrónico'),
            'phone' => Yii::t('backend', 'Teléfono'),
            'description' => Yii::t('backend', 'Descripción'),
            'main_logo' => Yii::t('backend', 'Logo principal'),
            'file_main_logo' => Yii::t('backend', 'Logo principal'),
            'header_logo' => Yii::t('backend', 'Logo de cabecera'),
            'file_header_logo' => Yii::t('backend', 'Logo de cabecera'),
            'mini_header_logo' => Yii::t('backend', 'Mini logo de cabecera'),
            'file_mini_header_logo' => Yii::t('backend', 'Mini logo de cabecera'),
            'save_api_logs' => Yii::t("backend", "Guardar peticiones API")
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSettingLangs()
    {
        return $this->hasMany(SettingLang::className(), ['setting_id' => 'id']);
    }

    /**
     * fetch stored logo file name with complete path
     * @return string
     */
    public function getImageFile($type)
    {
        switch ($type)
        {
            case 1:
                {
                    if(isset($this->main_logo) && !empty($this->main_logo) && $this->main_logo !== '')
                        return 'images/'.$this->main_logo;
                    else
                        return null;
                    break;
                }
            case 2:
                {
                    if(isset($this->header_logo) && !empty($this->header_logo) && $this->header_logo !== '')
                        return 'images/'.$this->header_logo;
                    else
                        return null;
                    break;
                }
            case 3:
                {
                    if(isset($this->mini_header_logo) && !empty($this->mini_header_logo) && $this->mini_header_logo !== '')
                        return 'images/'.$this->mini_header_logo;
                    else
                        return null;
                    break;
                }
        }

    }

    /**
     * fetch stored logo url
     * @param $type // [1 => main_logo, 2 => header_logo, 3 => mini_logo_header]
     * @return string
     */
    public function getImageUrl($type)
    {
        // return a default logo placeholder if your source avatar is not found
        switch ($type)
        {
            case 1:
                {
                    $logo = isset($this->main_logo) ? $this->main_logo : 'main_logo.png';
                    break;
                }
            case 2:
                {
                    $logo = isset($this->header_logo) ? $this->header_logo : 'header_logo.png';
                    break;
                }
            case 3:
                {
                    $logo = isset($this->mini_header_logo) ? $this->mini_header_logo : 'mini_header_logo.png';
                    break;
                }
        }

        return 'images/'.$logo;
    }

    /**
     * Process upload of logo
     * @param $file_name_atrributte //name of field to upload [[file_main_logo, file_header_logo, file_mini_header_logo]]
     * @param $type // [1 => main_logo, 2 => header_logo, 3 => mini_header_logo]
     * @return mixed the uploaded logo instance
     */
    public function uploadImage($file_name_atrributte,$type) {
        // get the uploaded file instance. for multiple file uploads
        // the following data will return an array (you may need to use
        // getInstances method)
        $logo = UploadedFile::getInstance($this, $file_name_atrributte);

        // if no logo was uploaded abort the upload
        if (empty($logo)) {
            return false;
        }

        // store the source file name
        // $this->filename = $logo->name;
        $explode= explode('.',$logo->name);
        $ext = end($explode);
        $language_active = Yii::$app->language;

        // generate a unique file name
        switch ($type)
        {
            case 1:
                {
                    $this->main_logo = "main_logo_$language_active.{$ext}";
                    break;
                }
            case 2:
                {
                    $this->header_logo = "header_logo_$language_active.{$ext}";
                    break;
                }
            case 3:
                {
                    $this->mini_header_logo = "mini_header_logo_$language_active.{$ext}";
                    break;
                }
        }

        // the uploaded logo instance
        return $logo;
    }

    /**
     * Process deletion of logo
     * @param $type // [1 => main_logo, 2 => header_logo, 3 => mini_logo_header]
     * @return boolean the status of deletion
     */
    public function deleteImage($type) {
        $file = $this->getImageFile($type);

        // check if file exists on server
        if (empty($file) || !file_exists($file)) {
            return false;
        }

        // check if uploaded file can be deleted on server
        try{
            if (!unlink($file)) {
                return false;
            }
        }catch (\Exception $exception){
            Yii::info("Error deleting image on setting: " . $file);
            Yii::info($exception->getMessage());
            return false;
        }

        // if deletion successful, reset your file attributes
        switch ($type)
        {
            case 1:
                {
                    $this->main_logo = null;
                    break;
                }
            case 2:
                {
                    $this->header_logo = null;
                    break;
                }
            case 3:
                {
                    $this->mini_header_logo = null;
                    break;
                }
        }

        return true;
    }

    /**
     * get path logo of setting
     * @param integer $setting_id
     * @param integer $type // [1 => main_logo, 2 => header_logo, 3 => mini_header_logo]
     * @return string $logo_path
     */
    public static function getUrlLogoBySettingAndType($type,$setting_id=null)
    {
        $path = Url::to('@web/images/');

        if($setting_id !== null)
        {
            $model = self::findOne($setting_id);

            if($model)
            {
                switch ($type)
                {
                    case 1:
                        {
                            if($model->main_logo === null || $model->main_logo === '')
                            {
                                $url = $path.'main_logo.png';
                            }
                            else
                            {
                                $url = $path.''.$model->main_logo;
                            }
                            break;
                        }
                    case 2:
                        {
                            if($model->header_logo === null || $model->header_logo === '')
                            {
                                $url = $path.'header_logo.png';
                            }
                            else
                            {
                                $url = $path.''.$model->header_logo;
                            }
                            break;
                        }
                    case 3:
                        {
                            if($model->mini_header_logo === null || $model->mini_header_logo === '')
                            {
                                $url = $path.'mini_header_logo.png';
                            }
                            else
                            {
                                $url = $path.''.$model->mini_header_logo;
                            }
                            break;
                        }
                }

                return $url;
            }

        }

        switch ($type)
        {
            case 1:
                {
                    $path_return = $path.'main_logo.png';
                    break;
                }
            case 2:
                {
                    $path_return = $path.'header_logo.png';
                    break;
                }
            case 3:
                {
                    $path_return = $path.'mini_header_logo.png';
                    break;
                }
        }

        return $path_return;
    }

    /**
     * @return string
     */
    public static function getName()
    {
        $model = SettingLang::find()->where(['language' => Yii::$app->language])->one();

        if(!$model)
        {
            return Yii::$app->name;
        }
        else
        {
            return $model->name;
        }
    }

    /**
     * @return int|mixed
     */
    public static function getIdSettingByActiveLanguage()
    {
        $active_language = Yii::$app->language;

        $model = SettingLang::find()->where(['language'=> $active_language])->one();

        if($model)
        {
            return $model->setting_id;
        }
        else
        {
            return 1;
        }
    }

    /**
     * Función para crear un entrada setting al crear un nuevo idioma
     *
     * @param string $language
     * @return bool
     */
    public static function createDefaultSettingByLanguage($language)
    {
        $model_setting = Setting::findOne(self::SETTING_ID);

        if($model_setting)
        {
            $model_lang_exist = SettingLang::find()->where(['language'=> $language])->one();

            if(isset($model_lang_exist) && !empty($model_lang_exist))
            {
                return true;
            }
            else
            {
                $upper_language = strtoupper($language);
                $model_setting_lang = new SettingLang();
                $model_setting_lang->setting_id = 1;
                $model_setting_lang->name = 'Default name in language '.$upper_language;
                $model_setting_lang->description = 'Default description in language '.$upper_language;
                $model_setting_lang->language = $language;

                If($model_setting_lang->save())
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
        else
        {
            return false;
        }

    }

    /**
     * @return string
     */
    public static function getEmail()
    {
        $model = Setting::findOne(self::SETTING_ID);

        if(!$model)
        {
            return 'info@info.com';
        }
        else
        {
            return $model->email;
        }
    }

    /**
     * @return string
     */
    public static function getSaveApiLogs()
    {
        $model = Setting::findOne(self::SETTING_ID);

        if(!$model)
        {
            return false;
        }
        else
        {
            return $model->save_api_logs == Setting::SAVE_API_LOGS;
        }
    }

    /**
     * @return string
     */
    public static function getSeoKeywords()
    {
        $model = Setting::findOne(self::SETTING_ID);

        if (!isset($model) || empty($model->seo_keywords)) {
            return 'CocoLeads, Subscripciones';
        } else {
            return $model->seo_keywords;
        }
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
        return "/setting";
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
