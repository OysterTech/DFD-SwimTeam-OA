<?php
$GamesID=isset($_GET['GamesID'])?$_GET['GamesID']:toAlertDie("500-Enrl-VEI-NoGID","参数错误！\n无比赛ID！\n\n请从正确方式进入本页面！");
$GamesName=isset($_GET['GamesName'])?$_GET['GamesName']:toAlertDie("500-Enrl-VEI-NoGN","参数错误！\n无比赛名称！\n\n请从正确方式进入本页面！");
$AthID=GetSess(Prefix."AthID");
$YearGroup=GetSess(Prefix."Ath_YearGroup");

$ItemIDs=array();
$ItemNames=array();

$Enroll_list=PDOQuery($dbcon,"SELECT * FROM enroll_item WHERE AthID=? AND GamesID=?",[$AthID,$GamesID],[PDO::PARAM_STR,PDO::PARAM_STR]);
$Enroll_total=count($Enroll_list[0]);

$Item_list=PDOQuery($dbcon,"SELECT * FROM item_list WHERE YearGroup=?",[$YearGroup],[PDO::PARAM_STR]);
$Item_total=count($Item_list[0]);

for($j=0;$j<$Item_total;$j++){
  array_push($ItemIDs,$Item_list[0][$j]['ItemID']);
  array_push($ItemNames,$Item_list[0][$j]['ItemName']);
}
?>

<center>
  <h2><?=$GamesName;?></h2>
  <h2>已报名的项目</h2>
</center>
<hr>

<table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">
<tr>
  <th>项目名称</th>
</tr>

<?php
for($i=0;$i<$Enroll_total;$i++){
  $ItemID=$Enroll_list[0][$i]['ItemID'];
  if(in_array($ItemID,$ItemIDs)){
    $Loc=array_search($ItemID,$ItemIDs);
    $ItemName=$ItemNames[$Loc];
  }else{
    $ItemName='<font color="red">系统错误 请联系管理员';
  }
?>

<tr>
  <td style="color:green;font-weight:bolder;background:#b3e5fc"><?php echo $YearGroup."年组 ".$ItemName; ?></td>
</tr>
<?php } ?>
</table>

<hr>

<a href="index.php" class="btn btn-primary btn-block">返 回 首 页</a>
