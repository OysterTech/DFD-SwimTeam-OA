<?php
$GamesID=isset($_GET['GamesID'])?$_GET['GamesID']:toAlertDie("500","参数错误！\\n请从正确途径进入本页面！");
$GamesName=isset($_GET['GamesName'])?$_GET['GamesName']:toAlertDie("500","参数错误！\\n请从正确途径进入本页面！");

$ItemIDs=array();
$ItemNames=array();
$ItemYearGroup=array();
$showItemID=array();
$showItemName=array();
$showItemYearGroup=array();

$GamesItem_list=PDOQuery($dbcon,"SELECT * FROM games_item WHERE GamesID=?",[$GamesID],[PDO::PARAM_STR]);
$GamesItem_total=sizeof($GamesItem_list[0]);

$Enroll_list=PDOQuery($dbcon,"",[],[]);
$Enroll_total=sizeof($Enroll_list[0]);

$Item_list=PDOQuery($dbcon,"SELECT * FROM item_list ORDER BY YearGroup",[],[]);
$Item_total=sizeof($Item_list[0]);

for($j=0;$j<$Item_total;$j++){
  array_push($ItemIDs,$Item_list[0][$j]['ItemID']);
  array_push($ItemNames,$Item_list[0][$j]['ItemName']);
  array_push($ItemYearGroup,$Item_list[0][$j]['YearGroup']);
}

for($k=0;$k<$GamesItem_total;$k++){
  $ItemID=$GamesItem_list[0][$k]['ItemID'];
  
  if(in_array($ItemID,$ItemIDs)){
    $Loc=array_search($ItemID,$ItemIDs);
    $ItemName=$ItemNames[$Loc];
    $YearGroup=$ItemYearGroup[$Loc];
    
    array_push($showItemID,$ItemID);
    array_push($showItemYearGroup,$YearGroup);
    array_push($showItemName,$ItemName);
  }
}
?>

<center>
  <h3 style="color:green;font-weight:bolder;"><?php echo $GamesName; ?></h3>
  <h3>按个人统计</h3>
  <h4><a href="index.php?file=Statistics&action=toStatisticsByItem.php&GamesID=<?php echo $GamesID; ?>&GamesName=<?php echo $GamesName; ?>">（点此按单项统计）</a></h4>
</center>

<hr>

<!-- ▼ [隐藏]当前比赛报名人数 ▼ -->
<input type="hidden" id="TotalAth">
<!-- ▲ [隐藏]当前比赛报名人数 ▲ -->

<!-- ▼ 数据导出按钮 ▼ -->
<div id="ExportButton" style="display:none;">
  <center>
    <button class="btn btn-primary" style="width:97%" onclick="exportEnrollData()">导出所有人的报名数据（Excel）</button>
  </center>
  <hr>
</div>
<!-- ▲ 数据导出按钮 ▲ -->

<table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;" id="AthList">
<tr>
  <th>运动员姓名</th>
  <th>选报项目</th>
  <th>资料</th>
</tr>
</table>

<?php
SetSess(Prefix."Ajax_Sign","");
$SessionID=session_id();
$Timestamp=time();
$Ajax_Sign=sha1(md5($SessionID.$Timestamp));
SetSess(Prefix."Ajax_Sign",$Ajax_Sign);
?>

<script>
var Sign="<?php echo $Ajax_Sign; ?>";

window.onload=function(){
  showEnrollAthByAth();
}

function exportEnrollData(){
  GamesID=getURLParam("GamesID");
  GamesName=getURLParam("GamesName");
  
  URL="Statistics/toExportEnrollData.php"+"?GamesID="+GamesID+"&GamesName="+GamesName+"&SortBy=Ath";
  
  window.open(URL);
}

function showExportButton(){
  total=$("#TotalAth").val();

  if(total>0){
    $("#ExportButton").attr("style","");
  }else{
    $("#ExportButton").attr("style","display:none;");
  }
}

