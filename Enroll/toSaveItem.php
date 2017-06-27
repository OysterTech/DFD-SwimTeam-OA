<?php
require_once("../Functions/PDOConn.php");
require_once("../Functions/PublicFunc.php");

$SQL_Insert="INSERT INTO enroll_item(AthID,GamesID,ItemID) VALUES ";
$AthID=GetSess(Prefix."AthID");

if(isset($_POST) && $_POST){
  $ItemIDs=$_POST['ItemIDs'];
  $GamesID=$_POST['GamesID'];
  
  // 循环写入SQL语句
  foreach($ItemIDs as $ItemID){
    $SQL_Insert=$SQL_Insert.'("'.$AthID.'","'.$GamesID.'","'.$ItemID.'"),';
  }
  $SQL_Insert=substr($SQL_Insert,0,strlen($SQL_Insert)-1);
  $SQL_Insert.=";";
  
  // 添加项目
  $rs_Insert=PDOQuery($dbcon,$SQL_Insert,[],[]);
  if($rs_Insert[1]>=1){
    die("1");
  }else{
    die("2| ".$SQL_Insert);
  }
}else{
  die("0");
}
?>