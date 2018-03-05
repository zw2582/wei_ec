<?php
?>
<html>
	<body>
		<button onclick="connection()">连接</button>
		<button onclick="send()">发送消息</button>
		<button onclick="login()">登录</button>
		<textarea id="message" rows="3" cols=""></textarea>
	</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/vue@2.5.13/dist/vue.js"></script>
<script src="https://cdn.bootcss.com/vue-resource/1.3.4/vue-resource.common.js"></script>
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
function login() {
	Vue.http.get('/test/login', {withCredentials:true}).then(function(res){
		console.log(res.data);
	}, function(err){
		console.log(err);
	});
}
</script>