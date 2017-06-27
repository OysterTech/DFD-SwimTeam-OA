<table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">

<?php
// 所有比赛都是6~13岁组
for($i=6;$i<=13;$i++){
  $YearGroup=date("Y")-$i;
  $list=PDOQuery($dbcon,"SELECT * FROM item_list WHERE YearGroup=$YearGroup",[],[]);
  $total=sizeof($list[0]);
  $ItemIDs="";
  
  for($m=0;$m<$total;$m++){
    $ItemIDs=$ItemIDs.$list[0][$m]['ItemID'].",";
  }
  $ItemIDs=substr($ItemIDs,0,strlen($ItemIDs)-1);
  
  // 每个组的第一个项目
  // 用于组别名称合并单元格
  if($total!=0){
  for($j=0;$j<1;$j++){
    $ItemID=$list[0][$j]['ItemID'];
?>
	  <tr>
	    <td rowspan="<?php echo $total; ?>" style='text-align:center;' align="center" id="<?php echo $ItemIDs; ?>" onclick="chooseAll(this.id)">
	      <?php echo $YearGroup; ?>年组
	    </td>
	    <td id="Line_<?php echo $ItemID; ?>" YearGroup="<?php echo $YearGroup; ?>" onclick='toggleColor("<?php echo $ItemID; ?>")'>
	      <b style="color:#795548"><?php echo $list[0][$j]['ItemName']; ?></b>
	    </td>
	  </tr>
<?php } ?>

<?php
  // 每个组的其他项目
  for($k=1;$k<$total;$k++){
    $ItemID=$list[0][$k]['ItemID'];
?>
	  <tr>
	    <td id="Line_<?php echo $ItemID; ?>" YearGroup="<?php echo $YearGroup; ?>" onclick='toggleColor("<?php echo $ItemID; ?>")'>
	      <b style="color:#795548"><?php echo $list[0][$k]['ItemName']; ?></b>
	    </td>
	  </tr>
<?php
  }
  }
}
?>
</table>

<center>
  <button onclick="lockScreen();saveGamesItem();" class="btn btn-success" style="width:98%">确 认 选 择</button>
</center>

<script>
var ItemIDs = Array();

function saveGamesItem(){
GamesID=getURLParam("GamesID");

if(ItemIDs.length==0){
  alert("请选择比赛项目！");
  unlockScreen();
  return false;
}

$.ajax({
  url:"Games/toSaveGamesItem.php",
  type:"post",
  data:{"ItemIDs":ItemIDs,"GamesID":GamesID},
  error:function(e){
    alert(JSON.stringify(e));
    console.log(JSON.stringify(e));
    unlockScreen();
  },
  success:function(got){
    if(got=="1"){
      alert("选择成功！");
      history.go(-1);
    }else{
      alert("选择失败！！！\n\n错误内容："+got+"\n\n请联系管理员并截图提交错误内容！");
      unlockScreen();
    }
  }
});
}


function toggleColor(ItemID){
  Loc=isInArray(ItemIDs,ItemID);
  
  if(Loc===false){
    $("#Line_"+ItemID).attr("style","background-color:#CCFF99;");
    ItemIDs.push(ItemID);
  }else{
    $("#Line_"+ItemID).attr("style","");
    ItemIDs.splice(Loc,1);
  }
}


function chooseAll(ItemIDs_id){
  ItemIDs_Arr=ItemIDs_id.split(",");
  for(i=0;i<ItemIDs_Arr.length;i++){
    ItemID=ItemIDs_Arr[i];
    Loc=isInArray(ItemIDs,ItemID);
    if(Loc===false){
      $("#Line_"+ItemID).attr("style","background-color:#CCFF99;");
      ItemIDs.push(ItemID);
    }else{
      $("#Line_"+ItemID).attr("style","");
      ItemIDs.splice(Loc,1);
    }
  }
}
</script>