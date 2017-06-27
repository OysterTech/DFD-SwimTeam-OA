<?php
if(GetSess(Prefix."isAthlete")!=1){
  toAlertDie("500","参数错误！\\n请从正确途径进入本页面！");
}

$GamesID=isset($_GET['GamesID'])?$_GET['GamesID']:"";
$GamesName=isset($_GET['GamesName'])?$_GET['GamesName']:"";
$AthID=GetSess(Prefix."AthID");

// 判断是否已经报名
$Enroll_SQL="SELECT * FROM enroll_item WHERE GamesID=? AND AthID=?";
$Enroll_rs=PDOQuery($dbcon,$Enroll_SQL,[$GamesID,$AthID],[PDO::PARAM_STR,PDO::PARAM_STR]);
if($Enroll_rs[1]>0){
  header("Location: index.php?file=Enroll&action=ViewEnrollItem.php&GamesID=$GamesID&GamesName=$GamesName");
}

// 获取运动员资料
$AthInfo_SQL="SELECT * FROM athlete_list WHERE AthID=?";
$AthInfo_rs=PDOQuery($dbcon,$AthInfo_SQL,[$AthID],[PDO::PARAM_INT]);
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

<div class="well col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center col-xs-10 col-xs-offset-1">
  <img src="res/img/back.png" style="position:absolute;width:24px;top:17px;left:5%;cursor:pointer" onclick="history.back()" aria-label="返回" >
  <h3>确认运动员资料</h3><br>
  <div class="col-md-offset-2" style="line-height:12px;">
    <div class="input-group">
      <span class="input-group-addon">真实姓名</span>
      <input type="text" class="form-control" value="<?php echo $RealName; ?>" disabled>
      <span class="input-group-addon" id="forgot">&lt;</span>
    </div>
    <div class="input-group">
      <span class="input-group-addon">性别</span>
      <input type="text" class="form-control" value="<?php echo $Sex; ?>" disabled>
      <span class="input-group-addon" id="forgot">&lt;</span>
    </div>
    <div class="input-group">
      <span class="input-group-addon">手机号</span>
      <input type="text" class="form-control" value="<?php echo $Phone; ?>" disabled>
      <span class="input-group-addon" id="forgot">&lt;</span>
    </div>

    <hr>

    <div class="input-group">
      <span class="input-group-addon">证件类型</span>
      <input type="text" class="form-control" value="<?php echo $IDCardType;?>" disabled>
      <span class="input-group-addon" id="forgot">&lt;</span>
    </div>
    <div class="input-group">
      <span class="input-group-addon">证件号</span>
      <input type="text" class="form-control" value="<?php echo $IDCard; ?>" disabled>
      <span class="input-group-addon" id="forgot">&lt;</span>
    </div>
    <div class="input-group">
      <span class="input-group-addon">出生年份</span>
      <input type="text" class="form-control" value="<?php echo $YearGroup; ?>" disabled>
      <span class="input-group-addon" id="forgot">&lt;</span>
    </div>
      
    <hr>

    <div class="input-group">
      <span class="input-group-addon">班别</span>
      <input type="text" class="form-control" value="<?php echo $SchoolClass; ?>" disabled>
      <span class="input-group-addon">&lt;</span>
    </div>

    <hr>

    <button type="button" class="btn btn-success" style="width:100%" onclick="toEnroll()">开 始 报 名 &gt;</button>
  </div>
</div>

<script>
function toEnroll(){
  if(confirm("请确认运动员资料无误，确认后将无法修改！\n\n确认无误后点击“确定”开始报名。")){
    URL=""
       +"index.php"
       +"?file=Enroll"
       +"&action=ChooseItem.php"
       +"&GamesID=<?php echo $GamesID; ?>"
       +"&GamesName=<?php echo $GamesName; ?>";  
    window.location.href=URL;
  }else{
    return false;
  }
}
</script>