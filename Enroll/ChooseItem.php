<?php
$YearGroup=GetSess(Prefix."Ath_YearGroup");
$GamesID=$_GET['GamesID'];
$GamesName=urldecode($_GET['GamesName']);
$AthID=GetSess(Prefix."AthID");
$ItemIDs=array();
$showItemID=array();
$showItemName=array();
$showCount=-1;

// 判断是否已经报名
$Enroll_SQL="SELECT * FROM enroll_item WHERE GamesID=? AND AthID=?";
$Enroll_rs=PDOQuery($dbcon,$Enroll_SQL,[$GamesID,$AthID],[PDO::PARAM_STR,PDO::PARAM_STR]);
if($Enroll_rs[1]>0){
  header("Location: index.php?file=Enroll&action=ViewEnrollItem.php&GamesID=$GamesID&GamesName=$GamesName");
}

$GamesItem_list=PDOQuery($dbcon,"SELECT * FROM games_item WHERE GamesID=?",[$GamesID],[PDO::PARAM_STR]);
$GamesItem_total=sizeof($GamesItem_list[0]);
$Item_list=PDOQuery($dbcon,"SELECT * FROM item_list WHERE YearGroup=?",[$YearGroup],[PDO::PARAM_STR]);
$Item_total=sizeof($Item_list[0]);

for($i=0;$i<$Item_total;$i++){
  array_push($ItemIDs,$Item_list[0][$i]['ItemID']);
}

for($j=0;$j<$GamesItem_total;$j++){
  $ItemID=$GamesItem_list[0][$j]['ItemID'];
  if(in_array($ItemID,$ItemIDs)){
    $Loc=array_search($ItemID,$ItemIDs);
    array_push($showItemName,$Item_list[0][$Loc]['ItemName']);
    array_push($showItemID,$Item_list[0][$Loc]['ItemID']);
    $showCount++;
  }
}

?>

<center>
  <font color="blue" style="font-weight:bolder;font-size:20;">
  <?php echo $GamesName."<br>（".$YearGroup."年组）"; ?>
  </font>
</center>

<table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">

<?php
for($k=0;$k<=$showCount;$k++){
  $ItemID=$showItemID[$k];
  $ItemName=$showItemName[$k];
?>
	  <tr>
	    <td id="Line_<?php echo $ItemID; ?>" ItemName="<?php echo $ItemName; ?>" onclick='toggleColor("<?php echo $ItemID; ?>")'>
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

function readytoEnroll(){
GamesID=getURLParam("GamesID");
msg="";

if(ItemIDs.length==0){
  alert("请选择比赛项目！");
  unlockScreen();
  return false;
}

for(i=0;i<ItemIDs.length;i++){
  ItemName=ItemNames[i];
  msg=msg+ItemName+"<br>";
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
      history.go(-1);
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
        <font color="red" style="font-weight:bolder;font-size:26;text-align:center;line-height:36px;">
          请确认您选择的项目：<br>
        </font>
        <font color="green" style="font-weight:bolder;font-size:24;text-align:center;line-height:36px;">
          <p id="msg"></p>
        </font>
        <hr>
        <font color="red" style="font-weight:bolder;font-size:25;text-align:center;line-height:36px;">
          ▲ 确认无误后请点“确认”<br>
          ▲ 确定后无法修改！！！<br>
        </font>        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" onclick="$('#myModal').modal('hide');unlockScreen();">返回 &lt;</button>
        <button type="button" class="btn btn-success" onclick="toEnroll();">确认 &gt;</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->