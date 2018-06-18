<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="../favicon.ico">
  <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
  <script type="text/javascript" src="https://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>
  <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="../res/js/utils.js"></script>
  <script type="text/javascript" src="../res/js/idCard.js"></script>
  <title>运动员注册 / 东风东游泳队报名系统</title>
</head>

<body>
<br>
<div class="well col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center col-xs-10 col-xs-offset-1">
  <img src="../res/img/back.png" style="position:absolute;wIDth:24px;top:17px;left:5%;cursor:pointer" onclick="history.back()" aria-label="返回">
  <h3>运 动 员 注 册</h3><br>
  
  <div class="input-group">
    <span class="input-group-addon">用户名</span>
    <input class="form-control" ID="UserName" autocomplete="off" onkeyup="if(event.keyCode==13)$('#Password')[0].focus();">
  </div>
  <div class="input-group">
    <span class="input-group-addon">密码</span>
    <input type="password" class="form-control" ID="Password" autocomplete="off" onkeyup="if(event.keyCode==13)$('#vrf_Password')[0].focus();">
  </div>
  <div class="input-group">
    <span class="input-group-addon">确认密码</span>
    <input type="password" class="form-control" ID="vrf_Password" onkeyup="if(event.keyCode==13)$('#RealName')[0].focus();">
  </div>

  <hr>

  <div class="input-group">
    <span class="input-group-addon">运动员真名</span>
    <input class="form-control" ID="RealName" autocomplete="off" onkeyup="if(event.keyCode==13)$('#Phone')[0].focus();">
  </div>
  <div class="input-group">
    <span class="input-group-addon">手机号</span>
    <input type="text" class="form-control" name="Phone" id="Phone" onkeyup="if(event.keyCode==13 || this.value.length==11){$('#IDCard')[0].focus();}">
  </div>
      
  <hr>
  
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
  <div class="input-group">
    <span class="input-group-addon">证件号</span>
    <input type="text" class="form-control" name="IDCard" id="IDCard" onkeyup="if(event.keyCode==13 || this.value.length==18 || ($('#IDCardType').val()=='2' && this.value.length>=8)){setYearGroupAndSex();}">
  </div>
  <div class="input-group">
    <span class="input-group-addon">出生年份(4位数字)</span>
    <input type="text" class="form-control" name="YearGroup" id="YearGroup" placeholder="例:2018" onfocus="setYearGroupAndSex()" disabled>
  </div>
  <div class="input-group">
    <span class="input-group-addon">性别</span>
    <select name="Sex" id="Sex" class="form-control" onchange="$('#SchoolGrade')[0].focus();" disabled>
      <option selected="true" disabled>---请选择性别---</option>
      <option value="男">▲ 男性</option>
      <option disabled>——————————</option>
      <option value="女">▲ 女性</option>
    </select>      
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
  </div>
  <div class="input-group">
    <span class="input-group-addon">班别</span>
    <select name="SchoolClass" id="SchoolClass" class="form-control">
    <?php for($i=1;$i<=13;$i++){ ?>
      <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
    <?php } ?>
    </select>
  </div>
  <hr>
  <button class="btn btn-success" style="width:100%" onclick="toReg()"> 注 册</button>
</div>

<hr>

<div class="container">
<p style="text-align:center;font-weight:bolder;font-size:19px;line-height:26px;">
  &copy; 生蚝科技 2014-<?php echo date("Y"); ?><br>
  All Rights Reserved.<br><br>
</p>
</div>

<script>
var ChnReg = new RegExp("[\\u4E00-\\u9FFF]+","g");
var LetterReg = /^[A-Za-z]*$/;
var LNReg = /^[A-Za-z0-9]*$/;

function selectIDCardType(val){
  if(val=="1"){
    $('#YearGroup').attr('disabled','disabled');
    $('#Sex').attr('disabled','disabled');
    $('#IDCard')[0].focus();
  }else if(val=="2"){
    $('#YearGroup').removeAttr('disabled');
    $('#Sex').removeAttr('disabled');
    
    $("#tips").html("输入香港身份证号时，证件号括号内的[数字/A]请直接随前面一起输入，无须输入括号。<br><br>如遇无法通过证件号校验的情况，请联系管理员！");
    $("#myModal").modal("show");
  }else if(val=="3"){
    $('#YearGroup').removeAttr('disabled');
    $('#Sex').removeAttr('disabled');
    $('#IDCard')[0].focus();
  }
}

function setYearGroupAndSex(){
  IDCard=$("#IDCard").val();
  IDCardType=$("#IDCardType").val();
  
  // 根据证件类型设置年龄组和性别
  if(IDCardType=="1"){
    // 二代身份证，自动设置
    if(checkValidIDCard(IDCard)==false){
      alert("身份证号校验失败！");
      return false;
    }
    
    YearGroup=IDCard.substr(6,4);
    Sex=IDCard.substr(16,1);
    
    // 设置性别
    if(Sex%2==0){
      $("#Sex").val("女");
    }else if(Sex%2==1){
      $("#Sex").val("男");
    }
    
    $("#YearGroup").val(YearGroup);
    $("SchoolGrade").focus();
    return true;
  }else{
    // 其他证件，手动选择
    
    if(IDCardType==2 && checkValidHKID(IDCard)==false){
      alert("香港身份证号校验失败！");
      return false;
    }
    
    $("#IDCard").val(IDCard.toUpperCase());
    $("#YearGroup").removeAttr("disabled");
    $("#Sex").removeAttr("disabled");
    $("#YearGroup").focus();
    return true;
  }
}

