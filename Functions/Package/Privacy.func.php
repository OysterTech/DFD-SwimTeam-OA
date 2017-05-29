<?php

/**
* -----------------------------------------
* @name PHP公用函数库 3 系统隐私函数
* @copyright 版权所有：小生蚝 <master@xshgzs.com>
* @create 创建时间：2016-08-28
* @modify 最后修改时间：2017-05-25
* -----------------------------------------
*/


/**
* -------------------------------------
* getRanPW 获取随机密码
* -------------------------------------
* @param Array (明文密码,salt值,密文密码)
* -------------------------------------
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


/**
* -------------------------------------
* encryptPW 密码加密函数
* -------------------------------------
* @param STR  需要加密的密码（明文）
* @param STR  salt值
* -------------------------------------
* @return STR 加密后的字符串
* -------------------------------------
*/
function encryptPW($Password,$salt)
{
  $Password=md5($Password);
  $Password=base64_encode($salt.$Password);
  $Password=sha1($Password);
  return $Password;
}


/**
* -------------------------------------
* AddLog 新增操作记录
* -------------------------------------
* @param Obj  PDO数据库连接对象
* @param STR  操作记录类型
* @param STR  操作记录内容
* @param STR  操作用户
* -------------------------------------
*/
function AddLog($dbcon,$LogType,$LogContent,$User){
  if(strlen($LogType)>6){
    toAlertDie("F304-LTT-LG");
  }
  
  $sql="INSERT INTO sys_log(LogType,LogContent,User) VALUES(?,?,?)";
  $rs=PDOQuery($dbcon,$sql,[$LogType,$LogContent,$User],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR]);
  
  if($rs[1]==1){
    return true;
  }else{
    toAlertDie("F304-IST-F");
  }
}


function emptyCache($dbcon,$Suffix)
{
  $TableName="cache_".$Suffix;
  $time=time();
  $sql="DELETE FROM $TableName WHERE ExpTime<$time";
  $rs=PDOQuery($dbcon,$sql,[],[]);
  return $rs[1];
}


function delCache($dbcon,$Suffix,$SessionID,$UserID)
{
  $TableName="cache_".$Suffix;
  $sql="DELETE FROM $TableName WHERE SessionID=? AND UserID=?";
  $rs=PDOQuery($dbcon,$sql,[$SessionID,$UserID],[PDO::PARAM_STR,PDO::PARAM_STR]);
  return $rs[1];
}