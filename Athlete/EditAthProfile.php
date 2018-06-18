<?php
if(GetSess(Prefix."isAthlete")==1){
  $AthID=GetSess(Prefix."AthID");
}else{
  $AthID=$_GET['AthID'];
}
?>

<input type="hidden" id="AthID" value="<?php echo $AthID; ?>">

<div class="well text-center col-xs-12">
  <img src="res/img/back.png" style="position:absolute;width:24px;top:17px;left:5%;cursor:pointer" onclick="history.back()" aria-label="返回" >
  <h3>编辑运动员资料</h3><br>
  <div class="alert alert-danger alert-dismissible" role="alert">
  注意：在身份证加密过程中，请勿刷新页面！
  </div>
  <div class="input-group">
    <span class="input-group-addon">真实姓名</span>
    <input type="text" class="form-control" name="RealName" id="RealName" onkeyup="if(event.keyCode==13){$('#Phone')[0].focus();}">
  </div>
  <div class="input-group">
    <span class="input-group-addon">手机号</span>
    <input type="text" class="form-control" name="Phone" id="Phone" onkeyup="if(event.keyCode==13 || this.value.length==11){$('#IDCardType')[0].focus();}">
  </div>
  <div class="input-group">
    <span class="input-group-addon">性别</span>
    <select name="Sex" id="Sex" class="form-control" <?php if(GetSess(Prefix."isAthlete")==1){echo 'disabled';}?>>
    <option selected="true" disabled>---请选择性别---</option>
    <option value="男">▲ 男性</option>
    <option disabled>——————————</option>
    <option value="女">▲ 女性</option>
    </select>    
  </div>  

  <hr>

  <div class="input-group">
    <span class="input-group-addon">证件类型</span>
    <select name="IDCardType" id="IDCardType" class="form-control" onchange="selectIDCardType(this.value);" required>
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
    <span class="input-group-addon">出生年份</span>
    <input type="text" class="form-control" name="YearGroup" id="YearGroup" disabled>
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
  </div>
  <div class="input-group">
    <span class="input-group-addon">班别</span>
    <select name="SchoolClass" id="SchoolClass" class="form-control" required>
    <option selected="true" disabled>---请选择班别---</option>
    <?php for($i=1;$i<=13;$i++){ ?>
    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
    <?php } ?>
    </select>
  </div>

  <hr>

  <button type="button" class="btn btn-success" style="width:100%" onclick="saveAthleteData()">确 认 修 改</button>
</div>

<script>
var AthID=$("#AthID").val();
var ChnReg = new RegExp("[\\u4E00-\\u9FFF]+","g");
var LetterReg = /^[A-Za-z]*$/;
var LNReg = /^[A-Za-z0-9]*$/;

window.onload=function(){
  lockScreen();
  if(AthID==null ||AthID.toString().length<1){
    alert("参数错误！\n请从正确途径进入此页！");
    history.go(-1);
  }
  getUserData(AthID);
};

function selectIDCardType(val){
  if(val=="1"){
    $('#YearGroup').attr('disabled','disabled');
    $('#IDCard')[0].focus();
  }else if(val=="2"){
    $('#YearGroup').removeAttr('disabled');
    
    $("#tips").html("输入香港身份证号时，证件号括号内的[数字/A]请直接随前面一起输入，无须输入括号。<br><br>如遇无法通过证件号校验的情况，请联系管理员！");
    $("#myModal").modal("show");
  }else if(val=="3"){
    $('#YearGroup').removeAttr('disabled');
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
    $("#YearGroup").focus();
    return true;
  }
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

function getUserData(AthID){
  $.ajax({
    url:"Functions/Api/getAthleteData.php",
    data:{"AthID":AthID},
    type:"post",
    dataType:"json",
    error:function(e){
      alert("数据传输出错！\n"+ JSON.stringify(e));
      console.log(e);
      unlockScreen();
    },
    success:function(got){
    if(got[0]["Code"]=="200"){
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
    }else{
      alert("运动员数据获取失败！请在首页联系管理员！");
      history.go(-1);
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
  
  if(Sex=="" || IDCard=="" || SchoolGrade=="" || SchoolClass==""){
    alert("请填写所有资料！");
    unlockScreen();
    $("input").removeAttr("disabled");
    $("select").removeAttr("disabled");
    return false;
  }
  if(IDCardType==1 && checkValidIDCard(IDCard)==false){
    alert("证件号校验失败！");
    unlockScreen();
    $("input").removeAttr("disabled");
    $("select").removeAttr("disabled");
    return false;
  }else if(IDCardType==2 && checkValidHKID(IDCard)==false){
    alert("证件号校验失败！");
    unlockScreen();
    $("input").removeAttr("disabled");
    $("select").removeAttr("disabled");
    return false;
  }

  $.ajax({
  url:"Athlete/toSaveAthleteData.php",
  type:"post",
  data:{"AthID":AthID,"RealName":RealName,"Sex":Sex,"Phone":Phone,"YearGroup":YearGroup,"IDCard":IDCard,"IDCardType":IDCardType,"SchoolGrade":SchoolGrade,"SchoolClass":SchoolClass},
  error:function(e){
    alert("数据传输出错！\n"+ JSON.stringify(e));
    unlockScreen();
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
