<?php
require_once("../Functions/PDOConn.php");
require_once("../Functions/PublicFunc.php");

if(isset($_POST) && $_POST){
  $AthID=$_POST['AthID'];
  $RealName=$_POST['RealName'];
  $Sex=$_POST['Sex'];
  $Phone=$_POST['Phone'];
  $IDCard=$_POST['IDCard'];
  $IDCardType=$_POST['IDCardType'];
  $YearGroup=$_POST['YearGroup'];
  $SchoolGrade=$_POST['SchoolGrade'];
  $SchoolClass=$_POST['SchoolClass'];
  
  if($Sex==""){
    $sql="UPDATE athlete_list SET RealName=?,Phone=?,YearGroup=?,IDCard=?,IDCardType=?,SchoolGrade=?,SchoolClass=? WHERE AthID=?";
    $rs=PDOQuery($dbcon,$sql,[$RealName,$Phone,$YearGroup,$IDCard,$IDCardType,$SchoolGrade,$SchoolClass,$AthID],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_INT]);
  }else{
    $sql="UPDATE athlete_list SET RealName=?,Sex=?,Phone=?,YearGroup=?,IDCard=?,IDCardType=?,SchoolGrade=?,SchoolClass=? WHERE AthID=?";
    $rs=PDOQuery($dbcon,$sql,[$RealName,$Sex,$Phone,$YearGroup,$IDCard,$IDCardType,$SchoolGrade,$SchoolClass,$AthID],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_INT]);
  }
  
  if($rs[1]==1){
    echo "1";
  }else{
    echo "0";
  }
}

?>