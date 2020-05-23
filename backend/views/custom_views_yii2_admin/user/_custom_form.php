<?php

use kartik\file\FileInput;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="row">
        <div class="col-md-5">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-7">
            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-7">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <?= $form->field($model, 'position')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <?php

    if($model->isNewRecord)
	    $urlAvatar = User::getUrlAvatarByUserID();
    else
        $urlAvatar = User::getUrlAvatarByUserID($model->id);
    ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'fileAvatar')->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/*'],
            'pluginOptions'=> [
                'browseIcon'=>'<i class="fa fa-camera"></i> ',
                'browseLabel'=> Yii::t('backend','Cambiar avatar'),
                //'allowedFileExtensions'=>['jpg','gif','png'],
                'defaultPreviewContent'=> '<img src="'.$urlAvatar.'" class="previewAvatar">',
                'showUpload'=> false,
                'layoutTemplates'=> [
                    'main1'=>  '{preview}<div class=\'input-group {class}\'><div class=\'input-group-btn\'>{browse}{upload}{remove}</div>{caption}</div>',
                    ],
                ]
            ]);
            ?>
        </div>
    </div>




