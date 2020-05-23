<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model \common\models\User */

$this->title = Yii::t('backend','Crear usuario');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">


    <?= $this->render('_custom_form_client', [
        'model' => $model,
    ]) ?>

</div>
