<?php
$GamesID=isset($_GET['GamesID'])?$_GET['GamesID']:"";
$NoticeID=isset($_GET['NoticeID'])?$_GET['NoticeID']:"";
if($GamesID=="" || $NoticeID=="") ErrCodedie("500");
$FileDIR='UploadFile/Notice/'.$GamesID."/";
$nowUserName=getSess(Prefix."RealName");

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
      $Suffix=strstr($name,".");
      $NumName=date("YmdHis").mt_rand(111,999).$Suffix;
      if(file_exists($FileDIR.$NumName)){
        echo '<font color="red" style="font-size:20;font-weight:bolder;">'.$name."已经存在</font><hr>";
      }else{
        if(move_uploaded_file($tmp_name,$FileDIR.$NumName)==true){
          echo '<font color="green" style="font-size:20;font-weight:bolder;">上传成功！</font><br>';
          echo "文件名: ".$name."<br>";
          echo "文件大小: ".($_FILES["file"]["size"][$key]/1024)." Kb<br>";

          // 生成Code
          $Rand1=getRanSTR(8,0);
          $Rand2=mt_rand(170,627);
          $RandStr=substr($Rand1,0,3).substr($Rand2,0,1).substr($Rand1,4,2).substr($Rand2,1,2).substr($Rand1,6);
          $Code=$GamesID.$RandStr.$NoticeID;
          $Code=base64_encode($Code);
          $Code_rs=PDOQuery($dbcon,"INSERT INTO file_list(FilePath,FileName,Code,UploadUser) VALUES (?,?,?,?)",[$FileDIR.$NumName,$name,$Code,$nowUserName],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR]);

          // 储存附件信息
          $Info['Name']=$name;
          $Info['Code']=$Code;
          array_push($FileJSON,$Info);
          $FileJSON=json_encode($FileJSON);
          setSess(Prefix."GN_File",$FileJSON);
        }else{
          echo '<font color="red" style="font-size:20;font-weight:bolder;">上传失败！</font><br>';
        }
      }
    }else{
      $ErrorCode=$_FILES["file"]["error"][$key];
      // 错误码4->未选择文件
      if($ErrorCode==4){
        echo '<font color="red" style="font-size:20;font-weight:bolder;">请选择需要上传的文件！</font><br>';
      }else{
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

<div class="alert alert-danger alert-dismissible" role="alert">
  ▲ 上传完毕/后务必要点击“立即发布”！
</div>

<hr>

<form method="post" enctype="multipart/form-data" name="UploadFile">
  <div align="left">
    附件标题：<input type="text" name="Name"><br><br>
    <input type="file" name="file[]">
  </div>
  <hr>
  <input type="submit" class="btn btn-primary" style="width:98%" value="上 传">
</form>

<hr>

<form method="post" name="AllPub">
  <input type="submit" class="btn btn-success" style="width:98%" value="立 即 发 布" name="Pub">
</form>