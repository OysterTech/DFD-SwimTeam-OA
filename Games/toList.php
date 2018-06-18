<?php
$list=PDOQuery($dbcon,"SELECT * FROM games_list ORDER BY isEnd ASC,EndDate",[],[]);
$total=sizeof($list[0]);
?>

<center>
  <h1>比赛列表</h1>
</center>

<hr>

<div class="alert alert-success alert-dismissible" role="alert">
  ▲ 点击 开放/关闭 可切换报名开放状态
</div>
<div class="alert alert-info alert-dismissible" role="alert">
  ▲ 限制报名：限制只有部分运动员可以报名（如：选拔赛/正式赛）
</div>

<hr>

<a class="btn btn-primary" href="index.php?file=Games&action=AddGames.php" style="width:97%">新 增 比 赛</a>

<hr>

<table id="table" class="table table-hover table-bordered" style="border-radius: 5px; border-collapse: separate;">
<thead>
<tr>
  <th style="word-wrap:break-word;">比赛名称</th>
  <th style="word-wrap:break-word;">结束时间</th>
  <th>限制报名</th>
  <th>开放状态</th>
  <th>操作</th>
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
    $EndYear=substr($EndDate,0,4);
    $EndMD=substr($EndDate,4,4);
    $EndDate=$EndYear."<br>".$EndMD;
    
    $oprURL=makeOprBtn("编辑","info","Games","EditGames.php",[["GamesID",$GamesID],["GamesName",$GamesName],["EndDate",$EndYear.$EndMD]]);
    $oprURL=$oprURL." ".makeOprBtn("项目","success","Games","ChooseGamesItem.php",[["GamesID",$GamesID],["GamesName",$GamesName]]);

    if($isPrivate=="0"){
      $AllowStatus="<font color=green>全体</font>";
    }elseif($isPrivate=="1"){
      $AllowStatus="<font color=blue>限制</font>";
      $oprURL=$oprURL." ".makeOprBtn("选手","warning","Games","ChooseGamesAthlete.php",[["GamesID",$GamesID]]);
    }
    
    $oprURL=$oprURL." ".makeOprBtn("通知","primary","Games","toGamesNoticeList.php",[["GamesID",$GamesID],["GamesName",$GamesName]]);
    
    if($isEnd=="1") $Status='<a onclick="changeEnd('.$GamesID.',1)" style="color:red;font-weight:bolder;" id="Status'.$GamesID.'">关闭</a>';
    elseif($isEnd=="0") $Status='<a onclick="changeEnd('.$GamesID.',0)" style="color:green;font-weight:bolder;" id="Status'.$GamesID.'">开放</a>';
?>
<tr>
  <td><?php echo $GamesName; ?></td>
  <td><?php echo $EndDate; ?></td>
  <td><?php echo $AllowStatus; ?></td>
  <td><?php echo $Status; ?></td>
  <td><?php echo $oprURL; ?> <button onclick='readyDelGames("<?php echo $GamesID; ?>","<?php echo $GamesName; ?>")' class="btn btn-danger">删除</button></td>
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
			"targets":[4],
			"orderable": false
		}]
	});
};

function changeEnd(GamesID,Status){
  lockScreen();
  $.ajax({
    url:"Games/toChangeGamesEnd.php",
    type:"post",
    data:{"GamesID":GamesID,"Status":Status},
    error:function(e){
      alert(JSON.stringify(e));
      console.log(JSON.stringify(e));
    },
    success:function(got){
      if(got=="1"){
        if(Status=="1"){
          $("#Status"+GamesID).attr("style","color:green;font-weight:bolder;");
          $("#Status"+GamesID).attr("onclick",'changeEnd('+GamesID+',0);');
          $("#Status"+GamesID).html("开放");
        }else if(Status=="0"){
          $("#Status"+GamesID).attr("style","color:red;font-weight:bolder;");
          $("#Status"+GamesID).attr("onclick",'changeEnd('+GamesID+',1);');
          $("#Status"+GamesID).html("关闭");
        }
      }else{
        alert("切换失败！！！"+got);
      }
      
      unlockScreen();
    }
  });
}

function readyDelGames(GamesID,GamesName){
  $("#GamesID_ipt").val(GamesID);
  $("#GamesName_ipt").val(GamesName);
  $("#GamesName_show").html("【"+GamesName+"】");
  $('#myModal').modal('show');
}

function toDelGames(){
  lockScreen();
  GamesID=$("#GamesID_ipt").val();
  GamesName=$("#GamesName_ipt").val();
  $.ajax({
    url:"Games/toDelGames.php",
    type:"post",
    data:{"GamesID":GamesID,"GamesName":GamesName},
    error:function(e){
      alert(JSON.stringify(e));
      console.log(JSON.stringify(e));
    },
    success:function(got){
      if(got=="1"){
        alert("删除成功！");
        location.reload();
      }else{
        alert("删除失败！！！\n错误码："+got+"\n\n请联系管理员并提交错误码！");
        unlockScreen();
        $("#myModal").modal("hide");
      }
    }  
  });
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
        <form method="post">
          <input type="hidden" id="GamesID_ipt" name="GamesID">
          <input type="hidden" id="GamesName_ipt" name="GamesName">
          <center>
          <font color="red" style="font-weight:bolder;font-size:23;">确定要删除下列比赛吗？</font>
          <br><br>
          <font color="blue" style="font-weight:bolder;font-size:23;"><p id="GamesName_show"></p></font>
          </center>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">&lt; 取消</button>
        <button type="button" class="btn btn-danger" onclick='toDelGames()'>删除 &gt;</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->