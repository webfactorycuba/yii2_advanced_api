<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model backend\models\settings\ConfigMailer */
/* @var $form yii\widgets\ActiveForm */
?>

    <?php
    $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
    ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'class')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'host')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <?=
                $form->field($model, "port")->widget(MaskedInput::className(), [
                        "clientOptions" => [
                            "alias" =>  "integer",
                        ],
                        "options" => [
                            "disabled" => false,
                            "class"=> "form-control",
                            "maxlength" => 15,
                        ],
                ])->error(false)
                ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'encryption')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-2">
                <?=
                $form->field($model, "timeout")->widget(MaskedInput::className(), [
                    "clientOptions" => [
                        "alias" =>  "integer",
                    ],
                    "options" => [
                        "disabled" => false,
                        "class"=> "form-control",
                        "maxlength" => 15,
                    ],
                ])->error(false)
                ?>
            </div>
        </div>

        <div class="box-footer">
            <?= Html::a('<i class="fa fa-send"></i> '.Yii::t('backend','Probar configuración'),['config-mailer/test_mailer','id'=>$model->id], ['class' => 'btn btn-default btn-flat', 'title' => Yii::t('backend','Probar configuración')]) ?>
            <?= Html::submitButton('<i class="fa fa-pencil"></i> '.Yii::t('yii', 'Update'), ['class' => 'btn btn-default btn-flat margin']) ?>
            <?= Html::a('<i class="fa fa-remove"></i> '.Yii::t('backend','Cancelar'),['/'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend','Cancelar')]) ?>
        </div>

    <?php ActiveForm::end(); ?>


