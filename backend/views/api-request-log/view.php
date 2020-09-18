<?php

use yii\helpers\Html;
use common\widgets\DetailView;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $model backend\models\support\ApiRequestLog */

$controllerId = '/'.$this->context->uniqueId.'/';
$this->title = Yii::t("backend", "Traza") . " #" .$model->id . " - (" . $model->action_id . ")";
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Trazas de Peticiones API'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="box-header">
        <?php
        echo Html::a('<i class="fa fa-remove"></i> '.Yii::t('backend','Cancelar'), ['index'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend','Cancelar')]);

        if (Helper::checkRoute($controllerId . 'delete')) {
            echo Html::a('<i class="fa fa-trash"></i> '.Yii::t('yii','Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger btn-flat margin',
                'data' => [
                    'confirm' => Yii::t('yii','Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]);
        }
        ?>
    </div>
    <div class="box-body">
        <?= DetailView::widget([
            'model' => $model,
            'labelColOptions' => ['style' => 'width: 15%'],
            'attributes' => [
                'id',
                [
                    'attribute'=> 'ip',
                    'value'=> $model->getIp(),
                    'format'=> 'html',
                ],
                [
                    'attribute'=> 'user_agent',
                    'value'=> $model->getUserAgent(),
                    'format'=> 'html',
                ],
                [
                    'attribute'=> 'action_id',
                    'value'=> $model->getActionId(),
                    'format'=> 'html',
                ],

                [
                    'attribute'=> 'method',
                    'value'=> $model->getMethod(),
                    'format'=> 'html',
                ],
                [
                    'attribute'=> 'headers',
                    'value'=> $model->getHeaders(),
                    'format'=> 'html',
                ],
                
                [
                    'attribute'=> 'body',
                    'value'=> $model->getBody(),
                    'format'=> 'html',
                ],
                [
                    'attribute'=> 'created_at',
                    'value'=> $model->created_at,
                    'format'=> 'html',
                ],
                
            ],
        ]) ?>
    </div>
