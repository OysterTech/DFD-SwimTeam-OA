<?php
$list=PDOQuery($dbcon,"SELECT * FROM games_list ORDER BY GamesName DESC",[],[]);
$total=sizeof($list[0]);
?>

<center>
  <h1>比赛列表</h1><hr>
  <?php
  echo "<h3>共 <font color=red>{$total}</font> 场比赛</h3>";
  ?>
</center>
<hr>
<table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">
<tr>
  <th>比赛名称</th>
  <th>进入统计</th>
</tr>

<?php
for($i=0;$i<$total;$i++){
  $GamesID=$list[0][$i]['GamesID'];
  $GamesName=$list[0][$i]['GamesName'];
  $oprURL=makeOprBtn("统计","info","Statistics","toStatisticsByItem.php",[["GamesID",$GamesID],["GamesName",$GamesName]]);
?>

<tr>
  <td><?php echo $GamesName; ?></td>
  <td><center><?php echo $oprURL; ?></center></td>
</tr>
<?php } ?>
</table>
