<?php
if(isset($_GET['action']) && $_GET['action']="ContactAdmin.php"){
  $GroupImageLink="res/img/ContactAdminByWXGroup.png";
}else{
  $GroupImageLink="../res/img/ContactAdminByWXGroup.png";
}
?>

<h2 style="text-align:center">
  联系管理员
</h2>
<hr>
<h3>
请在系统内提交工单，谢谢！<br><br>
<a href="index.php?file=WorkOrder&action=CreateWorkOrder.php" class="btn btn-primary">点 此 提 交 工 单</a>
</h3>
<!--hr>
<img src="<?php echo $GroupImageLink; ?>" style="width:300px;height:500px;"-->
