<?php
require_once("../Functions/PDOConn.php");
require_once("../Functions/PublicFunc.php");

if(isset($_POST) && $_POST){
  $UserID=$_POST['UserID'];
  $RealName=$_POST['RealName'];
  $Sex=$_POST['Sex'];
  $Phone=$_POST['Phone'];
  $YearGroup=$_POST['YearGroup'];
  $IDCard=$_POST['IDCard'];
  $IDCardType=$_POST['IDCardType'];
  $SchoolGrade=$_POST['SchoolGrade'];
  $SchoolClass=$_POST['SchoolClass'];
  
  $sql1="SELECT AthID FROM athlete_list WHERE IDCard=?";
  $rs1=PDOQuery($dbcon,$sql1,[$IDCard],[PDO::PARAM_STR]);
  
  if($rs1[1]!=0){
    die("HaveIDCard");
  }
  
  $sql2="INSERT INTO athlete_list SET UserID=?,RealName=?,Sex=?,Phone=?,YearGroup=?,IDCard=?,IDCardType=?,SchoolGrade=?,SchoolClass=?";
  $rs2=PDOQuery($dbcon,$sql2,[$UserID,$RealName,$Sex,$Phone,$YearGroup,$IDCard,$IDCardType,$SchoolGrade,$SchoolClass],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR]);

  if($rs2[1]==1){
    echo "1";
  }else{
    echo "InsertErr";
  }
}

?>