<?php

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
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\support\ApiDoc;

/* @var $this yii\web\View */
/* @var $model backend\models\support\ApiDoc */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box-body">
    <?php
    $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <?=
            $form->field($model, "type")->widget(Select2::classname(), [
                "data" => ApiDoc::getGenericSelectType(),
                "language" => Yii::$app->language,
                "options" => ["placeholder" => "----", "multiple" => false],
                "pluginOptions" => [
                    "allowClear" => true
                ],
            ]);
            ?>
        </div>
        <div class="col-md-3">
            <?=
            $form->field($model, "status")->widget(SwitchInput::classname(), [
                "type" => SwitchInput::CHECKBOX,
                "pluginOptions" => [
                    "onText" => Yii::t("backend", "Activo"),
                    "offText" => Yii::t("backend", "Inactivo")
                ]
            ])
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <?=
            $form->field($model, "get")->widget(SwitchInput::classname(), [
                "type" => SwitchInput::CHECKBOX,
                "pluginOptions" => [
                    "onText" => Yii::t("backend", "SI"),
                    "offText" => Yii::t("backend", "NO")
                ]
            ])
            ?>
        </div>


        <div class="col-md-2">
            <?=
            $form->field($model, "post")->widget(SwitchInput::classname(), [
                "type" => SwitchInput::CHECKBOX,
                "pluginOptions" => [
                    "onText" => Yii::t("backend", "SI"),
                    "offText" => Yii::t("backend", "NO")
                ]
            ])
            ?>
        </div>
        <div class="col-md-2">
            <?=
            $form->field($model, "put")->widget(SwitchInput::classname(), [
                "type" => SwitchInput::CHECKBOX,
                "pluginOptions" => [
                    "onText" => Yii::t("backend", "SI"),
                    "offText" => Yii::t("backend", "NO")
                ]
            ])
            ?>
        </div>
        <div class="col-md-2">
            <?=
            $form->field($model, "delete")->widget(SwitchInput::classname(), [
                "type" => SwitchInput::CHECKBOX,
                "pluginOptions" => [
                    "onText" => Yii::t("backend", "SI"),
                    "offText" => Yii::t("backend", "NO")
                ]
            ])
            ?>
        </div>
        <div class="col-md-2">
            <?=
            $form->field($model, "options")->widget(SwitchInput::classname(), [
                "type" => SwitchInput::CHECKBOX,
                "pluginOptions" => [
                    "onText" => Yii::t("backend", "SI"),
                    "offText" => Yii::t("backend", "NO")
                ]
            ])
            ?>
        </div>
        <div class="col-md-12">
            <?=
            $form->field($model, "description")->widget(CKEditor::className(), [
                "preset" => "custom",
                "clientOptions" => [
                    "toolbar" => GlobalFunctions::getToolBarForCkEditor(true),
                ],
            ])
            ?>
        </div>
    </div>
</div>

<div class="box-footer">
    <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-plus"></i> ' . Yii::t('backend', 'Crear') : '<i class="fa fa-pencil"></i> ' . Yii::t('yii', 'Update'), ['class' => 'btn btn-default btn-flat']) ?>
    <?= Html::a('<i class="fa fa-remove"></i> ' . Yii::t('backend', 'Cancelar'), ['index'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend', 'Cancelar')]) ?>
</div>
<?php ActiveForm::end(); ?>

