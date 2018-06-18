<?php
require_once("../Functions/PDOConn.php");
require_once("../Functions/PublicFunc.php");

$GB_Sets=new Settings("../GlobalSettings.json");
define("Prefix",$GB_Sets->G("SessionPrefix",2,"System"));

$SessName=array(Prefix."Reg_isReg",Prefix."Reg_UserID",Prefix."Reg_RealName",Prefix."Reg_Sign");
$Sign=getRanSTR(16);

if(isset($_POST) && $_POST){
  
  // 获取用户输入的数据
  $PW_ipt=$_POST['Password'];
  $UserName_ipt=$_POST['Name'];
  $RealName_ipt=$_POST['RealName'];
  $Sex=$_POST['Sex'];
  $Phone=$_POST['Phone'];
  $YearGroup=$_POST['YearGroup'];
  $IDCard=strtoupper($_POST['IDCard']);
  $IDCardType=$_POST['IDCardType'];
  $SchoolGrade=$_POST['SchoolGrade'];
  $SchoolClass=$_POST['SchoolClass'];

  // 密码处理
  $salt=getRanSTR(8);
  $indb_PW=encryptPW($PW_ipt,$salt);
  
  // 根据用户输入的用户名寻找对应资料
  $sql="SELECT UserID FROM sys_user WHERE UserName=?";
  $rs=PDOQuery($dbcon,$sql,[$UserName_ipt],[PDO::PARAM_STR]);
 
  // 已经存在同名用户
  if($rs[1]!=0){
    die("HaveUser");
  }

  // 判断身份证是否已被使用
  $sql1="SELECT AthID FROM athlete_list WHERE IDCard=?";
  $rs1=PDOQuery($dbcon,$sql1,[$IDCard],[PDO::PARAM_STR]);
  
  if($rs1[1]!=0){
    die("HaveIDCard");
  }
  
  // 获取运动员角色ID
  $sql2="SELECT RoleID FROM role_list WHERE isAthlete='1'";
  $rs2=PDOQuery($dbcon,$sql2,[],[]);
  
  // 没有运动员角色
  if($rs2[1]!=1){
    die("NoRole");
  }
  
  $RoleID=$rs2[0][0]['RoleID'];
  
  // 新增用户
  $sql3="INSERT INTO sys_user SET UserName=?,RealName=?,Password=?,Salt=?,RoleID=?,Status='2'";
  $rs3=PDOQuery($dbcon,$sql3,[$UserName_ipt,$RealName_ipt,$indb_PW,$salt,$RoleID],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR]);

  // 新增用户失败
  if($rs3[1]!=1){
    die("InsertErr1");
  }
  
  // 获取用户ID
  $sql4="SELECT UserID FROM sys_user WHERE UserName=?";
  $rs4=PDOQuery($dbcon,$sql4,[$UserName_ipt],[PDO::PARAM_STR]);
  $UserID=$rs4[0][0]['UserID'];

  // 新增运动员
  $sql5="INSERT INTO athlete_list SET UserID=?,RealName=?,Sex=?,Phone=?,YearGroup=?,IDCard=?,IDCardType=?,SchoolGrade=?,SchoolClass=?";
  $rs5=PDOQuery($dbcon,$sql5,[$UserID,$RealName_ipt,$Sex,$Phone,$YearGroup,$IDCard,$IDCardType,$SchoolGrade,$SchoolClass],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR]);
  
  // 新增运动员失败
  if($rs5[1]!=1){
    die("InsertErr2");
  }

  if($rs3[1]==1 && $rs5[1]==1){
    die("1");
  }else{
    die("UnknownError");
  }
}
?>