function toReg(){
  lockScreen();
  $("input").attr("disabled","disabled");

  name=$("#UserName").val();
  RealName=$("#RealName").val();
  pw=$("#Password").val();
  vrf_pw=$("#vrf_Password").val();
  Sex=$("#Sex").val();
  Phone=$("#Phone").val();
  IDCard=$("#IDCard").val();
  IDCardType=$("#IDCardType").val();
  YearGroup=$("#YearGroup").val();
  SchoolGrade=$("#SchoolGrade").val();
  SchoolClass=$("#SchoolClass").val();
  SchoolGrade_CN=showCNNum(SchoolGrade);

  if(name==""){
    showModalErr("请输入用户名！");
    return false;
  }
  if(name.length<6){
    showModalErr("用户名长度必须大于6位！<br>请重新输入！");
    return false;
  }
  if(ChnReg.test(name)){
    showModalErr("用户名不得含有汉字！<br>请重新输入！");
    return false;
  }
  if(!LetterReg.test(name.substr(0,1))){
    showModalErr("用户名第1位必须为字母！<br>请重新输入！");
    return false;
  }
  if(!LNReg.test(name)){
    showModalErr("用户名只能含有字母和数字！<br>请重新输入！");
    return false;
  }
  if(RealName==""){
    showModalErr("请输入真名！");
    return false;
  }
  if(isChn(RealName)==0 || RealName.length<2){
    showModalErr("您的真实姓名有误！<br>请重新输入！");
    return false;
  }
  if(pw==""){
    showModalErr("请输入密码！");
    return false;
  }
  if(pw.length<6){
    showModalErr("密码长度必须大于6位！<br>请重新输入！");
    return false;
  }
  if(vrf_pw==""){
    showModalErr("请再次输入密码！");
    return false;
  }
  if(vrf_pw != pw){
    showModalErr("两次输入的密码不相同！<br>请重新输入！");
    return false;
  }
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
  if(isChn(IDCard)!=0){
    showModalErr("证件号有误！");
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
  if(IDCardType==1 && checkValidIDCard(IDCard)==false){
    showModalErr("证件号校验失败！");
    return false;
  }else if(IDCardType==2 && checkValidHKID(IDCard)==false){
    showModalErr("证件号校验失败！");
    return false;
  }
    
  $.ajax({
    url:"toReg.php",
    type:"post",
    data:{"Name":name,"Password":pw,"RealName":RealName,"Sex":Sex,"Phone":Phone,"IDCard":IDCard,"IDCardType":IDCardType,"SchoolGrade":SchoolGrade,"SchoolClass":SchoolClass,"YearGroup":YearGroup},
    error:function(e){
      alert(JSON.stringify(e));
      console.log(JSON.stringify(e));
      $("#tips").html("服务器错误！");
      unlockScreen();
      unlockInput(4);
      $("#myModal").modal('show');
      return false;
    },
    success:function(got){
      if(got=="1"){
        alert("注册成功！\n\n用户名："+name+"\n姓名："+RealName+"\n手机："+Phone+"\n班级："+SchoolGrade_CN+"年("+SchoolClass+")班");
        window.location.href="../User/Login.php";
      }else if(got=="HaveUser"){
        showModalErr("此用户名已存在！请更换用户名！");
        return false;
      }else if(got=="NoRole"){
        showModalErr("不存在运动员角色！请联系管理员！<br>联系方式：<a href='../View/ContactAdmin.php'>点此查看</a>");
        return false;
      }else if(got=="HaveIDCard"){
        showModalErr("此身份证已被注册！<br>如有疑问，请联系管理员！<br>联系方式：<a href='../View/ContactAdmin.php'>点此查看</a>");
        return false;
      }else if(got=="InsertErr1"){
        showModalErr("新增用户失败！请联系管理员！<br>联系方式：<a href='../View/ContactAdmin.php'>点此查看</a>");
        return false;
      }else if(got=="InsertErr2"){
        showModalErr("新增运动员失败！请联系管理员！<br>联系方式：<a href='../View/ContactAdmin.php'>点此查看</a>");
        return false;
      }else{
        showModalErr("系统错误！<br><br>请提交错误码给管理员：<br>"+got+"<br>联系方式：<a href='../View/ContactAdmin.php'>点此查看</a>");
        return false;
      }
    }  
  });
}

function showModalErr(content){
  $("#tips").html(content);
  unlockScreen();
  $("#myModal").modal('show');
  unlockInput(8);
}

function unlockInput(total){
  for(i=0;i<total;i++){
    $("input")[i].disabled=0;
  }
}

function lockScreen(){
$('body').append(
  '<div ID="lockContent" style="left:50%; margin-left:-20px; top:50%; margin-top:-20px; position:fixed; _position:absolute; height:"+h+"px; wIDth: "+w+"px; z-index: 201; overflow: hIDden;">'+
  '<div class="nodata"><i class="fa fa-circle-o-notch fa-spin fa-5x fa-fw"></i></div>'+
  '</div>'+
  '<div ID="lockScreen" style="background: #000; opacity: 0.2; filter:alpha(opacity=20); width: 100%; height: 100%; z-index: 200; position:fixed; _position:absolute; top:0; left:0;">'+
  '</div>'
  );
}

function unlockScreen(){
  $('#lockScreen').remove();
  $('#lockContent').remove();
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