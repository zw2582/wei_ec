
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
    <script type="/js/index.js"></script>
</head>

<body style="background: none;margin: auto;">
    <div class="result-layer " style="display: none" id="phb">
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
                <span class="one">游戏还未开始,敬请期待</span>
                <span class="two"></span>
                <span class="three"></span>
            </div>
            参与人数: <span class="canyu"> <?=count($users)?> </span>
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
</body>
</html>