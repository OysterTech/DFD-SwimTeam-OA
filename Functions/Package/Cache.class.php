<?php

class Cache{

  public $dbcon;
  public $TableName;
  
  function __construct($dbcon,$Suffix){
    $this->dbcon=$dbcon;
    $this->TableName="cache_".$Suffix;
  }
  
  function setCache($Key,$Value){
    $Key_Total=count($Key);
    $Value_Total=count($Value);
    $sql="INSERT INTO ".$this->TableName." SET ";
    
    if($Key_Total!=$Value_Total){
      toAlertDie("C-CC-S-1");
    }
    
    for($i=0;$i<$Key_Total;$i++){
      $Key_Now=$Key[$i];
      $Value_Now=$Value[$i];
      $sql.=$Key_Now.'="'.$Value_Now.'",';
    }
    
    $sql=substr($sql,0,strlen($sql)-1);
    $rs=PDOQuery($this->dbcon,$sql,[],[]);
    
    return $rs;
  }
  
  
  function emptyCache(){
    $time=time();
    $sql="DELETE FROM ".$this->TableName." WHERE ExpTime<$time";
    $rs=PDOQuery($this->dbcon,$sql,[],[]);
    return $rs[1];
  }


  function delCache($SessionID,$UserID){
    $sql="DELETE FROM ".$this->TableName." WHERE SessionID=? AND UserID=?";
    $rs=PDOQuery($this->dbcon,$sql,[$SessionID,$UserID],[PDO::PARAM_STR,PDO::PARAM_STR]);
    return $rs[1];
  }
}