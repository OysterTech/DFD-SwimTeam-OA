<?php
$nowRealName=getSess(Prefix."RealName");

$list=PDOQuery($dbcon,"SELECT * FROM workorder_list",[],[]);
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
  <h1>工单列表</h1><hr>
  <?php
  echo "<h2>第{$Page}页 / 共{$TotalPage}页</h2>";
  echo "<h3>共 <font color=red>{$total}</font> 张工单</h3>";
  ?>
</center>
<hr>

<table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">
<tr>
  <th>工单ID</th>
  <th>标题</th>
  <th>状态</th>
  <th>最后处理时间</th>
  <th>查看</th>
</tr>

<?php
for($i=$Begin;$i<$Limit;$i++){
  $OrderID=$list[0][$i]['OrderID'];
  $Title=$list[0][$i]['Title'];
  $Status=$list[0][$i]['Status'];
  $OrderTime=$list[0][$i]['OrderTime'];
  $ReplyTime=$list[0][$i]['ReplyTime'];
  
  if($Status==1) $LastTime=$OrderTime;
  else $LastTime=$ReplyTime;
  
  $oprURL=makeOprBtn("详细","success","WorkOrder","WorkOrderDetail.php",[["OrderID",$OrderID]]);
  
  switch($Status){
    case "0":
      $Status="";
      $Status.='<font color="red">';
      $Status.='关闭';
      $Status.="</font>";
      break;
    case "1":
      $Status="";
      $Status.='<font color="blue">';
      $Status.='待处理';
      $Status.="</font>";
      break;
    case "2":
      $Status="";
      $Status.='<font color="green">';
      $Status.='待评价';
      $Status.="</font>";
      break;
    default:
      break;
  }
?>

<tr>
  <td><?php echo $OrderID; ?></td>
  <td><?php echo $Title; ?></td>
  <td><?php echo $Status; ?></td>
  <td><?php echo $LastTime; ?></td>
  <td><?php echo $oprURL; ?></td>
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
   <a href="<?php echo $nowURL."&Page=$Previous"; ?>" aria-label="Previous"> <span aria-hidden="true">&laquo;</span></a>
  </li>
  <?php } ?>
  <?php
  for($j=1;$j<=$TotalPage;$j++){
   if($j==$Page){
    echo "<li class='disabled'><a>$j</a></li>";
   }else{
    echo "<li><a href='$nowURL&Page=$j'>$j</a></li>";
   }
  }
  ?>
  <?php
  if($Page+1<=$TotalPage){
    $next=$Page+1;
  ?>
  <li>
   <a href="<?php echo $nowURL."&Page=$next"; ?>" aria-label="Next"> <span aria-hidden="true">&raquo;</span></a>
  </li>
  <?php } ?>
 </ul>
</nav></center>
<!-- 分页功能@选择页码[End] -->