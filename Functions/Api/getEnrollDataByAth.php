<?php
include("../PublicFunc.php");
include("../PDOConn.php");

$AllItem=array();$AllAth=array();
$AllItemID=array();$AllAthID=array();
$alreadyAthID=array();
$rtn=array();
$AllEnrollAthID=array();

$GB_Sets=new Settings("../../GlobalSettings.json");
define("Prefix",$GB_Sets->G("SessionPrefix",2,"System"));

if(isset($_POST) && $_POST){
  // 获取所有项目信息
  $rs1=PDOQuery($dbcon,"SELECT * FROM item_list",[],[]);
  $total1=sizeof($rs1[0]);
  for($i=0;$i<$total1;$i++){
    array_push($AllItemID,$rs1[0][$i]['ItemID']);
    $AllItem[$i]['ItemID']=$rs1[0][$i]['ItemID'];
    $AllItem[$i]['ItemName']=$rs1[0][$i]['ItemName'];
    $AllItem[$i]['YearGroup']=$rs1[0][$i]['YearGroup'];
  }
  
  // 获取所有运动员信息
  $rs2=PDOQuery($dbcon,"SELECT * FROM athlete_list",[],[]);
  $total2=sizeof($rs2[0]);  
  for($m=0;$m<$total2;$m++){
    array_push($AllAthID,$rs2[0][$m]['AthID']);
    $SchoolGrade=showCNNum($rs2[0][$m]['SchoolGrade']);
    $AllAth[$m]['AthID']=$rs2[0][$m]['AthID'];
    $AllAth[$m]['Sex']=$rs2[0][$m]['Sex'];
    $AllAth[$m]['Phone']=$rs2[0][$m]['Phone'];
    $AllAth[$m]['IDCard']=$rs2[0][$m]['IDCard'];
    $AllAth[$m]['RealName']=$rs2[0][$m]['RealName'];
    $AllAth[$m]['SchoolGrade']=$SchoolGrade;
    $AllAth[$m]['SchoolClass']=$rs2[0][$m]['SchoolClass'];
  }
  
  // 获取请求的比赛ID
  $GamesID=$_POST['GamesID'];
  
  $rs3=PDOQuery($dbcon,"SELECT * FROM enroll_item WHERE GamesID=?",[$GamesID],[PDO::PARAM_STR]);
  $total3=sizeof($rs3[0]);
  
  // 该项比赛没有人报名
  if($total3<1){
    die("NoData");
  }
  
  for($j=0;$j<$total3;$j++){
    $AthID=$rs3[0][$j]['AthID'];
    $ItemID=$rs3[0][$j]['ItemID'];
    
    array_push($AllEnrollAthID,$AthID);
    
    // 根据运动员ID获取对应运动员信息
    if(in_array($AthID,$AllAthID)){
      // 当前运动员在所有运动员的位置
      $Loc=array_search($AthID,$AllAthID);
      // 如果该运动员是第一次出现
      if(!in_array($AthID,$alreadyAthID)){
        $rtn[$j]['AthID']=$AthID;
        $rtn[$j]['RealName']=$AllAth[$Loc]['RealName'];
        $rtn[$j]['Sex']=$AllAth[$Loc]['Sex'];
        $rtn[$j]['Phone']=$AllAth[$Loc]['Phone'];
        $rtn[$j]['IDCard']=$AllAth[$Loc]['IDCard'];
        $rtn[$j]['SchoolGrade']=$AllAth[$Loc]['SchoolGrade'];
        $rtn[$j]['SchoolClass']=$AllAth[$Loc]['SchoolClass'];
      }
    }
    
    // 根据项目ID获取对应项目信息
    if(in_array($ItemID,$AllItemID)){
      // 当前项目在所有项目的位置
      $Loc=array_search($ItemID,$AllItemID);
      // 如果当前运动员第一次出现
      if(!in_array($AthID,$alreadyAthID)){
        $rtn[$j]['ItemID']=$ItemID;
        $rtn[$j]['ItemName']=$AllItem[$Loc]['ItemName'];
        $rtn[$j]['YearGroup']=$AllItem[$Loc]['YearGroup'];
      }else{
        // 上次该运动员出现的位置
        $AthLoc=array_search($AthID,$AllEnrollAthID);
        $rtn[$AthLoc]['ItemName'].=",".$AllItem[$Loc]['ItemName'];
        $rtn[$AthLoc]['YearGroup'].=",".$AllItem[$Loc]['YearGroup'];
      }
    }
    
    // 已经就绪的运动员报名数据
    array_push($alreadyAthID,$AthID);
  }
  
  $rtn=json_encode($rtn,JSON_UNESCAPED_UNICODE);
  
  $Cache=new Cache($dbcon,"enroll_export");
  // 删除导出缓存
  $SessionID=session_id();
  $UserID=GetSess(Prefix."UserID");
  $Cache->E();
  $Cache->D($SessionID,$UserID);
  
  // 新增导出缓存
  $ExpTime=time()+1800;// 30分钟后过期
  $IP=getIP();
  $AddCache_Param=array("SessionID","UserID","Content","ExpTime","IP");
  $AddCache_Value=array($SessionID,$UserID,$rtn,$ExpTime,$IP);
  $AddCache_rs=$Cache->S($AddCache_Param,$AddCache_Value);
  
  if($AddCache_rs[1]!=1){
    die("AddCacheFailed");
  }
  
  $rtn=urldecode($rtn);
  echo $rtn;
}
?>