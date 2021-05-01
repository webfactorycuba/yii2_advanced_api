<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator jac\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */

$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\widgets\FileInput;
use kartik\switchinput\SwitchInput;
use dosamigos\ckeditor\CKEditor;
use kartik\date\DatePicker;
use kartik\number\NumberControl;
use common\models\GlobalFunctions;
use kartik\datecontrol\DateControl;
<?php
$index = 1;
foreach ($generator->getColumnNames() as $attribute) {
   if (strlen($attribute) > 3) {

		$fieldrelation = substr($attribute, strlen($attribute)-3, 3);
   		$classrelation = ucfirst(substr($attribute, 0, strlen($attribute)-3));

		$classrelation = Inflector::camel2words($classrelation);
		$classrelation = str_replace(' ','',$classrelation);


       if ($classrelation == 'User') { ?>
use common\models\User;<?php echo "\n";
       } else {
           if ($fieldrelation == '_id') { ?>
use <?= trim('backend\\models\\' . $classrelation) ?>;<?php echo "\n";
           }
       }
		if ($index == 1) {
			?>
use kartik\select2\Select2;<?php echo "\n" ?>
use yii\helpers\ArrayHelper;<?php echo "\n" ?>
<?php
		}
		$index++;
   	}
}
?>

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box-body">
<?= "<?php \n" ?> $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <?php
        $fields = [];
        $num = 1;
        $count = $generator->getColumnNames();
        $exception = ['userUpdate','createDate','updateDate','userCreate'];
        foreach ($generator->getColumnNames() as $attribute) {
            $column = $generator->getTableSchema()->columns[$attribute];
            $type = $generator->getTableSchema()->columns[$attribute]->type;
            $dbtype = $generator->getTableSchema()->columns[$attribute]->dbType;
            $generaselect2 = false;

            $comment = $generator->getTableSchema()->columns[$attribute]->comment;

            if (strlen($attribute) > 3) {
                $fieldrelation = substr($attribute, strlen($attribute)-3, 3);
                $classrelation = ucfirst(substr($attribute, 0, strlen($attribute)-3));

                $classrelation = Inflector::camel2words($classrelation);
                $classrelation = str_replace(' ','',$classrelation);

                if ($fieldrelation == '_id' ) {
                    $generaselect2 = true;
                }
            }

            if (in_array($attribute, $safeAttributes)) {
                if ($generaselect2 == true) {
                    $fields[] ='   
    <?=
        $form->field($model, "'.$attribute.'")->widget(Select2::classname(), [
            "data" => '.$classrelation.'::getSelectMap(),
            "language" => Yii::$app->language,
            "options" => ["placeholder" => "----", "multiple"=>false],
            "pluginOptions" => [
                "allowClear" => true
            ],
        ]);
    ?>
    ';
                }
                elseif($type == 'integer')
                {
                    $fields[] ='
    <?= 
        $form->field($model, "'.$attribute.'")->widget(NumberControl::classname(), [
            "maskedInputOptions" => [
                "allowMinus" => false,
                "groupSeparator" => ".",
                "radixPoint" => ",",
                "digits" => 0
            ],
            "displayOptions" => ["class" => "form-control kv-monospace"],
            "saveInputContainer" => ["class" => "kv-saved-cont"]
        ])
    ?> 
    ';
                }
                elseif($type == 'decimal' || $type == 'numeric' || $type == 'float' || $type == 'double')
                {
                    $fields[] ='
    <?= 
        $form->field($model, "'.$attribute.'")->widget(NumberControl::classname(), [
            "maskedInputOptions" => [
                "allowMinus" => false,
                "groupSeparator" => ".",
                "radixPoint" => ",",
                "digits" => 2
            ],
            "displayOptions" => ["class" => "form-control kv-monospace"],
            "saveInputContainer" => ["class" => "kv-saved-cont"]
        ])
    ?>                
    ';
                }
                elseif($attribute == 'photo' || $attribute == 'document' || $attribute == 'image') {
                    $fields[] =  '
    <?=
        $form->field($model, "'.$attribute.'")->widget(FileInput::classname(), [
            "language" => Yii::$app->language,
            "pluginOptions" => GlobalFunctions::getConfigFileInputWithPreview($model->getImageFile(), $model->id),
        ]);
    ?>
    ';
                } elseif($type == 'text') {
                    $fields[] = ' 
    <?= 
        $form->field($model, "'.$attribute.'")->widget(CKEditor::className(), [
            "preset" => "custom",
            "clientOptions" => [
                "toolbar" => GlobalFunctions::getToolBarForCkEditor(),
            ],
        ])
    ?>
    ';
                }elseif ($type =='datetime' || $type =='timestamp'){
                    $fields[] = '
    <?=
        $form->field($model, "'.$attribute.'")->widget(DateControl::classname(), [
            "type" => DateControl::FORMAT_DATETIME
        ])
    ?>
    ';
                }elseif ($type =='date'){
                    $fields[] = '
   <?=
        $form->field($model, "'.$attribute.'")->widget(DateControl::classname(), [
            "type" => DateControl::FORMAT_DATE
        ])
    ?>
    ';
                }elseif ($type =='time'){
                    $fields[] = '
    <?=
        $form->field($model, "'.$attribute.'")->widget(DateControl::classname(), [
            "type" => DateControl::FORMAT_TIME
        ])
    ?>
    ';
                }
                elseif($attribute == 'status') {
                    $fields[] =  '
    <?=
        $form->field($model,"'.$attribute.'")->widget(SwitchInput::classname(), [
            "type" => SwitchInput::CHECKBOX,
            "pluginOptions" => [
                "onText"=> Yii::t("backend","Activo"),
                "offText"=> Yii::t("backend","Inactivo")
            ]
        ])
    ?>
    ';
                }
                elseif(($dbtype === 'boolean' || $dbtype == 'tinyint(1)') && $attribute !== 'status') {
                    $fields[] =  '
    <?=
        $form->field($model,"'.$attribute.'")->widget(SwitchInput::classname(), [
            "type" => SwitchInput::CHECKBOX,
            "pluginOptions" => [
                "onText"=> Yii::t("backend","SI"),
                "offText"=> Yii::t("backend","NO")
            ]
        ])
    ?>
    ';
                }
                else {
                    if(!in_array($attribute, $exception))
                        $fields[] = "\n    <?= " . $generator->generateActiveField($attribute) . " ?>\n";
                }
                $num++;
            }

        }
        ?>

<?php foreach ($fields as $val) {
        echo "         " . $val;
} ?>

</div>
<div class="box-footer">
    <?= "<?= " ?>Html::submitButton($model->isNewRecord ? '<i class="fa fa-plus"></i> '.Yii::t('backend','Crear') : '<i class="fa fa-pencil"></i> '.Yii::t('yii', 'Update'), ['class' => 'btn btn-default btn-flat']) ?>
    <?= "<?= " ?>Html::a('<i class="fa fa-remove"></i> '.Yii::t('backend','Cancelar'),['index'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend','Cancelar')]) ?>
</div>
<?= "<?php " ?>ActiveForm::end(); ?>

