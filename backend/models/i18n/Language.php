<?php

namespace backend\models\i18n;

use common\models\GlobalFunctions;
use Yii;
use backend\models\BaseModel;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "language".
 *
 * @property int $id
 * @property string $code
 * @property string $image
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 */
class Language extends BaseModel
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'language';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
            [ 'image', 'required', 'on' => 'create'],
            [['status'], 'integer'],
            [['created_at', 'updated_at','image'], 'safe'],
            [['code'], 'string', 'max' => 255],
            [['code'], 'unique'],
            [['image'], 'file', 'extensions'=>'jpg, jpeg, png, gif, svg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'code' => Yii::t('backend', 'Código'),
            'image' => Yii::t('backend', 'Bandera'),
            'status' => Yii::t('backend', 'Estado'),
            'created_at' => Yii::t('backend', 'Fecha de creación'),
            'updated_at' => Yii::t('backend', 'Fecha de actualización'),
        ];
    }

    /**
     * Function to get all languages actives
     */
    public static function getLanguagesActives($except= null)
    {
        if($except === null)
        {
            $models = self::find()->where(['status' => self::STATUS_ACTIVE])->all();
        }
        else
        {
            $models = self::find()
                ->where(['status' => self::STATUS_ACTIVE])
                ->andWhere(['not', ['code' => $except]])
                ->all();
        }

        return $models;
    }

    /**
     * Returns the attribute name that represents current object model, default to 'name'
     * @return string
     */
    public static function getRepresentativeAttrName()
    {
        return 'code';
    }

    /**
     * fetch stored image file name with complete path
     * @return string
     */
    public function getImageFile()
    {
        return isset($this->image) ? 'uploads/flags/'.$this->image : null;
    }

    /**
     * fetch stored image url
     * @return string
     */
    public function getImageUrl()
    {
        if(isset($this->image))
        {
            return 'uploads/flags/'.$this->image;
        }
        else
        {
            return 'uploads/noimage_default.jpg';
        }

    }

    /**
     * Process upload of image
     *
     * @return mixed the uploaded image instance
     */
    public function instanceImage()
    {
        $image = UploadedFile::getInstance($this, 'image');

        if (empty($image)) {
            return false;
        }

        $this->image = Yii::$app->security->generateRandomString().'.'.$image->extension;

        return $image;
    }

    public function upload($image,$is_update = NULL,$old_file_image = NULL)
    {
        if ($image !== false)
        {
            if ($this->validate())
            {
                if($is_update !== NULL && $old_file_image !== NULL)
                {
                    if(file_exists($old_file_image))
                        try{
                            unlink($old_file_image);
                        }catch (\Exception $exception){
                            Yii::info("Error deleting image on UserController: " . $old_file_image);
                            Yii::info($exception->getMessage());
                        }
                }

                $path = $this->getImageFile();
                $image->saveAs($path);

                return true;
            }
            else
                return false;
        }
        else
            return true;
    }

    /**
     * Process deletion of image
     *
     * @return boolean the status of deletion
     */
    public function deleteImage()
    {
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
            Yii::info("Error deleting image on language: " . $file);
            Yii::info($exception->getMessage());
            return false;
        }

        // if deletion successful, reset your file attributes
        $this->image = null;

        return true;
    }

    /**
     * @return array
     */
    public static function getLanguagesShortName()
    {
        $codes = [
            'ab' => 'ab',// 'Abkhazian',
            'aa' => 'aa',// 'Afar',
            'af' => 'af',// 'Afrikaans',
            'ak' => 'ak',// 'Akan',
            'sq' => 'sq',// 'Albanian',
            'am' => 'am',// 'Amharic',
            'ar' => 'ar',// 'Arabic',
            'an' => 'an',// 'Aragonese',
            'hy' => 'hy',// 'Armenian',
            'as' => 'as',// 'Assamese',
            'av' => 'av',// 'Avaric',
            'ae' => 'ae',// 'Avestan',
            'ay' => 'ay',// 'Aymara',
            'az' => 'az',// 'Azerbaijani',
            'bm' => 'bm',// 'Bambara',
            'ba' => 'ba',// 'Bashkir',
            'eu' => 'eu',// 'Basque',
            'be' => 'be',// 'Belarusian',
            'bn' => 'bn',// 'Bengali',
            'bh' => 'bh',// 'Bihari languages',
            'bi' => 'bi',// 'Bislama',
            'bs' => 'bs',// 'Bosnian',
            'br' => 'br',// 'Breton',
            'bg' => 'bg',// 'Bulgarian',
            'my' => 'my',// 'Burmese',
            'ca' => 'ca',// 'Catalan, Valencian',
            'km' => 'km',// 'Central Khmer',
            'ch' => 'ch',// 'Chamorro',
            'ce' => 'ce',// 'Chechen',
            'ny' => 'ny',// 'Chichewa, Chewa, Nyanja',
            'zh' => 'zh',// 'Chinese',
            'cu' => 'cu',// 'Church Slavonic, Old Bulgarian, Old Church Slavonic',
            'cv' => 'cv',// 'Chuvash',
            'kw' => 'kw',// 'Cornish',
            'co' => 'co',// 'Corsican',
            'cr' => 'cr',// 'Cree',
            'hr' => 'hr',// 'Croatian',
            'cs' => 'cs',// 'Czech',
            'da' => 'da',// 'Danish',
            'dv' => 'dv',// 'Divehi, Dhivehi, Maldivian',
            'nl' => 'nl',// 'Dutch, Flemish',
            'dz' => 'dz',// 'Dzongkha',
            'en' => 'en',// 'English',
            'eo' => 'eo',// 'Esperanto',
            'et' => 'et',// 'Estonian',
            'ee' => 'ee',// 'Ewe',
            'fo' => 'fo',// 'Faroese',
            'fj' => 'fj',// 'Fijian',
            'fi' => 'fi',// 'Finnish',
            'fr' => 'fr',// 'French',
            'ff' => 'ff',// 'Fulah',
            'gd' => 'gd',// 'Gaelic, Scottish Gaelic',
            'gl' => 'gl',// 'Galician',
            'lg' => 'lg',// 'Ganda',
            'ka' => 'ka',// 'Georgian',
            'de' => 'de',// 'German',
            'ki' => 'ki',// 'Gikuyu, Kikuyu',
            'el' => 'el',// 'Greek (Modern)',
            'kl' => 'kl',// 'Greenlandic, Kalaallisut',
            'gn' => 'gn',// 'Guarani',
            'gu' => 'gu',// 'Gujarati',
            'ht' => 'ht',// 'Haitian, Haitian Creole',
            'ha' => 'ha',// 'Hausa',
            'he' => 'he',// 'Hebrew',
            'hz' => 'hz',// 'Herero',
            'hi' => 'hi',// 'Hindi',
            'ho' => 'ho',// 'Hiri Motu',
            'hu' => 'hu',// 'Hungarian',
            'is' => 'is',// 'Icelandic',
            'io' => 'io',// 'Ido',
            'ig' => 'ig',// 'Igbo',
            'id' => 'id',// 'Indonesian',
            'ia' => 'ia',// 'Interlingua (International Auxiliary Language Association)',
            'ie' => 'ie',// 'Interlingue',
            'iu' => 'iu',// 'Inuktitut',
            'ik' => 'ik',// 'Inupiaq',
            'ga' => 'ga',// 'Irish',
            'it' => 'it',// 'Italian',
            'ja' => 'ja',// 'Japanese',
            'jv' => 'jv',// 'Javanese',
            'kn' => 'kn',// 'Kannada',
            'kr' => 'kr',// 'Kanuri',
            'ks' => 'ks',// 'Kashmiri',
            'kk' => 'kk',// 'Kazakh',
            'rw' => 'rw',// 'Kinyarwanda',
            'kv' => 'kv',// 'Komi',
            'kg' => 'kg',// 'Kongo',
            'ko' => 'ko',// 'Korean',
            'kj' => 'kj',// 'Kwanyama, Kuanyama',
            'ku' => 'ku',// 'Kurdish',
            'ky' => 'ky',// 'Kyrgyz',
            'lo' => 'lo',// 'Lao',
            'la' => 'la',// 'Latin',
            'lv' => 'lv',// 'Latvian',
            'lb' => 'lb',// 'Letzeburgesch, Luxembourgish',
            'li' => 'li',// 'Limburgish, Limburgan, Limburger',
            'ln' => 'ln',// 'Lingala',
            'lt' => 'lt',// 'Lithuanian',
            'lu' => 'lu',// 'Luba-Katanga',
            'mk' => 'mk',// 'Macedonian',
            'mg' => 'mg',// 'Malagasy',
            'ms' => 'ms',// 'Malay',
            'ml' => 'ml',// 'Malayalam',
            'mt' => 'mt',// 'Maltese',
            'gv' => 'gv',// 'Manx',
            'mi' => 'mi',// 'Maori',
            'mr' => 'mr',// 'Marathi',
            'mh' => 'mh',// 'Marshallese',
            'ro' => 'ro',// 'Moldovan, Moldavian, Romanian',
            'mn' => 'mn',// 'Mongolian',
            'na' => 'na',// 'Nauru',
            'nv' => 'nv',// 'Navajo, Navaho',
            'nd' => 'nd',// 'Northern Ndebele',
            'ng' => 'ng',// 'Ndonga',
            'ne' => 'ne',// 'Nepali',
            'se' => 'se',// 'Northern Sami',
            'no' => 'no',// 'Norwegian',
            'nb' => 'nb',// 'Norwegian Bokmål',
            'nn' => 'nn',// 'Norwegian Nynorsk',
            'ii' => 'ii',// 'Nuosu, Sichuan Yi',
            'oc' => 'oc',// 'Occitan (post 1500)',
            'oj' => 'oj',// 'Ojibwa',
            'or' => 'or',// 'Oriya',
            'om' => 'om',// 'Oromo',
            'os' => 'os',// 'Ossetian, Ossetic',
            'pi' => 'pi',// 'Pali',
            'pa' => 'pa',// 'Panjabi, Punjabi',
            'ps' => 'ps',// 'Pashto, Pushto',
            'fa' => 'fa',// 'Persian',
            'pl' => 'pl',// 'Polish',
            'pt' => 'pt',// 'Portuguese',
            'qu' => 'qu',// 'Quechua',
            'rm' => 'rm',// 'Romansh',
            'rn' => 'rn',// 'Rundi',
            'ru' => 'ru',// 'Russian',
            'sm' => 'sm',// 'Samoan',
            'sg' => 'sg',// 'Sango',
            'sa' => 'sa',// 'Sanskrit',
            'sc' => 'sc',// 'Sardinian',
            'sr' => 'sr',// 'Serbian',
            'sn' => 'sn',// 'Shona',
            'sd' => 'sd',// 'Sindhi',
            'si' => 'si',// 'Sinhala, Sinhalese',
            'sk' => 'sk',// 'Slovak',
            'sl' => 'sl',// 'Slovenian',
            'so' => 'so',// 'Somali',
            'st' => 'st',// 'Sotho, Southern',
            'nr' => 'nr',// 'South Ndebele',
            'es' => 'es',// 'Spanish, Castilian',
            'su' => 'su',// 'Sundanese',
            'sw' => 'sw',// 'Swahili',
            'ss' => 'ss',// 'Swati',
            'sv' => 'sv',// 'Swedish',
            'tl' => 'tl',// 'Tagalog',
            'ty' => 'ty',// 'Tahitian',
            'tg' => 'tg',// 'Tajik',
            'ta' => 'ta',// 'Tamil',
            'tt' => 'tt',// 'Tatar',
            'te' => 'te',// 'Telugu',
            'th' => 'th',// 'Thai',
            'bo' => 'bo',// 'Tibetan',
            'ti' => 'ti',// 'Tigrinya',
            'to' => 'to',// 'Tonga (Tonga Islands)',
            'ts' => 'ts',// 'Tsonga',
            'tn' => 'tn',// 'Tswana',
            'tr' => 'tr',// 'Turkish',
            'tk' => 'tk',// 'Turkmen',
            'tw' => 'tw',// 'Twi',
            'ug' => 'ug',// 'Uighur, Uyghur',
            'uk' => 'uk',// 'Ukrainian',
            'ur' => 'ur',// 'Urdu',
            'uz' => 'uz',// 'Uzbek',
            've' => 've',// 'Venda',
            'vi' => 'vi',// 'Vietnamese',
            'vo' => 'vo',// 'Volap_k',
            'wa' => 'wa',// 'Walloon',
            'cy' => 'cy',// 'Welsh',
            'fy' => 'fy',// 'Western Frisian',
            'wo' => 'wo',// 'Wolof',
            'xh' => 'xh',// 'Xhosa',
            'yi' => 'yi',// 'Yiddish',
            'yo' => 'yo',// 'Yoruba',
            'za' => 'za',// 'Zhuang, Chuang',
            'zu' => 'zu',// 'Zulu'
        ];

        return $codes;
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
        return "/language";
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
