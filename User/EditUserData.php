<?php
$Userid=isset($_GET['UID'])?$_GET['UID']:"";

if($Userid=="") ErrCodedie("500");

if(isset($_POST) && $_POST){
  $UserName=$_POST['UserName'];
  $RealName=$_POST['RealName'];
  $sql="UPDATE sys_user SET UserName=?,RealName=? WHERE Userid=?";
  $rs=PDOQuery($dbcon,$sql,[$UserName,$RealName,$Userid],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_INT]);

  if($rs[1]==1){
    echo "<script>alert('修改成功！');window.location.href='$nowURL';</script>";
  }else{
    echo "<script>alert('修改失败！！！');window.location.href='$nowURL';</script>";
  }
}
?>

<form method="post">
<div class="well col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center col-xs-10 col-xs-offset-1">
  <img src="res/img/back.png" style="position:absolute;width:24px;top:17px;left:5%;cursor:pointer" onclick="history.back()" aria-label="返回" >
  <h3>编辑用户资料</h3><br>
  <div class="col-md-offset-2" style="line-height:12px;">
      <div class="input-group">
        <span class="input-group-addon">用户名</span>
        <input type="text" class="form-control" name="UserName" id="UserName">
        <span class="input-group-addon" id="forgot">&lt;</span>
      </div>
      <div class="input-group">
        <span class="input-group-addon">真实姓名</span>
        <input type="text" class="form-control" name="RealName" id="RealName">
        <span class="input-group-addon" id="forgot">&lt;</span>
      </div>
      <hr>
      <input type="submit" class="btn btn-success" style="width:100%" value="确 认 修 改">
  </div>
</div>
</form>

<script>
function getUserData(Userid){
 $.ajax({
  url:"Functions/Api/getUserData.php",
  data:{UID:Userid},
  type:"post",
  dataType:"json",
  error:function(e){alert("数据传输出错！\n"+ JSON.stringify(e));},
  success:function(got){
   for(i in got[0]){
    if(i==="UserName"){
     $('#UserName').val(got[0][i]);
    }
    else if(i==="RealName"){
     $('#RealName').val(got[0][i]);
    }else{
     continue;
    }
   }
  }
 });
}

function URL_GetData(name)
{
 var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
 var r = window.location.search.substr(1).match(reg);
 if(r!=null){return unescape(r[2]);}
 else{return null;}
}

$(document).ready(function(){
 var Userid=URL_GetData("UID");
 if(Userid==null ||Userid.toString().length<1){
  return alert("参数错误！\n请从正确途径进入此页！");
 }
 getUserData(Userid);
});
</script>