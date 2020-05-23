<?php

use yii\helpers\Html;
use common\widgets\DetailView;
use mdm\admin\components\Helper;
use common\models\User;
use common\models\GlobalFunctions;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$controllerId = $this->context->uniqueId . '/';
?>
<div class="user-view">

    <div class="box-header">

    <?php
		if (Helper::checkRoute($controllerId . 'update')) {
			echo Html::a('<i class="fa fa-pencil"></i> '.Yii::t('yii','Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-default btn-flat margin']);
		}

        echo Html::a('<i class="fa fa-remove"></i> '.Yii::t('backend','Cancelar'), ['index'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend','Cancelar')]);

		if ($model->status === 0 && Helper::checkRoute($controllerId . 'activate')) {
			echo Html::a(Yii::t('rbac-admin', 'Activate'), ['activate', 'id' => $model->id], [
				'class' => 'btn btn-default btn-flat margin',
				'data' => [
					'confirm' => Yii::t('rbac-admin', 'Are you sure you want to activate this user?'),
					'method' => 'post',
				],
			]);
		}

		if (Helper::checkRoute($controllerId . 'delete')) {
			echo Html::a('<i class="fa fa-trash"></i> '.Yii::t('yii','Delete'), ['delete', 'id' => $model->id], [
				'class' => 'btn btn-danger btn-flat margin',
				'data' => [
					'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
					'method' => 'post',
				],
			]);
		}
    ?>

    </div>

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
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b><?= Yii::t('backend','Fecha de creación') ?></b> <a class="pull-right"><?= GlobalFunctions::formatDateToShowInSystem($model->created_at) ?></a>
                            </li>
                            <li class="list-group-item">
                                <b><?= Yii::t('backend','Estado') ?></b> <a class="pull-right"><?= User::getStatusValue($model->status) ?></a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#info" data-toggle="tab"><?= Yii::t('backend','Información personal') ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="info">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    'name',
                                    'last_name',
                                    'username',
                                    'email:email',

                                    'seniority:html',
                                    'skills:html',
                                    'personal_stuff:html',
                                ],
                            ]) ?>
                        </div>

                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

    </section>
    <!-- /.content -->

