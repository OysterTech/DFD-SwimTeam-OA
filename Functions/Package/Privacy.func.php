<?php

/**
* ----------------------------------------
* @name PHP公用函数库 3 系统隐私函数
* @copyright 版权所有：小生蚝 <master@xshgzs.com>
* @create 创建时间：2016-08-28
* @modify 最后修改时间：2017-06-06
* ----------------------------------------
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
* getRanSTR 获取随机字母串
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
* @return STR 加密后的密文
* -------------------------------------
*/
function encryptPW($Password,$Salt)
{
  // 加密后的Salt
  $Salt=md5($Salt);

  // 待处理的密文
  $AllPwd=$Password.$Salt;

  // 交换位置
  $a=substr($AllPwd,0,5);
  $b=substr($AllPwd,5,8);
  $c=substr($AllPwd,13,8);
  $d=substr($AllPwd,21,11);
  $e=substr($AllPwd,32);
  $ChangeLoc=$b.$d.$a.$c.$e;

  // 再次组合
  $Data=$Salt.$ChangeLoc;
  $Data=base64_encode($Data);

  // 返回加密后的密文
  return $Data;
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
function addLog($dbcon,$LogType,$LogContent,$User)
{
  if(strlen($LogType)>18){
    toAlertDie("F304-LTT-LG");
  }
  
  $IP=getIP();
  
  $sql="INSERT INTO sys_log(LogType,LogContent,LogUser,LogIP) VALUES(?,?,?,?)";
  $rs=PDOQuery($dbcon,$sql,[$LogType,$LogContent,$User,$IP],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR]);
  
  if($rs[1]==1){
    return true;
  }else{
    toAlertDie("F304-IST-F");
  }
}
