<table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">

<?php
for($i=1;$i<=6;$i++){  
  $list=PDOQuery($dbcon,"SELECT * FROM athlete_list WHERE SchoolGrade=$i",[],[]);
  $Grade=showCNNum($i);

  $total=sizeof($list[0]);
  $AthIDs="";
  
  for($m=0;$m<$total;$m++){
    $AthIDs=$AthIDs.$list[0][$m]['AthID'].",";
  }
  $AthIDs=substr($AthIDs,0,strlen($AthIDs)-1);

  // 每个年级的第一个用户
  // 用于年级名称合并单元格
  if($total!=0){
  for($j=0;$j<1;$j++){
    $AthID=$list[0][$j]['AthID'];
    $RealName=$list[0][$j]['RealName'];
    $Sex=$list[0][$j]['Sex'];
    $SchoolClass=$list[0][$j]['SchoolClass'];
?>
	  <tr>
	    <td rowspan="<?php echo $total; ?>" style='text-align:center;' align="center" id="<?php echo $AthIDs; ?>" onclick="chooseAll(this.id)">
	      <?php echo $Grade; ?>年级
	    </td>
	    <td id="Line_<?php echo $AthID; ?>" onclick='toggleColor("<?php echo $AthID; ?>")'>
	      <b><?php echo $RealName."（$Sex - {$Grade}年{$SchoolClass}班）"; ?></b>
	    </td>
	  </tr>
<?php } ?>

<?php
  // 每个年级的其他用户
  for($k=1;$k<$total;$k++){
    $AthID=$list[0][$k]['AthID'];
    $RealName=$list[0][$k]['RealName'];
    $Sex=$list[0][$k]['Sex'];
    $SchoolClass=$list[0][$k]['SchoolClass'];
?>
	  <tr>
	    <td id="Line_<?php echo $AthID; ?>" onclick='toggleColor("<?php echo $AthID; ?>")'>
	      <b><?php echo $RealName."（$Sex - {$Grade}年{$SchoolClass}班）"; ?></b>
	    </td>
	  </tr>
<?php
  }
  }
}
?>
</table>
<button onclick="lockScreen();saveGamesAthlete();" class="btn btn-success" style="width:98%">确 认 选 择</button>

<script>
var AthIDs = Array();

function saveGamesAthlete(){
GamesID=getURLParam("GamesID");

if(AthIDs.length===0){
  if(confirm("确定设置此比赛为[全开放比赛]？")){
    saveGamesAthlete();
  }else{
    unlockScreen();
    return false;
  }
}

$.ajax({
  url:"Games/toSaveGamesAthlete.php",
  type:"post",
  data:{"AthIDs":AthIDs,"GamesID":GamesID},
  error:function(e){
    alert(JSON.stringify(e));
    console.log(JSON.stringify(e));
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

function toggleColor(AthID){
  Loc=isInArray(AthIDs,AthID);
  
  if(Loc===false){
    $("#Line_"+AthID).attr("style","background-color:#CCFF99;");
    AthIDs.push(AthID);
  }else{
    $("#Line_"+AthID).attr("style","");
    AthIDs.splice(Loc,1);
  }
}

function chooseAll(AthIDs_id){
  AthIDs_Arr=AthIDs_id.split(",");
  for(i=0;i<AthIDs_Arr.length;i++){
    AthID=AthIDs_Arr[i];
    Loc=isInArray(AthIDs,AthID);
    if(Loc===false){
      $("#Line_"+AthID).attr("style","background-color:#CCFF99;");
      AthIDs.push(AthID);
    }else{
      $("#Line_"+AthID).attr("style","");
      AthIDs.splice(Loc,1);
    }
  }
}
</script>