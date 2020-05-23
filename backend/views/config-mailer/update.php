<?php

/* @var $this yii\web\View */
/* @var $model backend\modules\facturacion\models\ConfigMailer */

$this->title = Yii::t('backend', 'Actualizar').' '. Yii::t('backend', 'Configuración de Correo');

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-mailer-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
