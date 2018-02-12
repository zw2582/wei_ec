<?php
?>
<style>
.hidden {
    display: none;
    }
</style>
<div>
	<!-- uuid -->
	<input type="hidden" id="uuid" value="<?=$user->uuid?>" />
	<input type="hidden" id="uid" value="<?=$user->uid?>" />
	<input type="hidden" id="action" value="<?=$action?>" />
	
	<!-- 马场信息 -->
	<div>
		<span>这里是马场编号：<?=$room_no?></span>
		<!-- 如果用户没有登录则显示二维码登录 -->
    	<?php if (Yii::$app->user->isGuest):?>
    		<span>您还未登录，请微信扫码登录</span>
    		<div class="login">
    			<img alt="二维码扫描登录" src="/index.php/site/qrcode?str=<?=urlencode('http://paoma.com/phone/site/join?uuid='.$user->uuid)?>">
    		</div>
    	<?php else:?>
    		<span>您的头像：<?=$user['uname']?></span>
    		<span><img alt="$user" src="<?=$user['headimg']?>"></span>
    	<?php endif;?>
    	
    	<!-- 操作 -->
    	<?php if ($user->uuid == $room['uuid']):?>
        	<button class="setrule <?php empty($user->uid)?'hidden':''?>">设置奖励</button>
        	<button class="begin <?php empty($user->uid)?'hidden':''?>">开始比赛</button>
    	<?php endif;?>
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
	var uuid = $('#uuid').val();
	var uid = $('#uid').val();
	if (uuid == ''){
		alert('没有获取到uuid');
		return;
	}
	var ws = new WebSocket(wsaddr+'?source=web&uuid='+uuid);

	//建立连接事件
	ws.onopen = function() {
		connWebSocket();
	};

	//接受到消息事件
	ws.onmessage = function(evt) {
		var received_msg = evt.data;
		console.log("接收到消息");
		console.log(received_msg);
		if (received_msg.status == 0) {
			//消息发生错误
			alert(received_msg.message);
		} else {
			//判断消息做出相应
			var data = received_msg.data;
			switch (data.action) {
			case 'auth_confirm':
				login(data.uid);break;
			case 'play':
				if (data.status == 'comple') {
					showResult();
				} else {
					playing(data.data);
				}
				break;
			case 'start':
				start();break;
			case 'prepare':
				prepare(); break;
			}
		}
	};

	//服务端关闭
	ws.onclose = function() {
		alert('服务端异常已关闭');
	}

	//建立websocket链接
	function connWebSocket() {
		//如果用户没有登录，则发起认证申请
		if (!uid) {
			console.log('发起认证请求');
			ws.send(JSON.stringify({action:"auth_request",'uuid':uuid}));
		}
	}

	//开始比赛
	function start() {
		alert('比赛开始!');
	}

	//比赛中获取数据
	function playing(data) {
		console.log('获取到比赛数据:');
		console.log(data);
	}

	//登录认证
	function login(uid) {
		location.href='/login/login-auth?uid='+uid;
	}
})
</script>