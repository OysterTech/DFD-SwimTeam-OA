<?php
include("../PDOConn.php");
if(isset($_POST) && $_POST){
$GamesID=$_POST['GamesID'];
$rs=PDOQuery($dbcon,"SELECT * FROM games_list WHERE GamesID=?",[$GamesID],[PDO::PARAM_INT]);
$GamesDetail=array();

$GamesDetail[0]['GamesID']=$GamesID;
$GamesDetail[0]['GamesName']=$rs[0][0]['GamesName'];
$GamesDetail[0]['StartDate']=$rs[0][0]['StartDate'];
$GamesDetail[0]['EndDate']=$rs[0][0]['EndDate'];
$GamesDetail[0]['Venue']=$rs[0][0]['Venue'];

echo urldecode(json_encode($GamesDetail));
}
?>