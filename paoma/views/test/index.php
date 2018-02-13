<?php
?>
<html>
	<body>
		<button onclick="connection()">连接</button>
		<button onclick="send()">发送消息</button>
		<textarea id="message" rows="3" cols=""></textarea>
	</body>
</html>
<script>
var ws = null;

function connection() {
	ws = new WebSocket('ws://120.79.30.72:9502');

	//接受到消息事件
	ws.onmessage = function(evt) {
		var received_msg = evt.data;
		console.log("接收到消息");
		console.log(received_msg);
		document.getElementById('message').innerHTML = received_msg;
	};

	//服务端关闭
	ws.onclose = function() {
		document.getElementById('message').innerHTML = '服务端异常已关闭';
	}
}

function send() {
	if (!ws) {
		alert('服务器未连接');
		return;
	}
	ws.send('cacaa');
}
</script>