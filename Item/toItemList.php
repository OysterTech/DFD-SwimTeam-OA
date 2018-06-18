<?php
$YearGroup=isset($_GET['YearGroup'])?$_GET['YearGroup']:toAlertDie("500-It-NotYG","参数缺失！");
$sql="SELECT * FROM item_list WHERE YearGroup=?";
$list=PDOQuery($dbcon,$sql,[$YearGroup],[PDO::PARAM_STR]);
$total=sizeof($list[0]);

if(isset($_POST) && $_POST){
  switch($_POST['opr']){
    case "A":
      $sql="INSERT INTO item_list(YearGroup,ItemName) VALUES (?,?)";
      $data=array($YearGroup,$_POST['name']);
      
      break;
    case "E":
      $sql="UPDATE item_list SET ItemName=? WHERE ItemID=?";
      $data=array($_POST['name'],$_POST['id']);
      break;
    case "D":
      $sql="DELETE FROM item_list WHERE ItemID=? AND YearGroup=?";
      $data=array($_POST['id'],$YearGroup);
      break;
    default:
      break;
  }
  
  $rs=PDOQuery($dbcon,$sql,$data,[PDO::PARAM_STR,PDO::PARAM_STR]);
  if($rs[1]==1){
    die('<script>alert("操作成功！");window.location.href=this.location.href;</script>');
  }
}
?>

<center>
  <h1>
    <b><?php echo $YearGroup; ?></b>年组<br>
    项目列表
  </h1>
</center>

<hr>

<a href="index.php?file=Item&action=toYearGroupList.php" class="btn btn-primary btn-block">返 回 年 龄 组 列 表</a><br>
<a class="btn btn-success btn-block" onclick="toAdd()">新 增 项 目</a>

<hr>

<table id="table" class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">
<thead>
<tr>
  <th>项目名</th>
  <th>操作</th>
</tr>
</thead>

<tbody>
<?php
  for($i=0;$i<$total;$i++){
    $ItemID=$list[0][$i]['ItemID'];
    $ItemName=$list[0][$i]['ItemName'];
?>
<tr>
  <td><?php echo $ItemName; ?></td>
  <td><button class="btn btn-primary" onclick='toEdit("<?php echo $ItemID; ?>","<?php echo $ItemName; ?>")'>编辑</button> <button class="btn btn-danger" onclick='toDel("<?php echo $ItemID; ?>","<?php echo $ItemName; ?>")'>删除</button></td>
</tr>
<?php } ?>
</tbody>
</table>

<script>
window.onload=function(){
	$('#table').DataTable({
		"pageLength":25,
		"order":[[0,'desc']],
		"columnDefs":[{
			"targets":[1],
			"orderable": false
		}]
	});
};

function toAdd(){
  $("#opr").val("A");
  $("#id").val("");
  $("#name").val("");
  $("#name").removeAttr("disabled");
  $("#myModal").modal('show');
}

function toEdit(id,name){
  $("#opr").val("E");
  $("#id").val(id);
  $("#name").val(name);
  $("#name").removeAttr("disabled");
  $("#myModal").modal('show');
}

function toDel(id,name){
  $("#opr").val("D");
  $("#id").val(id);
  $("#name").val(name);
  $("#name").attr("disabled","disabled");
  $("#myModal").modal('show');
}
</script>

<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" id="ModalTitle">项目操作</h3>
      </div>
      <form method="post">
      <div class="modal-body">
        <input id="name" name="name" class="form-control">
        <input type="hidden" id="opr" name="opr">
        <input type="hidden" id="id" name="id">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">&lt; 返回</button><input type="submit" class="btn btn-success" value="确认提交 &gt;">
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
