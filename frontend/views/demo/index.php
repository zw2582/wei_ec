<?php 
use yii\helpers\Url;

?>
<div data-am-widget="slider" class="am-slider am-slider-default"
	data-am-slider='{}'>
	<ul class="am-slides">
		<li><img src="/images/banner.jpg"></li>
		<li><img src="/images/banner1.jpg"></li>
	</ul>
</div>
<a href="<?=Url::to(['/demo/search'])?>" class="search"> 开启你的美食之旅... </a>
<ul class="nav">
	<li><a href="<?=Url::to(['/demo/search'])?>"> <img src="/images/icon.jpg" />
			<p>最新推荐</p>
	</a></li>
	<li><a href="<?=Url::to(['/demo/search'])?>"> <img src="/images/icon1.jpg" />
			<p>热门菜谱</p>
	</a></li>
	<li><a href="<?=Url::to(['/demo/search'])?>"> <img src="/images/icon2.jpg" />
			<p>人气菜肴</p>
	</a></li>
	<li><a href="<?=Url::to(['/demo/yhq'])?>"> <img src="/images/icon3.jpg" />
			<p>优惠券</p>
	</a></li>
</ul>
<div data-am-widget="titlebar"
	class="am-titlebar am-titlebar-default title">
	<h2 class="am-titlebar-title ">积分菜品</h2>
	<nav class="am-titlebar-nav">
		<a href="#more" class="">more &raquo;</a>
	</nav>
</div>
<ul data-am-widget="gallery"
	class="am-gallery am-avg-sm-2 am-avg-md-3 am-avg-lg-4 am-gallery-default product">
	<li>
		<div class="am-gallery-item">
			<a href="<?=Url::to(['/demo/detail'])?>" class=""> <img src="/images/p.png" alt="" />
				<h3 class="am-gallery-title">商务单人餐</h3>
				<div class="am-gallery-desc">
					<em>￥50</em><i class="am-icon-cart-plus"></i>
				</div>
			</a>
		</div>
	</li>
	<li>
		<div class="am-gallery-item">
			<a href="<?=Url::to(['/demo/detail'])?>" class=""> <img src="/images/p1.png" alt="" />
				<h3 class="am-gallery-title">虐狗情人杯</h3>
				<div class="am-gallery-desc">
					<em>￥50</em><i class="am-icon-cart-plus"></i>
				</div>
			</a>
		</div>
	</li>
	<li>
		<div class="am-gallery-item">
			<a href="<?=Url::to(['/demo/detail'])?>" class=""> <img src="/images/p2.png" alt="" />
				<h3 class="am-gallery-title">卤香滑鸡</h3>
				<div class="am-gallery-desc">
					<em>￥50</em><i class="am-icon-cart-plus"></i>
				</div>
			</a>
		</div>
	</li>
	<li>
		<div class="am-gallery-item">
			<a href="<?=Url::to(['/demo/detail'])?>" class=""> <img src="/images/p3.png" alt="" />
				<h3 class="am-gallery-title">酷炫绵绵球</h3>
				<div class="am-gallery-desc">
					<em>￥50</em><i class="am-icon-cart-plus"></i>
				</div>
			</a>
		</div>
	</li>
</ul>
