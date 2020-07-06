<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;
use common\widgets\DetailView;
use mdm\admin\components\Helper;
use common\models\GlobalFunctions;
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
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$controllerId = '/'.$this->context->uniqueId.'/';
$this->title = $model-><?= $generator->getNameAttribute() ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="box-header">
        <?= "<?php \n" ?>
        if (Helper::checkRoute($controllerId . 'update')) {
            echo Html::a('<i class="fa fa-pencil"></i> '.Yii::t('yii','Update'), ['update', <?= $urlParams ?>], ['class' => 'btn btn-default btn-flat margin']);
        }

        echo Html::a('<i class="fa fa-remove"></i> '.Yii::t('backend','Cancelar'), ['index'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend','Cancelar')]);

        if (Helper::checkRoute($controllerId . 'delete')) {
            echo Html::a('<i class="fa fa-trash"></i> '.Yii::t('yii','Delete'), ['delete', <?= $urlParams ?>], [
                'class' => 'btn btn-danger btn-flat margin',
                'data' => [
                    'confirm' => Yii::t('yii','Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]);
        }
        ?>
    </div>
    <div class="box-body">
        <?= "<?= " ?>DetailView::widget([
            'model' => $model,
            'labelColOptions' => ['style' => 'width: 40%'],
            'attributes' => [
<?php

$lower_current_class = strtolower(StringHelper::basename($generator->modelClass));


if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        echo "                '" . $name . "',\n";
    }
} else {
    foreach ($generator->getTableSchema()->columns as $column)
    {
        $format = stripos($column->name, 'created_at') !== false || stripos($column->name, 'updated_at') !== false ? 'date' : $generator->generateColumnFormat($column);

        $name = $column->name;
        $type = $generator->getTableSchema()->columns[$name]->type;
        $dbtype = $generator->getTableSchema()->columns[$name]->dbType;

        $generateselect2 = '';
        if (strlen($name) > 3)
        {
            $fieldrelation = substr($name, strlen($name)-3, 3);
            $classrelationUpper = ucfirst(substr($name, 0, strlen($name)-3));

            $classrelation = Inflector::camel2words($classrelationUpper);
            $classrelationUpper = str_replace(' ','',$classrelation);

            $classrelationLower = lcfirst($classrelationUpper);
            if ($fieldrelation == '_id' )
            {
                $generateselect2 = true;
            }
        }

        if($generateselect2)
        {?>
                [
                    'attribute'=> '<?= $name ?>',
                    'value'=> (isset($model-><?= $classrelationLower ?>->name) && !empty($model-><?= $classrelationLower ?>->name))? $model-><?= $classrelationLower ?>->name : null,
                    'format'=> 'html',
                ],
        <?php
            echo "\n";
        }
        else
        {
            if($column->name === 'status')
            {?>
                [
                    'attribute'=> '<?= $name ?>',
                    'value'=> GlobalFunctions::getStatusValue($model-><?= $name ?>),
                    'format'=> 'html',
                ],
                <?php
                echo "\n";
            }
            elseif($dbtype === 'boolean' || $dbtype === 'tinyint(1)')
            {?>
                [
                'attribute'=> '<?= $name ?>',
                'value'=> GlobalFunctions::getStatusValue($model-><?= $name ?>,'true','badge bg-gray'),
                'format'=> 'html',
                ],
                <?php
                echo "\n";
            }
            elseif($column->name === 'photo' || $column->name === 'image')
            {?>
                [
                    'attribute'=> '<?= $name ?>',
                    'value'=> GlobalFunctions::getFileUrlByNamePath('<?= $lower_current_class ?>',$model-><?= $name ?>),
                    'format' => ['image',['width'=>'175','height'=>'150']],
                ],
                <?php
                echo "\n";
            }
            elseif($type === 'integer' && $name !== 'id')
            {?>
                [
                    'attribute'=> '<?= $name ?>',
                    'value'=> GlobalFunctions::formatNumber($model-><?= $name ?>),
                    'format'=> 'html',
                ],
                <?php
                echo "\n";
            }
            elseif($type == 'decimal' || $type == 'numeric' || $type == 'float' || $type == 'double')
            {?>
                [
                    'attribute'=> '<?= $name ?>',
                    'value'=> GlobalFunctions::formatNumber($model-><?= $name ?>,2),
                    'format'=> 'html',
                ],
                <?php
                echo "\n";
            }
            elseif($type == 'text')
            {?>
                [
                    'attribute'=> '<?= $name ?>',
                    'value'=> $model-><?= $name ?>,
                    'format'=> 'html',
                ],
                <?php
                echo "\n";
            }
            elseif($type == 'date' || $type == 'timestamp' || $type == 'datetime')
            {?>
                [
                    'attribute'=> '<?= $name ?>',
                    'value'=> GlobalFunctions::formatDateToShowInSystem($model-><?= $name ?>),
                    'format'=> 'html',
                ],
                <?php
                echo "\n";
            }
            else
            {
            echo "                '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
            }
        }

    }
}
?>
            ],
        ]) ?>
    </div>
