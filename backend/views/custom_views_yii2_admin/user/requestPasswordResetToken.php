<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\models\settings\Setting;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\PasswordResetRequest */

$this->title = Yii::t('backend','Recuperar Contraseña');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="login-box" style="margin: 1% auto !important;">
    <div class="login-logo adjust_logo_image">
        <h1><?= Setting::getName() ?></h1>
        <img src="<?= Setting::getUrlLogoBySettingAndType(1,1) ?>" alt="" style="max-width: 200px; max-height: 200px">
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg"><?= Yii::t('backend','Por favor ingrese su correo electrónico. Un enlace para restablecer la contraseña le será enviado') ?></p>

        <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'email') ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <?= Html::submitButton(Yii::t('backend','Enviar'), ['class' => 'btn btn-default btn-block btn-flat']) ?>
            </div>
            <div class="col-md-6">
                <?= Html::a(Yii::t('backend','Cancelar'),['/'], ['class' => 'btn btn-default btn-block btn-flat', 'title' => Yii::t('backend','Cancelar')]) ?>
            </div>
            <!-- /.col -->
        </div>

        <?php ActiveForm::end(); ?>

    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->
