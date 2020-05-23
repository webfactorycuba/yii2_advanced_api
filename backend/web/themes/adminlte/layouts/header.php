<?php
use yii\helpers\Html;
use common\models\User;
use common\models\GlobalFunctions;
use yii\helpers\Url;
use backend\models\settings\Setting;
use backend\models\i18n\Language;
use machour\yii2\notifications\widgets\NotificationsWidget;

/* @var $this \yii\web\View */
/* @var $content string */

$return_url = Url::current();
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini"><img class="logo-header-mini" src="'.Setting::getUrlLogoBySettingAndType(3,1).'" alt=""></span><span class="logo-lg"><img class="logo-header-lg" src="'.Setting::getUrlLogoBySettingAndType(2,1).'" alt=""></span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <?php
                if (GlobalFunctions::isTranslationAvailable()) {
                    $languages_actives = Language::getLanguagesActives();
                    ?>
                    <!-- Languages-->
                    <li class="dropdown messages-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-language"></i>
                            <span class="flag-icon flag-icon-<?= substr(Yii::$app->language, 0, 2) ?>">
                            <?= Html::img(GlobalFunctions::getFlagByLanguage(Yii::$app->language), ['class' => 'languageFlag']) ?>
                        </span>
                            <span class="label label-success"><?= strtoupper(Yii::$app->language) ?></span>

                        </a>
                        <ul class="dropdown-menu">
                            <li class="header"><?= Yii::t('backend', 'Idiomas') ?> </li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                    <?php
                                    foreach ($languages_actives AS $key => $value) {
                                        echo '<li>
                                              ' . Html::img(GlobalFunctions::getFlagByLanguage($value->code), ['class' => 'languageFlagList']) . ' ' .
                                            Html::a(strtoupper($value->code), ['/site/change_lang', 'lang' => $value->code, 'url' => $return_url])
                                            . '</li>';
                                    }
                                    ?>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <!-- Notifications: style can be found in dropdown.less -->

                    <?php
                }
                ?>
                <?php
                NotificationsWidget::widget([
                    'theme' => NotificationsWidget::THEME_TOASTR,
                    'pollInterval'=>10000,
                    'clientOptions' => [
                        'size' => 'large',
                        'location' => substr(Yii::$app->language,0,2),
                    ],
                    'listItemTemplate' => '
                            <a href="#">
                                 <div class="row">
                                    <div class="col-xs-10">
                                        <div class="title">{title}</div>
                                        <div class="description">{description}</div>
                                        <div class="timeago">
                                            <span class="notification-timeago">{timeago}</span>
                                        </div>
                                    </div>
                                    <div class="col-xs-2">
                                        <div class="actions pull-right">
                                            <span class="notification-seen fa fa-check"></span>
                                            <span class="notification-delete fa fa-close"></span>
                                        </div>
                                    </div>
                                </div>
                            </a>',
                    'counters' => [
                        '.notifications-header-count',
                        '.notifications-icon-count'
                    ],
                    'markAllSeenSelector' => '#notification-seen-all',
                    'listSelector' => '#notifications',
                ]);

                if(!Yii::$app->user->isGuest) {

                    ?>

                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning notifications-icon-count">0</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header"><?=Yii::t('backend', 'Tiene') ?> <span class="notifications-header-count">0</span> <?= Yii::t('backend', 'Notificaciones') ?></li>
                            <li>
                                <ul class="menu">
                                    <div id="notifications"></div>
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="#" id="notification-seen-all"><?=Yii::t('backend', 'Marcar todas como vistas') ?></a>
                            </li>
                        </ul>
                    </li>
                <?php } ?>

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= User::getUrlAvatarByActiveUser() ?>" class="user-image" />
                        <span class="hidden-xs"><?= User::getNameByActiveUser() ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= User::getUrlAvatarByActiveUser() ?>" class="img-circle"
                                 alt="User Image"/>

                            <p>
	                            <?= User::getFullNameByActiveUser() ?>
                                <small></small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
	                            <?= Html::a(
		                            Yii::t('backend','Perfil'),
		                            ['/security/user/profile'],
		                            ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
	                            ) ?>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    Yii::t('backend','Cerrar sesión'),
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>


