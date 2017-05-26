<?php
include("../PDOConn.php");

if(isset($_POST) && $_POST){
$AthID=$_POST['AthID'];
$rs=PDOQuery($dbcon,"SELECT * FROM athlete_list WHERE AthID=?",[$AthID],[PDO::PARAM_INT]);
$AthleteData_arr=array();

$AthleteData_arr[0]['Athid']=$AthID;
$AthleteData_arr[0]['UserID']=$rs[0][0]['UserID'];
$AthleteData_arr[0]['RealName']=$rs[0][0]['RealName'];
$AthleteData_arr[0]['Phone']=$rs[0][0]['Phone'];
$AthleteData_arr[0]['Sex']=$rs[0][0]['Sex'];
$AthleteData_arr[0]['YearGroup']=$rs[0][0]['YearGroup'];
$AthleteData_arr[0]['IDCard']=$rs[0][0]['IDCard'];
$AthleteData_arr[0]['IDCardType']=$rs[0][0]['IDCardType'];
$AthleteData_arr[0]['SchoolGrade']=$rs[0][0]['SchoolGrade'];
$AthleteData_arr[0]['SchoolClass']=$rs[0][0]['SchoolClass'];

echo urldecode(json_encode($AthleteData_arr));
}
?>