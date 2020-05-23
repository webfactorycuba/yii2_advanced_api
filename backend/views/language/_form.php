<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\widgets\FileInput;
use kartik\switchinput\SwitchInput;
use common\models\GlobalFunctions;
use kartik\select2\Select2;
use backend\models\i18n\Language;

/* @var $this yii\web\View */
/* @var $model backend\models\i18n\Language */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box-body">
<?php 
 $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?php

    if($model->isNewRecord)
        $url_image = GlobalFunctions::getNoImageDefaultUrl();
    else
        $url_image = GlobalFunctions::getFileUrlByNamePath('flags',$model->image);
    ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'image')->widget(FileInput::classname(), [
                'options' => ['accept' => 'image/*'],
                'pluginOptions'=> [
                    'browseIcon'=>'<i class="fa fa-camera"></i> ',
                    'browseLabel'=> Yii::t('backend','Cambiar'),
                    //'allowedFileExtensions'=>['jpg','gif','png'],
                    'defaultPreviewContent'=> '<img src="'.$url_image.'" class="previewAvatar">',
                    'showUpload'=> false,
                    'layoutTemplates'=> [
                        'main1'=>  '{preview}<div class=\'input-group {class}\'><div class=\'input-group-btn\'>{browse}{upload}{remove}</div>{caption}</div>',
                    ],
                ]
            ]);
            ?>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'code')->widget(Select2::classname(), [
                'data' => Language::getLanguagesShortName(),
                'language' => Yii::$app->language,
                'options' => ['placeholder' => '------'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]) ?>
        </div>
        <div class="col-md-2">
            <?=
            $form->field($model,"status")->widget(SwitchInput::classname(), [
                "type" => SwitchInput::CHECKBOX,
                "pluginOptions" => [
                    "onText"=> Yii::t('backend','Activo'),
                    "offText"=> Yii::t('backend','Inactivo')
                ]
            ])
            ?>
        </div>

    </div>

</div>
<div class="box-footer">
    <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-plus"></i> '.Yii::t('backend','Crear') : '<i class="fa fa-pencil"></i> '.Yii::t('yii', 'Update'), ['class' => 'btn btn-default btn-flat']) ?>
    <?= Html::a('<i class="fa fa-remove"></i> '.Yii::t('backend','Cancelar'), ['index'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend','Cancelar')]) ?>

</div>
<?php ActiveForm::end(); ?>

