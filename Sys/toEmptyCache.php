<?php
require_once("../Functions/PDOConn.php");
require_once("../Functions/PublicFunc.php");

if(isset($_POST) && $_POST){
	$Password=$_POST['Password'];
	$Sign=$_POST['Sign'];
  $CacheType=$_POST['CacheType'];
  
	if(getSess("SOA_Ajax_Sign") != $Sign){
		die("InvaildSign");
	}

	$NowUserid=GetSess("SOA_Userid");
  
  $sql1="SELECT Password,salt FROM sys_user WHERE Userid=?";
  $Verify_rs=PDOQuery($dbcon,$sql1,[$NowUserid],[PDO::PARAM_INT]);
  $iptPW_indb=$Verify_rs[0][0]['Password'];
  $salt=$Verify_rs[0][0]['salt'];

  $iptPW=encryptPW($Password,$salt);
  
  if($iptPW != $iptPW_indb){
    die('PasswordErr');
  }
  
  $Cache=new Cache($dbcon,$CacheType);
  // 删除导出缓存
  $Cache->E();

  die("1");
}
?>