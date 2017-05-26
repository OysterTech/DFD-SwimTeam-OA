<?php
$GamesID=isset($_GET['GamesID'])?$_GET['GamesID']:toAlertDie("500","参数错误！\n\n请从正确方式进入本页面！");
$AthID=GetSess("SOA_AthID");
$YearGroup=GetSess("SOA_Ath_YearGroup");

$ItemIDs=array();
$ItemNames=array();

$Enroll_list=PDOQuery($dbcon,"SELECT * FROM enroll_item WHERE AthID=? AND GamesID=?",[$AthID,$GamesID],[PDO::PARAM_STR,PDO::PARAM_STR]);
$Enroll_total=sizeof($Enroll_list[0]);

$Item_list=PDOQuery($dbcon,"SELECT * FROM item_list WHERE YearGroup=?",[$YearGroup],[PDO::PARAM_STR]);
$Item_total=sizeof($Item_list[0]);

for($j=0;$j<$Item_total;$j++){
  array_push($ItemIDs,$Item_list[0][$j]['ItemID']);
  array_push($ItemNames,$Item_list[0][$j]['ItemName']);
}
?>

<center>
  <h1>报名项目</h1>
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