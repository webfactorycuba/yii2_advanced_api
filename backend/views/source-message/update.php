<?php

/* @var $this yii\web\View */
/* @var $model backend\models\i18n\SourceMessage */

$this->title = Yii::t('backend','Actualizar traducción').': ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Traducciones'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('yii','Update');
?>
<div class="source-message-update">

    <?= $this->render('_form_update', [
        'model' => $model,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>
