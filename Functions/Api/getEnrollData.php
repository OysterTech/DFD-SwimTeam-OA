<?php
include("../PublicFunc.php");
include("../PDOConn.php");

if(isset($_POST) && $_POST){
  $Sign_sess=GetSess("SOA_Ajax_Sign");
  $Sign_post=$_POST['Sign'];
  
  if($Sign_sess != $Sign_post){
    die("InvaildSign");
  }
  
  $SortBy=$_POST['SortBy'];
  $GamesID=$_POST['GamesID'];
  $sql="SELECT * FROM enroll_item WHERE GamesID=? AND ";
  
  if($SortBy=="Item"){
    $ID=$_POST['ItemID'];
    $sql.="ItemID=?";
  }elseif($SortBy=="Athlete"){
    $ID=$_POST['AthID'];
    $sql.="AthID=?";
  }
  
  $rs=PDOQuery($dbcon,$sql,[$GamesID,$ID],[PDO::PARAM_STR,PDO::PARAM_STR]);
  $total=sizeof($rs[0]);
  
  if($total<1){
    die("NoData");
  }
  
  $Data_arr=array();
  for($i=0;$i<$total;$i++){
    $Data_arr[$i]['AthID']=$rs[0][$i]['AthID'];
    $Data_arr[$i]['ItemID']=$rs[0][$i]['ItemID'];
  }
  
  if($SortBy=="Item"){
    for($j=0;$j<$total;$j++){
      $AthID=$Data_arr[$j]['AthID'];
      $rs2=PDOQuery($dbcon,"SELECT RealName,SchoolGrade,SchoolClass FROM athlete_list WHERE AthID=?",[$AthID],[PDO::PARAM_STR]);
      $SchoolGrade=showCNNum($rs2[0][0]['SchoolGrade']);
      $Data_arr[$j]['RealName']=$rs2[0][0]['RealName'];
      $Data_arr[$j]['SchoolGrade']=$SchoolGrade;
      $Data_arr[$j]['SchoolClass']=$rs2[0][0]['SchoolClass'];
    }
  }else if($SortBy=="Ath"){
    for($j=0;$j<$total;$j++){
      $Item=$Data_arr[$j]['ItemID'];
      $rs2=PDOQuery($dbcon,"SELECT ItemName,YearGroup FROM item_list WHERE ItemID=?",[$ItemID],[PDO::PARAM_STR]);
      $Data_arr[$j]['ItemName']=$rs2[0][0]['ItemName'];
      $Data_arr[$j]['YearGroup']=$rs2[0][0]['YearGroup'];
    }
  }

  echo urldecode(json_encode($Data_arr));
}
?>