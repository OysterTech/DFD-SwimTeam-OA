<?php
$rs=PDOQuery($dbcon,"SELECT * FROM sys_menu",[],[]);
$Total=sizeof($rs[0]);
$allMenu=array();

if(isset($_GET['RID'])){
  $Purview=PDOQuery($dbcon,"SELECT * FROM role_purview WHERE Roleid=?",[$_GET['RID']],[PDO::PARAM_STR]);
  $PurviewList=$Purview[0];
}else{
  $PurviewList=array();
}

for($i=0;$i<$Total;$i++){
  $Menuid=$rs[0][$i]['Menuid'];
  $Fatherid=$rs[0][$i]['Fatherid'];
  $Name=$rs[0][$i]['Menuname'];
  $allMenu[$i]['id']=(int)$Menuid;
  $allMenu[$i]['pId']=(int)$Fatherid;
  $allMenu[$i]['name']=urlencode($Name);
  foreach($PurviewList as $Value){
    if($Value['Purvid']==$Menuid){
      $allMenu[$i]['checked']=true;
    }
  }
}

$str = urldecode(json_encode($allMenu));
echo $str;

?>