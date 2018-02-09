<?php
?>

<div>
	<!-- 房间信息 -->
	<div>
		<span>房间号码:<?=$room['room_no']?></span>
	</div>
	
	<?php if ($uuid == $room->uuid):?>
		<span>您是房主，请准备开始游戏，或邀请好友进入比赛</span>
	<?php else:?>
		<span>你是马客，请注意房主命令，摇动手机赢取奖金</span>
	<?php endif;?>
	
	<!-- 当前用户信息 -->
	<div>
		<img alt="用户头像" src="<?=$paoma_user->headimg?>" />
		<span>姓名:<?=$paoma_user->uname?></span>
		<span>性别:<?=$paoma_user->sex?></span>
	</div>
	
	<!-- 奖励机制 -->
	
</div>
<script>
$(function(){
	
})

//传送uuid，uid，action给swoole服务器
function loginCallback() {
	
}

//发送开始命令
function start() {
	
}

//设置奖励机制
function reward() {
	
}
</script>