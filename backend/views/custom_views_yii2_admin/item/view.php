<?php

use mdm\admin\AnimateAsset;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\YiiAsset;
use common\widgets\DetailView;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\AuthItem */
/* @var $context mdm\admin\components\ItemController */

$controllerId = $this->context->uniqueId . '/';
$context = $this->context;
$labels = $context->labels();
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', $labels['Items']), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

AnimateAsset::register($this);
YiiAsset::register($this);
$opts = Json::htmlEncode([
    'items' => $model->getItems(),
]);
$this->registerJs("var _opts = {$opts};");

$label_roles = Yii::t('backend','Roles');
$this->registerJs("var label_roles = '$label_roles';");

$label_routes = Yii::t('backend','Rutas');
$this->registerJs("var label_routes = '$label_routes';");

$label_permissions = Yii::t('backend','Permisos');
$this->registerJs("var label_permissions = '$label_permissions';");

$this->registerJs($this->render('_script.js'));
$animateIcon = ' <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>';
?>
<div class="auth-item-view">

    <div class="box-header">

        <?php
        if (Helper::checkRoute($controllerId . 'update')) {
            echo Html::a('<i class="fa fa-pencil"></i> '.Yii::t('yii','Update'), ['update', 'id' => $model->name], ['class' => 'btn btn-default btn-flat margin']);
        }

        echo Html::a('<i class="fa fa-remove"></i> '.Yii::t('backend','Cancelar'), ['index'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend','Cancelar')]);

        if (Helper::checkRoute($controllerId . 'delete')) {
            echo Html::a('<i class="fa fa-trash"></i> '.Yii::t('yii','Delete'), ['delete', 'id' => $model->name], [
                'class' => 'btn btn-danger btn-flat margin',
                'data' => [
                    'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]);
        }

        ?>

    </div>
    <div class="row">
        <div class="col-sm-11">
            <?=
DetailView::widget([
    'model' => $model,
    'attributes' => [
        'name',
        'description:ntext',
        //'ruleName',
        //'data:ntext',
    ],
    'template' => '<tr><th style="width:25%">{label}</th><td>{value}</td></tr>',
]);
?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-5">
            <input class="form-control search" data-target="available"
                   placeholder="<?=Yii::t('rbac-admin', 'Search for available');?>">
            <select multiple size="20" class="form-control list" data-target="available"></select>
        </div>
        <div class="col-sm-1">
            <br><br>
            <?=Html::a('&gt;&gt;' . $animateIcon, ['assign', 'id' => $model->name], [
    'class' => 'btn btn-success btn-assign',
    'data-target' => 'available',
    'title' => Yii::t('backend', 'Asignar'),
    'data-toggle' => 'tooltip'
]);?><br><br>
            <?=Html::a('&lt;&lt;' . $animateIcon, ['remove', 'id' => $model->name], [
    'class' => 'btn btn-danger btn-assign',
    'data-target' => 'assigned',
    'title' => Yii::t('backend', 'Revocar'),
    'data-toggle' => 'tooltip'
]);?>
        </div>
        <div class="col-sm-5">
            <input class="form-control search" data-target="assigned"
                   placeholder="<?=Yii::t('rbac-admin', 'Search for assigned');?>">
            <select multiple size="20" class="form-control list" data-target="assigned"></select>
        </div>
    </div>
</div>
