<?php
include("Functions/OEA.php");
$OEA=new OEA();

$RealName=getSess(Prefix."RealName");
$NowUserID=GetSess(Prefix."UserID");
$Info_SQL="SELECT UserName FROM sys_user WHERE UserID=?";
$Info_rs=PDOQuery($dbcon,$Info_SQL,[$NowUserID],[PDO::PARAM_INT]);
$NowUserName=$Info_rs[0][0]['UserName'];

if(isset($_POST) && $_POST){
  if(!isset($_GET['isFirst']) || $_GET['isFirst']!=1){
    $ipt_PW=$_POST['Password'];

    $sql1="SELECT Password,salt,UserName,RealName FROM sys_user WHERE UserID=?";
    $rs1=PDOQuery($dbcon,$sql1,[$NowUserID],[PDO::PARAM_INT]);
    $PW_indb=$rs1[0][0]['Password'];
    $salt=$rs1[0][0]['salt'];
    $UserName=$rs1[0][0]['UserName'];
    $RealName=$rs1[0][0]['RealName'];
    $iptPW=encryptPW($ipt_PW,$salt);
  
    if($iptPW != $PW_indb){
      die('<script>alert("原密码错误！");history.back();</script>');
    }
  }else{
    addLog($dbcon,"用户","强制修改密码",$RealName);
    $UserName=$_GET['u'];
    $RealName=$_GET['r'];
  }
  
  $sql2="UPDATE sys_user SET Password=?,salt=?,OriginPassword='',Status=2";
  $NewUserName=$_POST['ChangeUserName'];

  // 修改用户名之前，检查新用户名是否存在
  if($NowUserName!=$NewUserName){
    // 检查是否有汉字
    if(preg_match("/([\x81-\xfe][\x40-\xfe])/",$NewUserName,$match)){
      die("<script>alert('用户名不允许存在汉字！');history.go(-1);</script>");
    }
    if(preg_match("/^[a-zA-Z\s]+$/",substr($NewUserName,0,1))){
      die("<script>alert('用户名首位需为字母！');history.go(-1);</script>");
    }
    if(preg_match("/^[a-zA-Z0-9\s]+$/",$NewUserName)){
      die("<script>alert('用户名只能含有字母和数字！');history.go(-1);</script>");
    }

    $Info_SQL="SELECT COUNT(*) FROM sys_user WHERE UserName=?";
    $Info_rs=PDOQuery($dbcon,$Info_SQL,[$NewUserName],[PDO::PARAM_STR]);
    if($Info_rs[0][0]['COUNT(*)']!=0){
      die("<script>alert('此用户名已存在！\\n请更换其他用户名！');history.go(-1);</script>");
    }

    $sql2.=",UserName='{$NewUserName}'";
  }

  $salt=getRanSTR(8);
  $ipt_New=$_POST['NewPW'];
  $ipt_Vrf=$_POST['VerifyPW'];
  
  if(strlen($ipt_New)<6){
    die("<script>alert('密码长度须大于6位！');history.go(-1);</script>");
  }

  $NewPW=encryptPW($ipt_New,$salt);
  if($ipt_New!=$ipt_Vrf){
    die("<script>alert('两次输入的密码不相同！');history.go(-1);</script>");
  }
  
  $sql2.=" WHERE UserID=?";
  $rs2=PDOQuery($dbcon,$sql2,[$NewPW,$salt,$UserID],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_INT]);
  
  $ShowPwd=$OEA->Encrypt($ipt_New);
  if($rs2[1]==1){
    $url="index.php?file=User&action=ShowOriginPW.php&u=$NewUserName&r=$RealName&p={$ShowPwd[0]}&k={$ShowPwd[1]}";
    echo "<script>window.location.href='$url';</script>";
  }
}
?>

<form method="post">
<div class="well col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center col-xs-10 col-xs-offset-1">
  <img src="res/img/back.png" style="position:absolute;wIDth:24px;top:17px;left:5%;cursor:pointer" onclick="history.back()" aria-label="返回">
  <h3>修改用户名密码</h3><br>
  <div class="alert alert-success alert-dismissible" role="alert">
    ▲如不需修改用户名，可无需理会“用户名”一栏。
  </div>
  <div class="input-group">
    <span class="input-group-addon">用户名</span>
    <input class="form-control" name="ChangeUserName" value="<?php echo $NowUserName; ?>">
    <span class="input-group-addon" ID="forgot">&lt;</span>
  </div>
  <hr>
  <?php if(!isset($_GET['isFirst']) || $_GET['isFirst']!=1){ ?>
  <div class="input-group" ID="OldPassword">
    <span class="input-group-addon">旧密码</span>
    <input type="password" class="form-control" name="Password">
    <span class="input-group-addon" ID="forgot">&lt;</span>
  </div>
  <?php } ?>
  <div class="input-group">
    <span class="input-group-addon">新密码</span>
    <input type="password" class="form-control" name="NewPW">
    <span class="input-group-addon" ID="forgot">&lt;</span>
  </div>
  <div class="input-group">
    <span class="input-group-addon">再次输入</span>
    <input type="password" class="form-control" name="VerifyPW">
    <span class="input-group-addon" ID="forgot">&lt;</span>
  </div>
  <hr>
  <input type="submit" class="btn btn-success" style="width:100%" value="确 认 重 置">
</div>
</form>