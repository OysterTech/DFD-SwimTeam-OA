<?php
$sql="SELECT * FROM athlete_list";
$list=PDOQuery($dbcon,$sql,[],[]);
$total=sizeof($list[0]);
?>

<center>
  <h1>运动员列表</h1>
</center>

<hr>

<table id="table" class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">
<thead>
<tr>
  <th>真名</th>
  <th>班别</th>
  <th>手机号</th>
  <th>操作</th>
</tr>
</thead>

<tbody>
<?php
  for($i=0;$i<$total;$i++){
    $AthID=$list[0][$i]['AthID'];
    $RealName=$list[0][$i]['RealName'];
    $Sex=$list[0][$i]['Sex'];
    $YearGroup=$list[0][$i]['YearGroup'];
    $SchoolGrade=$list[0][$i]['SchoolGrade'];
    $SchoolClass=$list[0][$i]['SchoolClass'];
    $Phone=$list[0][$i]['Phone'];
    $oprURL=makeOprBtn("编辑","success","Athlete","EditAthProfile.php",[["AthID",$AthID]]);
    $SchoolGrade_CN=showCNNum($SchoolGrade);
?>
<tr>
  <td><?php echo $RealName; ?></td>
  <td><?php echo $YearGroup."<br>".$SchoolGrade_CN."(".$SchoolClass.")"; ?></td>
  <td><a href="tel:<?php echo $Phone; ?>"><?php echo $Phone; ?></a></td>
  <td><?php echo $oprURL; ?> <button class="btn btn-primary" onclick='toViewAthDataDetail("<?php echo $AthID; ?>")'>详细</button></td>
</tr>
<?php } ?>
</tbody>
</table>

<script>
window.onload=function(){
	$('#table').DataTable({
		"pageLength":100,
		"order":[[1,'desc']],
		"columnDefs":[{
			"targets":[3],
			"orderable": false
		}]
	});
};

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
