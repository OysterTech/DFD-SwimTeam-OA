<?php

if(isset($_POST) && $_POST){
  $GamesName=$_POST['GamesName'];
  $EndYear=$_POST['EndYear'];
  $EndMonth=$_POST['EndMonth'];
  $EndDay=$_POST['EndDay'];
  $isPrivate=$_POST['isPrivate'];
  
  if($GamesName=="" || $EndYear=="" || $EndMonth=="" || $EndDay=="" || $isPrivate==""){
    die("<script>alert('请填写所有信息！');history.go(-1);</script>");
  }
  
  $EndDate=$EndYear.$EndMonth.$EndDay;

  if($EndDate<date("Ymd")){
    $sql="UPDATE games_list SET GamesName=?,EndDate=?,isOpen='0',isPrivate=? WHERE Gamesid=?";  
  }else{
    $sql="UPDATE games_list SET GamesName=?,EndDate=?,isOpen='1',isPrivate=? WHERE Gamesid=?";
  }
  
  $rs=PDOQuery($dbcon,$sql,[$GamesName,$EndDate,$isPrivate,$Gamesid],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_INT]);

  if($rs[1]==1){
    echo "<script>alert('修改成功！');window.location.href='$rtnURL';</script>";
  }else{    
    echo "<script>alert('修改失败！！！');window.location.href='$rtnURL';</script>";
  }
}

?>