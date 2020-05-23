<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model backend\models\settings\TestMailer */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('backend', 'Probador de configuración de correo');

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-mailer-update">

    <?php
    $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
    ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>

    </div>


    <div class="box-footer">
        <?= Html::submitButton('<i class="fa fa-send"></i> '.Yii::t('backend', 'Enviar'), ['class' => 'btn btn-default btn-flat margin']) ?>
        <?= Html::a('<i class="fa fa-remove"></i> '.Yii::t('backend','Cancelar'),['config-mailer/update','id'=>1], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend','Cancelar')]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
