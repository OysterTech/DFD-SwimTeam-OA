<?php
session_start();
session_destroy();

$url="Login.php";

$re_Param=isset($_GET['re_Param'])?$_GET['re_Param']:"";
if($re_Param!=""){
  $url=$url."?re=1&re_Param=".$re_Param;
}

header("Location: $url");
?>