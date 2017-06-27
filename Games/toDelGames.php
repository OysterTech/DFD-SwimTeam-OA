<?php
require_once("../Functions/PDOConn.php");
require_once("../Functions/PublicFunc.php");
$NowUserName=getSess(Prefix."RealName");

if(isset($_POST['GamesID']) && $_POST['GamesID']){
  $GamesID=$_POST['GamesID'];
  $GamesName=$_POST['GamesName'];

  $sql1="DELETE FROM games_list WHERE GamesID=?";
  $sql2="DELETE FROM games_item WHERE GamesID=?";
  $sql3="DELETE FROM enroll_item WHERE GamesID=?";
  $sql4="UPDATE games_notice SET isDelete=1 WHERE GamesID=?";
  $rs1=PDOQuery($dbcon,$sql1,[$GamesID],[PDO::PARAM_INT]);
  $rs2=PDOQuery($dbcon,$sql2,[$GamesID],[PDO::PARAM_STR]);
  $rs3=PDOQuery($dbcon,$sql3,[$GamesID],[PDO::PARAM_STR]);
  $rs4=PDOQuery($dbcon,$sql4,[$GamesID],[PDO::PARAM_STR]);

  if($rs1[1]==1){
  	addLog($dbcon,"比赛","[$GamesName] 被删除",$NowUserName);
    die("1");
  }else{
    die("2");
  }
}else{
  die("0");
}
?>