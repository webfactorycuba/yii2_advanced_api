<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use mdm\admin\components\Helper;
use yii\web\View;
use yii\helpers\Url;
use backend\components\Footer_Bulk_Delete;
use backend\components\Custom_Settings_Column_GridView;
use common\models\GlobalFunctions;
use yii\helpers\BaseStringHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\support\CronjobTaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controllerId = '/' . $this->context->uniqueId . '/';
$this->title = Yii::t('backend', 'Tareas de CronJob');
$this->params['breadcrumbs'][] = $this->title;

$create_button = '';
?>
<?php Pjax::begin(); ?>

<?php
if (Helper::checkRoute($controllerId . 'create')) {
    $create_button = Html::a('<i class="fa fa-plus"></i> ' . Yii::t('backend', 'Crear'), ['create'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend', 'Crear') . ' ' . Yii::t('backend', 'Tarea de CronJob')]);
}

$custom_elements_gridview = new Custom_Settings_Column_GridView($create_button, $dataProvider);


$my_custom_action_column = [
    'class' => 'kartik\grid\ActionColumn',
    'dropdown' => false,
    'vAlign' => 'middle',
    'template' => Helper::filterActionColumn(['view', 'update', 'delete']),
    'viewOptions' => [
        'class' => 'btn btn-xs btn-default btn-flat',
        'title' => Yii::t('yii', 'View'),
        'data-toggle' => 'tooltip',
    ],
    'updateOptions' => [
        'class' => 'btn btn-xs btn-default btn-flat',
        'title' => Yii::t('yii', 'Update'),
        'data-toggle' => 'tooltip',
    ],
    'deleteOptions' => [
        'class' => 'btn btn-xs btn-danger btn-flat',
        'title' => Yii::t('yii', 'Delete'),
        'data-toggle' => 'tooltip',
        'data-confirm' => Yii::t('backend', '¿Seguro desea eliminar este elemento?')
    ],
    'visibleButtons' => [
        'delete' => function ($model, $key, $index) {
            return $model->canBeDeleted();
        }
    ]

];
$custom_elements_gridview->setActionColumn($my_custom_action_column);

?>

<div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'id' => 'grid',
        'dataProvider' => $dataProvider,
        'pjax' => true,
        'pjaxSettings' => [
            'neverTimeout' => true,
            'options' => [
                'enablePushState' => false,
                'scrollTo' => false,
            ],
        ],
        'responsiveWrap' => false,
        'floatHeader' => true,
        'floatHeaderOptions' => [
            'position' => 'absolute',
            'top' => 50
        ],
        'hover' => true,
        'pager' => [
            'firstPageLabel' => Yii::t('backend', 'Primero'),
            'lastPageLabel' => Yii::t('backend', 'Último'),
        ],
        'hover' => true,
        'persistResize' => true,
        'filterModel' => $searchModel,
        'columns' => [

            $custom_elements_gridview->getSerialColumn(),

            [
                'attribute' => 'name',
                'contentOptions' => ['class' => 'kv-align-left kv-align-middle'],
                'hAlign' => 'center',
                'format' => 'html',
                'value' => function ($data) {
                    return $data->name;
                }
            ],

            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function ($data) {
                    return GlobalFunctions::getStatusValue($data->status);
                },
                'filter' => [
                    0 => Yii::t('backend', 'Inactivo'),
                    1 => Yii::t('backend', 'Activo')
                ],
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => '------'],
            ],

            [
                'attribute' => 'created_at',
                'value' => function ($data) {
                    return GlobalFunctions::formatDateToShowInSystem($data->created_at);
                },
                'contentOptions' => ['class' => 'kv-align-left kv-align-middle'],
                'hAlign' => 'center',
                'filterType' => GridView::FILTER_DATE_RANGE,
                'filterWidgetOptions' => ([
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'presetDropdown' => false,
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'locale' => [
                            'format' => 'd-M-Y'
                        ]
                    ],
                    'pluginEvents' => [
                        'apply.daterangepicker' => 'function(ev, picker) {
                                if($(this).val() == "") {
                                    picker.callback(picker.startDate.clone(), picker.endDate.clone(), picker.chosenLabel);
                                }
                            }',
                    ]
                ])
            ],

            $custom_elements_gridview->getActionColumn(),

            $custom_elements_gridview->getCheckboxColumn(),

        ],

        'toolbar' => $custom_elements_gridview->getToolbar(),

        'panel' => $custom_elements_gridview->getPanel(),

        'toggleDataOptions' => $custom_elements_gridview->getTogleDataOptions(),
    ]); ?>
</div>
<?php Pjax::end(); ?>

<?php
$url = Url::to([$controllerId . 'multiple_delete']);
$js = Footer_Bulk_Delete::getFooterBulkDelete($url);
$this->registerJs($js, View::POS_READY);
?>

