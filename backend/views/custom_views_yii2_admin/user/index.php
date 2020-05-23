<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use mdm\admin\components\Helper;
use yii\web\View;
use yii\helpers\Url;
use backend\components\Footer_Bulk_Delete;
use backend\components\Custom_Settings_Column_GridView;
use common\models\User;
use common\models\GlobalFunctions;

/* @var $this yii\web\View */
/* @var $searchModel mdm\admin\models\searchs\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('rbac-admin', 'Users');
$this->params['breadcrumbs'][] = $this->title;

$create_button='';
$controllerId = $this->context->uniqueId . '/';
?>
<div class="user-index">

    <?php
    if (Helper::checkRoute('/security/user/create')) {

        $create_button = Html::a('<i class="fa fa-plus"></i> '.Yii::t('backend','Crear'), ['create'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend','Crear Usuario')]);
    }

    $custom_elements_gridview = new Custom_Settings_Column_GridView($create_button,$dataProvider);

    ?>


    <?php
        $my_custom_action_column = [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'vAlign'=>'middle',
            'template' => Helper::filterActionColumn(['view', 'activate', 'update', 'delete']),
            'buttons' => [
                'activate' => function($url, $model) {
                    if ($model->status === 10) {
                        return '';
                    }
                    $options = [
                        'title' => Yii::t('rbac-admin', 'Activate'),
                        'class' => 'btn btn-xs btn-default btn-flat',
                        'aria-label' => Yii::t('rbac-admin', 'Activate'),
                        'data-confirm' => Yii::t('backend', '¿Seguro desea activar este usuario?'),
                        'data-method' => 'post',
                        'data-pjax' => '0',
                        'data-toggle' => 'tooltip',
                    ];
                    return Html::a('<i class="glyphicon glyphicon-ok"></i>', $url, $options);
                }
            ],
            'viewOptions' => [
                'class' => 'btn btn-xs btn-default btn-flat',
                'title' => Yii::t('yii','View'),
                'data-toggle' => 'tooltip',
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
            'name',
            'last_name',
            'username',
            'email',
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function($model) {
                    return User::getStatusValue($model->status);
                },
                'filter' => [
                    0 =>  Yii::t('backend','Inactivo'),
                    10 => Yii::t('backend','Activo')
                ],
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'pluginOptions' => [ 'allowClear'=>true ],
                ],
                'filterInputOptions'=>['placeholder'=>'------'],
            ],
            [
                'attribute'=>'role',
                'contentOptions'=>['class'=>'kv-align-left kv-align-middle'],
                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=> GlobalFunctions::getRolesList(),
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                    'options'=>['multiple'=>false],
                ],
                'value'=>function($data){
                    return GlobalFunctions::getRol($data->id);
                },
                'filterInputOptions'=>['placeholder'=>'------'],
                'hAlign'=>'center',
            ],

            $custom_elements_gridview->getActionColumn(),

            $custom_elements_gridview->getCheckboxColumn(),

        ],
        'toolbar' =>  $custom_elements_gridview->getToolbar(),

        'panel' => $custom_elements_gridview->getPanel(),

        'toggleDataOptions' => $custom_elements_gridview->getTogleDataOptions(),

        ]);
        ?>
</div>

<?php
$url = Url::to(['/security/user/multiple_delete']);
$js = Footer_Bulk_Delete::getFooterBulkDelete($url);
$this->registerJs($js, View::POS_READY);
?>
