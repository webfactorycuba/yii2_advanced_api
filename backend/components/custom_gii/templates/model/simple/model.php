<?php
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $properties array list of properties (property => [type, name. comment]) */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;
use backend\models\BaseModel;
use dosamigos\translateable\TranslateableBehavior;

/**
 * This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
<?php foreach ($properties as $property => $data): ?>
 * @property <?= "{$data['type']} \${$property}"  . ($data['comment'] ? ' ' . strtr($data['comment'], ["\n" => ' ']) : '') . "\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
* @property TourLang[] $translations
 */
class <?= $className ?> extends <?= 'BaseModel'. "\n" ?>
{

    /**
    * Para el correcto funcionamiento de las traducciones del modelo se debe ajustar:
    * 1. @property TourLang[] $translations, ajustar con el modelo de lang
    * 2. public function behaviors(), ajustar el 'translationAttributes' y colocar los atributos del modeloLang que van a recibir traducción
    * 3. public function getTranslations(), ajustar el return de esa función, susituir la relaciónn por defecto
    */
    public function behaviors()
    {
        return [
            'trans' => [ // name it the way you want
                'class' => TranslateableBehavior::className(),
                // in case you named your relation differently, you can setup its relation name attribute
                //'relation' => 'translations',
                // in case you named the language column differently on your translation schema
                //'languageField' => 'language',
                'translationAttributes' => [
                    'name',
                    'description',
                ],
                // use english as fallback for all languages when no translation is available
                'fallbackLanguage' => 'es',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [<?= empty($rules) ? '' : ("\n            " . implode(",\n            ", $rules) . ",\n        ") ?>];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
            <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endforeach; ?>
        ];
    }
<?php foreach ($relations as $name => $relation): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= $name ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
<?php if ($queryClassName): ?>
<?php
    $queryClassFullName = ($generator->ns === $generator->queryNs) ? $queryClassName : '\\' . $generator->queryNs . '\\' . $queryClassName;
    echo "\n";
?>
    /**
     * {@inheritdoc}
     * @return <?= $queryClassFullName ?> the active query used by this AR class.
     */
    public static function find()
    {
        return new <?= $queryClassFullName ?>(get_called_class());
    }

<?php endif; ?>

    /**
    * @return \yii\db\ActiveQuery
    public function getTranslations()
    {
        return $this->hasMany(TourLang::className(), ['tour_id' => 'id']);
    }
    */
}
