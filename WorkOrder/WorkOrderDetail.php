<?php
$OrderID=isset($_GET['OrderID'])?$_GET['OrderID']:toAlertDie("500","参数错误！\n\n请从正确方式进入本页面！");
$rs=PDOQuery($dbcon,"SELECT * FROM workorder_list WHERE OrderID=?",[$OrderID],[PDO::PARAM_INT]);
?>

<link href="https://cdn.bootcss.com/bootstrap-star-rating/4.0.2/css/star-rating.min.css" media="all" rel="stylesheet" type="text/css">
<script src="https://cdn.bootcss.com/bootstrap-star-rating/4.0.2/js/star-rating.min.js"></script>

<center>
  <h2>工单详细内容</h2>
</center>

<hr>

<table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">

<?php
$OrderID=$rs[0][0]['OrderID'];
$RealName=$rs[0][0]['CreateRealName'];
$Title=$rs[0][0]['Title'];
$Type=$rs[0][0]['Type'];
$Status=$rs[0][0]['Status'];
$OrderTime=$rs[0][0]['OrderTime'];
$ReplyTime=$rs[0][0]['ReplyTime'];
$Content=$rs[0][0]['Content'];
$ReplyUserInfo=$rs[0][0]['ReplyUserInfo'];
$ReplyRealName=$rs[0][0]['ReplyRealName'];
$ReplyContent=$rs[0][0]['ReplyContent'];
$ReplyStar=$rs[0][0]['ReplyStar'];

if($Status==1) $LastTime=$OrderTime;
else $LastTime=$ReplyTime;

if($Type=="1") $Type="建议";
elseif($Type=="2") $Type="Bug反馈";
elseif($Type=="3") $Type="投诉";
elseif($Type=="4") $Type="提问";
elseif($Type=="5") $Type="其他";

$oprURL=makeOprBtn("详细","success","WorkOrder","WorkOrderDetail.php",[["OrderID",$OrderID]]);
  
switch($Status){
  case "0":
    $showStatus="";
    $showStatus.='<font color="red">';
    $showStatus.='关闭';
    $showStatus.="</font>";
    break;
  case "1":
    $showStatus="";
    $showStatus.='<font color="blue">';
    $showStatus.='待处理';
    $showStatus.="</font>";
    break;
  case "2":
    $showStatus="";
    $showStatus.='<a style="color:green;font-weight:bolder;font-size:16;" onclick="readytoStar()">';
    $showStatus.='待评价（点击评价）';
    $showStatus.="</a>";
    break;
  default:
    break;
}
?>

<tr>
  <th>提交人姓名</th>
  <td><?php echo $RealName; ?></td>
</tr>
<tr>
  <th>联系方式</th>
  <td><?php echo $ReplyUserInfo; ?></td>
</tr>
<tr>
  <th>标题</th>
  <td><?php echo $Title; ?></td>
</tr>
<tr>
  <th>类型</th>
  <td><?php echo $Type; ?></td>
</tr>
<tr>
  <th>提交时间</th>
  <td><?php echo $OrderTime; ?></td>
</tr>
<tr>
  <th>状态</th>
  <td><?php echo $showStatus; ?></td>
</tr>
</table>

<hr>
<p style="text-align:left;font-size:17;">
  <b style="font-size:18">工单内容</b>：<?php echo $Content; ?>
</p>

<?php if($Status!=1){ ?>
  <hr>
  <table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">
    <tr>
      <th>处理人</th>
      <td><?php echo $ReplyRealName; ?></td>
    </tr>
    <tr>
      <th>处理<br>时间</th>
      <td><?php echo $ReplyTime; ?></td>
    </tr>
    <tr>
      <th>处理<br>结果</th>
      <td><?php echo $ReplyContent; ?></td>
    </tr>
    <?php if($Status==0){ ?>
    <tr>
      <th>处理评价</th>
      <td><?php echo $ReplyStar; ?></td>
    </tr>
    <?php } ?>
  </table>
<?php } ?>


<script>
function readytoStar(){
  $("#myModal").modal('show');
}

function toStar(){
  lockScreen();
  OrderID=getURLParam("OrderID");
  Star=$("#Star").val();
  $.ajax({
    url:"WorkOrder/toStarReply.php",
    type:"post",
    data:{"OrderID":OrderID,"Star":Star},
    error:function(e){
      alert("系统错误！\n错误内容："+JSON.stringify(e));
      console.log(JSON.stringify(e));
      unlockScreen();
      $("#myModal").modal('hide');
      return false;
    },
    success:function(got){
      if(got=="1"){
        alert("评价成功！感谢您对我们工作的支持！");
        window.location.href="index.php";
      }else{
        alert("系统错误！<br>错误内容："+got);
        unlockScreen();
        $("#myModal").modal('hide');
        return false;
      }
    }
  });
}
</script>

<?php if($Status==2){ ?>
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" id="ModalTitle">评价</h3>
      </div>
      <div class="modal-body">
        <font style="font-size:18">请对<font color="green" style="font-size:20;font-weight:bolder;"><?php echo $ReplyRealName; ?></font>的回复作出评价：</font><br>
        <input id="Star" type="number" class="rating" min="0" max="5" step="1" data-size="sm">
      </div>
      <div class="modal-footer">
        <button class="btn btn-success" onclick="toStar()">提 交 评 价</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php } ?>