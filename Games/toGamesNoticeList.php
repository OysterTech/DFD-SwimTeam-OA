<?php
$GamesID=isset($_GET['GamesID'])?$_GET['GamesID']:"";
$GamesName=isset($_GET['GamesName'])?$_GET['GamesName']:"";
$isAth=getSess(Prefix."isAthlete");

$sql="SELECT * FROM games_notice WHERE GamesID=? AND isDelete=0";
$rs=PDOQuery($dbcon,$sql,[$GamesID],[PDO::PARAM_STR]);
$total=sizeof($rs[0]);
?>

<h1><?php echo $GamesName; ?></h1>
<h2>通知规程</h2>

<hr>

<table class="table table-hover table-striped table-bordered" style="border-radius:5px;border-collapse:separate;">
<tr>
  <?php if($isAth==0){ ?>
  <td colspan=4>
    <center>
      <a class="btn btn-primary btn-block" href="index.php?file=Games&action=PubGamesNotice.php&GamesID=<?php echo $GamesID; ?>">发 布 新 通 知</a>
    </center>
  </td>
  <?php } ?>
</tr>

<tr>
  <th>通知标题</th>
  <th>发布时间</th>
  <th>浏览量</th>
  <?php if($isAth==0){ ?>
  <th>操作</th>
  <?php } ?>
</tr>

<?php
if($total>0){
for($i=0;$i<$total;$i++){
  $NoticeID=$rs[0][$i]['NoticeID'];
  $Type=$rs[0][$i]['Type'];
  $Title=$rs[0][$i]['Title'];
  $PubTime=$rs[0][$i]['PubTime'];
  $PageView=$rs[0][$i]['PageView'];
  
  if($Type==1) $Type="通知";
  elseif($Type==2) $Type="规程";
  elseif($Type==3) $Type="秩序册";
?>
<tr>
  <td>
    <font color="green" style="font-weight:bolder;">[ <?=$Type;?> ]</font>
    <a href="index.php?file=Games&action=GamesNotice.php&NoticeID=<?=$NoticeID;?>"><?=$Title;?></a>
  </td>
  <td><?=$PubTime;?></td>
  <td><?=$PageView;?></td>
  <?php if($isAth==0){ ?>
  <td><a class="btn btn-info" href="index.php?file=Games&action=toEditGamesNotice.php&NoticeID=<?=$NoticeID;?>&GamesID=<?=$GamesID;?>">编辑</a> <button class="btn btn-danger" onclick="toDel('<?=$NoticeID;?>')">删除</button></td>
  <?php } ?>
</tr>
<?php } ?>
<?php }else{ ?>
  <td colspan="4" style="color:red;text-align:center;font-size:21px;font-weight:bold;">暂无通知</td>
<?php } ?>
</table>


<?php if($isAth==0){ ?>
<script>
function toDel(NoticeID){
  if(confirm("您确定要删除此通知吗？")){
    window.location.href="index.php?file=Games&action=toDelGamesNotice.php&NoticeID="+NoticeID;
  }
}
</script>
<?php } ?>
