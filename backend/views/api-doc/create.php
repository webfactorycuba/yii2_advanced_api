<?php

/* @var $this yii\web\View */
/* @var $model backend\models\support\ApiDoc */

$this->title = Yii::t('backend', 'Crear').' '. Yii::t('backend', 'Documentación');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Documentación API'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="api-doc-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
