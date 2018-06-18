<?php
/**
	* getGamesItem 获取比赛项目API
	* @author Jerry Cheung
	* @create 2018-06-10
	*/

require_once("../Functions/PDOConn.php");
require_once("../Functions/Package/Api.func.php");

if(isset($_GET['GamesID']) && $_GET['GamesID']>=1){
	$rs=PDOQuery($dbcon,"SELECT a.ItemID,a.YearGroup,a.ItemName FROM item_list a,games_item b WHERE b.GamesID=? AND b.ItemID=a.ItemID ORDER BY a.YearGroup",[$_GET['GamesID']],[PDO::PARAM_STR]);
	
	if($rs[1]>=1){
		$ret=returnApiData(1,"success",$rs[0]);
	}else{
		$ret=returnApiData(0,"no Item");	
	}
}

echo $ret;
?>
