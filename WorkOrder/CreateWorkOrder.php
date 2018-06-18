<!-- ▼ iCheck ▼ -->
<link href="res/css/iCheck/red.css" rel="stylesheet">
<script src="res/js/iCheck.min.js"></script>
<!-- ▲ iCheck ▲ -->

<input type="hidden" id="Modules">

<div class="well col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center">
  <img src="res/img/back.png" style="position:absolute;width:24px;top:17px;left:5%;cursor:pointer" onclick="history.back()" aria-label="返回">
  <h3>提 交 新 工 单</h3><br>
  <div class="input-group">
    <span class="input-group-addon"><font color="red">*</font> 工单类型</span>
    <select class="form-control" id="Type">
      <option value="" selected disabled>--- 请选择工单类型 ---</option>
      <option value="1">1. 建议</option>
      <option value="2">2. Bug反馈</option>
      <option value="3">3. 投诉</option>
      <option value="4">4. 提问</option>
      <option value="5">5. 其它</option>
    </select>
  </div>
  <br>
  <div class="input-group">
    <span class="input-group-addon"><font color="red">*</font> 选择模块</span>
    <div id="Modules_Button">
      <button class="btn btn-primary form-control" style="width:100%" onclick="readytoChooseModule()">点 击 选 择 模 块</button>
    </div>
    <div id="Modules_Show" style="display:none;">
      <input id="Modules_ShowInput" class="form-control" onclick="readytoChooseModule()" readonly>
    </div>
  </div>

  <hr>

  <div class="input-group">
    <span class="input-group-addon">标题</span>
    <input class="form-control" id="Title" placeholder="(不多于20字)" maxlength="20">
  </div>
  <br>
  <div class="input-group">
    <span class="input-group-addon">工<br><br>单<br><br>内<br><br>容</span>
    <textarea class="form-control" cols="10" rows="10" id="Content" placeholder="请填写工单内容......"></textarea>
  </div>

  <hr>
  <div class="alert alert-success alert-dismissible" role="alert">
    我们会直接在工单下回复<br>
    也会同时通过以下方式联系您<br>
    请您认真如实填写，谢谢！
  </div>
  <hr>
  
  <div class="input-group">
    <span class="input-group-addon"><font color="red">*</font> 联系方式</span>
    <select id="ReplyMethod" class="form-control" onchange="$('#ReplyUserInfo').focus();">
      <option value="" selected disabled>--- 请选择联系方式 ---</option>
      <option value="1">1. 邮箱 Email</option>
      <!--option value="2">2. QQ</option-->
    </select>
  </div>
  <div class="input-group">
    <span class="input-group-addon"><font color="red">*</font> 联系号码</span>
    <input type="email" class="form-control" id="ReplyUserInfo">
  </div>

  <hr>
  
  <input type="button" class="btn btn-success" value="提 交 工 单" onclick='CreateWorkOrder()' style="width:98%">
</div>


<script>
$(document).ready(function(){
  $('input').iCheck({
    checkboxClass: 'icheckbox_square-red',
    radioClass: 'iradio_square-red',
    increaseArea: '0%'
  });
});


function readytoChooseModule(){
  $("#chooseModule").modal('show');
}


function toChooseModule(){
  Modules="";// 给系统处理的
  Modules_Chn="";// 给用户查看的

  $('input[id="Module"]:checked').each(function(){
    Modules += $(this).val() + ",";
    Modules_Chn += $(this).attr("ChnName") + ",";
  });

  if(Modules==""){
    $("#tips").html("请选择需要反馈的应用模块！");
    $("#chooseModule").modal('hide');
    $("#TipsModal").modal("show");
    return false;
  }

  Modules=Modules.substr(0,Modules.length-1);
  Modules_Chn=Modules_Chn.substr(0,Modules_Chn.length-1);

  $("#Modules").val(Modules);
  $("#Modules_ShowInput").val(Modules_Chn);
  $("#Modules_Button").attr("style","display:none;");
  $("#Modules_Show").removeAttr("style");
  $("#chooseModule").modal('hide');
  $("#Content").focus();
}


function toWorkOrderDetail(){
  OrderID=$("#Show_OrderID").html();
  window.location.href='index.php?file=WorkOrder&action=WorkOrderDetail.php&OrderID='+OrderID;
}


