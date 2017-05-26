<?php
$UserID=GetSess("SOA_Userid");
$isAth=GetSess("SOA_isAthlete");
$AthID=GetSess("SOA_AthID");

if($isAth!=1){
  die("<script>alert('当前用户非运动员！');history.go(-1);</script>");
}

$list=PDOQuery($dbcon,"SELECT * FROM games_list ORDER BY isOpen DESC,EndDate",[],[]);
$total=sizeof($list[0]);

// 分页代码[Begin]
$Page=isset($_GET['Page'])?$_GET['Page']:"1";
$PageSize=isset($_GET['PageSize'])?$_GET['PageSize']:"20";
$TotalPage=ceil($total/$PageSize);
$Begin=($Page-1)*$PageSize;
$Limit=$Page*$PageSize;

if($Page>$TotalPage && $TotalPage!=0){
 header("Location: $nowURL");
}

if($Limit>$total){$Limit=$total;}
// 分页代码[End]


?>

<center>
  <h1>比赛列表</h1><hr>
  <?php
  echo "<h2>第{$Page}页 / 共{$TotalPage}页</h2>";
  echo "<h3>共 <font color=red>{$total}</font> 项比赛</h3>";
  ?>
</center>
<hr>
<table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">
<tr>
  <th>比赛名称</th>
  <th>结束时间</th>
  <th>报名</th>
</tr>
<?php
  for($i=$Begin;$i<$Limit;$i++){
    $GamesID=$list[0][$i]['GamesID'];
    $GamesName=$list[0][$i]['GamesName'];
    $EndDate=$list[0][$i]['EndDate'];
    $AllowUser=$list[0][$i]['AllowUser'];
    $isPrivate=$list[0][$i]['isPrivate'];
    $isOpen=$list[0][$i]['isOpen'];
    $AllowUser_arr=explode(",",$AllowUser);
    
    // 公开报名
    if($isPrivate=="0"){
      $Enroll=makeOprBtn("primary","Enroll","ConfirmAthleteInfo.php",[["GamesID",$GamesID],["GamesName",$GamesName]],"报名");
    }elseif($isPrivate=="1"){
      // 限制报名(允许)
      if(in_array($AthID,$AllowUser_arr)){
        $Enroll=makeOprBtn("primary","Enroll","ConfirmAthleteInfo.php",[["GamesID",$GamesID],["GamesName",$GamesName]],"报名");
      }else{// 限制报名(不允许)      
        $Enroll="<center>/</center>";
      }
    }
    
    // 结束报名
    if($isOpen=="0"){
      $Enroll="<font color=red>报名已结束</font>";
    }
?>
<tr>
  <td><?php echo $GamesName; ?></td>
  <td><?php echo $EndDate; ?></td>
  <td><?php echo $Enroll; ?></td>
</tr>
<?php } ?>
</table>

<!-- 分页功能@选择页码[Begin] -->
<center><nav>
 <ul class="pagination"> 
  <?php
  if($Page-1>0){
    $Previous=$Page-1;
  ?>
  <li>
   <a href="<?php echo $NowURL."?page=$Previous"; ?>" aria-label="Previous"> <span aria-hidden="true">&laquo;</span></a>
  </li>
  <?php } ?>
  <?php
  for($j=1;$j<=$TotalPage;$j++){
   if($j==$Page){
    echo "<li class='disabled'><a>$j</a></li>";
   }else{
    echo "<li><a href='$NowURL?page=$j'>$j</a></li>";
   }
  }
  ?>
  <?php
  if($Page+1<=$TotalPage){
    $next=$Page+1;
  ?>
  <li>
   <a href="<?php echo $NowURL."?page=$next"; ?>" aria-label="Next"> <span aria-hidden="true">&raquo;</span></a>
  </li>
  <?php } ?>
 </ul>
</nav></center>
<!-- 分页功能@选择页码[End] -->