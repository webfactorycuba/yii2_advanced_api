<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\i18n\Language */

$this->title = Yii::t('backend', 'Actualizar').' '. Yii::t('backend', 'Idioma').': '. $model->code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Idiomas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->code, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Actualizar');
?>
<div class="language-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
