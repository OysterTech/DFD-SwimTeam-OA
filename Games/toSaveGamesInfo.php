<?php
require_once("../Functions/PDOConn.php");
require_once("../Functions/PublicFunc.php");

if(isset($_POST) && $_POST){
$OprType=isset($_POST['OprType'])?$_POST['OprType']:die("0");

if($OprType=="Edit"){
  $GamesID=$_POST['GamesID'];
  $GamesName=$_POST['GamesName'];
  $EndDate=$_POST['EndDate'];
  $isPrivate=$_POST['isPrivate'];
  
  if($EndDate>date("Ymd")){
    $sql="UPDATE games_list SET GamesName=?,EndDate=?,isOpen='1',isPrivate=? WHERE GamesID=?";  
  }else{
    $sql="UPDATE games_list SET GamesName=?,EndDate=?,isOpen='0',isPrivate=? WHERE GamesID=?";
  }
  
  $rs=PDOQuery($dbcon,$sql,[$GamesName,$EndDate,$isPrivate,$GamesID],[PDO::PARAM_STR,PDO::PARAM_INT,PDO::PARAM_STR,PDO::PARAM_INT]);

  if($rs[1]==1){
    die("1");
  }else{
    die("2");
  }
}else if($OprType=="Add"){
  $GamesName=$_POST['GamesName'];
  $EndDate=$_POST['EndDate'];
  $isPrivate=$_POST['isPrivate'];
  
  $sql="INSERT INTO games_list(GamesName,EndDate,isPrivate) VALUES(?,?,?)";
  $rs=PDOQuery($dbcon,$sql,[$GamesName,$EndDate,$isPrivate],[PDO::PARAM_STR,PDO::PARAM_INT,PDO::PARAM_STR]);
  
  if($rs[1]==1){
    die("1");
  }else{
    die("2");
  }
}
}

?>