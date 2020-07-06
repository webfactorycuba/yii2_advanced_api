<?php

/* @var $this yii\web\View */
/* @var $model backend\models\support\Faq */

$this->title = Yii::t('backend', 'Crear').' '. Yii::t('backend', 'Faq');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Faqs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faq-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
