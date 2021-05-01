<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\Custom_Alert;
use backend\models\settings\Setting;

/* @var $this \yii\web\View */
/* @var $content string */

if (class_exists('backend\assets\AppAsset')) {
    backend\assets\AppAsset::register($this);
} else {
    app\assets\AppAsset::register($this);
}

dmstr\web\AdminLteAsset::register($this);

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');

$this->registerCssFile("@web/css/customYii.css");
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <?php
    $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => Setting::getUrlLogoBySettingAndType(3,1)]);
    ?>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<?php
$path = Url::to('@web/images/page_bg_blur02.jpg');
?>
<body class="login-page" style="background-image:url('<?= $path ?>'); background-size:cover;">

<?php $this->beginBody() ?>

<?= Custom_Alert::widget()  ?>
<?= $content ?>


<?php $this->endBody() ?>
<?php
$this->registerJsFile('@web/js/jquery.slimscroll.min.js');
$this->registerJsFile('@web/js/Chart.min.js');
$this->registerJsFile('@web/js/demo.js');
$this->registerJsFile('@web/js/customJS.js');
?>
</body>
</html>
<?php $this->endPage() ?>






