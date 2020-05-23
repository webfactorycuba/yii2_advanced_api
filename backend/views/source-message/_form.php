<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use backend\models\i18n\SourceMessage;

/* @var $this yii\web\View */
/* @var $model backend\models\i18n\SourceMessage */
/* @var $form yii\widgets\ActiveForm */
?>

    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body">

        <div class="row">
            <div class="col-md-2">
                <?= $form->field($model, 'category')->widget(Select2::classname(), [
                    'data' => SourceMessage::getCategoriesTranslationsList(),
                    'language' => Yii::$app->language,
                    'options' => ['placeholder' => '-----'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>
            </div>
        </div>


    </div>
    <div class="box-footer">
         <?= Html::submitButton('<i class="fa fa-plus"></i> '.Yii::t('backend','Crear'), ['class' => 'btn btn-default btn-flat']) ?>
         <?= Html::a('<i class="fa fa-remove"></i> '.Yii::t('backend','Cancelar'), ['index'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend','Cancelar')]) ?>
    </div>
    <?php ActiveForm::end(); ?>

