<?php
/**
* ---------------------------------------
* @name 生蚝比赛报名系统
* @user 广州市越秀区东风东路小学
* @copyright 版权所有：小生蚝 <master@xshgzs.com>
* @create 系统创建时间：2017-04-08
* @modify 最后修改时间：2017-06-16
* ---------------------------------------
*/

require_once("Functions/PDOConn.php");
require_once("Functions/PublicFunc.php");

define("GlobalSetName","GlobalSettings.json");
$GB_Sets=new Settings(GlobalSetName);
$title=$GB_Sets->G("Title",2,"System");

$Query=explode("&",$_SERVER['QUERY_STRING']);
// 去除页码的参数
foreach($Query as $Key=>$Value){
  if(substr($Value,0,4)=="Page"){
    unset($Query[$Key]);
  }
}
$Query=implode("&",$Query);
$nowURL=$_SERVER['PHP_SELF'].'?'.$Query;
unset($Query);

?>

<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="生蚝科技 Oyster Tech">
  <meta name="keyword" content="东风东,东风东路小学,东风东游泳队,生蚝科技,东风东游泳队报名系统">
  
  <link rel="stylesheet" href="res/css/demo.css" type="text/css">
  <link rel="stylesheet" href="res/css/Notification.css" type="text/css">
  <link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
  <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.bootcss.com/zTree.v3/3.5.28/css/zTreeStyle/zTreeStyle.min.css" type="text/css">
  
  <script src="https://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>
  <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdn.bootcss.com/zTree.v3/3.5.28/js/jquery.ztree.core.min.js"></script>
  <script type="text/javascript" src="https://cdn.bootcss.com/zTree.v3/3.5.28/js/jquery.ztree.excheck.min.js"></script>
  <script type="text/javascript" src="https://cdn.bootcss.com/zTree.v3/3.5.28/js/jquery.ztree.exedit.min.js"></script>
  <script src="res/js/utils.js"></script>
  <script src="res/js/Notification.js"></script>
  
  <title><?php echo $title; ?></title>
  	
  <style>
    th{font-weight:bolder;text-align:center;}
    body{padding-top:70px;}
  </style>
  
  <script>
    // 关闭所有input的自动填充功能
    $("input").attr("autocomplete","off");
  </script>
</head>

<body>

<?php

$Userid=GetSess("SOA_Userid");
$RealName=GetSess("SOA_RealName");
$Roleid=GetSess("SOA_Roleid");
$RoleName=GetSess("SOA_RoleName");

//待加载页面的参数 + 参数过滤
$file=@$_GET['file']==''?'View':$_GET['file'];
$action=@$_GET['action']==''?'Index.php':$_GET['action'];
$file=TextFilter($file);
$action=TextFilter($action);

//获取当前页面的ID
$NowMenuid_rs=PDOQuery($dbcon,"SELECT * FROM sys_menu WHERE PageFile=? AND PageDOS=?",[$file,$action],[PDO::PARAM_STR,PDO::PARAM_STR]);
$NowMenuid=@$NowMenuid_rs[0][0]['Menuid'];

//所有允许权限的Array Session
$AllPurv=GetSess("AllPurv");

if($AllPurv==null){
  //获取当前角色的所有权限ID
 	$AllPurv_rs=PDOQuery($dbcon,"SELECT * FROM role_purview WHERE Roleid=?",[$Roleid],[PDO::PARAM_INT]);
 	$TotalPurv=sizeof($AllPurv_rs[0]);
	
	//循环添加给所有允许权限的Array Session
	for($i=0;$i<$TotalPurv;$i++){
	  $_SESSION['AllPurv'][$i]=$AllPurv_rs[0][$i]['Purvid'];
	}
}

require_once("Functions/ChkLogged.php");

// 引入页面文件
include("Functions/ShowNav.php");
$includeFileName=$file.'/'.$action;

echo '<div class="container text-center">';
$includeStatus=include($includeFileName);
echo '</div>';

if($includeStatus==false){
  $ErrTips="";
  $ErrTips.='<center>';
  $ErrTips.='<h1>404</h1>';
  $ErrTips.="<h3>$includeFileName</h3>";
  $ErrTips.='<hr>';
  $ErrTips.='<p style="font-weight:bolder;font-size:18;line-height:23px;">';
  $ErrTips.="&copy; 生蚝科技 2014-".date("Y");
  $ErrTips.='</p>';
  $ErrTips.='</center>';
  die($ErrTips);
}

// 脚部版权文件
include("footer.php");
?>

<!-- ▼ 警示框 ▼ -->
<div id="dm-notif"></div>
<!-- ▲ 警示框 ▲ -->