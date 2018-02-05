<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div>
	<button click="joinRoom()" href="/room/join">进入房间</button>
	<a href="/room/create">创建房间</a>
</div>

<script>
function joinRoom() {
	var roomno = prompt("请输入房间号");
	location.href="/room/join?roomno="+roomno;
}
</script>
