<?php

/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use frontend\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
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
</head>
<body>
<?php $this->beginBody() ?>

<?= $content ?>
<div class="h50"></div>
<div data-am-widget="navbar"
		class="am-navbar am-cf am-navbar-default footer " id="">
		<ul class="am-navbar-nav am-cf am-avg-sm-4">
			<li><a href="<?=Url::to(['/demo/index'])?>" class=""> <span class=""><img
						src="/images/nav.png" /></span> <span class="am-navbar-label">点餐</span>
			</a></li>
			<li><a href="<?=Url::to(['/demo/speak'])?>" class=""> <span class=""><img
						src="/images/nav2.png" /></span> <span class="am-navbar-label">客说</span>
			</a></li>
			<li><a href="<?=Url::to(['/demo/we'])?>" class=""> <span class=""><img
						src="/images/nav3.png" /></span> <span class="am-navbar-label">我们</span>
			</a></li>
			<li><a href="<?=Url::to(['/demo/member'])?>" class=""> <span class=""><img
						src="/images/nav4.png" /></span> <span class="am-navbar-label">我的</span>
			</a></li>

		</ul>
	</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
