<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\i18n\SourceMessage */


$this->title = Yii::t('backend','Crear traducción');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Traducciones'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="source-message-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
