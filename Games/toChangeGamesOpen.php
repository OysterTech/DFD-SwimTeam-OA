<?php
require_once("../Functions/PDOConn.php");
require_once("../Functions/PublicFunc.php");

if(isset($_POST) && $_POST){
  $GamesID=$_POST['GamesID'];
  $Status=$_POST['Status'];
  
  if($Status=="0") $Change="1";
  elseif($Status=="1") $Change="0";
  
  $sql="UPDATE games_list SET isOpen=? WHERE GamesID=?";
  $rs=PDOQuery($dbcon,$sql,[$Change,$GamesID],[PDO::PARAM_STR,PDO::PARAM_INT]);
  if($rs[1]==1){
    die("1");
  }else{
    die("2".$sql);
  }
}
?>