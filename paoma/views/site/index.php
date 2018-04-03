
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=0, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <title>3D赛马 - 欢聚互动客户功能演示</title>

    <link rel="stylesheet" type="text/css" href="/css/bg.css">
    <style type="text/css">
        .phb-b2 table span{
            color:#fff;
        }
    </style>
    <script src="/js/jquery.min.js"></script>
    <script>
        var test = 0;
        console.log('sdfs')
        //ws://120.79.30.72:9502
        ws = new WebSocket("ws://127.0.0.1:9502?room_no=<?=$room_no?>&source=web");
        // 当socket连接打开时，输入用户名
        ws.onopen = function (event) {
            console.log('fff');
        };
        // 当有消息时根据消息类型显示不同信息
        ws.onmessage = onmessage;

        function onopen(e) {
            //console.log('f');
        }
        // 服务端发来消息时
        function onmessage(evt) {
        	//处理消息
			var received_msg = evt.data;
			console.log("接收到消息");
			console.log(received_msg);
			var msg = JSON.parse(received_msg);
			if (!msg.status || msg.status == 0) {
				console.log({title:'ws报错', content:msg.message})
			} else {
				var data = msg.data;
				if (data.action == 'exit_room') {
					//用户离开通知
					console.log(data.user.uname+":离开房间")
				} else if (data.action == 'join') {
					//用户加入通知
					console.log(data.user.uname+":加入房间")
				} else if (data.action == 'prepare') {
					//房间预备通知
					console.log('准备中')
				} else if (data.action == 'start') {
					console.log('开始比赛')
					$('.paomabeijing2').addClass('okplay')
					$('.paomabeijing').addClass('okplay')
					$('.tracklist').addClass('okplay')
				} else if (data.action == 'play') {
					
				} else if(data.action == 'stop') {
					console.log('比赛结束')
					$('.paomabeijing2').removeClass('okplay')
					$('.paomabeijing').removeClass('okplay')
					$('.tracklist').removeClass('okplay')
				} else if(data.action == 'result') {
					//比赛数据推送通知
					//_this.showRunData(data.result,data.ranks,data.max,data.min)
				}
			}
        }
    </script>

</head>

<body style="background: none;margin: auto;">
    <div class="result-layer " id="phb">
        <div class="result-label" style="display: none;">GAME OVER</div>
        <div class="result-cup" style="display: block;">
            <div class="phb-b1">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="phbbiaoge">
                    <thead>
                        <tr>
                            <th width="120" align="center">排名</th>
                            <th width="120" align="center">头像</th>
                            <th align="left">昵称</th>
                            <th width="200" align="center">
                                <span class="phoneth">手机号码</span>
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="phb-b2">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" class="phbbiaoge">
                    <tbody>
                        <tr>
                            <td width="120" align="center">
                                <span class="paimin">1</span>
                            </td>
                            <td width="120" align="center">
                                <img class="touxiang" src="./img/yin.png">
                            </td>
                            <td align="left">
                                <span class="nicheng">sdfsdf</span>
                            </td>
                            <td width="200" align="center">
                                <span class="phone">135****8773</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="120" align="center">
                                <span class="paimin">2</span>
                            </td>
                            <td width="120" align="center">
                                <img class="touxiang" src="./img/yin.png">
                            </td>
                            <td align="left">
                                <span class="nicheng">哦要</span>
                            </td>
                            <td width="200" align="center">
                                <span class="phone">150****8621</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="120" align="center">
                                <span class="paimin">3</span>
                            </td>
                            <td width="120" align="center">
                                <img class="touxiang" src="./img/tong.png">
                            </td>
                            <td align="left">
                                <span class="nicheng">庆</span>
                            </td>
                            <td width="200" align="center">
                                <span class="phone">138****9176</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
    <div class="yyy3d">
        <div class="paomabeijing2"></div>
        <div class="paomabeijing"></div>

        <div class="countdown_box">
            <div class="yyy3d_title_box game_title" style="display: block;">
                <!-- 距离结束<span class="time-down"> 7</span> 秒 -->
                <span class="one">游戏还未开始,敬请期待</span>
                <span class="two"></span>
                <span class="three"></span>
            </div>
            参与人数: <span class="canyu"> 0 </span>
        </div>

        <div class="tracklist" id="play-area">
        		<?php foreach ($users as $key=>$user):?>
            <div class="trackline leftfadein" style="display: block; height: 47.3px; line-height: 47.3px; font-size: 23.65px;">
                <div class="track-start" style="width: 47.3px; height: 47.3px;">1</div>
                <div class="track-end" style="width: 6px; height: 47.3px; display: block;"></div>
            
            <div class="player" style="width: 11.9px; height: 47.3px; line-height: 47.3px; font-size: 23.65px; top: <?=(($key+1)*47.3).px?>; left: 50%; display: block;">
                <div class="yyytp">
                		<div class="lunzib car0 okplay"></div>
                	</div>
                <div class="pnctx">
                     <div class="head shake" style="background-image: url('<?=$user['headimg']?>');"></div>
                     <div class="nickname"><?=$user['uname']?></div>
                </div>
           </div>
            
            </div>
            <?php endforeach;?>
        </div>

    </div>
    <div class="phb-b3" style="text-align:center">
        <button class="ready">准备</button>
        <button class="start" style="">开始</button>
        <button class="over" style="">结束</button>
        <!-- <span class="button reset" style="color:#fff">重玩本轮</span> -->
    </div>
</body>
<script type="text/javascript">
    $('.start').hide();
    $('.over').hide();
    $('#phb').hide();
    $('.ready').on('click',function(){
        $('.canyu').html('0');
        $(this).hide();
        $('#phb').hide();
        $('.start').show();
        ws.send('{"type":"ready"}');
    });

    $('.start').on('click',function(){
        $(this).hide();
        $('.one').html('开始倒计时:'); 
        $('.three').html('秒'); 
        starttime(5);
        // ws.send('{"type":"start"}');
    });

    $('.over').on('click',function(){
        ws.send('{"type":"over"}');
    });


    
    //显示倒数秒数  
    function starttime(t){  
        t -= 1;  
        $('.two').html(t);  
        // console.log(t);
        if(t==0){  
            $('.one').html('游戏结束倒计时：');
            ws.send('{"type":"start"}'); 
            overtime(50);
        }else{
            setTimeout("starttime("+t+")",1000);  
        }
        //每秒执行一次,starttime()  
    }

    function overtime(f){  
        $('.two').html(f);  
        f -= 1;  
        
        if(f==-1){  
            $('.one').html('游戏结束');
            $('.two').html('');
            $('.three').html('');
            ws.send('{"type":"over"}'); 
            $('#phb').show();
            $('.ready').show();
        }else{
            setTimeout("overtime("+f+")",1000);  
        }
        //每秒执行一次,overtime()  
    }

</script>
</html>