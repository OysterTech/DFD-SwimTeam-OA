<?php
$MyUserID=GetSess(Prefix."UserID");
$list=PDOQuery($dbcon,"SELECT * FROM sys_user",[],[]);
$total=sizeof($list[0]);

if(isset($_POST) && $_POST){
  $OprType=$_POST['OprType'];
  // 用户ID
  $uID=@$_POST['uID'];
  // 角色/状态ID
  $ID=@$_POST['ID'];
  if($uID!="" && $ID!=""){
    $sql2="UPDATE sys_user SET ";
    if($OprType=="1"){
      // 如果要重置用户激活信息
      if($ID=="1"){
        $Pw_arr=getRanPW();
        $originPassword=$Pw_arr[0];
        $salt=$Pw_arr[1];
        $Password=$Pw_arr[2];
        $sql2.="Status=?,originPassword='{$originPassword}',salt='{$salt}',Password='{$Password}' ";
      }else{
        // 修改状态并清空初始密码
        $sql2.="Status=?,originPassword='' ";
      }
    }else{
      $sql2.="RoleID=? ";
    }
    $sql2.="WHERE UserID=?";
    
    $rs2=PDOQuery($dbcon,$sql2,[$ID,$uID],[PDO::PARAM_INT,PDO::PARAM_INT]);
    die("<script>window.location.href='$nowURL';</script>");
  }else{
    echo "<script>alert('您未选择需要修改的角色/状态\n请重试！！！');</script>";
  }
}

//给重置密码验证是否直接输URL进行重置
setSess(Prefix."inUserList","1");
?>

<center>
  <h1>用户列表</h1>
</center>

<hr>

<a href="?file=User&action=AddUser.php" class="btn btn-success btn-block">新 增 用 户</a>

<hr>

<table id="table" class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">
<thead>
<tr>
  <th>用户名</th>
  <th>姓名</th>
  <th>角色</th>
  <th>状态</th>
  <th>初始密码</th>
  <th>操作</th>
</tr>
</thead>

<tbody>
<?php
  for($i=0;$i<$total;$i++){
    $UserID=$list[0][$i]['UserID'];
    $UserName=$list[0][$i]['UserName'];
    $RealName=$list[0][$i]['RealName'];
    $RoleID=$list[0][$i]['RoleID'];
    $Status=$list[0][$i]['Status'];
    $originPassword=$list[0][$i]['originPassword'];
    if($UserID!=$MyUserID){
      $oprURL=makeOprBtn("编辑","info","User","EditProfile.php",[["UID",$UserID],["UserName",$UserName],["RealName",$RealName]]);
      $oprURL.=makeOprBtn("删除","danger","User","toDelUser.php",[["UID",$UserID]]);
    }else{
      $oprURL="";
    }
    $Roleinfo=PDOQuery($dbcon,"SELECT * FROM role_list WHERE RoleID=?",[$RoleID],[PDO::PARAM_INT]);
    $Rolename=@$Roleinfo[0][0]['RoleName'];
    if($Rolename==""){
      $Rolename="<font color='red'>无角色用户</font>";
    }
    
    // 根据用户状态判断它能否重置密码
    switch($Status){
     // 禁用，不能重置密码
     case 0:
      $Status="<a style='color:red' onclick='updateStatus($UserID)'>已禁用</a>";
      $originPassword="<center>/</center>";
      break;
     // 未激活，显示初始密码
     case 1:
      $Status="<a style='color:blue' onclick='updateStatus($UserID)'>未激活</a>";
      break;
     // 启用，可以重置密码
     case 2:
      $Status="<a style='color:green' onclick='updateStatus($UserID)'>使用中</a>";
      // 如果不是现在这个用户，可以重置
      if($UserID!=$MyUserID){
        $originPassword='<a class="btn btn-warning" href="?file=User&action=toResetPW.php&UID='.$UserID.'&n='.$UserName.'&r='.$RealName.'">重置密码</a>';
      }else{
        $originPassword="";
      }
      break;
     default:
      $Status="<a style='color:red' onclick='updateStatus($UserID)'>异常</a>";
      $originPassword="/";
      break;
    }

?>
<tr>
  <td><?php echo $UserName; ?></td>
  <td><?php echo $RealName; ?></td>
  <td><?php echo "<a onclick='getRole($UserID)'>".$Rolename."</a>"; ?></td>
  <td><?php echo $Status; ?></td>
  <td><?php echo $originPassword; ?></td>
  <td><?php echo $oprURL; ?></td>
</tr>
<?php } ?>
</tbody>
</table>


