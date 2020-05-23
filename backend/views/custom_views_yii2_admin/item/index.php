<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use mdm\admin\components\RouteRule;
use mdm\admin\components\Configs;
use mdm\admin\components\Helper;
use yii\helpers\BaseStringHelper;
use yii\web\View;
use yii\helpers\Url;
use backend\components\Footer_Bulk_Delete;
use backend\components\Custom_Settings_Column_GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\AuthItem */
/* @var $context mdm\admin\components\ItemController */

$controllerId = $this->context->uniqueId . '/';
$context = $this->context;
$labels = $context->labels();
$this->title = Yii::t('rbac-admin', $labels['Items']);
$this->params['breadcrumbs'][] = $this->title;
$create_button='';

$rules = array_keys(Configs::authManager()->getRules());
$rules = array_combine($rules, $rules);
unset($rules[RouteRule::RULE_NAME]);
?>
<div class="role-index">


    <?php
    if (Helper::checkRoute('/'.$controllerId.'create')) {
        $create_button = Html::a('<i class="fa fa-plus"></i> '.Yii::t('backend', 'Crear'), ['create'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend', 'Crear '.$this->title)]);
    }

    $custom_elements_gridview = new Custom_Settings_Column_GridView($create_button,$dataProvider);
    ?>


    <?=
    GridView::widget([
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
                'attribute' => 'name',
                'label' => Yii::t('rbac-admin', 'Name'),
            ],
            [
                'attribute' => 'description',
                'label' => Yii::t('rbac-admin', 'Description'),
                'format' => 'raw',
                'value'=>function($model){
                    $message_origin = $model->description;
                    $formattedDesc = BaseStringHelper::truncateWords($message_origin,5,'...',true);
                    return $formattedDesc;
                },
            ],
            $custom_elements_gridview->getActionColumn(),

            $custom_elements_gridview->getCheckboxColumn(),
        ],

        'toolbar' =>  $custom_elements_gridview->getToolbar(),

        'panel' => $custom_elements_gridview->getPanel(),

        'toggleDataOptions' => $custom_elements_gridview->getTogleDataOptions(),
    ])
    ?>

</div>
<?php
$url = Url::to(['/'.$controllerId.'multiple_delete']);
$js = Footer_Bulk_Delete::getFooterBulkDelete($url);
$this->registerJs($js, View::POS_READY);
?>
