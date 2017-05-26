<?php
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

<div class="alert alert-success alert-dismissible" role="alert">
  ▲ 点击 开放/关闭 可切换报名开放状态
</div>
<div class="alert alert-info alert-dismissible" role="alert">
  ▲ 限制报名：限制只有部分运动员可以报名（如：选拔赛/正式赛）
</div>

<hr>

<table class="table table-hover table-bordered" style="border-radius: 5px; border-collapse: separate;">
<tr>
  <td colspan=5>
    <center>
      <a class="btn btn-primary" href="index.php?file=Games&action=AddGames.php" style="width:97%">新增比赛</a>
    </center>
  </td>
</tr>

<tr>
  <th style="word-wrap:break-word;">比赛名称</th>
  <th style="word-wrap:break-word;">结束时间</th>
  <th>限制报名</th>
  <th>开放状态</th>
  <th>操作</th>
</tr>
<?php
  for($i=$Begin;$i<$Limit;$i++){
    $GamesID=$list[0][$i]['GamesID'];
    $GamesName=$list[0][$i]['GamesName'];
    $EndDate=$list[0][$i]['EndDate'];
    $AllowUser=$list[0][$i]['AllowUser'];
    $isPrivate=$list[0][$i]['isPrivate'];
    $isOpen=$list[0][$i]['isOpen'];
    $EndYear=substr($EndDate,0,4);
    $EndMD=substr($EndDate,4,4);
    $EndDate=$EndYear."<br>".$EndMD;
    
    $oprURL=makeOprBtn("info","Games","EditGames.php",[["GamesID",$GamesID],["GamesName",$GamesName]],"编辑");
    $oprURL=$oprURL." ".makeOprBtn("success","Games","ChooseGamesItem.php",[["GamesID",$GamesID],["GamesName",$GamesName]],"选择项目");

    if($isPrivate=="0"){
      $AllowStatus="<font color=green>全体</font>";
    }elseif($isPrivate=="1"){
      $AllowStatus="<font color=blue>限制</font>";
      $oprURL=$oprURL." ".makeOprBtn("warning","Games","ChooseGamesAthlete.php",[["GamesID",$GamesID]],"选择选手");
    }
    
    if($isOpen=="0") $Status='<a onclick="changeOpen('.$GamesID.',0)" style="color:red;font-weight:bolder;" id="Status'.$GamesID.'">关闭</a>';
    elseif($isOpen=="1") $Status='<a onclick="changeOpen('.$GamesID.',1)" style="color:green;font-weight:bolder;" id="Status'.$GamesID.'">开放</a>';
?>
<tr>
  <td><?php echo $GamesName; ?></td>
  <td><?php echo $EndDate; ?></td>
  <td><?php echo $AllowStatus; ?></td>
  <td><?php echo $Status; ?></td>
  <td><?php echo $oprURL; ?> <button onclick='readyDelGames("<?php echo $GamesID; ?>","<?php echo $GamesName; ?>")' class="btn btn-danger">删除</button></td>
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

<script>
function changeOpen(GamesID,Status){
  lockScreen();
  $.ajax({
    url:"Games/toChangeGamesOpen.php",
    type:"post",
    data:{"GamesID":GamesID,"Status":Status},
    error:function(e){
      alert(JSON.stringify(e));
      console.log(JSON.stringify(e));
    },
    success:function(got){
      if(got=="1"){
        if(Status=="0"){
          $("#Status"+GamesID).attr("style","color:green;font-weight:bolder;");
          $("#Status"+GamesID).attr("onclick",'changeOpen('+GamesID+',1);');
          $("#Status"+GamesID).html("开放");
        }
        else if(Status=="1"){
          $("#Status"+GamesID).attr("style","color:red;font-weight:bolder;");
          $("#Status"+GamesID).attr("onclick",'changeOpen('+GamesID+',0);');
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
  $("#GamesID").val(GamesID);
  $("#GamesName").html("【"+GamesName+"】");
  $('#myModal').modal('show');
}

function toDelGames(){
  lockScreen();
  GamesID=$("#GamesID").val();
  $.ajax({
    url:"Games/toDelGames.php",
    type:"post",
    data:{"GamesID":GamesID},
    error:function(e){
      alert(JSON.stringify(e));
      console.log(JSON.stringify(e));
    },
    success:function(got){
      if(got=="1"){
        alert("删除成功！");
        window.reload();
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
          <input type="hidden" id="GamesID" name="GamesID">
          <center>
          <font color="red" style="font-weight:bolder;font-size:23;">确定要删除下列比赛吗？</font>
          <br><br>
          <font color="blue" style="font-weight:bolder;font-size:23;"><p id="GamesName"></p></font>
          </center>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">&lt; 取消</button>
        <button type="button" class="btn btn-success" id='okbtn' onclick='toDelGames()'>删除 &gt;</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->