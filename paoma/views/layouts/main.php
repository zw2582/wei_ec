<?php

/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use frontend\assets\AppAsset;
use yii\helpers\Url;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	
    <script src="/js/jquery.min.js"></script>
    
    <script type="text/javascript">
		var wsaddr = "ws://120.79.30.72:9502";
		var host = "http://paoma.iillyy.com";
    </script>
</head>
<body>
<?php $this->beginBody() ?>

<?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
