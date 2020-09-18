<?php

use kartik\grid\GridView;
use mdm\admin\components\Helper;
use yii\widgets\Pjax;
use yii\web\View;
use yii\helpers\Url;
use backend\components\Footer_Bulk_Delete;
use backend\components\Custom_Settings_Column_GridView;
use yii\helpers\BaseStringHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\support\ApiRequestLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controllerId = '/'.$this->context->uniqueId.'/';
$this->title = Yii::t('backend', 'Trazas de Peticiones API');
$this->params['breadcrumbs'][] = $this->title;

$create_button='';
?>
    <?php Pjax::begin(); ?>

<?php 

	$custom_elements_gridview = new Custom_Settings_Column_GridView($create_button,$dataProvider);

    $my_custom_action_column = [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => Helper::filterActionColumn(['view', 'delete']),
        'viewOptions' => [
            'class' => 'btn btn-xs btn-default btn-flat',
            'title' => Yii::t('yii','View'),
            'data-toggle' => 'tooltip',
        ],
        'deleteOptions' => [
            'class' => 'btn btn-xs btn-danger btn-flat',
            'title' => Yii::t('yii','Delete'),
            'data-toggle' => 'tooltip',
        ],
    ];
    $custom_elements_gridview->setActionColumn($my_custom_action_column);

?>

    <div class="box-body">
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
					'attribute'=>'action_id',
                    'format' => 'html',
					'contentOptions'=>['class'=>'kv-align-left kv-align-middle'],
					'value' => function ($data) {
						return $data->getActionId();
					},
					'hAlign'=>'center',
				],
                            
				[
					'attribute'=>'method',
					'contentOptions'=>['class'=>'kv-align-left kv-align-middle'],
					'hAlign'=>'center',
					'format'=> 'html',
					'value' => function ($data) {
						return $data->getMethod();
					}
				],
                                            
				[
					'attribute'=>'ip',
					'contentOptions'=>['class'=>'kv-align-left kv-align-middle'],
					'hAlign'=>'center',
					'format'=> 'html',
					'value' => function ($data) {
						return $data->getIp();
					}
				],

                [
                    'attribute'=>'user_agent',
                    'contentOptions'=>['class'=>'kv-align-left kv-align-middle'],
                    'hAlign'=>'center',
                    'format'=> 'html',
                    'value' => function ($data) {
                        if(isset($data->user_agent) && !empty($data->user_agent)){
                            $field_data = $data->user_agent;
                            $formatted_field_data = BaseStringHelper::truncateWords($field_data, 10, '...', true);
                        }else{
                            $formatted_field_data = $data->getUserAgent();
                        }
                        return $formatted_field_data;
                    }
                ],


                [
					'attribute'=>'created_at',
                    'value' => function($data){
                        return $data->created_at;
                    },
					'contentOptions'=>['class'=>'kv-align-left kv-align-middle'],
					'hAlign'=>'center',
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

            'toolbar' =>  $custom_elements_gridview->getToolbar(),

            'panel' => $custom_elements_gridview->getPanel(),

            'toggleDataOptions' => $custom_elements_gridview->getTogleDataOptions(),
        ]); ?>
    </div>
    <?php Pjax::end(); ?>

<?php
    $url = Url::to([$controllerId.'multiple_delete']);
    $js = Footer_Bulk_Delete::getFooterBulkDelete($url);
    $this->registerJs($js, View::POS_READY);
?>

