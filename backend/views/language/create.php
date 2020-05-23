<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\i18n\Language */

$this->title = Yii::t('backend', 'Crear').' '. Yii::t('backend', 'Idioma');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Idiomas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="language-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
