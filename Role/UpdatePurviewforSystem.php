<?php
$Roleid=isset($_GET['RID'])?$_GET['RID']:"";
if($Roleid=="") ErrCodedie("500-GTDA");
$Insert_count=0;

// 验证当前角色是否为系统内置角色
$vfy_sql="SELECT * FROM role_list WHERE Roleid=?";
$vfy_rs=PDOQuery($dbcon,$vfy_sql,[$Roleid],[PDO::PARAM_INT]);
if($vfy_rs[0][0]['isSuper']!="1") ErrCodedie("500-NS");

// 删除当前角色所有权限
$del_sql="DELETE FROM role_purview WHERE Roleid=?";
$del_rs=PDOQuery($dbcon,$del_sql,[$Roleid],[PDO::PARAM_INT]);
if($del_rs[1]<0) ErrCodedie("500-DTFL");

// 查询所有菜单的ID
$select_sql="SELECT * FROM sys_menu";
$select_rs=PDOQuery($dbcon,$select_sql,[],[]);
$totalMenu=count($select_rs[0]);

// 循环添加权限
foreach($select_rs[0] as $key=>$value){
  $Menuid=$select_rs[0][$key]['Menuid'];
  $Insert_sql="INSERT INTO role_purview(Roleid,Purvid) VALUES(?,?)";
  $Insert_rs=PDOQuery($dbcon,$Insert_sql,[$Roleid,$Menuid],[PDO::PARAM_INT,PDO::PARAM_INT]);
  
  // 成功添加
  if($Insert_rs[1]==1) $Insert_count++;
}

$rtnURL="index.php?file=Role&action=toList.php";
    
if($Insert_count != $totalMenu){
  echo '<script>alert("更新权限出错！\n\n共有菜单数量：'.$totalMenu.'\n已添加权限数量：'.$Insert_count.'");window.location.href="'.$rtnURL.'";</script>';
}else{
  echo '<script>alert("成功给系统角色更新'.$Insert_count.'项权限！");window.location.href="'.$rtnURL.'";</script>';
}
?>