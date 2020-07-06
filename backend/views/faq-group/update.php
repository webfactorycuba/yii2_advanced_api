<?php

/* @var $this yii\web\View */
/* @var $model backend\models\support\FaqGroup */

$this->title = Yii::t('backend', 'Actualizar').' '. Yii::t('backend', 'Grupo de FAQ').': '. $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Grupos de FAQ'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Actualizar');
?>
<div class="faq-group-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