function CreateWorkOrder(){
  lockScreen();
  Modules=$("#Modules").val();
  Type=$("#Type").val();
  Title=$("#Title").val();
  Content=$("#Content").val();
  ReplyMethod=$("#ReplyMethod").val();
  ReplyUserInfo=$("#ReplyUserInfo").val();
  
  if(Type==null){
    unlockScreen();
    $("#tips").html("请选择工单类型！");
    $("#TipsModal").modal("show");
    $("#Type").focus();
    return false;
  }
  if(Modules==""){
    unlockScreen();
    $("#tips").html("请选择需要反馈的应用模块！");
    $("#TipsModal").modal("show");
    return false;
  }
  if(Content==""){
    unlockScreen();
    $("#tips").html("请输入工单内容！");
    $("#TipsModal").modal("show");
    $("#Content").focus();
    return false;
  }
  if(Title==""){
    unlockScreen();
    $("#tips").html("请输入工单标题！");
    $("#TipsModal").modal("show");
    $("#Title").focus();
    return false;
  }
  if(Title.length>20){
    unlockScreen();
    $("#tips").html("工单标题不允许超过20个字！");
    $("#TipsModal").modal("show");
    $("#Title").focus();
    return false;
  }
  if(ReplyMethod==null){
    unlockScreen();
    $("#tips").html("请选择联系方式！");
    $("#TipsModal").modal("show");
    $("#ReplyMethod").focus();
    return false;
  }
  if(ReplyUserInfo==""){
    unlockScreen();
    $("#tips").html("请输入联系号码！");
    $("#TipsModal").modal("show");
    $("#ReplyUserInfo").focus();
    return false;
  }

  $.ajax({
    url:"WorkOrder/toCreateWorkOrder.php",
    type:"post",
    data:{"Type":Type,"Modules":Modules,"Title":Title,"Content":Content,"ReplyMethod":ReplyMethod,"ReplyUserInfo":ReplyUserInfo},
    error:function(e){
      alert(JSON.stringify(e));
      console.log(JSON.stringify(e));
      unlockScreen();
      $("#tips").html("系统错误！");
      $("#TipsModal").modal('show');
      return false;
    },
    success:function(got){
      Data=got.split("|");
      if(Data[0]=="1"){
        unlockScreen();
        
        OrderID=Data[1];
        OrderTime=Data[2];
        
        $("#Show_OrderID").html(OrderID);
        $("#Show_OrderTime").html(OrderTime);
        $("#Show_ReplyMethod").html("邮箱 Email");
        $("#Show_ReplyUserInfo").html(ReplyUserInfo);
        $("#OKModal").modal('show');
      }else{
        unlockScreen();
        $("#tips").html("系统错误！<br>错误内容："+got);
        $("#TipsModal").modal('show');
        return false;
      }
    }
  });
}
</script>


<div class="modal fade" id="chooseModule">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" id="ModalTitle">选择模块</h3>
      </div>
      <div class="modal-body" style="color:green;font-size:16;font-weight:bolder;">
        <input type="checkbox" id="Module" value="GlobalNotice" ChnName="公告"> 公 告&nbsp;&nbsp;
        <input type="checkbox" id="Module" value="Enroll" ChnName="报名"> 报 名&nbsp;&nbsp;
        <input type="checkbox" id="Module" value="Games" ChnName="赛事"> 赛 事<br><br>
        
        <input type="checkbox" id="Module" value="Statistics" ChnName="统计"> 统 计&nbsp;&nbsp;
        <input type="checkbox" id="Module" value="GamesNotice" ChnName="规程"> 规 程&nbsp;&nbsp;
        <input type="checkbox" id="Module" value="WorkOrder" ChnName="工单"> 工 单<br><br>
        
        <input type="checkbox" id="Module" value="Personal" ChnName="个人中心"> 个 人 中 心&nbsp;&nbsp;&nbsp;
        <input type="checkbox" id="Module" value="UI" ChnName="系统界面"> 系 统 界 面<br><br>
        
        <input type="checkbox" id="Module" value="Other" ChnName="其它"> 其 它
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="toChooseModule()">确 定 &gt;</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="TipsModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" id="ModalTitle">温馨提示</h3>
      </div>
      <div class="modal-body">
        <font color="red" style="font-weight:bolder;font-size:26;text-align:center;">
          <p id="tips"></p>
        </font>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">返回 &gt;</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="OKModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" id="ModalTitle">提交成功</h3>
      </div>
      <div class="modal-body">
        <font color="green" style="font-weight:bolder;font-size:26;text-align:center;">
          <p id="tips">提交成功！<br>请耐心等待客服的回复！</p>
        </font>
        <table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">
          <tr>
            <th>工单编号</th>
            <td><p id="Show_OrderID"></p></td>
          </tr>
          <tr>
            <th>提交时间</th>
            <td><p id="Show_OrderTime"></p></td>
          </tr>
          <tr>
            <th>联系方式</th>
            <td><p id="Show_ReplyMethod"></p></td>
          </tr>
          <tr>
            <th>联系号码</th>
            <td><p id="Show_ReplyUserInfo"></p></td>
          </tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="toWorkOrderDetail()">查看工单</button> <button type="button" class="btn btn-success" onclick="window.location.href='index.php';">回首页</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->