<?php
?>
<script src="/js/jquery.min.js"></script>
<div>
	<p>这里是聊天室</p>
	<div>
		<span>my email:</span><input type="text" id="myemail" />
		<button id="conn">连接</button>
	</div>
	<div id="history"></div>
	<textarea rows="3" cols="" id ="chat"></textarea><br/>
	<input type="text" id="toemail" /><br/>
	<button id="send">send</button>
	<button id="close">close</button>
</div>
<script>
$(function(){
	var websocket = null;
	$('#conn').click(function(){
		var myemail = $('#myemail').val();
		if (myemail == '') {
			console.log('输入email');
			return;
		}
		var wsServer = 'ws://121.42.137.238:9501?email='+myemail;
		websocket = new WebSocket(wsServer);
		websocket.onopen = function (evt) {
		    console.log("Connected to WebSocket server.");
		    $('#history').append('<p>Connected to WebSocket server.</p>');
		};

		websocket.onclose = function (evt) {
		    console.log("Disconnected");
		    $('#history').append('<p>Disconnected</p>');
		};

		websocket.onmessage = function (evt) {
		    console.log('Retrieved data from server: ' + evt.data);
		    $('#history').append('<p>'+'Retrieved data from server: ' + evt.data+'</p>');
		};

		websocket.onerror = function (evt, e) {
		    console.log('Error occured: ' + evt.data);
		    $('#history').append('<p>'+'Error occured: ' + evt.data+'</p>');
		};
	})
	
	$('#send').click(function(){
		var data = $('#chat').val();
		var toEmail = $('#toemail').val();
		var senddata = JSON.stringify({'to':toEmail, 'data':data});
		websocket.send(senddata);
		$('#history').append('<p>send:'+data+'</p>');
		$('#chat').val("");
	});

	$('#close').click(function() {
		websocket.close();
	})
});
</script>