<?php
require_once("../Functions/PublicFunc.php");
require_once("../Functions/PDOConn.php");

$GB_Sets=new Settings("../GlobalSettings.json");
define("Prefix",$GB_Sets->G("SessionPrefix",2,"System"));

$nowRealName=getSess(Prefix."RealName");

if(isset($_POST) && $_POST){
  $Type=$_POST['Type'];
  $Modules=$_POST['Modules'];
  $Title=$_POST['Title'];
  $Content=$_POST['Content'];
  $ReplyMethod=$_POST['ReplyMethod'];
  $ReplyUserInfo=$_POST['ReplyUserInfo'];
  $OrderTime=date("Y-m-d H:i:s");
  $sql="INSERT INTO workorder_list(CreateRealName,Type,Status,Modules,OrderTime,Title,Content,ReplyMethod,ReplyUserInfo) VALUES (?,?,?,?,?,?,?,?,?)";
  $rs=PDOQuery($dbcon,$sql,[$nowRealName,$Type,"1",$Modules,$OrderTime,$Title,$Content,$ReplyMethod,$ReplyUserInfo],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR]);
  
  if($rs[1]==1){
    $rs2=PDOQuery($dbcon,"SELECT OrderID FROM workorder_list WHERE CreateRealName=? AND OrderTime=? AND Title=?",[$nowRealName,$OrderTime,$Title],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR]);
    $OrderID=$rs2[0][0]['OrderID'];
    die("1|".$OrderID."|".$OrderTime);
  }else{
    die("2");
  }
}
?>