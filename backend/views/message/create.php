<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\i18n\Message */

$this->title = Yii::t('backend','Crear').' '.Yii::t('backend','Mensaje de Traducción');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Traducciones'), 'url' => ['source-message/index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['source-message/update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend','Crear').' '.Yii::t('backend','Mensaje de Traducción');
?>
<div class="message-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
