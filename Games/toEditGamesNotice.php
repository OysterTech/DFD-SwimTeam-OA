<?php
$NoticeID=isset($_GET['NoticeID'])?$_GET['NoticeID']:"";
$GamesID=isset($_GET['GamesID'])?$_GET['GamesID']:"";
if($NoticeID=="" || $GamesID=="") ErrCodedie("500");

$rs=PDOQuery($dbcon,"SELECT * FROM games_notice WHERE NoticeID=?",[$NoticeID],[PDO::PARAM_STR]);

if(isset($_POST) && $_POST){
  $Type=$_POST['Type'];
  $Title=$_POST['Title'];
  $Content=$_POST['Content'];
  $rs2=PDOQuery($dbcon,"UPDATE games_notice SET Type=?,Title=?,Content=? WHERE NoticeID=?",[$Type,$Title,$Content,$NoticeID],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR]);
  
  if($rs2[1]==1){
    $URL="index.php?file=Games&action=UploadGamesNoticeFile.php&NoticeID=$NoticeID&GamesID=$GamesID";
    die("<script>window.location.href='$URL';</script>");
  }else{
    die(var_dump($rs));
  }
}
?>

<script src="res/js/wangEditor.js"></script>

<h1>编 辑 比 赛 通 知</h1>
<div class="alert alert-success alert-dismissible" role="alert">
  ▲ 若屏幕不能显示所有编辑功能，请向右滑动以显示。
  <br>
  ▲ 先发布文字，再上传附件。
</div>
<hr>

<form method="post" id="Form">
<select name="Type" id="Type" style="width:165px;" value="<?=$rs[0][0]['Type'];?>">
  <option value="0">--- 请选择通知类型 ---</option>
  <option value="1">通知</option>
  <option value="2">规程</option>
  <option value="3">秩序册</option>
</select>
<input name="Title" id="title" style="width:250px;" autocomplete="off" value="<?=$rs[0][0]['Title'];?>">
<hr>

<div id="wangEditor_div"></div>
<br>

<input type="hidden" id="Content" name="Content">
</form>

<hr>

<button type="button" class="btn btn-success" style="width:98%" onclick="toPublish()">发 布</button>
<script>

/** ▼ WangEditor 文本编辑器 ▼ */
var E = window.wangEditor;
var editor = new E('#wangEditor_div');
editor.create();
editor.txt.html('<?=$rs[0][0]['Content'];?>');
$("div").blur();
/** ▲ WangEditor 文本编辑器 ▲ */

function toPublish(){
  Content=editor.txt.html();
  
  if($("#Type").val()=="0"){
    alert("请选择通知类型！");
    $("#Type").focus();
    return false;
  }
  if($("#title").val()==""){
    alert("请输入通知标题！");
    $("#title").focus();
    return false;
  }
  if(Content=="<p><br></p>"){
    alert("请输入需要发布的内容！");
    return false;
  }

  $("#Content").val(Content);
  $("#Form").submit();
}
</script>
