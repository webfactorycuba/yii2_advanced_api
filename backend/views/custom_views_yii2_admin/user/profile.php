<?php

use yii\helpers\Html;
use common\models\User;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \common\models\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Perfil');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-profile">

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="<?= User::getUrlAvatarByActiveUser() ?>" alt="Avatar">
                        <h3 class="profile-username text-center"><?= User::getFullNameByActiveUser() ?></h3>
                        <p class="text-muted text-center"><?= $model->position ?></p>
                        <p class="text-center">
                        <?=Html::a(Yii::t('backend','Cambiar Contraseña'),['/security/user/change-password'],['class'=>'btn btn-default margin']) ?>
                        </p>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->


            </div>

            <!-- /.col -->
            <div class="col-md-9">
                <?php $form = ActiveForm::begin(); ?>
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#general_settings" data-toggle="tab"><?= Yii::t('backend','Datos generales') ?></a></li>
                        <li><a href="#personal_settings" data-toggle="tab"><?= Yii::t('backend','Datos adicionales') ?></a></li>
                    </ul>
                    <div class="tab-content">

                        <div class="active tab-pane" id="general_settings">
	                        <?= $this->render('_custom_form', [
		                        'model' => $model,
                                'form' => $form
	                        ]) ?>
                        </div>
                        <div class="tab-pane" id="personal_settings">
                            <?= $this->render('_custom_form_personal_info', [
                                'model' => $model,
                                'form' => $form
                            ]) ?>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- /.nav-tabs-custom -->
                <div class="box-footer">
                    <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-plus"></i> '.Yii::t('rbac-admin','Create') : '<i class="fa fa-pencil"></i> '.Yii::t('yii', 'Update'), ['class' => 'btn btn-default btn-flat']) ?>

                </div>
                <?php ActiveForm::end(); ?>
            </div>
            <!-- /.col -->

        </div>
        <!-- /.row -->

    </section>
    <!-- /.content -->


</div>
