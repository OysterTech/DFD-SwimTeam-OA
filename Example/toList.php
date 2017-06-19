<?php
$list=PDOQuery($dbcon,"SELECT * FROM role_list",[],[]);
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
  <h1>角色列表</h1><hr>
  <?php
  echo "<h2>第{$Page}页 / 共{$TotalPage}页</h2>";
  echo "<h3>共 <font color=red>{$total}</font> 种角色</h3>";
  ?>
</center>
<hr>

<table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;">
<tr>
  <th>角色名</th>
  <th>角色描述</th>
  <th>操作</th>
</tr>

<?php
for($i=0;$i<$total;$i++){
  $RID=$list[0][$i]['Roleid'];
  $name=$list[0][$i]['RoleName'];
  $brief=$list[0][$i]['Brief'];
  $isSuper=$list[0][$i]['isSuper']; 
$OprURL_del=makeOprBtn("删除","danger","Role","toDelRole.php",[["RID",$RID]]);
?>

<tr>
  <td><?php echo $name; ?></td>
  <td><?php echo $brief; ?></td>
</tr>
<?php } ?>
</table>