<?php
$BeginDay=strtotime($GB_Sets->G("SystemBeginRunDay",2,"System"));
$NowDay=strtotime(date("Ymd"));
$RunDays=round(($NowDay-$BeginDay)/3600/24);

if($RunDays>365){
  $Year=floor($RunDays/365);
  $RunDays=$RunDays-$Year*365;
}else{
  $Year=0;
}

if($RunDays>30){
  $Month=floor($RunDays/30);
  $RunDays=$RunDays-$Month*30;
}else{
  $Month=0;
}
?>

<div class="container">
  <hr>
  <center>
  <p style="font-weight:bolder;font-size:19px;line-height:26px;">
    &copy; 生蚝科技 2014-<?php echo date("Y"); ?>
	<a style="color:#07C160" data-toggle="modal" data-target="#wxModal"><i class="fa fa-weixin fa-lg" aria-hidden="true"></i></a>
	<a style="color:#FF7043" onclick='launchQQ()'><i class="fa fa-qq fa-lg" aria-hidden="true"></i></a>
	<a style="color:#29B6F6" href="mailto:master@xshgzs.com"><i class="fa fa-envelope fa-lg" aria-hidden="true"></i></a>
	<a style="color:#AB47BC" href="https://github.com/OysterTech" target="_blank"><i class="fa fa-github fa-lg" aria-hidden="true"></i></a><br>
    All Rights Reserved.<br>
    系统已安全运行<font color="green"><?php echo $Year; ?></font>年<font color="green"><?php echo $Month; ?></font>月<font color="green"><?php echo $RunDays; ?></font>天
  </center>
</div>

<script>
function launchQQ(){
	if(/Android|webOS|iPhone|iPod|BlackBerry/i.test(navigator.userAgent)){
		window.location.href="mqqwpa://im/chat?chat_type=wpa&uin=571339406";
	}else{
		window.open("http://wpa.qq.com/msgrd?v=3&uin=571339406");
	}
}
</script>

<div class="modal fade" id="wxModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<h3 class="modal-title">微信公众号/小程序二维码</h3>
			</div>
			<div class="modal-body">
				<center><img src="https://www.xshgzs.com/resource/index/images/mpCode.jpg" style="width:85%"><hr><img src="https://www.xshgzs.com/resource/index/images/wxOfficialAccountQRCode.jpg" style="width:85%"></center>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick='$("#wxModal").modal("hide");'>关闭 &gt;</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
