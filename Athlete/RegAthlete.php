<?php
require_once("../Functions/PublicFunc.php");
$Sign_Sess=GetSess(Prefix."Reg_Sign");
$Sign_URL=$_GET['Sign'];

if($Sign_Sess != $Sign_URL){
  toAlertDie("500","参数错误！\\n请从正确途径进入本页面！");
}

$RealName=GetSess(Prefix."Reg_RealName");
$UserID=GetSess(Prefix."Reg_UserID");
?>

<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<script type="text/javascript" src="https://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>
	<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="../res/js/utils.js"></script>
	<title>运动员注册 / 东风东游泳队报名系统</title>
</head>

<body>
<br>

<input type="hidden" id="UserID" value="<?php echo $UserID; ?>">

<div class="well col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center col-xs-10 col-xs-offset-1">
  <img src="../res/img/back.png" style="position:absolute;width:24px;top:17px;left:5%;cursor:pointer" onclick="history.back()" aria-label="返回">
  <h3>运 动 员 注 册</h3><br>
  <div class="alert alert-danger alert-dismissible" role="alert">
    注意：在身份证加密过程中，请勿刷新页面！
  </div>
  <div class="col-md-offset-2" style="line-height:12px;">
    <div class="input-group">
      <span class="input-group-addon">真实姓名</span>
      <input type="text" class="form-control" name="RealName" id="RealName" value="<?php echo $RealName; ?>" disabled>
      <span class="input-group-addon" id="forgot">&lt;</span>
    </div>
    <div class="input-group">
      <span class="input-group-addon">手机号</span>
      <input type="text" class="form-control" name="Phone" id="Phone" onkeyup="if(event.keyCode==13 || this.value.length==11){$('#IDCardType')[0].focus();}">
      <span class="input-group-addon" id="forgot">&lt;</span>
    </div>
    <div class="input-group">
      <span class="input-group-addon">性别</span>
      <select name="Sex" id="Sex" class="form-control" onchange="$('#IDCardType')[0].focus();">
        <option selected="true" disabled>---请选择性别---</option>
        <option value="男">▲ 男性</option>
        <option disabled>——————————</option>
        <option value="女">▲ 女性</option>
      </select>      
      <span class="input-group-addon" id="forgot">&lt;</span>
    </div>
    <hr>

    <div class="input-group">
      <span class="input-group-addon">证件类型</span>
      <select name="IDCardType" id="IDCardType" class="form-control" onchange="$('#IDCard')[0].focus();if(this.value!=1){$('#YearGroup').removeAttr('disabled');}">
        <option value="1" selected="true">▲ 中国二代身份证</option>
        <option disabled>——————————</option>
        <option value="2">▲ 香港居民身份证</option>
        <option disabled>——————————</option>
        <option value="3">▲ 护照</option>
      </select>
      <span class="input-group-addon" id="forgot">&lt;</span>
    </div>
    <div class="input-group">
      <span class="input-group-addon">证件号</span>
      <input type="text" class="form-control" name="IDCard" id="IDCard" onkeyup="if(event.keyCode==13 || this.value.length==18){getYearGroup();}" onblur="getYearGroup();">
      <span class="input-group-addon" id="forgot">&lt;</span>
    </div>
    <div class="input-group">
      <span class="input-group-addon">出生年份</span>
      <input type="text" class="form-control" name="YearGroup" id="YearGroup" onfocus="getYearGroup()" disabled>
      <span class="input-group-addon" id="forgot">&lt;</span>
    </div>
      
    <hr>

    <div class="input-group">
      <span class="input-group-addon">年级</span>
      <select name="SchoolGrade" id="SchoolGrade" class="form-control" onchange="$('#SchoolClass')[0].focus();">
        <option value="1">一年级</option>
        <option value="2">二年级</option>
        <option value="3">三年级</option>
        <option value="4">四年级</option>
        <option value="5">五年级</option>
        <option value="6">六年级</option>
      </select>
      <span class="input-group-addon">&lt;</span>
    </div>
    <div class="input-group">
      <span class="input-group-addon">班别</span>
      <select name="SchoolClass" id="SchoolClass" class="form-control">
      <?php for($i=1;$i<=13;$i++){ ?>
        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
      <?php } ?>
      </select>
      <span class="input-group-addon">&lt;</span>
    </div>

    <hr>

    <button type="button" class="btn btn-success" style="width:100%" onclick="toRegAthlete()">确 认 注 册</button>
  </div>
