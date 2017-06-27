<?php
require_once("../Functions/PDOConn.php");
require_once("../Functions/PublicFunc.php");

$SessName=array("SOA_isLogged","SOA_Userid","SOA_Roleid","SOA_RealName","SOA_RoleName","SOA_isAthlete");

if(isset($_POST) && $_POST){
  
  // 获取用户输入的数据
  $ipt_PW=$_POST['Password'];
  $ipt_UserName=$_POST['Name'];
  
  // 根据用户输入的用户名寻找对应资料
  $sql="SELECT * FROM sys_user WHERE UserName=?";
  $rs=PDOQuery($dbcon,$sql,[$ipt_UserName],[PDO::PARAM_STR]);
  
  // 无此用户
  if($rs[1]!=1){
    die();
  }

  $PW_indb=$rs[0][0]['Password'];
  $salt=$rs[0][0]['salt'];
  $Userid=$rs[0][0]['Userid'];
  $Roleid=$rs[0][0]['Roleid'];
  $RealName=$rs[0][0]['RealName'];
  $Status=$rs[0][0]['Status'];
  $originPassword=$rs[0][0]['originPassword'];
  
  // 用户被禁用
  if($Status==0){
    die("UserForbidden");
  }
  
  // 获取角色资料
  $roleinfo_sql="SELECT RoleName,isAthlete FROM role_list WHERE Roleid=?";
  $roleinfo_rs=PDOQuery($dbcon,$roleinfo_sql,[$Roleid],[PDO::PARAM_INT]);
  $RoleName=$roleinfo_rs[0][0]['RoleName'];
  $isAthlete=$roleinfo_rs[0][0]['isAthlete'];
  
  // 将数据库里的输入的密码和salt合并加密
  $ipt_PW=encryptPW($ipt_PW,$salt);
  
  if($ipt_PW != $PW_indb){
    die();
  }else{
    $SessVal=array("1",$Userid,$Roleid,$RealName,$RoleName,$isAthlete);
    
    if($isAthlete==1){
      $AthInfo_rs=PDOQuery($dbcon,"SELECT * FROM athlete_list WHERE UserID=?",[$Userid],[PDO::PARAM_STR]);
      $AthID=$AthInfo_rs[0][0]['AthID'];
      
      array_push($SessName,"SOA_AthID");
      array_push($SessVal,$AthID);
    }
    
    $Date=date("Y-m-d H:i:s");
    $rs2=PDOQuery($dbcon,"UPDATE sys_user SET LastDate=? WHERE Userid=?",[$Date,$Userid],[PDO::PARAM_STR,PDO::PARAM_INT]);
    
    SetSess($SessName,$SessVal);

    $Cache=new Cache($dbcon,"login");
    $Cache->E();
    $SessionID=session_id();
    $ExpTime=time()+600;// 10分钟后过期
    $IP=getIP();

    // 检查是否有重复登录
    $Condition[0]=array("UserID",$Userid);
    $OldCache=$Cache->G($Condition);
    if($OldCache[1]!=0){
      die("2".$OldCache[0][0]['CacheTime'].$OldCache[0][0]['IP']);
    }

    $Cache_Param=array("SessionID","UserID","RealName","ExpTime","IP");
    $Cache_Value=array($SessionID,$Userid,$RealName,$ExpTime,$IP);
    $Cache_rs=$Cache->S($Cache_Param,$Cache_Value);

    addLog($dbcon,"登录","[$RealName] 登录系统",$ipt_UserName);

    if($_POST['re_Param']!=""){
      $re_Param=$_POST['re_Param'];
      $re_Param=base64_decode($re_Param);
      die("1"."../index.php?re=1".$re_Param);
    }elseif($Status==1){
      die("1"."../index.php?file=User&action=UpdatePersonalPW.php&isFirst=1&u={$ipt_UserName}&r={$RealName}");
    }else{
      die("1"."../index.php");
    }    
  }
}
?>
