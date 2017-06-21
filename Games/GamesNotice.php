<?php
$NoticeID=isset($_GET['NoticeID'])?$_GET['NoticeID']:"";
$sql="SELECT * FROM games_notice WHERE NoticeID=?";
$rs=PDOQuery($dbcon,$sql,[$NoticeID],[PDO::PARAM_STR]);

$Title=$rs[0][0]['Title'];
$Content=$rs[0][0]['Content'];
$FileJSON=$rs[0][0]['FileJSON'];
$PageView=$rs[0][0]['PageView']+1;
$PubTime=$rs[0][0]['PubTime'];

if($FileJSON!=""){
  $FileJSON=json_decode($FileJSON,true);
}else{
  $FileJSON=array();
}

$rs=PDOQuery($dbcon,"UPDATE games_notice SET PageView=$PageView WHERE NoticeID=?",[$NoticeID],[PDO::PARAM_INT]);
?>

<h2><?php echo $Title; ?></h2>

<hr>

<h4>
  发布日期：<?php echo $PubTime; ?>
  <br>
  浏览量：<?php echo $PageView; ?>
</h4>

<hr>

<!-- ▼ 通知内容 ▼ -->
<div align="left" style="font-size:16px">
  <?php echo $Content; ?>
</div>
<!-- ▲ 通知内容 ▲ -->

<hr>

<!-- ▼ 附件列表 ▼ -->
<div align="right" style="font-size:16px">
  <?php
    foreach($FileJSON as $Value){
    	$Name=$Value['Name'];
    	$Path=$Value['Path'];
  ?>
  ▲ <a href="<?php echo $Path; ?>"><?php echo $Name; ?></a><br>
  <?php } ?>
</div>
<!-- ▲ 附件列表 ▲ -->
