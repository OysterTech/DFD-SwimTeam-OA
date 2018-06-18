<?php
$isAth=GetSess(Prefix."isAthlete");
$AthID=GetSess(Prefix."AthID");

if($isAth!=1){
  die("<script>alert('当前用户非运动员！');history.go(-1);</script>");
}

$list=PDOQuery($dbcon,"SELECT * FROM games_list ORDER BY isEnd,EndDate",[],[]);
$total=sizeof($list[0]);

// 获取运动员资料
$AthInfo_SQL="SELECT * FROM athlete_list WHERE AthID=?";
$AthInfo_rs=PDOQuery($dbcon,$AthInfo_SQL,[$AthID],[PDO::PARAM_INT]);

if($AthInfo_rs[1]!=1){
  toAlertDie("500-Enrl-L-NoAthData","无运动员资料！\\n请从正确途径进入本页面！\\n\\n如有疑问，请在首页联系管理员！");
}

$YearGroup=$AthInfo_rs[0][0]['YearGroup'];
setSess(Prefix."Ath_YearGroup",$YearGroup);

?>

<center>
  <h1>比赛列表</h1>
</center>

<hr>

<table id="table" class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">
<thead>
<tr>
  <th>比赛名称</th>
  <th>截止时间</th>
  <th>报名</th>
  <th>通知</th>
</tr>
</thead>
<tbody>
<?php
  for($i=0;$i<$total;$i++){
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
          $Enroll='<font color="blue" style="text-align:center;">报名已锁定</font>';
        }
      }
    
      // 已结束报名
      if($isEnd=="1"){
        if($Enroll_rs[1]==0){
          $Enroll='<font color="red">报名已结束</font>';
        }
      }
    }
    
    $NoticeBtn=makeOprBtn("通知","success","Games","toGamesNoticeList.php",[["GamesID",$GamesID],["GamesName",$GamesName]]);
?>
<tr>
  <td><a onclick='showGamesDetail("<?=$GamesID;?>","<?=$GamesName;?>")'><?=$GamesName;?></a></td>
  <td><?=$EndDate;?></td>
  <td><?=$Enroll;?></td>
  <td><?=$NoticeBtn;?></td>
</tr>
<?php } ?>
</tbody>
</table>


<script>
window.onload=function(){
  $('#table').DataTable({
    responsive: true,
    "order":[[1,'desc']],
    "columnDefs":[{
      "targets":[2,3],
      "orderable": false
    }]
  });
};

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
