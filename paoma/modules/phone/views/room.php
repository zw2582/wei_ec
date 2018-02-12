<?php
?>
<div>
	<!-- 房间信息 -->
	<div>
		<span>房间号码:<?=$room['room_no']?></span>
	</div>
	
	<?php if ($user['uuid'] == $room['uuid']):?>
		<span>您是房主，请准备开始游戏，或邀请好友进入比赛</span>
		<?php if ($user['uid']):?>
		<button class="begin">开始</button>
		<button class="invite">邀请好友</button>
		<?php else :?>
		<span>请先认证</span>
		<?php endif;?>
	<?php else:?>
		<span>你是马客，请注意房主命令，摇动手机赢取奖金</span>
	<?php endif;?>
	
	<!-- 当前用户信息 -->
	<input type="hidden" id="isactive" value="<?=$room['isactive']?>"/>
	<input type="hidden" id="room_no" value="<?=$room['"room_no"']?>"/>
	<input type="hidden" id="uid" value="<?=$user['uid']?>" />
	<input type="hidden" id="uuid" value="<?=$user['uuid']?>" />
	<input type="hidden" id="confirm_auth" value="<?= $confirm_auth ? true : false?>">
	<div>
		<?php if ($user['uid']):?>
		<img alt="用户头像" src="<?=$paoma_user->headimg?>" />
		<span>姓名:<?=$paoma_user->uname?></span>
		<span>性别:<?=$paoma_user->sex?></span>
		<?php endif;?>
	</div>
	
	<!-- 奖励机制 -->
	
	<!-- 得分 -->
	<h1 class="score"></h1>
	
</div>
<script>
$(function(){
	var uuid = $('#uuid').val();
	var uid = $('#uid').val();
	var confirm_auth = $('#confirm_auth').val();
	var begin = false;
	var isactive = $('#isactive').val();
	var count = 0;

	//如果没有登录则请求登录
	if (!uid) {
		location.href="/phone/site/auth";
	}
	
	var ws = new WebSocket(wsaddr+'?source=phone&uuid='+uuid);

	ws.onopen = function() {
		alert('您已进入房间，请注意开始命令');
		if(confirm_auth) {
			//认证确认
			ws.send(JSON.stringify({action:'auth_confirm','uuid':uuid,'uid':uid}));
		}
	};

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
			case 'auth_request':
				//认证确认
				ws.send(JSON.stringify({action:'auth_confirm','uuid':uuid,'uid':uid}));
				break;
			case 'play':
				//暂时不对phone开通
				if (data.status == 'comple') {
					showResult();
				} else {
					playing(data.data);
				}
				break;
			case 'start':
				alert('比赛开始，赶紧把手机摇起来!');
				begin = true;
				break;
			case 'prepare':
				alert('比赛即将开始，请做好准备!');
				break;
			}
		} 
	};

	//服务端关闭
	ws.onclose = function() {
		alert('服务端异常已关闭');
	}

	//发起游戏开始
	$('.begin').click(function(){
		var room_no = $('#room_no').val();
		ws.send(JSON.stringify({action:'start','uuid':uuid,'room_no':room_no}));
	});

	//邀请好友
	$('.invite').click(function(){
		//微信分享链接
		var room_no = $('#room_no').val();
		$url = host+"/phone/site/join?room_no="+room_no;
	});

	//监听手机摇动事件
	if (window.DeviceMotionEvent) {
		window.addEventListener('devicemotion', deviceMotionHandler, false);
	} else {
		alert('你的设备不支持摇动');
	}
	var SHAKE_THRESHOLD = 3000;
    var last_update = 0;
    var x = y = z = last_x = last_y = last_z = 0;
	function deviceMotionHandler(eventData) {
        var acceleration = eventData.accelerationIncludingGravity;
        var curTime = new Date().getTime();
        if ((curTime - last_update) > 100) {
            var diffTime = curTime - last_update;
            last_update = curTime;
            x = acceleration.x;
            y = acceleration.y;
            z = acceleration.z;
            var speed = Math.abs(x + y + z - last_x - last_y - last_z) / diffTime * 10000;
            if (speed > SHAKE_THRESHOLD) {
                if(!begin) {
                    alert('还没有开始，别着急');
                } else {
                	count++;
                	ws.send(JSON.stringify({action:'play','uuid':uuid,'room_no':room_no,'count':1}));
                	$('.score').val(count);
                }
            }
            last_x = x;
            last_y = y;
            last_z = z;
        }
    }
})

//设置奖励机制
function reward() {
	
}
</script>