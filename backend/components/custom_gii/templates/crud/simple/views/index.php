<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use <?= $generator->indexWidgetType === 'grid' ? "kartik\\grid\\GridView" : "yii\\widgets\\ListView" ?>;
<?= $generator->enablePjax ? 'use yii\widgets\Pjax;' : '' ?>
use mdm\admin\components\Helper;
use yii\web\View;
use yii\helpers\Url;
use backend\components\Footer_Bulk_Delete;
use backend\components\Custom_Settings_Column_GridView;
use common\models\GlobalFunctions;
use yii\helpers\BaseStringHelper;
<?php
foreach ($generator->getColumnNames() as $attribute) {
    if (strlen($attribute) > 3) {
        $fieldrelation = substr($attribute, strlen($attribute) - 3, 3);
        $classrelation = ucfirst(substr($attribute, 0, strlen($attribute) - 3));

        $classrelation = Inflector::camel2words($classrelation);
        $classrelation = str_replace(' ', '', $classrelation);
        if ($classrelation == 'User') { ?>
use common\models\User;<?php echo "\n";
        } else {
            if ($fieldrelation == '_id') { ?>
use <?= trim('backend\\models\\' . $classrelation) ?>;<?php echo "\n";
            }
        }
    }
}
?>

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$controllerId = '/'.$this->context->uniqueId.'/';
$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;

$create_button='';
?>
<?= $generator->enablePjax ? "    <?php Pjax::begin(); ?>\n" : '' ?>

<?= "<?php \n" ?>
	if (Helper::checkRoute($controllerId . 'create')) {
		$create_button = Html::a('<i class="fa fa-plus"></i> '.<?= $generator->generateString('Crear') ?>, ['create'], ['class' => 'btn btn-default btn-flat margin', 'title' => <?= $generator->generateString('Crear')?>.' '.<?= $generator->generateString(StringHelper::basename($generator->modelClass)) ?>]);
	}

	$custom_elements_gridview = new Custom_Settings_Column_GridView($create_button,$dataProvider);
?>

    <div class="box-body">
