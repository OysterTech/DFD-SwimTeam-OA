<?php
$list=PDOQuery($dbcon,"SELECT DISTINCT YearGroup FROM item_list ORDER BY YearGroup",[],[]);
$total=sizeof($list[0]);
?>

<center>
  <h1>年龄组列表</h1>
</center>
<hr>
<table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">
<tr>
  <td colspan="2"><a onclick="addYearGroup();" class="btn btn-success" style="width:98%">新 增 年 龄 组</a></td>
</tr>
<tr>
  <th>年龄组</th>
  <th>操作</th>
</tr>

<?php
for($i=0;$i<$total;$i++){
  $YearGroup=$list[0][$i]['YearGroup']; 
  $OprURL_detail=makeOprBtn("详情","primary","Item","toItemList.php",[["YearGroup",$YearGroup]]);  
?>

<tr style="text-align:center;">
  <td><?php echo $YearGroup; ?></td>
  <td><?php echo $OprURL_detail; ?></td>
</tr>
<?php } ?>
</table>

<script>
function addYearGroup(){
  YearGroup=prompt("请输入新增的年龄组所在年份：");
  if(YearGroup.length==4 && YearGroup.substr(0,2)=="20"){
    window.location.href="index.php?file=Item&action=toItemList.php&YearGroup="+YearGroup;
  }else{
    alert("请输入正确的年份数字！");
  }
}
</script>
