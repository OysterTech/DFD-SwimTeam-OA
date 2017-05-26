<?php
$Roleid=isset($_GET['RID'])?$_GET['RID']:"";
if($Roleid=="") ErrCodedie("500-GTDA");

$vfy_sql="SELECT * FROM role_list WHERE Roleid=?";
$vfy_rs=PDOQuery($dbcon,$vfy_sql,[$Roleid],[PDO::PARAM_INT]);
if($vfy_rs[1]!="1") ErrCodedie("500-NR");
$RoleName=$vfy_rs[0][0]['RoleName'];

if(isset($_POST) && $_POST){
$ids=$_POST['ids'];
$ids=explode(",",$ids);
$total=sizeof($ids);
$Insert_count=0;

//先删除该角色的所有权限
$Del_sql="DELETE FROM role_purview WHERE Roleid=?";
$Del_rs=PDOQuery($dbcon,$Del_sql,[$Roleid],[PDO::PARAM_INT]);

for($i=0;$i<$total;$i++){
  //循环添加
  $Insert_sql="INSERT INTO role_purview(Roleid,Purvid) VALUES (?,?)";
  $Insert_rs=PDOQuery($dbcon,$Insert_sql,[$Roleid,$ids[$i]],[PDO::PARAM_INT,PDO::PARAM_STR]);
  if($Insert_rs[1]>0){
    $Insert_count++;
  }
}

$rtnURL="index.php?file=Role&action=toList.php";
    
if($Insert_count>0){
  echo '<script>alert("成功给当前角色分配'.$Insert_count.'项权限！");window.location.href="'.$rtnURL.'";</script>';
}
}
?>

<script type="text/javascript" src="res/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="res/js/jquery.ztree.core.js"></script>
<script type="text/javascript" src="res/js/jquery.ztree.excheck.js"></script>
<script>
	var setting = {
		view: {
			selectedMulti: false
		},
		check: {
			enable: true
		},
		data: {
			simpleData: {
				enable: true
			}
		}
	};

	var zNodes=<?php include("Functions/Api/AllMenuData.php"); ?>;

	function getCheckedNodes(){
		ids="";
		var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
		nodes = zTree.getCheckedNodes();
		for (i=0,l=nodes.length;i<l;i++){
			ids+=nodes[i].id+",";
		}
		ids=ids.substr(0,ids.length-1);
		console.log(ids);
		$("#chkids").val(ids);
		$("form").submit();
	}

	$(document).ready(function(){
		$.fn.zTree.init($("#treeDemo"),setting,zNodes);
	});
</script>

<center>
  <h1>
    <?php echo "分配权限：".$RoleName; ?>
  </h1>
  <hr>
  <ul id="treeDemo" class="ztree"></ul>

  <form method="post">
    <input type="hidden" name="ids" id="chkids">
  </form>
  <hr>
  <button class="btn btn-success" style="width:80%" onclick="getCheckedNodes()">确认分配</button>
  <br><br>

</center>