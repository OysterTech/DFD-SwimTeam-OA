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
    
    if($_POST['re_file']!="" && $_POST['re_action']!=""){
      die("1"."../index.php?file=".$_POST['re_file']."&action=".$_POST['re_action']);
    }elseif($Status==1){
      die("1"."../index.php?file=User&action=UpdatePersonalPW.php&isFirst=1&u={$ipt_UserName}&r={$RealName}");
    }else{
      die("1"."../index.php");
    }    
  }
}
?>
