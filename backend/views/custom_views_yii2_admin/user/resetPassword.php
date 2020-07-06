<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\models\settings\Setting;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\ResetPassword */

$this->title = Yii::t('common','Restaurar Contraseña');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="login-box" style="margin: 1% auto !important;">
    <div class="login-logo adjust_logo_image">
        <img src="<?= Setting::getUrlLogoBySettingAndType(1,1) ?>" alt="" style="max-width: 200px; max-height: 200px">
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg"><?= Yii::t('backend','Por favor, elija su nueva contraseña:') ?></p>

        <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'password')->passwordInput() ?>
            </div>
        </div>

        <div class="box-footer">
                <?= Html::submitButton(Yii::t('yii','Update'), ['class' => 'btn btn-default btn-block btn-flat']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->