function showEnrollAthByAth(){
  GamesID=getURLParam("GamesID");
  $("#AthList tr:not(:first)").empty("");
  lockScreen();

  $.ajax({
    url:"Functions/Api/getEnrollDataByAth.php",
    type:"post",
    dataType:"text",
    data:{"Sign":Sign,"GamesID":GamesID},
    error:function(e){
      alert(JSON.stringify(e));
      console.log(JSON.stringify(e));
    },
    success:function(got){
      if(got=="InvaildSign"){
        tips="签名错误！";
        $("#tips").html(tips);
        $("#Modal-Tips").modal('show');
        unlockScreen();
        return false;
      }else if(got=="AddCacheFailed"){
        tips="新增缓存失败！";
        $("#tips").html(tips);
        $("#Modal-Tips").modal('show');
        unlockScreen();
        return false;
      }else if(got=="NoData"){
        ct=""
        +"<tr>"
        +'<td colspan="2"><center><font color="red" style="font-size:22;font-weight:bolder;">本比赛暂无运动员报名！</font></center></td>'
        +"</tr>";          
        $("#AthList").append(ct);
        unlockScreen();
        return false;
      }else{
        got=$.parseJSON(got);
        for(i in got){
          for(j in got[i]){
            if(j==="AthID"){
              AthID=got[i][j];
            }else if(j==="RealName"){
              AthName=got[i][j];
            }else if(j==="SchoolGrade"){
              SchoolGrade=got[i][j];
            }else if(j==="SchoolClass"){
              SchoolClass=got[i][j];
            }else if(j==="ItemName"){
              ItemName=got[i][j].split(",");
              ItemName[0]="▲ "+ItemName[0];
              ItemName=ItemName.join("<br>▲ ");
            }else if(j==="YearGroup"){
              YearGroup=got[i][j].substr(0,4);
              YearGroup=YearGroup+"年组";
            }else{
              continue;
            }
          }
          
          ct=""
          +"<tr>"
          +"<td>"
          +AthName+"("+SchoolGrade+"年"+SchoolClass+"班)("+YearGroup+")"
          +"</td>"
          +"<td>"+ItemName+"</td>"
          +"<td>"
          +'<button class="btn btn-info" onclick="toViewAthDataDetail('+AthID+')">详细</button>'
          +"</td>"
          +"</tr>";
          
          $("#AthList").append(ct);
        }
      }
      
      // i为键值，从0开始，所以要+1
      $("#TotalAth").val(i+1);
      showExportButton();
      unlockScreen();
    }
  });
}

function toViewAthDataDetail(AthID){
 $.ajax({
  url:"Functions/Api/getAthleteData.php",
  data:{"AthID":AthID},
  type:"post",
  dataType:"json",
  error:function(e){
    alert("数据传输出错！\n"+ JSON.stringify(e));
    console.log(e);
  },
  success:function(got){
    for(i in got[0]){
      if(i==="RealName"){
        $('#RealName').html(got[0][i]);
      }else if(i==="Phone"){
        $('#Phone').html(got[0][i]);
      }else if(i==="YearGroup"){
        $('#YearGroup').html(got[0][i]);
      }else if(i==="IDCard"){
        $('#IDCard').html(got[0][i]);
      }else if(i==="Sex"){
        $("#Sex").html(got[0][i]);
      }else if(i==="IDCardType"){
        IDCardType=got[0][i];
      }else if(i==="SchoolGrade"){
        SchoolGrade=showCNNum(got[0][i]);
      }else if(i==="SchoolClass"){
        SchoolClass=got[0][i];
      }else{
        continue;
      }
    }
    showClass=SchoolGrade+"年("+SchoolClass+")班";
    $("#SchoolClass").html(showClass);
    
    if(IDCardType=="1") IDCardType="大陆二代身份证";
    else if(IDCardType=="2") IDCardType="香港居民身份证";
    else if(IDCardType=="3") IDCardType="护照";
    $("#IDCardType").html(IDCardType);
    $("#Modal-AthInfo").modal("show");
  }
 });
}

</script>


<div class="modal fade" id="Modal-AthInfo">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" id="ModalTitle">运动员详细资料</h3>
      </div>
      <div class="modal-body">
        <table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">
          <tr>
            <th>姓名</th>
            <td><p id="RealName"></p></td>
          </tr>
          <tr>
            <th>性别</th>
            <td><p id="Sex"></p></td>
          </tr>
          <tr>
            <th>手机号</th>
            <td><p id="Phone"></p></td>
          </tr>
          <tr>
            <th>班别</th>
            <td><p id="SchoolClass"></p></td>
          </tr>
          <tr>
            <th>年龄组</th>
            <td><p id="YearGroup"></p></td>
          </tr>
          <tr>
            <th>证件号</th>
            <td><p id="IDCard"></p></td>
          </tr>
          <tr>
            <th>证件类型</th>
            <td><p id="IDCardType"></p></td>
          </tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">&lt; 返回</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="Modal-Tips">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" id="ModalTitle">温馨提示</h3>
      </div>
      <div class="modal-body">
        <form method="post">
          <font color="red" style="font-weight:bolder;font-size:26;text-align:center;">
            <p id="tips"></p>
          </font>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">返回 &gt;</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->