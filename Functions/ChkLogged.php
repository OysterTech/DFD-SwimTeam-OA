<?php

$url="user/Logout.php";

$re_file=isset($_GET['file'])?$_GET['file']:"";
$re_action=isset($_GET['action'])?$_GET['action']:"";
if($re_file!="" && $re_action!=""){
  $url=$url."?re_file=".$re_file."&re_action=".$re_action;
}

// 是否有登录
$isLogged=GetSess("SOA_isLogged");
if($isLogged != "1") header("Location: $url");

//如果是主页面，不做任何处理
if($file=="View" && $action=="Index.php"){}
//不是主页
elseif($AllPurv!=null){
  //如果没有页面权限
  if(!in_array($NowMenuid,$AllPurv)){
    $Chk_sql="SELECT * FROM sys_menu WHERE Menuid=?";
    $Chk_rs=PDOQuery($dbcon,$Chk_sql,[$NowMenuid],[PDO::PARAM_INT]);
    if($Chk_rs[1]==1){
      //是否为公有页面
      $isPublic=$Chk_rs[0][0]['isPublic'];
    }else{
      //是否为不公开显示的页面
      $isPublic="1";
    }
    if($isPublic=="0"){
      die('<script>alert("对不起，您暂无权限访问本页面！\nTips：可尝试刷新页面！\n\n错误码：502");history.go(-1);</script>');
    }
  }
}
?>