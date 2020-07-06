<?php

use yii\helpers\Html;
use common\widgets\DetailView;
use mdm\admin\components\Helper;
use common\models\GlobalFunctions;

/* @var $this yii\web\View */
/* @var $model backend\models\support\Faq */

$controllerId = $this->context->uniqueId . '/';
$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Faqs'), 'url' => ['index']];
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
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                [
                    'attribute'=> 'question',
                    'value'=> $model->question,
                    'format'=> 'html',
                ],
                [
                    'attribute'=> 'answer',
                    'value'=> $model->answer,
                    'format'=> 'html',
                ],
                [
                    'attribute'=> 'faq_group_id',
                    'value'=> $model->faqGroup->name,
                ],

                [
                    'attribute'=> 'image',
                    'value'=> GlobalFunctions::renderPreviewForIndex($model->getImageFile(), $model->question, ['width'=>'200px','height'=>'100%']),
                    'format' => 'raw',
                ],
                [
                    'attribute'=> 'status',
                    'value'=> GlobalFunctions::getStatusValue($model->status),
                    'format'=> 'html',
                ],
                'created_at:date',
                'updated_at:date',
            ],
        ]) ?>
    </div>
