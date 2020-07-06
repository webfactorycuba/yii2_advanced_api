<?php

namespace backend\models\support;

use backend\models\support\FaqGroup;
use Yii;
use backend\models\BaseModel;
use dosamigos\translateable\TranslateableBehavior;
use backend\models\support\FaqLang;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;
use common\models\GlobalFunctions;
use yii\helpers\Html;
use yii\web\UploadedFile;

/**
 * This is the model class for table "faq".
 *
 * @property int $id
 * @property int $faq_group_id
 * @property string $question
 * @property string $answer
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $image
 *
 * @property FaqGroup $faqGroup
 * @property FaqLang[] $faqLangs
 */
class Faq extends BaseModel
{

    public function behaviors()
    {
        return [
            'trans' => [ // name it the way you want
                'class' => TranslateableBehavior::className(),
                // in case you named your relation differently, you can setup its relation name attribute
                'relation' => 'faqLangs',
                // in case you named the language column differently on your translation schema
                //'languageField' => 'language',
                'translationAttributes' => [
                    'question',
                    'answer',
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
        return 'faq';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['faq_group_id', 'question', 'answer'], 'required'],
            [['faq_group_id', 'status'], 'integer'],
            [['question', 'answer'], 'string'],
            [['question', 'answer'], 'filter', 'filter'=>'\yii\helpers\HtmlPurifier::process'],
            [['created_at', 'updated_at'], 'safe'],
            [['image'], 'file', 'extensions' => implode(',', GlobalFunctions::getImageFormats())],
            [['faq_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => FaqGroup::className(), 'targetAttribute' => ['faq_group_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'faq_group_id' => Yii::t('backend', 'Grupo'),
            'question' => Yii::t('backend', 'Pregunta'),
            'answer' => Yii::t('backend', 'Respuesta'),
            'status' => Yii::t('backend', 'Estado'),
            'created_at' => Yii::t('backend', 'Fecha de creación'),
            'updated_at' => Yii::t('backend', 'Fecha de actualización'),
            'image' => Yii::t('backend', 'Imagen'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFaqGroup()
    {
        return $this->hasOne(FaqGroup::className(), ['id' => 'faq_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFaqLangs()
    {
        return $this->hasMany(FaqLang::className(), ['faq_id' => 'id']);
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
        return "/faq";
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

    /**
     * @return boolean true if exists stored image
     */
    public function hasImage()
    {
        return (isset($this->image) && !empty($this->image) && $this->image !== '');
    }

    /**
     * fetch stored image file name with complete path
     * @return string
     */
    public function getImageFile()
    {
        if(!file_exists("uploads/faqs/") || !is_dir("uploads/faqs/")){
            try{
                FileHelper::createDirectory("uploads/faqs/", 0777);
            }catch (\Exception $exception){
                Yii::info("Error handling Faqs folder resources");
            }

        }
        if(isset($this->image) && !empty($this->image) && $this->image !== '')
            return "uploads/faqs/{$this->image}";
        else
            return null;

    }

    /**
     * fetch stored image url
     * @return string
     */
    public function getImageUrl()
    {
        if($this->hasImage()){
            return "uploads/faqs/{$this->image}";
        }else{
            return GlobalFunctions::getNoImageDefaultUrl();
        }

    }

    /**
     * Process upload of image
     * @return mixed the uploaded image instance
     */
    public function uploadImage() {
        // get the uploaded file instance. for multiple file uploads
        // the following data will return an array (you may need to use
        // getInstances method)
        $image = UploadedFile::getInstance($this, 'image');

        // if no logo was uploaded abort the upload
        if (empty($image)) {
            return false;
        }

        // store the source file name
        // $this->filename = $image->name;
        $explode= explode('.',$image->name);
        $ext = end($explode);
        $hash_name = GlobalFunctions::generateRandomString(10);
        $this->image = "{$hash_name}.{$ext}";

        // the uploaded logo instance
        return $image;
    }

    /**
     * Process deletion of logo
     * @return boolean the status of deletion
     */
    public function deleteImage() {
        $file = $this->getImageFile();

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
        $this->image = null;

        return true;
    }
}
