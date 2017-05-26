<?php
/**
* -----------------------------------------
* @name 小生蚝角色权限系统 PHP公用函数库
* @copyright 版权所有：小生蚝 <master@xshgzs.com>
* @create 创建时间：2016-09-16
* @modify 最后修改时间：2016-12-04
* -----------------------------------------
*/

/* Base Settings */
SESSION_START();


/* Require Package <Showing> */
require_once("Package/Show.func.php");
/* Require Package <Session> */
require_once("Package/Session.func.php");
/* Require Package <User Privacy> */
require_once("Package/Privacy.func.php");
/* Require Class <Global Settings> */
require_once("Package/Settings.class.php");



/**
* ------------------------------
* toAlertDie 弹框并die
* ------------------------------
* @param String 自定义错误码
* @param String 可选，自定义提示内容
* ------------------------------
**/
function toAlertDie($ErrorNo,$Tips="",$isInScript="")
{
 if($isInScript=="Ajax"){
  $Alerting=$ErrorNo."\n".$Tips;
 }else if($isInScript==0 || $isInScript==""){
 $Alerting='<script>alert("Oops！系统处理出错了！\n\n错误码：'.$ErrorNo.'\n'.$Tips.'");</script>';
 }else if($isInScript==1){
  $Alerting='alert("Oops！系统处理出错了！\n\n错误码：'.$ErrorNo.'\n'.$Tips.'");';
 } 
 die($Alerting.$ErrorNo);
}


/**
* ------------------------------
* ErrCodedie 页面显示文字并die
* ------------------------------
* @param String 自定义错误码
* ------------------------------
**/
function ErrCodedie($ErrorCode)
{
  die('<center><h1>'.$ErrorCode.'</h1><hr><p style="font-weight:bolder;font-size:18;line-height:23px;">&copy; 生蚝科技 2014-2017</p></center>');
}


/**
* ------------------------------
* TextFilter 过滤字符（留下数字和字母）
* ------------------------------
* @param String 需要过滤的字符串
* ------------------------------
* @return String 过滤后的字符串
* ------------------------------
**/
function TextFilter($str)
{
  $w="qwertyuiopasdfghjklzxcvbnm";
  $n="1234567890";
  $all=$w.$n.".";
  $length=strlen($str);
  
  for($i=0;$i<$length;$i++){
    if(stripos($all,$str[$i])===false){
      $str[$i]="";
    }
  }
  
  return $str;
}
?>