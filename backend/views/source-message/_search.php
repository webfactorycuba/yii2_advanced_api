<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\i18n\SourceMessageSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="source-message-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'category') ?>

    <?= $form->field($model, 'message') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
