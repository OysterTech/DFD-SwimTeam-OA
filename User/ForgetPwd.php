<?php
require_once("../Functions/PDOConn.php");
require_once("../Functions/PublicFunc.php");

$GB_Sets=new Settings("../GlobalSettings.json");
define("Prefix",$GB_Sets->G("SessionPrefix",2,"System"));

if(isset($_POST) && $_POST){
  $RealName=$_POST['RealName'];
  $Phone=$_POST['Phone'];
  $IDCardType=$_POST['IDCardType'];
  $IDCard=$_POST['IDCard'];

  $Info_rs=PDOQuery($dbcon,"SELECT UserID FROM athlete_list WHERE RealName=? AND Phone=? AND IDCardType=? AND IDCard=?",[$RealName,$Phone,$IDCardType,$IDCard],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR]);
  if($Info_rs[1]!=1){
    die('<script>alert("无此运动员！");history.go(-1);</script>');
  }else{
    $UserID=$Info_rs[0][0]['UserID'];
  }

  $sql="SELECT UserName FROM sys_user WHERE UserID=?";
  $rs=PDOQuery($dbcon,$sql,[$UserID],[PDO::PARAM_STR]);
  if($rs[1]!=1){
    die('<script>alert("无此运动员的用户资料！");history.go(-1);</script>');
  }else{
    $UserName=$rs[0][0]['UserName'];
  }

  setSess(Prefix."FGPW_isVerify","1");
  setSess(Prefix."FGPW_UserID",$UserID);
  setSess(Prefix."FGPW_UserName",$UserName);
  setSess(Prefix."FGPW_RealName",$RealName);

  die('<script>window.location.href="ForgetPwd_2.php";</script>');
}
?>

<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="../favicon.ico">
  <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
  <script type="text/javascript" src="https://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>
  <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="../res/js/utils.js"></script>
  <script type="text/javascript" src="../res/js/idCard.js"></script>
  <title>忘记用户名密码 / 东风东游泳队报名系统</title>
</head>

<body>
<br>
<form method="post">
<div class="well col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center col-xs-10 col-xs-offset-1">
  <img src="../res/img/back.png" style="position:absolute;wIDth:24px;top:17px;left:5%;cursor:pointer" onclick="history.back()" aria-label="返回">
  <h3>运 动 员 忘 记 用 户 名 / 密 码</h3><br>
    <div class="alert alert-warning alert-dismissible" role="alert">
    请按提示输入资料以认证身份！<br>
    感谢您的配合！<br>
    <font color="red">* 领队忘记密码请联系管理员</font>
  </div>
  <div class="input-group">
    <span class="input-group-addon">运动员真名</span>
    <input class="form-control" name="RealName">
  </div>
  <br>
  <div class="input-group">
    <span class="input-group-addon">手机号</span>
    <input type="number" class="form-control" name="Phone">
  </div>
  <br>
  <div class="input-group">
  <span class="input-group-addon">证件类型</span>
  <select name="IDCardType" id="IDCardType" class="form-control" onchange="selectIDCardType(this.value);">
    <option value="1" selected="true">▲ 中国二代身份证</option>
    <option disabled>——————————</option>
    <option value="2">▲ 香港居民身份证</option>
    <option disabled>——————————</option>
    <option value="3">▲ 护照</option>
  </select>
  </div>
  <br>
  <div class="input-group">
    <span class="input-group-addon">证件号</span>
    <input type="text" class="form-control" name="IDCard" ID="IDCard" onkeyup="if(event.keyCode==13 || this.value.length==18 || ($('#IDCardType').val()=='2' && this.value.length>=8)){checkIDCard();}">
  </div>
  <hr>
  <input type="button" class="btn btn-primary" value="取 消 操 作" onclick='window.close();' style="width:48%"> <input id="submitBtn" type="submit" class="btn btn-success" style="width:48%;" value="下 一 步"> 
</div>
</form>

<script>
window.onload=function(){
  $("input").attr("autocomplete","off");
}

function selectIDCardType(val){
  if(val=="1"){
    $('#IDCard')[0].focus();
  }else if(val=="2"){    
    $("#tips").html("输入香港身份证号时，证件号括号内的[数字/A]请直接随前面一起输入，无须输入括号。<br><br>如遇无法通过证件号校验的情况，请联系管理员！");
    $("#myModal").modal("show");
  }else if(val=="3"){
    $('#IDCard')[0].focus();
  }
}

function checkIDCard(){
  IDCard=$("#IDCard").val();
  IDCardType=$("#IDCardType").val();

  if(IDCardType=="1" && checkValidIDCard(IDCard)==false){
    alert("身份证号校验失败！");
    lockSubmitButton();
    return false;
  }else if(IDCardType==2 && checkValidHKID(IDCard)==false){
    alert("香港身份证号校验失败！");
    lockSubmitButton();
    return false;
  }else{
    unlockSubmitButton();
    return true;
  }
}

function lockSubmitButton(){
  $("#submitBtn").attr('disabled','disabled');
}

function unlockSubmitButton(){
  $("#submitBtn").removeAttr('disabled');
}
</script>

<div class="modal fade" ID="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hIDden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" ID="ModalTitle">温馨提示</h3>
      </div>
      <div class="modal-body">
        <form method="post">
          <font color="red" style="font-weight:bolder;font-size:26;text-align:center;">
            <p id="tips"></p>
          </font>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">返回 &gt;</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
</html>
