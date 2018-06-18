<?php
/**
	* toEnroll 报名API
	* @author Jerry Cheung
	* @create 2018-06-11
	*/

require_once("../Functions/PDOConn.php");
require_once("../Functions/Package/Api.func.php");

if(isset($_POST) && $_POST){
	$successCount=0;
	$AthID=isset($_POST['AthID'])?$_POST['AthID']:die(returnApiData(5001,"Param Lack AID"));
	$GamesID=isset($_POST['GamesID'])?$_POST['GamesID']:die(returnApiData(5002,"Param Lack GID"));
	$ItemIDs=isset($_POST['ItemIDs'])?$_POST['ItemIDs']:die(returnApiData(5002,"Param Lack IID"));
	
	$ItemIDs=explode(",",$ItemIDs);
	
	foreach($ItemIDs as $ItemID){
		$rs=PDOQuery($dbcon,"INSERT INTO enroll_item(AthID,GamesID,ItemID) VALUES (?,?,?)",[$AthID,$GamesID,$ItemID],[PDO::PARAM_INT,PDO::PARAM_INT,PDO::PARAM_INT]);
		$successCount+=$rs[1];
	}
	
	if($successCount>=1){
		$ret=returnApiData(1,"success");
	}else{
		$ret=returnApiData(0,"unknown Error");	
	}
	
	echo $ret;
}
?>
