<?php
include("../PDOConn.php");
if(isset($_POST) && $_POST){
$Menuid=$_POST['MID'];
$rs=PDOQuery($dbcon,"SELECT * FROM sys_menu WHERE Menuid=?",[$Menuid],[PDO::PARAM_INT]);
$MenuData_arr=array();

$MenuData_arr[0]['Menuid']=$Menuid;
$MenuData_arr[0]['Fatherid']=$rs[0][0]['Fatherid'];
$MenuData_arr[0]['Menuname']=$rs[0][0]['Menuname'];
$MenuData_arr[0]['MenuIcon']=$rs[0][0]['MenuIcon'];
$MenuData_arr[0]['PageFile']=$rs[0][0]['PageFile'];
$MenuData_arr[0]['PageDOS']=$rs[0][0]['PageDOS'];

echo urldecode(json_encode($MenuData_arr));
}
?>