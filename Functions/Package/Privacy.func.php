<?php

/**
* -----------------------------------------
* @name PHP公用函数库 3 系统隐私函数
* @copyright 版权所有：小生蚝 <master@xshgzs.com>
* @create 创建时间：2016-08-28
* @modify 最后修改时间：2016-11-23
* -----------------------------------------
*/


function getRanPW()
{
  $salt="";$letters="";$num1="";$num2="";
  $num1=mt_rand(136,954);
  $num2=mt_rand(281,795);
  $letters=getRanSTR(2,0);
  
  /**
  * 8位随机密码组成方式：
  * 数字1x2 + 字母1 + 数字2x1 + 数字1x1 + 字母2 + 字母2x2
  */
  $pw=substr($num1,0,2).substr($letters,0,1).substr($num2,0,1).substr($num1,2).substr($letters,1).substr($num2,1);
 
  $salt=getRanSTR(8);
  $pw_indb=encryptPW($pw,$salt);
  return array($pw,$salt,$pw_indb);
}


/**
* -------------------------------------
* getRanSTR 获取随机字符串
* -------------------------------------
* @param int    欲获取的随机字符串长度
* @param 0|1|2  0:只要大写|1:只要小写|2:无限制
* -------------------------------------
*/
function getRanSTR($length,$LettersType=2)
{
  if($LettersType==0){
    $str="ZXCVBNQWERTYASDFGHJKLUPM";
  }elseif($LettersType==1){
    $str="qwertyasdfghzxcvbnupmjk";
  }else{
    $str="qwertyZXCVBNasdfghQWERTYzxcvbnASDFGHupJKLnmUPjk";
  }

  $ranstr="";
  $strlen=strlen($str)-1;
  for($i=1;$i<=$length;$i++){
    $ran=mt_rand(0,$strlen);
    $ranstr.=$str[$ran];
  }
  
  return $ranstr;
}


function encryptPW($Password,$salt)
{
  $Password=md5($Password);
  $Password=base64_encode($salt.$Password);
  $Password=sha1($Password);
  return $Password;
}
