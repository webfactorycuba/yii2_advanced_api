<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use backend\models\i18n\SourceMessage;

use kartik\grid\GridView;
use mdm\admin\components\Helper;
use yii\web\View;
use yii\helpers\Url;
use backend\components\Footer_Bulk_Delete;
use backend\components\Custom_Settings_Column_GridView;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model backend\models\i18n\SourceMessage */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
Modal::begin([
    'header' => '<h4>'.Yii::t('backend','Traducción').'</h4>',
    'id' => 'modal',
    'size' => 'modal-lg',
    'options'=>['tabindex'=>false],
]);

echo "<div id='modalContent'></div>";
Modal::end();

?>

    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body">

        <div class="row">
            <div class="col-md-2">
                <?= $form->field($model, 'category')->widget(Select2::classname(), [
                    'data' => SourceMessage::getCategoriesTranslationsList(),
                    'language' => Yii::$app->language,
                    'options' => ['placeholder' => '-----'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>
            </div>
        </div>

        <h4 class="text-center"><?= Yii::t('backend','Listado de Mensajes de Traducción').':' ?></h4>

        <div class="row">
            <div class="col-md-12">
                <?php
                $create_button='';
                ?>

                <?php
                if (Helper::checkRoute('/message/create')) {
                    $create_button = Html::a('<i class="fa fa-plus"></i> '.Yii::t('backend', 'Crear'), ['/message/create', 'source_message_id' => $model->id], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend', 'Crear').' '.Yii::t('backend', 'Traducción')]);
                }

                $custom_elements_gridview = new Custom_Settings_Column_GridView($create_button,$dataProvider);
                ?>

                <div class="box-body">
                    <?php // echo $this->render('_search', ['model' => $searchModel]);

        $my_custom_action_column = [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'vAlign'=>'middle',
            'template' => Helper::filterActionColumn(['update', 'delete']),
            'buttons' => [
                'update' => function($url, $model) {
                    $options = [
                        'class' => 'btn btn-xs btn-default btn-flat',
                        'title' => Yii::t('yii','Update'),
                        'data-toggle' => 'tooltip',
                    ];

                    $url_path = Url::to(['message/update','id'=>$model->id, 'language'=> $model->language, 'return_update'=>true]);
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url_path, $options);
                },
                'delete' => function($url, $model) {
                    $options = [
                        'class' => 'btn btn-xs btn-danger btn-flat',
                        'title' => Yii::t('yii','Delete'),
                        'data-toggle' => 'tooltip',
                        'data-confirm' => Yii::t('backend', '¿Seguro desea eliminar este elemento?'),
                        'data-method' => 'post',
                    ];

                    $url_path = Url::to(['message/delete','id'=>$model->id, 'language'=> $model->language, 'return_update'=>true]);
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url_path, $options);
                }
            ],
            'updateOptions' => [
                'class' => 'btn btn-xs btn-default btn-flat',
                'title' => Yii::t('yii','Update'),
                'data-toggle' => 'tooltip',
            ],
            'deleteOptions' => [
                'class' => 'btn btn-xs btn-danger btn-flat',
                'title' => Yii::t('yii','Delete'),
                'data-toggle' => 'tooltip',
                'data-confirm' => Yii::t('backend', '¿Seguro desea eliminar este elemento?'),
            ],

        ];
        $custom_elements_gridview->setActionColumn($my_custom_action_column);
    ?>

                    <?= GridView::widget([
                        'id'=>'grid',
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [

                            $custom_elements_gridview->getSerialColumn(),

                            [
                                'attribute'=>'language',
                                'contentOptions'=>['class'=>'kv-align-left kv-align-middle'],
                                'hAlign'=>'center',
                                'format'=> 'html',
                                'value' => function ($data) {
                                    $url = urldecode(Url::toRoute(['update', 'id' => $data->id]));
                                    return Html::a($data->language, $url, ['data-pjax'=>0, 'data-method'=>"post"]);
                                }
                            ],

                            [
                                'attribute'=>'translation',
                                'contentOptions'=>['class'=>'kv-align-left kv-align-middle'],
                                'hAlign'=>'center',
                                'format'=> 'html',
                                'value' => function ($data) {
                                    $url = urldecode(Url::toRoute(['update', 'id' => $data->id]));
                                    return Html::a($data->translation, $url, ['data-pjax'=>0, 'data-method'=>"post"]);
                                }
                            ],

                            $custom_elements_gridview->getActionColumn(),


                        ],

                        'toolbar' =>  $custom_elements_gridview->getToolbar(),

                        'panel' => [
                            'type' => 'default',
                        ],

                        'toggleDataOptions' => $custom_elements_gridview->getTogleDataOptions(),
                    ]); ?>
                </div>
            </div>
        </div>


    </div>
    <div class="box-footer">
         <?= Html::submitButton('<i class="fa fa-pencil"></i> '.Yii::t('yii', 'Update'), ['class' => 'btn btn-default btn-flat']) ?>
         <?= Html::a('<i class="fa fa-remove"></i> '.Yii::t('backend','Cancelar'), ['index'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend','Cancelar')]) ?>
    </div>
    <?php ActiveForm::end(); ?>

