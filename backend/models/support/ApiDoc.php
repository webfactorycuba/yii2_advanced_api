<?php

namespace backend\models\support;

use Yii;
use backend\models\BaseModel;
use yii\helpers\StringHelper;
use common\models\GlobalFunctions;
use yii\helpers\Html;
use dosamigos\translateable\TranslateableBehavior;

/**
 * This is the model class for table "api_doc".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $get
 * @property int $post
 * @property int $put
 * @property int $delete
 * @property int $options
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $type
 *
 */
class ApiDoc extends BaseModel
{
    const GENERIC_SELECT_TYPE_AUTH = 1;
    const GENERIC_SELECT_TYPE_USER = 2;
    const GENERIC_SELECT_TYPE_TEST = 4;

    public $type_select_export;

    public function behaviors()
    {
        return [
            'trans' => [ // name it the way you want
                'class' => TranslateableBehavior::className(),
                // in case you named your relation differently, you can setup its relation name attribute
                'relation' => 'apiDocLangs',
                // in case you named the language column differently on your translation schema
                //'languageField' => 'language',
                'translationAttributes' => [
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
        return 'api_doc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type'],'required'],
            [['description'], 'string'],
            [['get', 'post', 'put', 'delete', 'options', 'status'], 'integer'],
            [['created_at', 'updated_at','type_select_export'], 'safe'],
            [['name', 'type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Endpoint',
            'description' => Yii::t('backend', 'Descripción'),
            'get' => 'GET',
            'post' => 'POST',
            'put' => 'PUT',
            'delete' => 'DELETE',
            'options' => 'OPTIONS',
            'status' => Yii::t('backend', 'Estado'),
            'created_at' => Yii::t('backend', 'Fecha de creación'),
            'updated_at' => Yii::t('backend', 'Fecha de actualización'),
            'type' => Yii::t('backend', 'Tipo'),
            'type_select_export' => Yii::t('backend', 'Secciones a exportar'),
        ];
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
        return "/api-doc";
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
     * @param null|integer $value
     * @param boolean $optional_value Poner este valor en true cuando se quiere mostrar en los index el valor específico pero este es opcional, evita dar error y devuelve null
     * @return array|mixed
     */
    public static function getGenericSelectType($value = null, $optional_value = false)
    {
        $array = [];

        $array[self::GENERIC_SELECT_TYPE_AUTH] = Yii::t('backend', 'Autenticación');
        $array[self::GENERIC_SELECT_TYPE_USER] = Yii::t('backend', 'Usuarios');
        $array[self::GENERIC_SELECT_TYPE_TEST] = Yii::t('backend', 'Prueba');

        if ($value !== null) {
            return (isset($array[$value]) && !empty($array[$value]))? $array[$value] : null;
        } else {
            if($optional_value)
                return null;
            else
                return $array;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApiDocLangs()
    {
        return $this->hasMany(ApiDocLang::className(), ['api_doc_id' => 'id']);
    }

    public function isGet()
    {
        return $this->get == 1;
    }

    public function isPost()
    {
        return $this->post == 1;
    }

    public function isPut()
    {
        return $this->put == 1;
    }

    public function isDelete()
    {
        return $this->delete == 1;
    }

    public function isOptions()
    {
        return $this->options == 1;
    }
}
