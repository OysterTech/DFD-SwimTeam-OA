<?php
require_once("Functions/PDOConn.php");
require_once("Functions/PublicFunc.php");


/********** ▼ 检测登录状态 ▼ **********/
define("GlobalSetName","GlobalSettings.json");
$GB_Sets=new Settings(GlobalSetName);
define("Prefix",$GB_Sets->G("SessionPrefix",2,"System"));

if(getSess(Prefix."isLogged")!="1"){
  header("Location:User/Logout.php");
}
/********** ▲ 检测登录状态 ▲ **********/


if(isset($_GET['Code']) && $_GET['Code']){
  $Code=$_GET['Code'];
  $rs=PDOQuery($dbcon,"SELECT * FROM file_list WHERE Code=?",[$Code],[PDO::PARAM_STR]);
  if($rs[1]!=1) die(toAlertDie("500","参数错误！\\n请从正确途径进入本页面！"));
  $FilePath=$rs[0][0]['FilePath'];
  $FileName=$rs[0][0]['FileName'];
}else{
	die(toAlertDie("500","参数错误！\\n请从正确途径进入本页面！"));
}

// 检查文件是否存在
if(!file_exists($FilePath)){
  die(toAlertDie("500","参数错误！\\n请从正确途径进入本页面！"));
}else{
  $File=fopen($FilePath,"r");
  Header("Content-type:application/octet-stream");
  Header("Accept-Ranges:bytes");
  Header("Accept-Length:".filesize($FilePath));
  Header("Content-Disposition:attachment;filename=".$FileName);
  ob_clean();  
  flush();
  // 读取文件内容并直接输出到浏览器
  echo fread($File,filesize($FilePath));
  fclose($File);
}
?>