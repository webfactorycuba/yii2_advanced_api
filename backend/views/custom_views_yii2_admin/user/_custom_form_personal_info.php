<?php

use dosamigos\ckeditor\CKEditor;
use common\models\GlobalFunctions;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'seniority')->widget(CKEditor::className(), [
                'preset' => 'custom',
                'clientOptions' => [
                    # 'extraPlugins' => 'pbckcode', *//Download already and in the plugins folder...*
                    'toolbar' => GlobalFunctions::getToolBarForCkEditor(),
                ],
            ]) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'skills')->widget(CKEditor::className(), [
                'preset' => 'custom',
                'clientOptions' => [
                    # 'extraPlugins' => 'pbckcode', *//Download already and in the plugins folder...*
                    'toolbar' => GlobalFunctions::getToolBarForCkEditor(),
                ],
            ]) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'personal_stuff')->widget(CKEditor::className(), [
                'preset' => 'custom',
                'clientOptions' => [
                    # 'extraPlugins' => 'pbckcode', *//Download already and in the plugins folder...*
                    'toolbar' => GlobalFunctions::getToolBarForCkEditor(),
                ],
            ]) ?>
        </div>
    </div>


