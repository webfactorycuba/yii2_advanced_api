<?php

use kartik\select2\Select2;
use backend\models\support\ApiDoc;

/* @var $this yii\web\View */
?>

<?=
$form->field($model, "type_select_export")->widget(Select2::classname(), [
    "data" => ApiDoc::getGenericSelectType(),
    "language" => Yii::$app->language,
    'maintainOrder' => true,
    "options" => [
        "placeholder" => "----",
        "multiple"=>true],
    "pluginOptions" => [
        "allowClear" => true
    ]
])
?>
