<?php
require_once("../Functions/PDOConn.php");
require_once("../Functions/PublicFunc.php");

$AllowUser="";

if(isset($_POST) && $_POST){
  $AthIDs=$_POST['AthIDs'];
  $GamesID=$_POST['GamesID'];
  
  // 循环写入
  foreach($AthIDs as $v){
    $AllowUser=$AllowUser.$v.",";
  }
  $AllowUser=substr($AllowUser,0,strlen($AllowUser)-1);
  
  $sql="UPDATE games_list SET AllowUser=? WHERE GamesID=?";
  $rs=PDOQuery($dbcon,$sql,[$AllowUser,$GamesID],[PDO::PARAM_STR,PDO::PARAM_INT]);
  if($rs[1]==1){
    die("1");
  }else{
    die("2|".$AthIDs."|".$GamesID);
  }
}else{
  die("0");
}
?>