<?php

/* @var $this yii\web\View */
/* @var $model backend\models\support\CronjobTask */

$this->title = Yii::t('backend', 'Crear').' '. Yii::t('backend', 'Tarea de CronJob');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Tareas de CronJob'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cronjob-task-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
