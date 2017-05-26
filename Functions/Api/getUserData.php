<?php
include("../PDOConn.php");
if(isset($_POST) && $_POST){
$Userid=$_POST['UID'];
$rs=PDOQuery($dbcon,"SELECT * FROM sys_user WHERE Userid=?",[$Userid],[PDO::PARAM_INT]);
$UserData_arr=array();

$UserData_arr[0]['Userid']=$Userid;
$UserData_arr[0]['UserName']=$rs[0][0]['UserName'];
$UserData_arr[0]['RealName']=$rs[0][0]['RealName'];
$UserData_arr[0]['Roleid']=$rs[0][0]['Roleid'];
$UserData_arr[0]['Status']=$rs[0][0]['Status'];

echo urldecode(json_encode($UserData_arr));
}
?>