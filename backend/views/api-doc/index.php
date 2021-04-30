<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use mdm\admin\components\Helper;
use yii\web\View;
use yii\helpers\Url;
use backend\components\Footer_Bulk_Delete;
use backend\components\Custom_Settings_Column_GridView;
use common\models\GlobalFunctions;
use yii\helpers\BaseStringHelper;
use backend\models\support\ApiDoc;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\support\ApiDocSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controllerId = '/'.$this->context->uniqueId.'/';
$this->title = Yii::t('backend', 'Documentación API');
$this->params['breadcrumbs'][] = $this->title;

$create_button='';
?>

<?php
    $button_swagger = Html::button('<i class="fa fa-file-pdf-o"> </i> '.Yii::t("backend", "Exportar"), [
        'class' => "btn btn-default margin",
        'type' => 'button',
        'data-toggle' => 'modal',
        'data-target' => '#exportModal'
    ]);

    $button_export = Html::button('<i class="fa fa-file-pdf-o"> </i> '.Yii::t("backend", "Exportar"), [
        'class' => "btn btn-default margin",
        'type' => 'button',
        'data-toggle' => 'modal',
        'data-target' => '#exportModal'
    ]);

	if (Helper::checkRoute($controllerId . 'create')) {
		$create_button = Html::a('<i class="fa fa-plus"></i> '.Yii::t('backend', 'Crear'), ['create'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend', 'Crear').' '.Yii::t('backend', 'Documentación')]);
	}

	$headers_buttons = $button_export.' '.$create_button;

	$custom_elements_gridview = new Custom_Settings_Column_GridView($headers_buttons,$dataProvider);
?>

    <div class="box-body">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'id'=>'grid',
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
                'position'=>'absolute',
                'top' => 50
            ],
            'hover' => true,
            'pager' => [
                'firstPageLabel' => Yii::t('backend', 'Primero'),
                'lastPageLabel' => Yii::t('backend', 'Último'),
            ],
            'hover' => true,
            'persistResize'=>true,
            'filterModel' => $searchModel,
            'columns' => [

				$custom_elements_gridview->getSerialColumn(),
    
				[
					'attribute'=>'name',
					'contentOptions'=>['class'=>'kv-align-left kv-align-middle'],
					'hAlign'=>'center',
					'format'=> 'html',
					'value' => function ($data) {
						return $data->name;
					}
				],

                [
                    'attribute'=>'type',
                    'contentOptions'=>['class'=>'kv-align-left kv-align-middle'],
                    'hAlign'=>'center',
                    'format'=> 'html',
                    'value' => function ($data) {
                        return ApiDoc::getGenericSelectType($data->type,true);
                    },
                    'filter' => ApiDoc::getGenericSelectType(),
                    'filterType'=>GridView::FILTER_SELECT2,
                    'filterWidgetOptions'=>[
                        'pluginOptions'=>['allowClear'=>true],
                    ],
                    'filterInputOptions'=>['placeholder'=>'------'],
                ],

                [
                    'attribute' => 'get',
                    'format' => 'html',
                    'value' => function ($data) {
                        return GlobalFunctions::getStatusValue($data->get,'true','badge bg-gray');
                    },
                    'filter' => [
                        0 =>  Yii::t('backend','NO'),
                        1 => Yii::t('backend','SI')
                    ],
                    'filterType'=>GridView::FILTER_SELECT2,
                    'filterWidgetOptions'=>[
                        'pluginOptions'=>['allowClear'=>true],
                    ],
                    'filterInputOptions'=>['placeholder'=>'------'],
                ],
                                         
                [
                    'attribute' => 'post',
                    'format' => 'html',
                    'value' => function ($data) {
                        return GlobalFunctions::getStatusValue($data->post,'true','badge bg-gray');
                    },
                    'filter' => [
                        0 =>  Yii::t('backend','NO'),
                        1 => Yii::t('backend','SI')
                    ],
                    'filterType'=>GridView::FILTER_SELECT2,
                    'filterWidgetOptions'=>[
                        'pluginOptions'=>['allowClear'=>true],
                    ],
                    'filterInputOptions'=>['placeholder'=>'------'],
                ],
                                         
                [
                    'attribute' => 'put',
                    'format' => 'html',
                    'value' => function ($data) {
                        return GlobalFunctions::getStatusValue($data->put,'true','badge bg-gray');
                    },
                    'filter' => [
                        0 =>  Yii::t('backend','NO'),
                        1 => Yii::t('backend','SI')
                    ],
                    'filterType'=>GridView::FILTER_SELECT2,
                    'filterWidgetOptions'=>[
                        'pluginOptions'=>['allowClear'=>true],
                    ],
                    'filterInputOptions'=>['placeholder'=>'------'],
                ],
                                         
                [
                    'attribute' => 'delete',
                    'format' => 'html',
                    'value' => function ($data) {
                        return GlobalFunctions::getStatusValue($data->delete,'true','badge bg-gray');
                    },
                    'filter' => [
                        0 =>  Yii::t('backend','NO'),
                        1 => Yii::t('backend','SI')
                    ],
                    'filterType'=>GridView::FILTER_SELECT2,
                    'filterWidgetOptions'=>[
                        'pluginOptions'=>['allowClear'=>true],
                    ],
                    'filterInputOptions'=>['placeholder'=>'------'],
                ],
                                         
                [
                    'attribute' => 'options',
                    'format' => 'html',
                    'value' => function ($data) {
                        return GlobalFunctions::getStatusValue($data->options,'true','badge bg-gray');
                    },
                    'filter' => [
                        0 =>  Yii::t('backend','NO'),
                        1 => Yii::t('backend','SI')
                    ],
                    'filterType'=>GridView::FILTER_SELECT2,
                    'filterWidgetOptions'=>[
                        'pluginOptions'=>['allowClear'=>true],
                    ],
                    'filterInputOptions'=>['placeholder'=>'------'],
                ],
                                         
                [
                    'attribute' => 'status',
                    'format' => 'html',
                    'value' => function ($data) {
                        return GlobalFunctions::getStatusValue($data->status);
                    },
                    'filter' => [
                        0 =>  Yii::t('backend','Inactivo'),
                        1 => Yii::t('backend','Activo')
                    ],
                    'filterType'=>GridView::FILTER_SELECT2,
                    'filterWidgetOptions'=>[
                        'pluginOptions'=>['allowClear'=>true],
                    ],
                    'filterInputOptions'=>['placeholder'=>'------'],
                ],

				$custom_elements_gridview->getActionColumn(),

				$custom_elements_gridview->getCheckboxColumn(),

            ],

            'toolbar' =>  $custom_elements_gridview->getToolbar(),

            'panel' => $custom_elements_gridview->getPanel(),

            'toggleDataOptions' => $custom_elements_gridview->getTogleDataOptions(),
        ]); ?>
    </div>

    <?= $this->render('ajax_generic_form',[
        'id_form' => 'export-form',
        'id_modal' => 'exportModal',
        'title_modal' => Yii::t("backend", "Crear").' '.Yii::t("backend", "Exportar"),
        'model_form' => new ApiDoc(),
        'action_form' => '/api-doc/select_export',
        'path_to_renderpartial' => '/api-doc/_form_select_export',
        'name_btn_submit' => null,
        'modal_large' => 'modal-xl'
    ]); ?>

<?php
    $url = Url::to([$controllerId.'multiple_delete']);
    $js = Footer_Bulk_Delete::getFooterBulkDelete($url);
    $this->registerJs($js, View::POS_READY);
?>

<?php
$js_modals = <<<JS

$('#exportModal').on('hidden.bs.modal', function (e) {
    let form = $(this).find('form');
    $(form).trigger('reset');
});

$("#export-form").on("beforeSubmit", function(event) {
    event.preventDefault(); // stopping submitting

    let form = document.querySelector("#export-form");
    let url = $(form).attr('action');
    let formdata = new FormData(form);
    $.ajax({
        url: url,
        type: 'post',
        data: formdata,
        processData: false,
        contentType: false,
    })
    .fail(function() {
        console.log("No conection to server");
    });

}).on('submit', function(e){
    e.preventDefault();
});

JS;

$this->registerJs($js_modals);
?>


