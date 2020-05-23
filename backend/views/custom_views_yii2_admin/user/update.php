<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \common\models\User */

$this->title = Yii::t('backend','Actualizar usuario').': ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend','Actualizar');
?>
<div class="user-update">


    <?= $this->render('_custom_form_client', [
        'model' => $model,
    ]) ?>

</div>
