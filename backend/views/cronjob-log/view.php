<?php

use yii\helpers\Html;
use common\widgets\DetailView;
use mdm\admin\components\Helper;
use common\models\GlobalFunctions;
use backend\models\CronjobTask;

/* @var $this yii\web\View */
/* @var $model backend\models\support\CronjobLog */

$controllerId = '/'.$this->context->uniqueId.'/';
$this->title = $model->cronjobTask->name . ' -> ' . $model->execution_date;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Trazas de CronJob'), 'url' => ['index']];
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
            'labelColOptions' => ['style' => 'width: 40%'],
            'attributes' => [
                'id',
                [
                    'attribute'=> 'cronjob_task_id',
                    'value'=> $model->getCronjobTaskLink(),
                    'format'=> 'html',
                ],
        
                [
                    'attribute'=> 'message',
                    'value'=> $model->getMessage(),
                    'format'=> 'html',
                ],
                
                [
                    'attribute'=> 'execution_date',
                    'value'=> GlobalFunctions::formatDateToShowInSystem($model->execution_date),
                    'format'=> 'html',
                ],
                
                [
                    'attribute'=> 'created_at',
                    'value'=> GlobalFunctions::formatDateToShowInSystem($model->created_at),
                    'format'=> 'html',
                ],
                
            ],
        ]) ?>
    </div>
