<?php

use backend\models\settings\Setting;

/* @var $this yii\web\View */

$this->title = Setting::getName();

?>

<div class="site-index">
<?= Yii::t("backend", "Inicio"); ?>
</div>
