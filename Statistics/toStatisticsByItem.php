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
$GamesItem_total=count($GamesItem_list[0]);

$Item_list=PDOQuery($dbcon,"SELECT * FROM item_list ORDER BY YearGroup DESC",[],[]);
$Item_total=count($Item_list[0]);

for($j=0;$j<$GamesItem_total;$j++){
  array_push($ItemIDs,$GamesItem_list[0][$j]['ItemID']);
}

for($k=0;$k<$Item_total;$k++){
  $ItemID=$Item_list[0][$k]['ItemID'];
  
  if(in_array($ItemID,$ItemIDs)){
    array_push($showItemID,$ItemID);
    array_push($showItemYearGroup,$Item_list[0][$k]['YearGroup']);
    array_push($showItemName,$Item_list[0][$k]['ItemName']);
  }
}
?>

<center>
  <h3 style="color:green;font-weight:bolder;"><?php echo $GamesName; ?></h3>
  <h3>按单项统计</h3>
  <h4><a href="index.php?file=Statistics&action=toStatisticsByAth.php&GamesID=<?php echo $GamesID; ?>&GamesName=<?php echo $GamesName; ?>">（点此按运动员个人统计）</a></h4>
</center>

<hr>

<!-- ▼ [下拉框]项目选择 ▼ -->
<center>
  <font color="blue" style="font-size:22;">
    请选择需要统计的项目：
  </font>
  <br>
  <select id="ItemList" onchange="showEnrollAthByItem(this.value);">
  <option selected disabled>----- 请选择项目 -----</option>
  <?php
  $TotalEnrollAth=0;
  $nowYearGroup=0;
  for($i=0;$i<$GamesItem_total;$i++){
    $ItemID=$showItemID[$i];
    $ItemName=$showItemName[$i];
    $YearGroup=$showItemYearGroup[$i];
    
    if($nowYearGroup != $YearGroup){
      $nowYearGroup=$YearGroup;
  ?>
  <option disabled>---------- <?php echo $nowYearGroup; ?>年组 ----------</option>
  <?php
    }
    
    $Enroll_list=PDOQuery($dbcon,"SELECT * FROM enroll_item WHERE GamesID=? AND ItemID=?",[$GamesID,$ItemID],[PDO::PARAM_STR,PDO::PARAM_STR]);
    $EnrollAth=$Enroll_list[1];
    $TotalEnrollAth+=$EnrollAth;
  ?>
    <option id="<?php echo $ItemID; ?>" value="<?php echo $ItemID; ?>" totalAth="<?php echo $EnrollAth; ?>"><?php echo $ItemName."（".$EnrollAth."人）"; ?></option>
  <?php } ?>
  </select>
</center>
<!-- ▲ [下拉框]项目选择 ▲ -->

<hr>

<!-- ▼ [隐藏]当前比赛报名人次 ▼ -->
<input type="hidden" id="TotalAth" value="<?php echo $TotalEnrollAth; ?>">
<!-- ▲ [隐藏]当前比赛报名人次 ▲ -->

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
  <th>个人资料</th>
</tr>
</table>

<script>

window.onload=function(){
  TotalAth=$("#TotalAth").val();
  if(TotalAth>0){showExportButton();}
}

function exportEnrollData(){
  GamesID=getURLParam("GamesID");
  GamesName=getURLParam("GamesName");

  $.ajax({
    url:"Functions/Api/getEnrollDataByAth.php",
    type:"post",
    dataType:"text",
    data:{"GamesID":GamesID},
    error:function(e){
      alert(JSON.stringify(e));
      console.log(JSON.stringify(e));
    },
    success:function(got){
      if(got=="AddCacheFailed"){
        tips="新增缓存失败！";
        $("#tips").html(tips);
        $("#Modal-Tips").modal('show');
        unlockScreen();
        return false;
      }

      URL="Statistics/toExportEnrollData.php"+"?GamesID="+GamesID+"&GamesName="+GamesName+"&SortBy=Ath";
      window.open(URL);
    }
  });
}

function showExportButton(){
  $("#ExportButton").attr("style","");
}

function showEnrollAthByItem(ItemID){
  GamesID=getURLParam("GamesID");
  total=$("#ItemList").find("option:selected").attr("totalAth");
  $("#AthList tr:not(:first)").empty("");
  lockScreen();
  
  if(total=="0"){
    ct=""
    +"<tr>"
    +'<td colspan="2"><center><font color="red" style="font-size:22;font-weight:bolder;">本项目暂无运动员报名！</font></center></td>'
   +"</tr>";          
   $("#AthList").append(ct);
   unlockScreen();
   return false;
  }
  
  $.ajax({
    url:"Functions/Api/getEnrollDataByItem.php",
    type:"post",
    dataType:"text",
    data:{"GamesID":GamesID,"ItemID":ItemID},
    error:function(e){
      alert(JSON.stringify(e));
      console.log(JSON.stringify(e));
    },
    success:function(got){
      if(got=="NoData"){
        ct=""
        +"<tr>"
        +'<td colspan="2"><center><font color="red" style="font-size:22;font-weight:bolder;">本项目暂无运动员报名！</font></center></td>'
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
            }else if(j==="Sex"){
              Sex=got[i][j];
            }else if(j==="RealName"){
              AthName=got[i][j];
            }else if(j==="SchoolGrade"){
              SchoolGrade=got[i][j];
            }else if(j==="SchoolClass"){
              SchoolClass=got[i][j];
            }else{
              continue;
            }
          }
          
          ct=""
          +"<tr>"
          +"<td>"
          +AthName+"（"+Sex+" - "+SchoolGrade+"年"+SchoolClass+"班）"
          +"</td>"
          +"<td>"
          +'<button class="btn btn-info" onclick="toViewAthDataDetail('+AthID+')">详细</button>'
          +"</td>"
          +"</tr>";
          
          $("#AthList").append(ct);
        }
      }
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