<?php
require_once("../Functions/PDOConn.php");
require_once("../Functions/PublicFunc.php");

if(isset($_POST['GamesID']) && $_POST['GamesID']){
  $GamesID=$_POST['GamesID'];
  $sql="DELETE FROM games_list WHERE GamesID=?";
  $rs=PDOQuery($dbcon,$sql,[$GamesID],[PDO::PARAM_INT]);
  if($rs[1]==1){
    die("1");
  }else{
    die("2");
  }
}else{
  die("0");
}
?>