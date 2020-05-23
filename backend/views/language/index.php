<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use mdm\admin\components\Helper;
use yii\web\View;
use yii\helpers\Url;
use backend\components\Footer_Bulk_Delete;
use backend\components\Custom_Settings_Column_GridView;
use common\models\GlobalFunctions;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\i18n\LanguageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controllerId = $this->context->uniqueId . '/';
$this->title = Yii::t('backend', 'Idiomas');
$this->params['breadcrumbs'][] = $this->title;

$create_button='';
?>

<?php 
	if (Helper::checkRoute($controllerId . 'create')) {
		$create_button = Html::a('<i class="fa fa-plus"></i> '.Yii::t('backend', 'Crear'), ['create'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend', 'Crear').' '.Yii::t('backend', 'Idioma')]);
	}

	$custom_elements_gridview = new Custom_Settings_Column_GridView($create_button,$dataProvider);
?>

    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'id'=>'grid',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pager' => [
                'firstPageLabel' => Yii::t('backend','Primero'),
                'lastPageLabel' => Yii::t('backend','Último'),
            ],
            'responsiveWrap' => false,
            'floatHeader' => true,
            'floatHeaderOptions' => [
                'position'=>'absolute',
                'top' => 50
            ],
            'pjax'=>true,
            'pjaxSettings'=>[
                'neverTimeout'=>true,
                'options'=>[
                    'enablePushState'=>false,
                ],
            ],
            'hover' => true,
            'columns' => [
			
				$custom_elements_gridview->getSerialColumn(),

				[
					'attribute'=>'code',
					'contentOptions'=>['class'=>'kv-align-left kv-align-middle'],
					'hAlign'=>'center',
					'format'=> 'html',
					'value' => function ($data) {
						$url = urldecode(Url::toRoute(['update', 'id' => $data->id]));
						return Html::a($data->code, $url, ['data-pjax'=>0, 'data-method'=>"post"]);
					}
				],


                [
                    'attribute' => 'status',
                    'format' => 'html',
                    'value' => function ($data) {
						$url = urldecode(Url::toRoute(['update', 'id' => $data->id]));
						return Html::a(GlobalFunctions::getStatusValue($data->status), $url, ['data-pjax'=>0, 'data-method'=>"post"]);
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

                [
                    'label'=> Yii::t('backend','Bandera'),
                    'contentOptions'=>['class'=>'kv-align-left kv-align-middle'],
                    'hAlign'=>'center',
                    'format'=> 'raw',
                    'value' => function ($data) {
                        $url = urldecode(Url::toRoute(['update', 'id' => $data->id]));
                        $path_url = GlobalFunctions::getFileUrlByNamePath('flags',$data->image);
                        $result=  '<img src="'.$path_url.'" class="languageFlag">';
                        return Html::a($result, $url, ['data-pjax'=>0, 'data-method'=>"post"]);
                    }
                ],

				$custom_elements_gridview->getActionColumn(),

				$custom_elements_gridview->getCheckboxColumn(),

            ],

            'toolbar' =>  $custom_elements_gridview->getToolbar(),

            'panel' => $custom_elements_gridview->getPanel(),

            'toggleDataOptions' => $custom_elements_gridview->getTogleDataOptions(),
        ]); ?>
    </div>

<?php
    $url = Url::to([$controllerId.'multiple_delete']);
    $js = Footer_Bulk_Delete::getFooterBulkDelete($url);
    $this->registerJs($js, View::POS_READY);
?>

