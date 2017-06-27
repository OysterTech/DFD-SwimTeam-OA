<?php
$GamesID=isset($_GET['GamesID'])?$_GET['GamesID']:"";
$NoticeID=isset($_GET['NoticeID'])?$_GET['NoticeID']:"";
if($GamesID=="" || $NoticeID=="") ErrCodedie("500");
$FileDIR='UploadFile/Notice/'.$GamesID."/";

$FileJSON=getSess(Prefix."GN_File");
if($FileJSON==NULL) $FileJSON=array();
else $FileJSON=json_decode($FileJSON);

// 创建文件夹
if(!file_exists($FileDIR)){
	mkdir($FileDIR);
}

if(isset($_FILES) && $_FILES){
  $Name=$_POST['Name'];
  if($Name=="") toAlertDie("请输入附件名称！");
  foreach($_FILES["file"]["error"] as $key => $error){
    if($error == UPLOAD_ERR_OK){
      $name=$_FILES["file"]["name"][$key];
      $tmp_name=$_FILES["file"]["tmp_name"][$key];
      if(file_exists($FileDIR.$name)){
        echo "<font color='red'>".$name." 已经存在</font><hr>";
      }else{
        echo "Upload: ".$name."<br>";
        echo "Type: ".$_FILES["file"]["type"][$key]."<br>";
        echo "Size: ".($_FILES["file"]["size"][$key]/1024)." Kb<br>";
        move_uploaded_file($tmp_name,$FileDIR.$name);

        // 储存附件信息
        $Info['Name']=$Name;
        $Info['Path']=$FileDIR.$name;
        array_push($FileJSON,$Info);
        $FileJSON=json_encode($FileJSON);
        setSess(Prefix."GN_File",$FileJSON);
      }
    }else{
      $ErrorCode=$_FILES["file"]["error"][$key];
      // 错误码4->未选择文件
      if($ErrorCode==4){}
      else{
        echo "Error Code: ".$ErrorCode."<br>";
      }
    }
  }
}

if(isset($_POST['Pub']) && $_POST['Pub']){
  $FileJSON=json_encode($FileJSON);
  $rs=PDOQuery($dbcon,"UPDATE games_notice SET FileJSON=? WHERE NoticeID=?",[$FileJSON,$NoticeID],[PDO::PARAM_STR,PDO::PARAM_INT]);
  if($rs[1]==1){
    setSess(Prefix."GN_File","");
    $URL="index.php?file=Games&action=toGamesNoticeList.php&GamesID=".$GamesID;
    die("<script>window.location.href='$URL';</script>");
  }else{
    var_dump($rs);
  }
}
?>

<h1>发布比赛通知</h1>
<h2>上传附件</h2>
<hr>

<form method="post" enctype="multipart/form-data" name="UploadFile">
  <div align="left">
    附件标题：<input type="text" name="Name"><br>
    <input type="file" name="file[]">
  </div>
  <hr>
  <input type="submit" class="btn btn-primary" style="width:98%" value="上 传">
</form>

<hr>

<form method="post" name="AllPub">
  <input type="submit" class="btn btn-success" style="width:98%" value="立 即 发 布" name="Pub">
</form>