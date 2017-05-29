<?php
include("../PublicFunc.php");
include("../PDOConn.php");

if(isset($_POST) && $_POST){
  $Sign_sess=GetSess("SOA_Ajax_Sign");
  $Sign_post=$_POST['Sign'];
  
  if($Sign_sess != $Sign_post){
    die("InvaildSign");
  }
  
  $GamesID=$_POST['GamesID'];
  $ItemID=$_POST['ItemID'];
  
  $rs=PDOQuery($dbcon,"SELECT * FROM enroll_item WHERE GamesID=? AND ItemID=?",[$GamesID,$ItemID],[PDO::PARAM_STR,PDO::PARAM_STR]);
  $total=sizeof($rs[0]);
  
  if($total<1){
    die("NoData");
  }
  
  $Data_arr=array();
  for($i=0;$i<$total;$i++){
    $Data_arr[$i]['AthID']=$rs[0][$i]['AthID'];
    $Data_arr[$i]['ItemID']=$rs[0][$i]['ItemID'];
  }
  
  for($j=0;$j<$total;$j++){
    $AthID=$Data_arr[$j]['AthID'];
    $rs2=PDOQuery($dbcon,"SELECT RealName,Sex,SchoolGrade,SchoolClass FROM athlete_list WHERE AthID=?",[$AthID],[PDO::PARAM_STR]);
    $SchoolGrade=showCNNum($rs2[0][0]['SchoolGrade']);
    $Data_arr[$j]['Sex']=$rs2[0][0]['Sex'];
    $Data_arr[$j]['RealName']=$rs2[0][0]['RealName'];
    $Data_arr[$j]['SchoolGrade']=$SchoolGrade;
    $Data_arr[$j]['SchoolClass']=$rs2[0][0]['SchoolClass'];
  }

  echo urldecode(json_encode($Data_arr));
}
?>