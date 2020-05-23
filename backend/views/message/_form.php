<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use backend\models\i18n\Message;

/* @var $this yii\web\View */
/* @var $model backend\models\i18n\Message */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box-body">
<?php 
 $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <div class="callout callout-info">
                <h4><?= Yii::t('backend', 'Mensaje original').':' ?></h4>

                <p><?= $model->id0->message ?></p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'language')->widget(Select2::classname(), [
                'data' => ($model->isNewRecord)? Message::getTranslationsCodesAvailables($model->id) : Message::getTranslationsCodesAvailables($model->id,$model->language),
                'options' => ['placeholder' => '-----'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'translation')->textarea(['rows' => 6]) ?>
        </div>
    </div>

</div>
<div class="box-footer">
    <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-plus"></i> '.Yii::t('backend','Crear') : '<i class="fa fa-pencil"></i> '.Yii::t('yii', 'Update'), ['class' => 'btn btn-default btn-flat']) ?>
    <?= Html::a('<i class="fa fa-remove"></i> '.Yii::t('backend','Cancelar'), ['source-message/update', 'id'=> $model->id], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend','Cancelar')]) ?>
</div>
<?php ActiveForm::end(); ?>

