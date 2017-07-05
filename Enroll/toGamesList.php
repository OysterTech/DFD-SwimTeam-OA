<?php
$isAth=GetSess(Prefix."isAthlete");
$AthID=GetSess(Prefix."AthID");

if($isAth!=1){
  die("<script>alert('当前用户非运动员！');history.go(-1);</script>");
}

$list=PDOQuery($dbcon,"SELECT * FROM games_list ORDER BY isEnd DESC,EndDate",[],[]);
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
  <th>截止时间</th>
  <th>报名</th>
  <th>详细</th>
  <th>通知</th>
</tr>
<?php
  for($i=$Begin;$i<$Limit;$i++){
    $GamesID=$list[0][$i]['GamesID'];
    $GamesName=$list[0][$i]['GamesName'];
    $EndDate=$list[0][$i]['EndDate'];
    $AllowUser=$list[0][$i]['AllowUser'];
    $isPrivate=$list[0][$i]['isPrivate'];
    $isEnd=$list[0][$i]['isEnd'];
    $AllowUser_arr=explode(",",$AllowUser);
    $EndYear=substr($EndDate,0,4);
    $EndMD=substr($EndDate,4,4);
    $EndDate=$EndYear."<br>".$EndMD;
    
    $Enroll_rs=PDOQuery($dbcon,"SELECT * FROM enroll_item WHERE GamesID=? AND AthID=?",[$GamesID,$AthID],[PDO::PARAM_STR,PDO::PARAM_STR]);
    
    // 是否已经报名
    if($Enroll_rs[1]>0){
      $Enroll=makeOprBtn("查看报项","success","Enroll","ViewEnrollItem.php",[["GamesID",$GamesID],["GamesName",$GamesName]]);
    }else{      
      if($isPrivate=="0"){// 公开报名
        $Enroll=makeOprBtn("报名","primary","Enroll","ConfirmAthleteInfo.php",[["GamesID",$GamesID],["GamesName",$GamesName]]);
      }elseif($isPrivate=="1"){// 限制报名
        if(in_array($AthID,$AllowUser_arr)){// 限制报名(允许)
          $Enroll=makeOprBtn("报名","primary","Enroll","ConfirmAthleteInfo.php",[["GamesID",$GamesID],["GamesName",$GamesName]]);
        }else{// 限制报名(不允许)      
          $Enroll="<center>/</center>";
        }
      }
    
      // 已结束报名
      if($isEnd=="1"){
        if($Enroll_rs[1]==0){
          $Enroll="<font color=red>报名已关闭</font>";
        }
      }
      
      $NoticeBtn=makeOprBtn("通知","success","Games","toGamesNoticeList.php",[["GamesID",$GamesID],["GamesName",$GamesName]]);
    }
?>
<tr>
  <td><?php echo $GamesName; ?></td>
  <td><?php echo $EndDate; ?></td>
  <td><?php echo $Enroll; ?></td>
  <td><button class="btn btn-info" onclick='showGamesDetail("<?php echo $GamesID; ?>","<?php echo $GamesName; ?>")'>详细</button></td>
  <td><?php echo $NoticeBtn; ?></td>
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
   <a href="<?php echo $NowURL."&Page=$Previous"; ?>" aria-label="Previous"> <span aria-hidden="true">&laquo;</span></a>
  </li>
  <?php } ?>
  <?php
  for($j=1;$j<=$TotalPage;$j++){
   if($j==$Page){
    echo "<li class='disabled'><a>$j</a></li>";
   }else{
    echo "<li><a href='$NowURL&Page=$j'>$j</a></li>";
   }
  }
  ?>
  <?php
  if($Page+1<=$TotalPage){
    $next=$Page+1;
  ?>
  <li>
   <a href="<?php echo $NowURL."&Page=$next"; ?>" aria-label="Next"> <span aria-hidden="true">&raquo;</span></a>
  </li>
  <?php } ?>
 </ul>
</nav></center>
<!-- 分页功能@选择页码[End] -->


<script>
function showGamesDetail(GamesID,GamesName){
  $.ajax({
    url:"Functions/Api/getGamesDetail.php",
    type:"POST",
    dataType:"json",
    data:{"GamesID":GamesID},
    error:function(e){
      alert("数据传输出错！\n"+ JSON.stringify(e));
      console.log(e);
    },
    success:function(got){
      for(i in got[0]){
        if(i==="StartDate"){
          StartDate=got[0][i];
        }else if(i==="Venue"){
          Venue=got[0][i];
        }else{
          continue;
        }
      }
      
      if(StartDate=="0"){
        StartDate="待定";
      }else{
        StartYear=StartDate.substr(0,4);
        StartMonth=StartDate.substr(4,2);
        StartDay=StartDate.substr(6,2);
        StartDate=StartYear+"年"+StartMonth+"月"+StartDay+"日";
      }

      msg=""
         +"比赛名："+GamesName+"\n"
         +"比赛地："+Venue+"\n"
         +"开赛日："+StartDate;
      alert(msg);
    }
  });
}
</script>