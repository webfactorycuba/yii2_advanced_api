<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/security/user/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p> <?= Yii::t('common','Estimado').' '.Html::encode($user->username) ?>,</p>

    <p><?= Yii::t('common','Siga el enlace de abajo para restablecer su contraseña').':' ?></p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
