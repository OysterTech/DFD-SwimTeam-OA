<?php
if(GetSess(Prefix."isAthlete")!=1){
  toAlertDie("500-Enrl-CAI-NotAth","当前用户非运动员！\\n请从正确途径进入本页面！\\n\\n如有疑问，请在首页联系管理员！");
}

$GamesID=isset($_GET['GamesID'])?$_GET['GamesID']:"";
$GamesName=isset($_GET['GamesName'])?$_GET['GamesName']:"";
$AthID=GetSess(Prefix."AthID");

// 判断是否已经报名
$Enroll_SQL="SELECT * FROM enroll_item WHERE GamesID=? AND AthID=?";
$Enroll_rs=PDOQuery($dbcon,$Enroll_SQL,[$GamesID,$AthID],[PDO::PARAM_STR,PDO::PARAM_STR]);
if($Enroll_rs[1]>0){
  die('<script>window.location.href="index.php?file=Enroll&action=ViewEnrollItem.php&GamesID='.$GamesID.'&GamesName='.$GamesName.'";</script>');
}

// 获取运动员资料
$AthInfo_SQL="SELECT * FROM athlete_list WHERE AthID=?";
$AthInfo_rs=PDOQuery($dbcon,$AthInfo_SQL,[$AthID],[PDO::PARAM_INT]);

if($AthInfo_rs[1]!=1){
  toAlertDie("500-Enrl-CAI-NoInfo","无运动员资料！\\n请从正确途径进入本页面！\\n\\n如有疑问，请在首页联系管理员！");
}

$RealName=$AthInfo_rs[0][0]['RealName'];
$Sex=$AthInfo_rs[0][0]['Sex'];
$Phone=$AthInfo_rs[0][0]['Phone'];
$YearGroup=$AthInfo_rs[0][0]['YearGroup'];
$IDCard=$AthInfo_rs[0][0]['IDCard'];
$IDCardType=$AthInfo_rs[0][0]['IDCardType'];
$SchoolGrade=$AthInfo_rs[0][0]['SchoolGrade'];
$SchoolClass=$AthInfo_rs[0][0]['SchoolClass'];

$SchoolGrade=showCNNum($SchoolGrade)."年";
$SchoolClass="(".$SchoolClass.")班";
$SchoolClass=$SchoolGrade.$SchoolClass;

switch($IDCardType){
  case "1":
    $IDCardType="大陆二代身份证";
    break;
  case "2":
    $IDCardType="香港居民身份证";
    break;
  case "3":
    $IDCardType="护照";
    break;
}

SetSess(Prefix."Ath_YearGroup",$YearGroup);
?>

<div class="well text-center col-xs-12">
  <img src="res/img/back.png" style="position:absolute;width:24px;top:17px;left:5%;cursor:pointer" onclick="history.back()" aria-label="返回" >
  <h3>确认运动员资料</h3><br>
    <div class="input-group">
      <span class="input-group-addon">真实姓名</span>
      <input type="text" class="form-control" value="<?=$RealName;?>" readonly>
    </div>
    <div class="input-group">
      <span class="input-group-addon">性别</span>
      <input type="text" class="form-control" value="<?=$Sex;?>" readonly>
    </div>
    <div class="input-group">
      <span class="input-group-addon">手机号</span>
      <input type="text" class="form-control" value="<?=$Phone;?>" readonly>
    </div>

    <hr>

    <div class="input-group">
      <span class="input-group-addon">证件类型</span>
      <input type="text" class="form-control" value="<?=$IDCardType;?>" readonly>
    </div>
    <div class="input-group">
      <span class="input-group-addon">证号</span>
      <input type="text" class="form-control" value="<?=$IDCard;?>" readonly>
    </div>
    <div class="input-group">
      <span class="input-group-addon">出生年份</span>
      <input type="text" class="form-control" value="<?=$YearGroup;?>" readonly>
    </div>
      
    <hr>

    <div class="input-group">
      <span class="input-group-addon">班别</span>
      <input type="text" class="form-control" value="<?=$SchoolClass;?>" readonly>
    </div>

    <hr>

    <a href="index.php?file=Athlete&action=EditAthProfile.php" class="btn btn-info" style="width:49%">&lt; 修 改 资 料</a> <button type="button" class="btn btn-success" style="width:49%" onclick="toEnroll()">开 始 报 名 &gt;</button>
  </div>
</div>

<script>
function toEnroll(){
  if(confirm("请确认运动员资料无误，确认后将无法修改！\n\n确认无误后点击“确定”开始报名。")){
    URL=""
       +"index.php"
       +"?file=Enroll"
       +"&action=ChooseItem.php"
       +"&GamesID=<?=$GamesID;?>"
       +"&GamesName=<?=$GamesName;?>";  
    window.location.href=URL;
  }else{
    return false;
  }
}
</script>