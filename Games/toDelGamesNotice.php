<?php

if(isset($_GET['NoticeID']) && $_GET['NoticeID']){
  $NoticeID=$_GET['NoticeID'];
  $rs=PDOQuery($dbcon,"UPDATE games_notice SET isDelete=1 WHERE NoticeID=?",[$NoticeID],[PDO::PARAM_INT]);
  die("<script>history.go(-1);</script>");
}

?>