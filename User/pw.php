<?php
require_once("../Functions/PDOConn.php");
require_once("../Functions/PublicFunc.php");

if(isset($_POST) && $_POST){
  
  //获取用户输入的数据
  $ipt_PW=$_POST['Password'];
  $ipt_UserName=$_POST['UserName'];
  
  //根据用户输入的用户名寻找对应资料
  $sql="SELECT Userid,Roleid,RealName,Password,salt FROM sys_user WHERE UserName=?";
  $rs=PDOQuery($dbcon,$sql,[$ipt_UserName],[PDO::PARAM_STR]);
  $PW_indb=$rs[0][0]['Password'];
  $salt=$rs[0][0]['salt'];
  $Userid=$rs[0][0]['Userid'];
  $Roleid=$rs[0][0]['Roleid'];
  $RealName=$rs[0][0]['RealName'];
  
  //将数据库里的输入的密码和salt合并加密
  $ipt_PW=encryptPW($ipt_PW,$salt);
  echo $ipt_PW;
}
?>

<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<script type="text/javascript" src="http://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>
</head>

<body>
<form method="post">
<div class="well col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center col-xs-10 col-xs-offset-1">
  <img src="res/img/back.png" style="position:absolute;width:24px;top:17px;left:5%;cursor:pointer" onclick="history.back()" aria-label="返回">
  <h3>登 录</h3><br>
  <div class="col-md-offset-2" style="line-height:12px;">
      <div class="input-group">
        <span class="input-group-addon">用户名</span>
        <input class="form-control" name="UserName" autocomplete="off">
        <span class="input-group-addon" id="forgot">&lt;</span>
      </div>
      <div class="input-group">
        <span class="input-group-addon">密码</span>
        <input type="password" class="form-control" name="Password">
        <span class="input-group-addon" id="forgot">&lt;</span>
      </div>
      <hr>
      <input type="submit" class="btn btn-success" style="width:100%" value="登 录 Login">
  </div>
</div>
</form>
