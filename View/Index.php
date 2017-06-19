<?php
$isAthlete=getSess("SOA_isAthlete");
?>

<h2 style="text-align:center">
  欢迎登录<br>东风东游泳队报名系统！
</h2>
<hr>
<h3>快速菜单</h3>
<hr>
<center>

  <?php if($isAthlete==1){ ?>
  <a class="btn btn-success" href="index.php?file=Enroll&action=toGamesList.php" style="width:98%">马 上 报 名</a>
  <br><br>
  <?php } ?>
  
  <?php if($isAthlete==1){ ?>
  <a class="btn btn-info" href="index.php?file=Athlete&action=EditData.php" style="width:98%">资 料 修 改</a>
  <?php }elseif($isAthlete==0){ ?>
  <a class="btn btn-info" href="index.php?file=Athlete&action=toList.php" style="width:98%">运 动 员 管 理</a>
  <?php } ?>
  
  <br><br>
  
  <?php if($isAthlete==0){ ?>
  <a class="btn btn-success" onclick='$("#myModal").modal("show");' style="width:98%">比 赛 管 理 / 统 计</a>
  <?php } ?>

</center>
<hr>

<?php if($isAthlete==0){ ?>
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" id="ModalTitle">比赛管理 / 报名数据统计</h3>
      </div>
      <div class="modal-body">
        <a class="btn btn-info" href="index.php?file=Games&action=toList.php" style="width:48%">比 赛 管 理</a> <a class="btn btn-success" href="index.php?file=Statistics&action=toGamesList.php" style="width:48%">报 名 统 计</a>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">返回 &gt;</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php } ?>

<script>
var GlobalNotice="";

window.onload=function(){
	getGlobalNotice();
};

function getGlobalNotice(){
	$.ajax({
    url:"Functions/Api/getGlobalNotice.php",
    type:"get",
    dataType:"json",
    success:function(got){
    	if(got.Content!="" && got.PubTime!=""){
    		Content=got.Content;
    		PubTime=got.PubTime;
    		msg="发布时间："+PubTime+"<hr><b>"+Content+"</b>";
    		dm_notification(msg,'green',10000);
    	}
    }
	});
}
</script>