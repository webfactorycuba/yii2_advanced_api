<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use mdm\admin\components\Helper;
use backend\components\Custom_Settings_Column_GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\Assignment */
/* @var $usernameField string */
/* @var $extraColumns string[] */

$this->title = Yii::t('rbac-admin', 'Assignments');
$this->params['breadcrumbs'][] = $this->title;
$custom_elements_gridview = new Custom_Settings_Column_GridView('',$dataProvider);

$columns = [
    $custom_elements_gridview->getSerialColumn(),
    $usernameField,
];
if (!empty($extraColumns)) {
    $columns = array_merge($columns, $extraColumns);
}
$columns[] = [
    'class' => 'kartik\grid\ActionColumn',
    'dropdown' => false,
    'vAlign'=>'middle',
    'template' => Helper::filterActionColumn(['view']),
    'viewOptions' => [
        'class' => 'btn btn-xs btn-info btn-flat',
        'title' => Yii::t('yii','View'),
        'data-toggle' => 'tooltip',
        //'icon' => '<i class="fa fa-eye"></i>',
    ]
];

$my_custom_panel= [
    'type' => 'default',
    'after'=>
        Html::a('<i class="fa fa-refresh"></i> '.Yii::t('backend','Resetear'), ['index'], ['class' => 'btn btn-default btn-flat margin','title'=> Yii::t('backend','Resetear listado'), 'data-toggle' => 'tooltip']),
];

$custom_elements_gridview->setPanel($my_custom_panel);

?>
<div class="assignment-index">

    <?php Pjax::begin(); ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
        'toolbar' =>  $custom_elements_gridview->getToolbar(),
        'panel' => $custom_elements_gridview->getPanel(),
        'toggleDataOptions' => $custom_elements_gridview->getTogleDataOptions(),
        'responsiveWrap' => false,
    ]);
    ?>
    <?php Pjax::end(); ?>

</div>
