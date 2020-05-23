<?php

use yii\helpers\Html;
use common\widgets\DetailView;
use backend\models\i18n\Message;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $model backend\models\i18n\SourceMessage */

$controllerId = $this->context->uniqueId . '/';
$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Traducciones'), 'url' => ['index']];
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
            <div class="col-md-12">
                <?= DetailView::widget([
                    'model' => $model,
                    'bordered'=>false,
                    'striped'=>false,
                    'condensed'=>false,
                    'responsive'=>true,
                    'hover'=>true,
                    'attributes' => [
                        'id',
                        'category',
                        'message:ntext',
                    ],
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                    $lits_messages = $model->getAllMessagesList();

                    if(count($lits_messages) > 0)
                    {

                        foreach ($lits_messages AS $index => $message)
                        {
                        ?>
                            <div class="col-md-4">
                                <div class="box box-default box-solid">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><?= Yii::t('backend', 'Traducción').': '.strtoupper($message->language) ?></h3>

                                        <div class="box-tools pull-right">
                                            <?php
                                                if (Helper::checkRoute('/message/update')) {
                                                    echo Html::a('<i class="fa fa-pencil"></i> ', ['message/update', 'id' => $model->id, 'language' => $message->language], ['class' => 'btn btn-box-tool', 'data-toggle' => 'tooltip', 'title'=>Yii::t('yii','Update'), 'data-original-title'=> Yii::t('yii','Update')]);
                                                }
                                            ?>

                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <!-- /.box-tools -->
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <?php
                                            if($message->translation === null || $message->translation === '')
                                            {
                                                echo Yii::t('backend','Sin traducir');
                                            }
                                            else
                                            {
                                                echo $message->translation;
                                            }
                                        ?>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->
                            </div>
                        <?php
                        }

                    }

                ?>
            </div>
        </div>
    </div>