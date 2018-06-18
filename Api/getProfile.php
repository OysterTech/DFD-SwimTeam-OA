<?php
/**
	* getProfile 获取用户资料API
	* @author Jerry Cheung
	* @create 2018-06-11
	*/

require_once("../Functions/PDOConn.php");
require_once("../Functions/Package/Api.func.php");

if(isset($_GET) && $_GET){
	$openID=$_GET['openID'];
	
	// 获取用户信息
	$rs=PDOQuery($dbcon,"SELECT a.UserID,a.UserName,a.RealName,a.Status,a.wxOpenID,b.isAthlete FROM sys_user a,role_list b WHERE a.RoleID=b.RoleID AND a.wxOpenID=?",[$openID],[PDO::PARAM_STR]);
	
	if($rs[1]!=1){
		$ret=returnApiData(4031,"User Not Found");
		die($ret);
	}
	
	$UserID=$rs[0][0]['UserID'];
	$isAthlete=$rs[0][0]['isAthlete'];
	$data=$rs[0][0];
	
	// 如果是运动员，获取运动员信息
	if($isAthlete==1){
		$sql2="SELECT b.* FROM sys_user a,athlete_list b WHERE a.UserID=b.UserID AND b.UserID=?";
		$rs2=PDOQuery($dbcon,$sql2,[$UserID],[PDO::PARAM_INT]);
		
		if($rs2[1]!=1){
			$ret=returnApiData(4032,"Athlete Not Found");
			die($ret);
		}
		
		$data=array_merge($data,$rs2[0][0]);
	}
	
	// 返回数据
	$ret=returnApiData(1,"success",$data);
	die($ret);
}
?>
