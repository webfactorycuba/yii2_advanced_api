<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-plus"></i> '.Yii::t('rbac-admin','Create') : '<i class="fa fa-pencil"></i> '.Yii::t('yii', 'Update'), ['class' => 'btn btn-default btn-flat']) ?>

    </div>

    <?php ActiveForm::end(); ?>

</div>
