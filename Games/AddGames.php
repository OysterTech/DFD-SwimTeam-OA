<div class="alert alert-info alert-dismissible" role="alert">
  ▲ 比赛名命名规则：<font color="red" style="font-size:18px;font-weight:bolder;">20XX年越秀区/广州市比赛</font>（后面可加“选拔赛”二字）<br>
  * 因为过长会导致系统出错
</div>

<hr>

<div class="well col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center col-xs-10 col-xs-offset-1">
  <img src="res/img/back.png" style="position:absolute;width:24px;top:17px;left:5%;cursor:pointer" onclick="history.back()" aria-label="返回" >
  <h3>新 增 比 赛</h3><br>
  <div class="col-md-offset-2" style="line-height:12px;">
    <div class="input-group">
      <span class="input-group-addon">比赛名称</span>
      <select id="Name_Year" class="form-control">
        <option value="" selected="selected" disabled>请选择比赛年份</option>
        <?php
          $y=date("Y");
          $yl=$y+3;
          for($y;$y<=$yl;$y++){
        ?>
        <option value="<?php echo $y; ?>"><?php echo $y; ?>年</option>
        <?php } ?>
      </select>
      <br>
      <select id="Name_District" class="form-control">
        <option value="" selected="selected" disabled>请选择赛区</option>
        <option value="广州市">广州市</option>
        <option value="越秀区">越秀区</option>
      </select>
      <br>
      <select id="Name_Type" class="form-control" onchange="changePrivateType()">
        <option value="" selected="selected" disabled>请选择比赛类型</option>
        <option value="0">正式赛</option>
        <option value="（选拔赛）" >选拔赛</option>
      </select>
      <span class="input-group-addon" id="forgot">&lt;</span>
    </div>

    <hr>

    <div class="input-group">
      <span class="input-group-addon">结束报名<br><br>日期</span>
      <select id="EndYear" class="form-control">
        <option value="" selected="selected" disabled>请选择（年）</option>
        <?php
          $y=date("Y");
          $yl=$y+3;
          for($y;$y<=$yl;$y++){
        ?>
        <option value="<?php echo $y; ?>"><?php echo $y; ?>年</option>
        <?php } ?>
      </select>
      <select id="EndMonth" class="form-control">
        <option value="" selected="selected" disabled>请选择（月）</option>
        <?php
          for($m=1;$m<=12;$m++){
            if($m<10) $m1="0".$m;
            else $m1=$m;
        ?>
        <option value="<?php echo $m1; ?>"><?php echo $m1; ?></option>
        <?php } ?>
      </select>
      <select id="EndDay" class="form-control">
        <option value="" selected="selected" disabled>请选择（日）</option>
        <?php
          for($d=1;$d<=31;$d++){
            if($d<10) $d1="0".$d;
            else $d1=$d;
        ?>
        <option value="<?php echo $d1; ?>"><?php echo $d1; ?></option>
        <?php } ?>
      </select>
      <span class="input-group-addon" id="forgot">&lt;</span>
    </div>
    <hr>
    <div class="input-group">
      <span class="input-group-addon">限制报名</span>
      <select id="isPrivate" class="form-control">
        <option value="" selected="selected" disabled>--------</option>
        <option value="0">面向全体运动员</option>
        <option value="" disabled>--------</option>
        <option value="1">限制部分运动员</option>
      </select>
      <span class="input-group-addon" id="forgot">&lt;</span>
    </div>
    <hr>
    <button class="btn btn-success" style="width:100%" onclick="toAddGames()">确 认 新 增</button>
  </div>
</div>

<script>
function InputErrResponse(InputName,Content){
  alert(Content);
  unlockScreen();
  $("#"+InputName).focus();
}

function changePrivateType(){
  Name_Type=$("#Name_Type").val();
  if(Name_Type=="（选拔赛）"){
    $("#isPrivate").val("0");
    $("#isPrivate").attr("disabled","disabled");
  }else if(Name_Type=="0"){
   	$("#isPrivate").val("1");
   	$("#isPrivate").attr("disabled","disabled");
  }
}

function toAddGames(){
  TipsCT_i="请输入";
  TipsCT_c="请选择";
  
  lockScreen();
  GamesName=$("#GamesName").val();
  EndYear=$("#EndYear").val();
  EndMonth=$("#EndMonth").val();
  EndDay=$("#EndDay").val();
  isPrivate=$("#isPrivate").val();
  Name_Year=$("#Name_Year").val();
  Name_Type=$("#Name_Type").val();
  Name_District=$("#Name_District").val();

  if(GamesName==""){
    Tips=TipsCT_i+"比赛名称！";
    InputErrResponse("GamesName",Tips);
    return false;
  }
  if(EndYear==null){
    Tips=TipsCT_c+"比赛结束时间（年份）！";
    InputErrResponse("EndYear",Tips);
    return false;
  }
  if(EndMonth==null){
    Tips=TipsCT_c+"比赛结束时间（月份）！";
    InputErrResponse("EndMonth",Tips);
    return false;
  }
  if(EndDay==null){
    Tips=TipsCT_c+"比赛结束时间（日期）！";
    InputErrResponse("EndDay",Tips);
    return false;
  }
  if(isPrivate==null){
    Tips=TipsCT_c+"比赛报名限制情况！";
    InputErrResponse("isPrivate",Tips);
    return false;
  }
  if(Name_Year==""){
    Tips=TipsCT_c+"比赛年份！";
    InputErrResponse("Name_Year",Tips);
    return false;
  }
  if(Name_District==""){
    Tips=TipsCT_c+"赛区！";
    InputErrResponse("Name_District",Tips);
    return false;
  }
  if(Name_Type==""){
    Tips=TipsCT_c+"比赛类型！";
    InputErrResponse("Name_Type",Tips);
    return false;
  }
  if(EndMonth==2 && EndDay>29){
    Tips="比赛结束时间有误！";
    InputErrResponse("EndDay",Tips);
    return false;
  }
  
  if(Name_Type=="0"){
    Name_Type="";
  }

  GamesName=Name_Year+Name_District+"赛"+Name_Type;
  EndDate=EndYear+EndMonth+EndDay;
  
  $.ajax({
    url:"Games/toSaveGamesInfo.php",
    type:"post",
    data:{"OprType":"Add","GamesName":GamesName,"EndDate":EndDate,"isPrivate":isPrivate},
    error:function(e){
      alert(JSON.stringify(e));
      console.log(JSON.stringify(e));
    },
    success:function(got){
      if(got=="1"){
        alert("新增成功！");
        history.go(-1);
        return;
      }else if(got=="0"){
        alert("新增失败！！！\n\n请联系管理员并提交错误码！\n\n错误码：GS-A-0");
        unlockScreen();
        return;
      }else{
        alert("新增失败！！！\n\n请联系管理员并提交错误码！\n\n错误码：GS-A-"+got);
        unlockScreen();
        return;
      }
    }
  });
}
</script>