<script>
window.onload=function(){
	$('#table').DataTable({
		"pageLength":100,
		"order":[[0,'desc']],
		"columnDefs":[{
			"targets":[4,5],
			"orderable": false
		}]
	});
};

function updateStatus(ID){
 msg='';
 msg='<input name="ID" type="radio" value="0" onclick="updateVALUE(0)"><font color="red">已禁用</font><br>'
 +'<input name="ID" type="radio" value="1" onclick="updateVALUE(1)"><font color="blue">未激活</font><br>'
 +'<input name="ID" type="radio" value="2" onclick="updateVALUE(2)"><font color="green">已启用</font><br>';
 $("#OprType").val("1");
 $("#uID").val(ID);
 $('#ModalTitle').html("修改用户状态");
 $('#msg').html(msg);
 $('#detail').html("");
 $('#myModal').modal('show');
}

function updateVALUE(value){
  $("#status").val(value);
}

function getRole(ID){
 msg='';
 msg='<center>'
 +'<select name="ID" onchange="selectRole(this.options[this.options.selectedIndex].value)">'
 +'<option selected="selected" disabled>---请选择角色---</option>'
 +'<center>';
 $.ajax({
  url:"Functions/Api/getRole.php",
  data:{type:1},
  type:"post",
  dataType:"json",
  error:function(e){alert()},
  success:function(got){
   for(i in got){
    msg+='<option ';
    for(j in got[i]){
     if(j==="ID"){
      msg+='value="'+got[i][j]+'">';
     }
     else if(j==="name"){
      msg+=got[i][j]+"</option>";
     }
    }
   }
   $("#OprType").val("2");
   $("#uID").val(ID);
   $('#ModalTitle').html("修改用户角色");
   $('#msg').html(msg);
   $('#myModal').modal('show');
  }
 });
}

function selectRole(rID){
 detail="";
 $.ajax({
  url:"Functions/Api/getRole.php",
  data:{type:2,rID:rID},
  type:"post",
  dataType:"json",
  error:function(e){alert(JSON.stringify(e))},
  success:function(got){
   detail+='<table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;"><h4><tr>';
   for(i in got[0]){
    if(i==="name"){
     detail+="<th>角色名称</th><td><font color='green'>"+got[0][i]+"</font></td></tr>";
    }else{
     detail+="<th>角色简介</th><td><font color='blue'>"+got[0][i]+"</font></td></tr></table>";
    }
   }
   $('#detail').html(detail);
  }
 });
}

function submitForm(){
  type=$("#OprType").val();
  status=$("#status").val();
  if(type=="1"){
    if(status=="0"){
      //如果要禁用用户
      if(confirm("确定要禁用此用户吗？")){
        $("form").submit();
      }
    }else if(status=="1"){
      //如果要重置用户激活信息
      if(confirm("确定要重置此用户的激活信息吗？\n这将导致其密码初始化！")){
        $("form").submit();
      }
    }else{
      $("form").submit();
    }
  }else{
    $("form").submit();
  }
}
</script>

<div class="modal fade" ID="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hIDden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" ID="ModalTitle">温馨提示</h3>
      </div>
      <div class="modal-body">
        <div style="overflow:hIDden;">
        </div>
        <form method="post">
        <input type="hIDden" ID="uID" name="uID">
        <input type="hIDden" ID="OprType" name="OprType">
        <input type="hIDden" ID="status">
        <p ID="msg"></p><hr>
        <p ID="detail"></p>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">&lt; 取消</button>
        <button type="button" class="btn btn-success" ID='okbtn' onclick='submitForm()'>确定 &gt;</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->