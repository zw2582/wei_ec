<?php 
use yii\helpers\Url;

?>
<div class="member">
			<div class="member-pic">
				<img src="/images/default_photo.png" />
			</div>
			<div class="member-infor">157****8547</div>
		</div>
		<ul class="member-nav">
			<li><a href="<?=Url::to(['/demo/address'])?>"><i class="am-icon-map-marker"></i><span>收货地址</span></a></li>
			<li><a href="<?=Url::to(['/demo/order'])?>"><i class="am-icon-newspaper-o"></i><span>我的订单</span></a></li>
			<li><a href=""><i class="am-icon-cart-arrow-down"></i><span>购物车</span></a></li>
			<li><a href=""><i class="am-icon-bell-o"></i><span>系统通知</span></a></li>
			<li><a href=""><i class="am-icon-credit-card"></i><span>会员卡</span></a></li>
			<li><a href="<?=Url::to(['/demo/yhq'])?>"><i class="am-icon-cc-mastercard"></i><span>优惠券</span></a></li>
			<li><a href=""><i class="am-icon-dollar"></i><span>积分</a></li>
		</ul>
		<ul class="member-nav mt">
			<li><a href=""><i class="am-icon-phone"></i>联系我们</a></li>
		</ul>
