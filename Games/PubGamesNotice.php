<?php
$GamesID=isset($_GET['GamesID'])?$_GET['GamesID']:"";
if($GamesID=="") ErrCodedie("500");

if(isset($_POST) && $_POST){
  $Type=$_POST['Type'];
  $Title=$_POST['Title'];
  $Content=$_POST['Content'];
  $rs=PDOQuery($dbcon,"INSERT INTO games_notice(GamesID,Type,Title,Content) VALUES(?,?,?,?)",[$GamesID,$Type,$Title,$Content],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR]);
  
  if($rs[1]==1){
    $rs=PDOQuery($dbcon,"SELECT NoticeID FROM games_notice ORDER BY NoticeID DESC",[],[]);
    $NoticeID=$rs[0][0]['NoticeID'];
    $URL="index.php?file=Games&action=UploadGamesNoticeFile.php&NoticeID=$NoticeID&GamesID=$GamesID";
    die("<script>window.location.href='$URL';</script>");
  }else{
    die(var_dump($rs));
  }
}
?>

<script src="https://unpkg.com/wangeditor/release/wangEditor.min.js"></script>

<h1>发布比赛通知</h1>
<div class="alert alert-success alert-dismissible" role="alert">
  ▲ 若屏幕不能显示所有编辑功能，请向右滑动以显示。
  <br>
  ▲ 先发布文字，再上传附件。
</div>
<hr>

<form method="post" id="Form">
[<select name="Type" id="Type">
  <option value="0">-----请选择通知类型-----</option>
  <option value="1">通知</option>
  <option value="2">规程</option>
  <option value="3">秩序册</option>
</select>]：<input name="Title">

<hr>

<div id="wangEditor_div"></div>
<br>

  <input type="hidden" id="Content" name="Content">
</form>

<hr>

<button type="button" class="btn btn-success" style="width:98%" onclick="toPublish()">发 布</button>
<script>
var E = window.wangEditor;
var editor = new E('#wangEditor_div');
editor.customConfig.uploadImgShowBase64 = true;
editor.create();

function toPublish(){
  Content=editor.txt.html();
  
  if(Content=="<p><br></p>"){
    alert("请输入需要发布的内容！");
    return false;
  }
  if($("#Type").val()=="0"){
    alert("请选择通知类型！");
    $("#Type").focus();
    return false;
  }
  
  $("#Content").val(Content);
  $("#Form").submit();
}
</script>
