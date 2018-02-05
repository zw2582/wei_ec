<?php
?>
<div>
	<!-- uuid -->
	<input type="hidden" id="uuid" value="<?=$master->uuid?>" />
	<input type="hidden" id="action" value="<?=$action?>" />
	
	<!-- 马场信息 -->
	<div>
		<span>这里是马场编号：<?=room_no?></span>
		<!-- 如果用户没有登录则显示二维码登录 -->
    	<?php if (Yii::$app->user->isGuest):?>
    		<span>当前房主还未登录，请微信扫码登录</span>
    		<div class="login">
    			<img alt="二维码扫描登录" src="/index.php/site/qrcode?str=<?=urlencode('http://paoma.com/phone/user/login?uuid='.$uuid.'&action='.$action)?>">
    		</div>
    	<?php else:?>
    		<span>房主：<?=master['uname']?></span>
    		<span><img alt="房主头像" src="<?=$master['headimg']?>"></span>
    	<?php endif;?>
    	
    	<!-- 操作 -->
    	<button>设置奖励</button>
    	<button>开始比赛</button>
	</div>
	
	<!-- 赛道信息 -->
	<div class="">
		<p class="speedway"><span class="horse">马</span></p>
		<p class="speedway"><span class="horse">马</span></p>
		<p class="speedway"><span class="horse">马</span></p>
		<p class="speedway"><span class="horse">马</span></p>
		<p class="speedway"><span class="horse">马</span></p>
		<p class="speedway"><span class="horse">马</span></p>
	</div>
</div>

<script>
$(function(){
	//建立websocket链接
	
})

//建立websocket链接
function connWebSocket() {
	//接收到房主信息绑定消息
	//接收到比赛消息
	//接收到结束消息
	//接收到房主退出消息
}

//开始比赛
function start() {
	
}

//比赛中获取数据
function playing(data) {
	
}

</script>