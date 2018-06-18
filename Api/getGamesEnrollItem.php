<?php
/**
	* getGamesEnrollItem 获取当场比赛报名项目API
	* @author Jerry Cheung
	* @create 2018-06-09
	*/

require_once("../Functions/PDOConn.php");
require_once("../Functions/Package/Api.func.php");

if(isset($_POST) && $_POST){
	$AthID=isset($_POST['AthID'])?$_POST['AthID']:die(returnApiData(5001,"Param Lack AID"));
	$GamesID=isset($_POST['GamesID'])?$_POST['GamesID']:die(returnApiData(5002,"Param Lack GID"));
	
	$rs=PDOQuery($dbcon,"SELECT b.* FROM enroll_item a,item_list b WHERE a.ItemID=b.ItemID AND a.AthID=? AND a.GamesID=?",[$AthID,$GamesID],[PDO::PARAM_STR,PDO::PARAM_STR]);
	
	if($rs[1]>=1){
		$ret=returnApiData(1,"success",$rs[0]);
	}else{
		$ret=returnApiData(0,"no Item");	
	}
	
	die($ret);
}
?>
