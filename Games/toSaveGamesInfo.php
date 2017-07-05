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
  $StartDate=$_POST['StartDate'];
  $Venue=$_POST['Venue'];
  
  $sql="UPDATE games_list SET GamesName=?,EndDate=?,StartDate=?,Venue=?,";
  
  if($EndDate>date("Ymd")){
    $sql.="isEnd='0',";  
  }else{
    $sql.="isEnd='1',";
  }
  
  $sql.="isPrivate=? WHERE GamesID=?";
  
  $rs=PDOQuery($dbcon,$sql,[$GamesName,$EndDate,$StartDate,$Venue,$isPrivate,$GamesID],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_INT]);

  if($rs[1]==1){
    die("1");
  }else{
    die("2");
  }
  
}else if($OprType=="Add"){
  $GamesName=$_POST['GamesName'];
  $EndDate=$_POST['EndDate'];
  $isPrivate=$_POST['isPrivate'];
  $StartDate=$_POST['StartDate'];
  $Venue=$_POST['Venue'];
  
  $sql="INSERT INTO games_list(GamesName,EndDate,StartDate,Venue,isPrivate) VALUES(?,?,?,?,?)";
  $rs=PDOQuery($dbcon,$sql,[$GamesName,$EndDate,$StartDate,$Venue,$isPrivate],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR]);
  
  if($rs[1]==1){
    die("1");
  }else{
    die("2");
  }
}
}

?>