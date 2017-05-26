<?php
session_start();
session_destroy();

$url="Login.php";

$re_file=isset($_GET['re_file'])?$_GET['re_file']:"";
$re_action=isset($_GET['re_action'])?$_GET['re_action']:"";
if($re_file!="" && $re_action!=""){
  $url=$url."?re_file=".$re_file."&re_action=".$re_action;
}

header("Location: $url");
?>