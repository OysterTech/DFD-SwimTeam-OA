<?php
/**
	* getAllGames 获取所有比赛API
	* @author Jerry Cheung
	* @create 2018-06-08
	*/

require_once("../Functions/PDOConn.php");
require_once("../Functions/Package/Api.func.php");

$rs=PDOQuery($dbcon,"SELECT * FROM games_list ORDER BY isEnd ASC,EndDate",[],[]);
foreach($rs[0] as $key=>$info){
	$EndDate=$info['EndDate'];
	$year=substr($EndDate,0,4);
	$month=substr($EndDate,4,2);
	$day=substr($EndDate,6);
	$rs[0][$key]['EndDate']=$year.'-'.$month.'-'.$day;
}
$ret=returnApiData(1,"success",$rs[0]);
echo $ret;
?>
