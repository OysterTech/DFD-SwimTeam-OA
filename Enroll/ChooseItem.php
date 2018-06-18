<?php
$YearGroup=GetSess(Prefix."Ath_YearGroup");
$GamesID=$_GET['GamesID'];
$GamesName=urldecode($_GET['GamesName']);
$AthID=GetSess(Prefix."AthID");

// 判断是否已经报名
$Enroll_SQL="SELECT * FROM enroll_item WHERE GamesID=? AND AthID=?";
$Enroll_rs=PDOQuery($dbcon,$Enroll_SQL,[$GamesID,$AthID],[PDO::PARAM_STR,PDO::PARAM_STR]);
if($Enroll_rs[1]>0){
  die('<script>window.location.href="index.php?file=Enroll&action=ViewEnrollItem.php&GamesID='.$GamesID.'&GamesName='.$GamesName.'";</script>');
}

// 获取比赛项目
$GamesItem_list=PDOQuery($dbcon,"SELECT a.*,b.ItemName,b.YearGroup FROM games_item a,item_list b WHERE a.GamesID=? AND a.ItemID=b.ItemID AND b.YearGroup=?",[$GamesID,$YearGroup],[PDO::PARAM_STR,PDO::PARAM_STR]);
$GamesItem_total=sizeof($GamesItem_list[0]);

if($GamesItem_list[1]<1){
  die('<script>alert("本场比赛暂无您可报名的项目！");window.location.href="index.php?file=Enroll&action=toGamesList.php";</script>');
}
?>

<center>
  <font color="blue" style="font-weight:bolder;font-size:20;">
  <?php echo $GamesName."<br>（".$YearGroup."年组）"; ?>
  </font>
</center>

<hr>

<div class="alert alert-success alert-dismissible" role="alert">
  <font style="font-size:16px">
  请在下方表格点击需要报名的项目。<br><br>
  点击后项目底色会变成绿色，即代表已经选中。<br><br>
  如需取消报名该项目，再次点击至变回白色即可。
  </font>
</div>
<br>
<div class="alert alert-info alert-dismissible" role="alert">
  <font style="font-size:16px">
  报名前请先阅读比赛规程，<a href="index.php?file=Games&action=toGamesNoticeList.php&GamesID=<?=$GamesID; ?>&GamesName=<?=$GamesName; ?>" class="btn btn-info" target="_blank">点 此 阅 读</a>
  </font>
</div>
<br>
<div class="alert alert-danger alert-dismissible" role="alert">
  <font style="font-size:16px">
  点击确认报名后请勿再次点击，防止二次报名
  </font>
</div>

<hr>

<table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">

<?php
for($k=0;$k<$GamesItem_total;$k++){
  $ItemID=$GamesItem_list[0][$k]['ItemID'];
  $ItemName=$GamesItem_list[0][$k]['ItemName'];
?>
	  <tr>
	    <td id="Line_<?=$ItemID;?>" ItemName="<?=$ItemName;?>" onclick='toggleColor("<?=$ItemID;?>")'>
	      <b style="color:#795548"><?php echo $YearGroup."年组 ".$ItemName; ?></b>
	    </td>
	  </tr>
<?php } ?>
</table>

<center>
  <button onclick="readytoEnroll();" class="btn btn-success" style="width:98%">确 认 报 名</button>
</center>

<script>
var ItemIDs = Array();
var ItemNames = Array();
var GamesID=getURLParam("GamesID");
var GamesName=getURLParam("GamesName");

function readytoEnroll(){
msg="";

if(ItemIDs.length==0){
  alert("请选择比赛项目！");
  unlockScreen();
  return false;
}

for(i=0;i<ItemIDs.length;i++){
  ItemName=ItemNames[i];
  msg+="▲ "+ItemName+"<br>";
}

$("#msg").html(msg);
$("#myModal").modal("show");
}

function toEnroll(){
lockScreen();
$.ajax({
  url:"Enroll/toSaveItem.php",
  type:"post",
  data:{"ItemIDs":ItemIDs,"GamesID":GamesID},
  error:function(e){
    alert(JSON.stringify(e));
    console.log(JSON.stringify(e));
    unlockScreen();
  },
  success:function(got){
    if(got=="1"){
      alert("报名成功！请等待领队的通知！");
      url="index.php?file=Enroll&action=ViewEnrollItem.php&GamesID="+GamesID+"&GamesName="+GamesName;
      window.location.href=url;
    }else{
      alert("报名失败！！！\n\n错误内容："+got+"\n\n请联系管理员并截图提交错误内容！");
      unlockScreen();
    }
  }
});
}


function toggleColor(ItemID){
  Loc=isInArray(ItemIDs,ItemID);
  
  if(Loc===false){
    $("#Line_"+ItemID).attr("style","background-color:#CCFF99;");
    Name=$("#Line_"+ItemID).attr("ItemName");
    ItemIDs.push(ItemID);
    ItemNames.push(Name);
  }else{
    $("#Line_"+ItemID).attr("style","");
    Name=$("#Line_"+ItemID).attr("ItemName");
    ItemIDs.splice(Loc,1);
    ItemNames.splice(Loc,1);
  }
}
</script>

<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" id="ModalTitle">温馨提示</h3>
      </div>
      <div class="modal-body">
        <font color="blue" style="font-weight:bolder;font-size:25;text-align:center;line-height:36px;">
          请确认您选择的项目：<br>
        </font>
        <font color="green" style="font-weight:bolder;font-size:24;text-align:center;line-height:36px;">
          <p id="msg"></p>
        </font>
        <hr>
        <font color="red" style="font-weight:bolder;font-size:26;text-align:center;line-height:36px;">
          ▲ 确认无误后请点“确认报名”<br>
          ▲ 报名成功后无法修改！！！<br>
        </font>        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" onclick="$('#myModal').modal('hide');unlockScreen();">返回 &lt;</button>
        <button type="button" class="btn btn-success" onclick="toEnroll();">确认报名 &gt;</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