<?php if(!empty($generator->searchModelClass)): ?>
<?= "        <?php " . ($generator->indexWidgetType === 'grid' ? "// " : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
<?php endif;

if ($generator->indexWidgetType === 'grid'):
    echo "        <?= " ?>GridView::widget([
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
            <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n            'columns' => [\n" : "'layout' => \"{items}\\n{summary}\\n{pager}\",\n            'columns' => [\n"; ?>

				$custom_elements_gridview->getSerialColumn(),
    <?php
    $count = 0;
    if (($tableSchema = $generator->getTableSchema()) === false) {
        foreach ($generator->getColumnNames() as $name) {
            if ($name != 'id')
            {
                $comment = $generator->getTableSchema()->columns[$name]->comment;
                if($comment == 'imagen'){
echo "
				[
					 'attribute' => '".$name."',
					 'format' => 'html',
					 'value' => function($data) { return $data->thumb;}
				], \n";
                }
                else
                {?>
				[
					'attribute'=>'<?= $name ?>',
					'contentOptions'=>['class'=>'kv-align-left kv-align-middle'], // <-- right here
				],
                    <?php
                }
            }
        }
    } else {
        foreach ($tableSchema->columns as $column) {
            $format = $generator->generateColumnFormat($column);
            $name = $column->name;
            $type = $generator->getTableSchema()->columns[$name]->type;
            $dbtype = $generator->getTableSchema()->columns[$name]->dbType;
            $generateselect2 = '';
            if (strlen($name) > 3) {
                $fieldrelation = substr($name, strlen($name)-3, 3);
                $classrelationUpper = ucfirst(substr($name, 0, strlen($name)-3));

                $classrelation = Inflector::camel2words($classrelationUpper);
                $classrelationUpper = str_replace(' ','',$classrelation);

                $classrelationLower = lcfirst($classrelationUpper);
                if ($fieldrelation == '_id' ) {
                    $generateselect2 = true;
                }
            }
            if ($name != 'id') {
                $comment = $column->comment;
                if($comment == 'imagen' || $name == 'imagen'){ ?>
				[
					'attribute' => '<?= $name ?>',
					'format' => 'html',
					'value' => function($data) { return $data->imagen;}
				],
                <?php }
                    else
                        if ($generateselect2 == true) {
                            if($classrelation === 'User'){ ?>
                                <?= "\n" ?>
				[
					'attribute'=>'<?= $name ?>',
                    'format' => 'html',
					'contentOptions'=>['class'=>'kv-align-left kv-align-middle'],
					'filterType'=>GridView::FILTER_SELECT2,
					'filter'=> <?= $classrelationUpper ?>::getSelectMap(),
					'filterWidgetOptions' => [
						'pluginOptions'=>['allowClear'=>true],
						'options'=>['multiple'=>false],
					],
                    'value'=> function($model){ return User::getFullNameByUserId($model-><?= $name ?>); },
                    'filterInputOptions'=>['placeholder'=> '------'],
					'hAlign'=>'center',
				],
                <?php
                            }
                            else
                            { ?>
                <?= "\n" ?>
				[
					'attribute'=>'<?= $name ?>',
                    'format' => 'html',
					'contentOptions'=>['class'=>'kv-align-left kv-align-middle'],
					'filterType'=>GridView::FILTER_SELECT2,
					'filter'=> <?= $classrelationUpper ?>::getSelectMap(),
					'filterWidgetOptions' => [
						'pluginOptions'=>['allowClear'=>true],
						'options'=>['multiple'=>false],
					],
					'value'=> '<?= $classrelationLower ?>.name',
					'filterInputOptions'=>['placeholder'=> '------'],
					'hAlign'=>'center',
				],
                            <?php
                            }
                        }
                        else
                            if($type == 'integer'){?> <?= "\n" ?>
				[
					'attribute'=>'<?= $name ?>',
					'contentOptions'=>['class'=>'kv-align-left kv-align-middle'],
					'vAlign' => 'middle',
					'hAlign'=>'center',
                    'filterType'=>GridView::FILTER_NUMBER,
                    'filterWidgetOptions'=>[
                        'maskedInputOptions' => [
                            'allowMinus' => false,
                            'groupSeparator' => '.',
                            'radixPoint' => ',',
                            'digits' => 0
                        ],
                        'displayOptions' => ['class' => 'form-control kv-monospace'],
                        'saveInputContainer' => ['class' => 'kv-saved-cont']
                    ],
                    'value' => function ($data) {
                        return GlobalFunctions::formatNumber($data-><?= $name ?>);
                    },
                    'format' => 'html',
				],
                                <?php
                            }
                            else
                                if($type == 'decimal' || $type == 'numeric' || $type == 'float' || $type == 'double'){?> <?= "\n" ?>
				[
					'attribute'=>'<?= $name ?>',
					'contentOptions'=>['class'=>'kv-align-left kv-align-middle'],
					'vAlign' => 'middle',
					'hAlign' => 'center',
					'pageSummary' => true,
					'pageSummaryFunc' => GridView::F_SUM,
                    'filterType'=>GridView::FILTER_NUMBER,
                    'filterWidgetOptions'=>[
                        'maskedInputOptions' => [
                            'allowMinus' => false,
                            'groupSeparator' => '.',
                            'radixPoint' => ',',
                            'digits' => 2
                        ],
                        'displayOptions' => ['class' => 'form-control kv-monospace'],
                        'saveInputContainer' => ['class' => 'kv-saved-cont']
                    ],
                    'value' => function ($data) {
                        return GlobalFunctions::formatNumber($data-><?= $name ?>,2);
                    },
                    'format' => 'html',
				],
                                    <?php
                                }
                                else
                                    if($name == 'status'){?> <?= "\n" ?>
                [
                    'attribute' => '<?= $name ?>',
                    'format' => 'html',
                    'value' => function ($data) {
                        return GlobalFunctions::getStatusValue($data-><?= $name ?>);
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
                                    <?php
                                }
                                else
                                    if($dbtype === 'boolean' || $dbtype === 'tinyint(1)'){?> <?= "\n" ?>
                [
                    'attribute' => '<?= $name ?>',
                    'format' => 'html',
                    'value' => function ($data) {
                        return GlobalFunctions::getStatusValue($data-><?= $name ?>,'true','badge bg-gray');
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
                                        <?php
                                }
                                else
                                    if($type == 'date' || $type == 'datetime' || $type == 'timestamp'){?> <?= "\n" ?>
				[
					'attribute'=>'<?= $name ?>',
                    'value' => function($data){
                        return GlobalFunctions::formatDateToShowInSystem($data-><?= $name ?>);
                    },
					'contentOptions'=>['class'=>'kv-align-left kv-align-middle'],
					'hAlign'=>'center',
					'filterType' => GridView::FILTER_DATE_RANGE,
					'filterWidgetOptions' => ([
						'model' => $searchModel,
						'attribute' => '<?= $name ?>',
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
                                        <?php
                                    }
                                    else
                                        if($type == 'text'){?> <?= "\n" ?>
                [
                    'attribute'=>'<?= $name ?>',
                    'contentOptions'=>['class'=>'kv-align-left kv-align-middle'],
                    'hAlign'=>'center',
                    'format'=> 'html',
                    'value' => function ($data) {
                        $field_data = $data-><?= $name ?>;
                        $formatted_field_data = BaseStringHelper::truncateWords($field_data, 5, '...', true);

                        return $formatted_field_data;
                    }
                ],
                                            <?php
                                        }
                                    else{?><?= "\n" ?>
				[
					'attribute'=>'<?= $name ?>',
					'contentOptions'=>['class'=>'kv-align-left kv-align-middle'],
					'hAlign'=>'center',
					'format'=> 'html',
					'value' => function ($data) {
						return $data-><?= $name ?>;
					}
				],
                                        <?php
                                    }
            }
        }
    }
    ?>

				$custom_elements_gridview->getActionColumn(),

				$custom_elements_gridview->getCheckboxColumn(),

            ],

            'toolbar' =>  $custom_elements_gridview->getToolbar(),

            'panel' => $custom_elements_gridview->getPanel(),

            'toggleDataOptions' => $custom_elements_gridview->getTogleDataOptions(),
        ]); ?>
<?php else: ?>
        <?= "<?= " ?>ListView::widget([
            'dataProvider' => $dataProvider,
            'itemOptions' => ['class' => 'item'],
            'itemView' => function ($model, $key, $index, $widget) {
                return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
            },
        ]) ?>
<?php endif; ?>
    </div>
<?= $generator->enablePjax ? "    <?php Pjax::end(); ?>\n" : '' ?>

<?php
    echo "<?php\n";
?>
    $url = Url::to([$controllerId.'multiple_delete']);
    $js = Footer_Bulk_Delete::getFooterBulkDelete($url);
    $this->registerJs($js, View::POS_READY);
?>

