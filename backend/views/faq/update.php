<?php

/* @var $this yii\web\View */
/* @var $model backend\models\support\Faq */

$this->title = Yii::t('backend', 'Actualizar').' '. Yii::t('backend', 'Faq').': '. $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Faqs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Actualizar');
?>
<div class="faq-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
