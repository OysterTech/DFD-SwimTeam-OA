<?php
if(GetSess(Prefix."isAthlete")==1){
  $AthID=GetSess(Prefix."AthID");
}else{
  $AthID=$_GET['AthID'];
}
?>

<input type="hidden" id="AthID" value="<?php echo $AthID; ?>">

<div class="well col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center col-xs-10 col-xs-offset-1">
  <img src="res/img/back.png" style="position:absolute;width:24px;top:17px;left:5%;cursor:pointer" onclick="history.back()" aria-label="返回" >
  <h3>编辑运动员资料</h3><br>
  <div class="alert alert-danger alert-dismissible" role="alert">
    注意：在身份证加密过程中，请勿刷新页面！
  </div>
  <div class="col-md-offset-2" style="line-height:12px;">
    <div class="input-group">
      <span class="input-group-addon">真实姓名</span>
      <input type="text" class="form-control" name="RealName" id="RealName" onkeyup="if(event.keyCode==13){$('#Phone')[0].focus();}">
      <span class="input-group-addon" id="forgot">&lt;</span>
    </div>
    <div class="input-group">
      <span class="input-group-addon">手机号</span>
      <input type="text" class="form-control" name="Phone" id="Phone" onkeyup="if(event.keyCode==13 || this.value.length==11){$('#IDCardType')[0].focus();}">
      <span class="input-group-addon" id="forgot">&lt;</span>
    </div>
    <div class="input-group">
      <span class="input-group-addon">性别</span>
      <select name="Sex" id="Sex" class="form-control" required disabled>
        <option selected=true disabled>---请选择性别---</option>
        <option value="男">▲ 男性</option>
        <option disabled>——————————</option>
        <option value="女">▲ 女性</option>
      </select>      
      <span class="input-group-addon" id="forgot">&lt;</span>
    </div>    

    <hr>

    <div class="input-group">
      <span class="input-group-addon">证件类型</span>
      <select name="IDCardType" id="IDCardType" class="form-control" required>
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
      <input type="text" class="form-control" name="YearGroup" id="YearGroup" disabled>
      <span class="input-group-addon" id="forgot">&lt;</span>
    </div>
      
    <hr>

    <div class="input-group">
      <span class="input-group-addon">年级</span>
      <select name="SchoolGrade" id="SchoolGrade" class="form-control" required>
        <option selected="true" disabled>---请选择年级---</option>
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
      <select name="SchoolClass" id="SchoolClass" class="form-control" required>
      <option selected="true" disabled>---请选择班别---</option>
      <?php for($i=1;$i<=13;$i++){ ?>
        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
      <?php } ?>
      </select>
      <span class="input-group-addon">&lt;</span>
    </div>

    <hr>

    <button type="button" class="btn btn-success" style="width:100%" onclick="saveAthleteData()">确 认 修 改</button>
  </div>
</div>

<script>
var AthID=$("#AthID").val();

$(document).ready(function(){
  lockScreen();
  if(AthID==null ||AthID.toString().length<1){
    alert("参数错误！\n请从正确途径进入此页！");
    history.go(-1);
  }
  getUserData(AthID);
});

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

function getUserData(AthID){
 $.ajax({
  url:"Functions/Api/getAthleteData.php",
  data:{AthID:AthID},
  type:"post",
  dataType:"json",
  error:function(e){
    alert("数据传输出错！\n"+ JSON.stringify(e));
    console.log(e);
    unlockScreen();
  },
  success:function(got){
    for(i in got[0]){
      if(i==="RealName"){
        $('#RealName').val(got[0][i]);
      }else if(i==="Phone"){
        $('#Phone').val(got[0][i]);
      }else if(i==="YearGroup"){
        $('#YearGroup').val(got[0][i]);
      }else if(i==="IDCard"){
        $('#IDCard').val(got[0][i]);
      }else if(i==="Sex"){
        $("#Sex option[value='"+got[0][i]+"']").attr("selected","true");
      }else if(i==="IDCardType"){
        $("#IDCardType option[value='"+got[0][i]+"']").attr("selected","true");
      }else if(i==="SchoolGrade"){
        $("#SchoolGrade option[value='"+got[0][i]+"']").attr("selected","true");
      }else if(i==="SchoolClass"){
        $("#SchoolClass option[value='"+got[0][i]+"']").attr("selected","true");
      }else{
        continue;
      }
    }
    unlockScreen();
  }
 });
}


function saveAthleteData(){
	lockScreen();
  $("input").attr("disabled",true);
  $("select").attr("disabled",true);
  
  RealName=$("#RealName").val();
  Sex=$("#Sex").val();
  Phone=$("#Phone").val();
  IDCard=$("#IDCard").val();
  IDCardType=$("#IDCardType").val();
  YearGroup=$("#YearGroup").val();
  SchoolGrade=$("#SchoolGrade").val();
  SchoolClass=$("#SchoolClass").val();
  SchoolGrade_CN=showCNNum(SchoolGrade);
  
  if(Sex=="" || IDCardType=="" || SchoolGrade=="" || SchoolClass==""){
    alert("请填写所有资料！");
    return false;
  }
  
  $.ajax({
    url:"Athlete/toSaveAthleteData.php",
    type:"post",
    data:{"AthID":AthID,"RealName":RealName,"Sex":Sex,"Phone":Phone,"YearGroup":YearGroup,"IDCard":IDCard,"IDCardType":IDCardType,"SchoolGrade":SchoolGrade,"SchoolClass":SchoolClass},
    error:function(e){
      alert("数据传输出错！\n"+ JSON.stringify(e));
      console.log(e);
    },
    success:function(got){
      if(got=="1"){
        alert("修改成功！\n\n姓名："+RealName+"\n手机："+Phone+"\n班级："+SchoolGrade_CN+"年("+SchoolClass+")班");
        history.go(-1);
      }else{
      	alert("修改失败！\n\nTips：是否没有修改任何信息呢？");
      	unlockScreen();
      	$("input").removeAttr("disabled");
      	$("select").removeAttr("disabled");
      }
    }
  });
}
</script>