<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\widgets\FileInput;
use kartik\switchinput\SwitchInput;
use dosamigos\ckeditor\CKEditor;
use kartik\date\DatePicker;
use yii\widgets\MaskedInput;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\form\ActiveField;

/* @var $this yii\web\View */
/* @var $model backend\models\support\FaqGroup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box-body">
<?php 
 $form = ActiveForm::begin([
         'options' => ['enctype' => 'multipart/form-data'],
         'fieldConfig' => [
              'showHints' => true,
              'hintType' => ActiveField::HINT_SPECIAL,
         ],
 ]); ?>

    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-2">
            <?=
            $form->field($model,"status")->widget(SwitchInput::classname(), [
                "type" => SwitchInput::CHECKBOX,
                "pluginOptions" => [
                    "onText"=> Yii::t("backend","Activo"),
                    "offText"=> Yii::t("backend","Inactivo")
                ]
            ])
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?=
            $form->field($model, "description")->widget(\dosamigos\ckeditor\CKEditor::className(), [
                "options" => ["rows" => 6],
                "preset" => "basic",
                "clientOptions" => [
                    'filebrowserUploadUrl' => \yii\helpers\Url::to(['/site/ckeditorupload']),
                ]
            ])
            ?>
        </div>
    </div>

</div>
<div class="box-footer">
    <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-plus"></i> '.Yii::t('backend','Crear') : '<i class="fa fa-pencil"></i> '.Yii::t('yii', 'Update'), ['class' => 'btn btn-default btn-flat']) ?>
    <?= Html::a('<i class="fa fa-remove"></i> '.Yii::t('backend','Cancelar'),['index'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend','Cancelar')]) ?>
</div>
<?php ActiveForm::end(); ?>