</div>

<script>
window.onload=function(){
  $("#Phone").focus();
}

function getYearGroup(){
  IDCard=$("#IDCard").val();
  IDCardType=$("#IDCardType").val();
  
  if(IDCardType=="1"){
    YearGroup=IDCard.substr(6,4);
    $("#YearGroup").val(YearGroup);
    $("SchoolGrade").focus();
    return true;
  }else{
    $("#YearGroup").removeAttr("disabled");
    $("#YearGroup").focus();
    return true;
  }
}

function toRegAthlete(){
  lockScreen();
  UserID=$("#UserID").val();
  RealName=$("#RealName").val();
  Sex=$("#Sex").val();
  Phone=$("#Phone").val();
  IDCard=$("#IDCard").val();
  IDCardType=$("#IDCardType").val();
  YearGroup=$("#YearGroup").val();
  SchoolGrade=$("#SchoolGrade").val();
  SchoolClass=$("#SchoolClass").val();
  SchoolGrade_CN=showCNNum(SchoolGrade);
  
  if(Sex==""){
    showModalErr("请选择性别！");
    return false;
  }
  if(Phone=="" || Phone.length!=11){
    showModalErr("请输入正确的手机号码！");
    return false;
  }
  if(IDCard==""){
    showModalErr("请输入证件号！");
    return false;
  }
  if(IDCardType==""){
    showModalErr("请选择证件类型！");
    return false;
  }
  if(SchoolGrade==""){
    showModalErr("请输入运动员所在年级！");
    return false;
  }
  if(SchoolClass==""){
    showModalErr("请输入运动员所在班别！");
    return false;
  }
  if(YearGroup==""){
    showModalErr("请输入运动员出生年份！");
    return false;
  }
	if(IDCardType==1 && IDCard.length!=18){
    showModalErr("证件号位数有误！");
    return false;
  }

	$.ajax({
    url:"toRegAthlete.php",
    type:"post",
    data:{"UserID":UserID,"RealName":RealName,"Sex":Sex,"Phone":Phone,"IDCard":IDCard,"IDCardType":IDCardType,"SchoolGrade":SchoolGrade,"SchoolClass":SchoolClass,"YearGroup":YearGroup},
    error:function(e){
      alert(JSON.stringify(e));
      console.log(JSON.stringify(e));
      $("#tips").html("系统错误！");
		    unlockScreen();
		    $("#myModal").modal('show');
		    return false;
    },
    success:function(got){
      if(got=="1"){
        alert("注册成功！\n\n姓名："+RealName+"\n手机："+Phone+"\n班级："+SchoolGrade_CN+"年("+SchoolClass+")班");
        window.location.href="../User/Login.php";
      }else if(got=="HaveIDCard"){
        showModalErr("此身份证已被注册！");
		      return false;
      }else if(got=="InsertErr"){
        showModalErr("数据新增失败！请联系管理员！");
		      return false;
      }else{
        showModalErr("服务器错误！\n\n请提交错误码给管理员：\n"+got);
		      return false;
      }
    }  
  });
}

function showModalErr(content){
  $("#tips").html(content);
  unlockScreen();
  $("#myModal").modal('show');
}

function unlockInput(total){
  for(i=0;i<total;i++){
    $("input")[i].disabled=0;
  }
}

function lockScreen(){
$('body').append(
  '<div id="lockContent" style="opacity: 0.2; filter:alpha(opacity=20); width: 100%; height: 100%; z-index: 9999; position:fixed; _position:absolute; top:0; left:0;left:50%; margin-left:-20px; top:50%; margin-top:-20px;">'+
  '<div><img src="../res/img/loading.gif"></img></div>'+
  '</div>'+
  '<div id="lockScreen" style="background: #000; opacity: 0.2; filter:alpha(opacity=20); width: 100%; height: 100%; z-index: 9999; position:fixed; _position:absolute; top:0; left:0;">'+
  '</div>'
  );
}

function unlockScreen(){
  $('#lockScreen').remove();
  $('#lockContent').remove();
}
</script>


<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" id="ModalTitle">温馨提示</h3>
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