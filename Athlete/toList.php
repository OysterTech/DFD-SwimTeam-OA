<?php
$list=PDOQuery($dbcon,"SELECT * FROM athlete_list",[],[]);
$total=sizeof($list[0]);

// 分页代码[Begin]
$Page=isset($_GET['Page'])?$_GET['Page']:"1";
$PageSize=isset($_GET['PageSize'])?$_GET['PageSize']:"20";
$TotalPage=ceil($total/$PageSize);
$Begin=($Page-1)*$PageSize;
$Limit=$Page*$PageSize;

if($Page>$TotalPage && $TotalPage!=0){
 header("Location: $nowURL");
}

if($Limit>$total){$Limit=$total;}
// 分页代码[End]


?>

<center>
  <h1>运动员列表</h1><hr>
  <?php
  echo "<h2>第{$Page}页 / 共{$TotalPage}页</h2>";
  echo "<h3>共 <font color=red>{$total}</font> 个运动员</h3>";
  ?>
</center>
<hr>
<table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">
<tr>
  <th>真名</th>
  <th>性别</th>
  <th>班别</th>
  <th>手机号</th>
  <th>操作</th>
</tr>
<?php
  for($i=$Begin;$i<$Limit;$i++){
    $AthID=$list[0][$i]['AthID'];
    $RealName=$list[0][$i]['RealName'];
    $Sex=$list[0][$i]['Sex'];
    $SchoolGrade=$list[0][$i]['SchoolGrade'];
    $SchoolClass=$list[0][$i]['SchoolClass'];
    $Phone=$list[0][$i]['Phone'];
    $oprURL=makeOprBtn("info","Athlete","EditData.php",[["AthID",$AthID]],"编辑");
        $SchoolGrade_CN=showCNNum($SchoolGrade);
?>
<tr>
  <td><?php echo $RealName; ?></td>
  <td><?php echo $Sex; ?></td>
  <td><?php echo $SchoolGrade_CN."年".$SchoolClass."班"; ?></td>
  <td><?php echo $Phone; ?></td>
  <td><?php echo $oprURL; ?> <button class="btn btn-primary" onclick='toViewAthDataDetail("<?php echo $AthID; ?>")'>详细</button></td>
</tr>
<?php } ?>
</table>

<!-- 分页功能@选择页码[Begin] -->
<center><nav>
 <ul class="pagination"> 
  <?php
  if($Page-1>0){
    $Previous=$Page-1;
  ?>
  <li>
   <a href="<?php echo $NowURL."?page=$Previous"; ?>" aria-label="Previous"> <span aria-hidden="true">&laquo;</span></a>
  </li>
  <?php } ?>
  <?php
  for($j=1;$j<=$TotalPage;$j++){
   if($j==$Page){
    echo "<li class='disabled'><a>$j</a></li>";
   }else{
    echo "<li><a href='$NowURL?page=$j'>$j</a></li>";
   }
  }
  ?>
  <?php
  if($Page+1<=$TotalPage){
    $next=$Page+1;
  ?>
  <li>
   <a href="<?php echo $NowURL."?page=$next"; ?>" aria-label="Next"> <span aria-hidden="true">&raquo;</span></a>
  </li>
  <?php } ?>
 </ul>
</nav></center>
<!-- 分页功能@选择页码[End] -->

<script>
function toViewAthDataDetail(AthID){
 $.ajax({
  url:"Functions/Api/getAthleteData.php",
  data:{"AthID":AthID},
  type:"post",
  dataType:"json",
  error:function(e){
    alert("数据传输出错！\n"+ JSON.stringify(e));
    console.log(e);
  },
  success:function(got){
    for(i in got[0]){
      if(i==="RealName"){
        $('#RealName').html(got[0][i]);
      }else if(i==="Phone"){
        $('#Phone').html(got[0][i]);
      }else if(i==="YearGroup"){
        $('#YearGroup').html(got[0][i]);
      }else if(i==="IDCard"){
        $('#IDCard').html(got[0][i]);
      }else if(i==="Sex"){
        $("#Sex").html(got[0][i]);
      }else if(i==="IDCardType"){
        IDCardType=got[0][i];
      }else if(i==="SchoolGrade"){
        SchoolGrade=showCNNum(got[0][i]);
      }else if(i==="SchoolClass"){
        SchoolClass=got[0][i];
      }else{
        continue;
      }
    }
    showClass=SchoolGrade+"年("+SchoolClass+")班";
    $("#SchoolClass").html(showClass);
    
    if(IDCardType=="1") IDCardType="大陆二代身份证";
    else if(IDCardType=="2") IDCardType="香港居民身份证";
    else if(IDCardType=="3") IDCardType="护照";
    $("#IDCardType").html(IDCardType);
    $("#myModal").modal("show");
  }
 });
}
</script>

<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" id="ModalTitle">运动员详细资料</h3>
      </div>
      <div class="modal-body">
        <table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">
          <tr>
            <th>姓名</th>
            <td><p id="RealName"></p></td>
          </tr>
          <tr>
            <th>性别</th>
            <td><p id="Sex"></p></td>
          </tr>
          <tr>
            <th>手机号</th>
            <td><p id="Phone"></p></td>
          </tr>
          <tr>
            <th>班别</th>
            <td><p id="SchoolClass"></p></td>
          </tr>
          <tr>
            <th>年龄组</th>
            <td><p id="YearGroup"></p></td>
          </tr>
          <tr>
            <th>证件号</th>
            <td><p id="IDCard"></p></td>
          </tr>
          <tr>
            <th>证件类型</th>
            <td><p id="IDCardType"></p></td>
          </tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">&lt; 返回</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->