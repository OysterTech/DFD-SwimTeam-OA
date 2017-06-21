<?php

$url="User/Logout.php";

$re_Param="";
$Param=isset($_GET)?$_GET:"";
foreach($Param as $Key=>$Value){
  $re_Param=$re_Param."&".$Key."=".$Value;
}
$re_Param=base64_encode($re_Param);
if($re_Param!=""){
  $url=$url."?re_Param=".$re_Param;
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