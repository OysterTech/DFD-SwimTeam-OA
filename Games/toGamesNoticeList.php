<?php
$GamesID=isset($_GET['GamesID'])?$_GET['GamesID']:"";
$GamesName=isset($_GET['GamesName'])?$_GET['GamesName']:"";
$isAth=getSess("SOA_isAthlete");

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
  <?php }else{ ?>
  <td colspan=3>
  <?php } ?>
    <center>
      <a class="btn btn-primary" href="index.php?file=Games&action=PubGamesNotice.php&GamesID=<?php echo $GamesID; ?>" style="width:97%">发 布 新 通 知</a>
    </center>
  </td>
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
for($i=0;$i<$total;$i++){
  $NoticeID=$rs[0][$i]['NoticeID'];
  $Type=$rs[0][$i]['Type'];
  $Title=$rs[0][$i]['Title'];
  $PubTime=$rs[0][$i]['PubTime'];
  $PageView=$rs[0][$i]['PageView'];
  
  if($Type==1) $Type="规程";
  elseif($Type==2) $Type="秩序册";
  elseif($Type==3) $Type="成绩册";
?>
<tr>
  <td>
    <font color="green" style="font-weight:bolder;">[ <?php echo $Type; ?> ]</font>
    <a href="index.php?file=Games&action=GamesNotice.php&NoticeID=<?php echo $NoticeID; ?>"><?php echo $Title; ?></a>
  </td>
  <td><?php echo $PubTime; ?></td>
  <td><?php echo $PageView; ?></td>
  <?php if($isAth==0){ ?>
  <td><button class="btn btn-danger" onclick="toDel('<?php echo $NoticeID; ?>')">删除</button></td>
  <?php } ?>
</tr>
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