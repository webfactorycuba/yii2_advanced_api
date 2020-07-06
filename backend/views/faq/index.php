<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use mdm\admin\components\Helper;
use yii\web\View;
use yii\helpers\Url;
use backend\components\Footer_Bulk_Delete;
use backend\components\Custom_Settings_Column_GridView;
use common\models\GlobalFunctions;
use backend\models\support\FaqGroup;
use yii\helpers\BaseStringHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\support\FaqSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controllerId = $this->context->uniqueId . '/';
$this->title = Yii::t('backend', 'Faqs');
$this->params['breadcrumbs'][] = $this->title;

$create_button='';
?>

<?php 
	if (Helper::checkRoute($controllerId . 'create')) {
		$create_button = Html::a('<i class="fa fa-plus"></i> '.Yii::t('backend', 'Crear'), ['create'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend', 'Crear').' '.Yii::t('backend', 'Faq')]);
	}

$custom_elements_gridview = new Custom_Settings_Column_GridView($create_button,$dataProvider);
?>

    <div class="box-body">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'id'=>'grid',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
			
				$custom_elements_gridview->getSerialColumn(),

                [
                    'attribute'=>'question',
                    'contentOptions'=>['class'=>'kv-align-left kv-align-middle'],
                    'hAlign'=>'center',
                    'format'=> 'html',
                    'value' => function ($data) {
                        $question = $data->question;
                        $formattedDesc = BaseStringHelper::truncateWords($question,5,'...',true);

                        return $formattedDesc;
                    }
                ],

                [
                    'attribute'=>'answer',
                    'contentOptions'=>['class'=>'kv-align-left kv-align-middle'],
                    'hAlign'=>'center',
                    'format'=> 'html',
                    'value' => function ($data) {
                        $answer = $data->answer;
                        $formattedDesc = BaseStringHelper::truncateWords($answer,5,'...',true);

                        return $formattedDesc;
                    }
                ],

				[
					'attribute'=>'faq_group_id',
                    'format' => 'html',
					'contentOptions'=>['class'=>'kv-align-left kv-align-middle'],
					'filterType'=>GridView::FILTER_SELECT2,
					'filter'=> FaqGroup::getSelectMap(),
					'filterWidgetOptions' => [
						'pluginOptions'=>['allowClear'=>true],
						'options'=>['multiple'=>false],
					],
					'value' => function ($data) {
                        return $data->faqGroup->name;
                    },
					'filterInputOptions'=>['placeholder'=> '------'],
					'hAlign'=>'center',
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

<?php
    $url = Url::to([$controllerId.'multiple_delete']);
    $js = Footer_Bulk_Delete::getFooterBulkDelete($url);
    $this->registerJs($js, View::POS_READY);
?>

