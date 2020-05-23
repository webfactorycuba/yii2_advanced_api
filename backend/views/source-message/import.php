<?php

/* @var $this \yii\web\View */
/* @var $modelImport \yii\base\DynamicModel */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('backend','Importar fichero de traducciones');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Traducciones'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]);?>
<div class="row">
    <div class="col-md-5">
        <?= $form->field($modelImport,'fileImport')->fileInput(['class'=>'form-control'])->label(Yii::t('backend','Fichero a importar')) ?>
    </div>
</div>

<?= Html::submitButton('<i class="fa fa-upload"></i> '.Yii::t('backend','Importar'),['class'=>'btn btn-primary btn-flat']);?>

<?php ActiveForm::end();?>