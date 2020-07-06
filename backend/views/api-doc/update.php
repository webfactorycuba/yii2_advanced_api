<?php

/* @var $this yii\web\View */
/* @var $model backend\models\support\ApiDoc */

$this->title = Yii::t('backend', 'Actualizar').' '. Yii::t('backend', 'Documentación').': '. $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Documentación API'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Actualizar');
?>
<div class="api-doc-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
