<?php

/* @var $this yii\web\View */
/* @var $model backend\models\support\FaqGroup */

$this->title = Yii::t('backend', 'Crear').' '. Yii::t('backend', 'Grupo de FAQ');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Grupos de FAQ'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faq-group-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
