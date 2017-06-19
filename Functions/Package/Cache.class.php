<?php

class Cache{

  public $dbcon;
  public $TableName;
  
  function __construct($dbcon,$Suffix){
    $this->dbcon=$dbcon;
    $this->TableName="cache_".$Suffix;
  }
  
  function S($Key,$Value){
    $Key_Total=count($Key);
    $Value_Total=count($Value);
    $sql="INSERT INTO ".$this->TableName." SET ";
    
    if($Key_Total!=$Value_Total){
      toAlertDie("C-CC-1-TT");
    }
    
    for($i=0;$i<$Key_Total;$i++){
      $Key_Now=$Key[$i];
      $Value_Now=$Value[$i];
      $sql.=$Key_Now."='".$Value_Now."',";
    }
    
    $sql=substr($sql,0,strlen($sql)-1);
    $rs=PDOQuery($this->dbcon,$sql,[],[]);
    
    return $rs;
  }
  
  
  function G($condition){  
    $sql="SELECT * FROM ".$this->TableName." WHERE ";
    
    if(is_array($condition)==false){
      toAlertDie("C-CC-2-INA");
    }
    
    foreach($condition as $Value){
      $sql.=$Value[0]."='".$Value[1]."' AND ";
    }
    
    $sql=substr($sql,0,strlen($sql)-5);
    $rs=PDOQuery($this->dbcon,$sql,[],[]);
    
    return $rs;
  }
  
  
  function E(){
    $time=time();
    $sql="DELETE FROM ".$this->TableName." WHERE ExpTime<$time";
    $rs=PDOQuery($this->dbcon,$sql,[],[]);
    return $rs;
  }


  function D($SessionID,$UserID){
    $sql="DELETE FROM ".$this->TableName." WHERE SessionID=? AND UserID=?";
    $rs=PDOQuery($this->dbcon,$sql,[$SessionID,$UserID],[PDO::PARAM_STR,PDO::PARAM_STR]);
    return $rs;
  }
}
