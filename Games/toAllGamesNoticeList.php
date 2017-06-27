<?php
$sql="SELECT * FROM games_notice WHERE isDelete=0";
$rs=PDOQuery($dbcon,$sql,[],[]);
$total=sizeof($rs[0]);
?>

<h2>通知规程</h2>

<hr>

<table class="table table-hover table-striped table-bordered" style="border-radius:5px;border-collapse:separate;">
<tr>
  <th>通知标题</th>
  <th>关联比赛</th>
  <th>发布时间</th>
  <th>浏览量</th>
</tr>

<?php
for($i=0;$i<$total;$i++){
  $NoticeID=$rs[0][$i]['NoticeID'];
  $GamesID=$rs[0][$i]['GamesID'];
  $Type=$rs[0][$i]['Type'];
  $Title=$rs[0][$i]['Title'];
  $PubTime=$rs[0][$i]['PubTime'];
  $PageView=$rs[0][$i]['PageView'];
  
  if($Type==1) $Type="通知";
  elseif($Type==2) $Type="规程";
  elseif($Type==3) $Type="秩序册";

  $Gamesrs=PDOQuery($dbcon,"SELECT GamesName FROM games_list WHERE GamesID=$GamesID",[],[]);
  $GamesName=$Gamesrs[0][0]['GamesName'];
?>
<tr>
  <td>
    <font color="green" style="font-weight:bolder;">[ <?php echo $Type; ?> ]</font>
    <a href="index.php?file=Games&action=GamesNotice.php&NoticeID=<?php echo $NoticeID; ?>"><?php echo $Title; ?></a>
  </td>
  <td>
    <font color="red" style="font-weight:bolder;"><?php echo $GamesName; ?></font>
  </td>
  <td><?php echo $PubTime; ?></td>
  <td><?php echo $PageView; ?></td>
</tr>
<?php } ?>
</table>