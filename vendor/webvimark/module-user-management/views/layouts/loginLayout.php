<?php
use app\assets\AppAsset;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\bootstrap\BootstrapAsset;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

$this->title =  Yii::$app->params['app_name'];
BootstrapAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>"/>
	<meta name="robots" content="noindex, nofollow">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?>/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?>/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?>/favicon-16x16.png">
	<link rel="manifest" href="site.webmanifest">
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>

<?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>