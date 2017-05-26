<?php
require_once("../Functions/PDOConn.php");
require_once("../Functions/PublicFunc.php");

$SQL_Insert="INSERT INTO games_item(GamesID,ItemID) VALUES ";
$SQL_Select="SELECT * FROM games_item WHERE GamesID=?";
$SQL_Del="DELETE FROM games_item WHERE GamesID=?";

if(isset($_POST) && $_POST){  
  $ItemIDs=$_POST['ItemIDs'];
  $GamesID=$_POST['GamesID'];
  
  // 查询原本是否有项目，以便删除项目的记录数检验
  $rs_Select=PDOQuery($dbcon,$SQL_Select,[$GamesID],[PDO::PARAM_STR]);
  if($rs_Select[1]>=1){
    $HaveRows=1;
  }else{
    $HaveRows=0;
  }
  
  // 删除之前的项目
  $rs_Del=PDOQuery($dbcon,$SQL_Del,[$GamesID],[PDO::PARAM_STR]);
  if($rs_Del[1]<1 && $HaveRows==1){
    die("3");
  }
  
  // 循环写入SQL语句
  foreach($ItemIDs as $ItemID){
    $SQL_Insert=$SQL_Insert.'("'.$GamesID.'","'.$ItemID.'"),';
  }
  $SQL_Insert=substr($SQL_Insert,0,strlen($SQL_Insert)-1);
  $SQL_Insert.=";";
  
  // 添加项目
  $rs_Insert=PDOQuery($dbcon,$SQL_Insert,[],[]);
  if($rs_Insert[1]>=1){
    die("1");
  }else{
    die("2|".$ItemIDs."|".$GamesID."|".$sql);
  }

}else{
  die("0");
}
?>