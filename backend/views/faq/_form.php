<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\switchinput\SwitchInput;
use dosamigos\ckeditor\CKEditor;
use backend\models\support\FaqGroup;
use kartik\select2\Select2;
use kartik\form\ActiveField;
use kartik\widgets\FileInput;
use common\models\GlobalFunctions;


/* @var $this yii\web\View */
/* @var $model backend\models\support\Faq */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box-body">
<?php 
 $form = ActiveForm::begin(
         ['options' => ['enctype' => 'multipart/form-data'],
         'fieldConfig' => [
             'showHints' => true,
             'hintType' => ActiveField::HINT_SPECIAL,
         ],

         ]); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'question')->widget(CKEditor::className(), [
                'preset' => 'custom',
                'clientOptions' => [
                    # 'extraPlugins' => 'pbckcode', *//Download already and in the plugins folder...*
                    'toolbar' => [
                        [
                            'name' => 'row1',
                            'items' => [
                                'Bold', 'Italic', 'Underline', 'Strike', '-',
                                'Subscript', 'Superscript', 'RemoveFormat', '-',
                                'TextColor', 'BGColor', '-',
                                'Outdent', 'Indent', '-', 'Blockquote', '-',
                                'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'list', 'indent', 'blocks', 'align', 'bidi', '-',
                                'Link', 'Unlink', 'Anchor', '-',
                                'ShowBlocks', 'Maximize',
                            ],
                        ],
                        [
                            'name' => 'row2',
                            'items' => [
                                'SpecialChar', 'Iframe', '-',
                                'NewPage', 'Print', 'Templates', '-',
                                'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-',
                                'Undo', 'Redo', '-',
                                'Find', 'SelectAll', 'Format', 'Font', 'FontSize',
                            ],
                        ],
                    ],
                ],
            ]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'answer')->widget(CKEditor::className(), [
                'preset' => 'custom',
                'clientOptions' => [
                    # 'extraPlugins' => 'pbckcode', *//Download already and in the plugins folder...*
                    'filebrowserUploadUrl' => \yii\helpers\Url::to(['/site/ckeditorupload']),
                    'toolbar' => [
                        [
                            'name' => 'row1',
                            'items' => [
                                'Bold', 'Italic', 'Underline', 'Strike', '-',
                                'Subscript', 'Superscript', 'RemoveFormat', '-',
                                'TextColor', 'BGColor', '-',
                                'NumberedList', 'BulletedList', '-',
                                'Outdent', 'Indent', '-', 'Blockquote', '-',
                                'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'list', 'indent', 'blocks', 'align', 'bidi', '-',
                                'Link', 'Unlink', 'Anchor', 'Image', '-',
                                'ShowBlocks', 'Maximize',
                            ],
                        ],
                        [
                            'name' => 'row2',
                            'items' => [
                                'Table', 'HorizontalRule', 'SpecialChar', 'Iframe', '-',
                                'NewPage', 'Print', 'Templates', '-',
                                'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-',
                                'Undo', 'Redo', '-',
                                'Find', 'SelectAll', 'Format', 'Font', 'FontSize',
                            ],
                        ],
                    ],
                ],
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?=
            $form->field($model, "image")->widget(FileInput::classname(), [
                "language" => Yii::$app->language,
                'pluginOptions'=> GlobalFunctions::getConfigFileInputWithPreview($model->getImageFile(), $model->question)
            ]);
            ?>
        </div>
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    <?=
                    $form->field($model, "faq_group_id")->widget(Select2::classname(), [
                        "data" => FaqGroup::getSelectMap(),
                        "language" => "es",
                        "options" => ["placeholder" => "----", "multiple"=>false],
                        "pluginOptions" => [
                            "allowClear" => true
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-md-12">
                    <?=
                    $form->field($model,"status")->widget(SwitchInput::classname(), [
                        "type" => SwitchInput::CHECKBOX,
                        "pluginOptions" => [
                            "onText"=> Yii::t("backend","Activo"),
                            "offText"=> Yii::t("backend","Inactivo")
                        ]
                    ])
                    ?>
                </div>
            </div>
        </div>
    </div>


</div>
<div class="box-footer">
    <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-plus"></i> '.Yii::t('backend','Crear') : '<i class="fa fa-pencil"></i> '.Yii::t('yii', 'Update'), ['class' => 'btn btn-default btn-flat']) ?>
    <?= Html::a('<i class="fa fa-remove"></i> '.Yii::t('backend','Cancelar'),['index'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend','Cancelar')]) ?>
</div>
<?php ActiveForm::end(); ?>

