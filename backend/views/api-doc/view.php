<?php

use yii\helpers\Html;
use common\widgets\DetailView;
use mdm\admin\components\Helper;
use common\models\GlobalFunctions;
use backend\models\support\ApiDoc;
use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model backend\models\support\ApiDoc */

$controllerId = '/'.$this->context->uniqueId.'/';
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Documentación API'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="box-header">
        <?php 
        if (Helper::checkRoute($controllerId . 'update')) {
            echo Html::a('<i class="fa fa-pencil"></i> '.Yii::t('yii','Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-default btn-flat margin']);
        }

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
        <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                <?= DetailView::widget([
                    'model' => $model,
                    'labelColOptions' => ['style' => 'width: 40%'],
                    'attributes' => [
                        'name',
                        [
                            'attribute'=> 'type',
                            'value'=>  ApiDoc::getGenericSelectType($model->type,true),
                            'format'=> 'html',
                        ],
                        [
                            'attribute'=> 'status',
                            'value'=> GlobalFunctions::getStatusValue($model->status),
                            'format'=> 'html',
                        ],

                        [
                            'attribute'=> 'created_at',
                            'value'=> GlobalFunctions::formatDateToShowInSystem($model->created_at),
                            'format'=> 'html',
                        ],

                        [
                            'attribute'=> 'updated_at',
                            'value'=> GlobalFunctions::formatDateToShowInSystem($model->updated_at),
                            'format'=> 'html',
                        ],

                    ],
                ]) ?>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                <?= DetailView::widget([
                    'model' => $model,
                    'labelColOptions' => ['style' => 'width: 40%'],
                    'attributes' => [

                        [
                            'attribute'=> 'get',
                            'value'=> GlobalFunctions::getStatusValue($model->get,'true','badge bg-gray'),
                            'format'=> 'html',
                        ],

                        [
                            'attribute'=> 'post',
                            'value'=> GlobalFunctions::getStatusValue($model->post,'true','badge bg-gray'),
                            'format'=> 'html',
                        ],

                        [
                            'attribute'=> 'put',
                            'value'=> GlobalFunctions::getStatusValue($model->put,'true','badge bg-gray'),
                            'format'=> 'html',
                        ],

                        [
                            'attribute'=> 'delete',
                            'value'=> GlobalFunctions::getStatusValue($model->delete,'true','badge bg-gray'),
                            'format'=> 'html',
                        ],

                        [
                            'attribute'=> 'options',
                            'value'=> GlobalFunctions::getStatusValue($model->options,'true','badge bg-gray'),
                            'format'=> 'html',
                        ],

                    ],
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                <h3><?= $model->getAttributeLabel('description');?></h3>
            </div>
            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                <?= HtmlPurifier::process($model->description); ?>
            </div>
        </div>

    </div>
