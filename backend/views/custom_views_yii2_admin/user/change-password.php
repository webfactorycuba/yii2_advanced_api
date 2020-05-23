<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\ChangePassword*/

$this->title = Yii::t('backend', 'Cambiar Contraseña');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Perfil'), 'url' => ['/security/user/profile']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">

    <div class="row">
        <div class="col-md-5">
            <?php $form = ActiveForm::begin(['id' => 'form-change']); ?>
                <?= $form->field($model, 'oldPassword')->passwordInput() ?>
                <?= $form->field($model, 'newPassword')->passwordInput() ?>
                <?= $form->field($model, 'retypePassword')->passwordInput() ?>
                <div class="form-group">
                    <?= Html::submitButton('<i class="fa fa-pencil"></i> '.Yii::t('backend', 'Cambiar'), ['class' => 'btn btn-default btn-flat', 'name' => 'change-button']) ?>
                    <?= Html::a('<i class="fa fa-remove"></i> '.Yii::t('backend','Cancelar'),['profile'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend','Cancelar')]) ?>

                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
