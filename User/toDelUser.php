<?php
if(isset($_GET['UID']) && $_GET['UID']){
  $uid=$_GET['UID'];  
  $sql="DELETE FROM sys_user WHERE Userid=?";
  if(isset($_POST['sure']) && $_POST['sure']){
    $rs=PDOQuery($dbcon,$sql,[$uid],[PDO::PARAM_INT]);
    echo "<script>alert('删除用户成功！');window.location.href='index.php?file=User&action=toList.php';</script>";
  }
}else{
  ErrCodedie("500");
}
?>

<form method="post">
<center>
  <input type="submit" class="btn btn-danger" value="确认删除" name="sure">
  <input type="button" class="btn btn-success" value="取消操作" onclick="window.location.href='index.php?file=User&action=toList.php';">
</center>
</form>