<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\models\settings\Setting;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('rbac-admin','Login');

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>

<div class="login-box" style="margin: 1% auto !important;">
    <div class="login-logo adjust_logo_image">
        <img src="<?= Setting::getUrlLogoBySettingAndType(1,1) ?>" alt="" style="max-width: 200px; max-height: 200px">
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg"><?= Yii::t('backend','Inicie sesión para comenzar') ?></p>

        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

        <div class="row">
            <div class="col-md-12">
                <?= $form
                    ->field($model, 'username', $fieldOptions1)
                    ->label(false)
                    ->textInput(['placeholder' => $model->getAttributeLabel('username')])
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?= $form
                    ->field($model, 'password', $fieldOptions2)
                    ->label(false)
                    ->passwordInput(['placeholder' => $model->getAttributeLabel('password')])
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
            </div>
            <!-- /.col -->
            <div class="col-md-4">
                <?= Html::submitButton('<i class="fa fa-sign-in"></i> '.Yii::t('backend','Acceder'), ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
            <!-- /.col -->
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= Html::a(Yii::t('backend','Olvidé mi contraseña'),['/security/user/request-password-reset']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->
