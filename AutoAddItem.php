<?php
include("Functions/PDOConn.php");

$sql="INSERT INTO item_list(YearGroup,ItemName) VALUES ";
$items=array("50米蝶泳","50米仰泳","50米蛙泳","50米自由泳","100米蝶泳","100米仰泳","100米蛙泳","100米自由泳","200米自由泳","400米自由泳","4x50米自由泳接力","4x50米混合泳接力");

if(isset($_POST) && $_POST){
  $begin=$_POST['begin'];
  $end=$_POST['end'];
  for($i=$begin;$i<=$end;$i++){
    foreach($items as $v){
      $sql.='("'.$i.'","'.$v.'"),';
    }
  }
  
  $sql=substr($sql,0,strlen($sql)-1);
  echo $sql.=";";
  $rs=PDOQuery($dbcon,$sql,[],[]);
  var_dump($rs);
}
?>

<meta name="viewport" content="width=device-width, initial-scale=1">

<form method="post">
  <input type=number name=begin>
  ~
  <input type=number name=end>
  <br>
  <input type=submit value="确 认 新 增">
</form>