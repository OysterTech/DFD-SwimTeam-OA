<?php
require_once("../Functions/PDOConn.php");
require_once("../Functions/PublicFunc.php");
include('../res/Plugin/PHPExcel/PHPExcel.php');
include('../res/Plugin/PHPExcel/PHPExcel/Writer/Excel2007.php');

$ItemIDs=array();
$ItemNames=array();
$ItemYearGroup=array();
$showItemID=array();
$showItemName=array();
$showItemYearGroup=array();

$GamesName=isset($_GET['GamesName'])?$_GET['GamesName']:toAlertDie("500","参数错误！\\n请从正确途径进入本页面！");
$GamesID=isset($_GET['GamesID'])?$_GET['GamesID']:toAlertDie("500","参数错误！\\n请从正确途径进入本页面！");
$FileName="东风东游泳队-".$GamesName."报名表.xlsx";

/******* ▼ 获取比赛所有人的报名数据 ▼ *******/
$Cache=new Cache($dbcon,"enroll_export");
$UserID=GetSess(Prefix."UserID");
$SessionID=session_ID();
$getCacheCondition[0]=["UserID",$UserID];
$getCacheCondition[1]=["SessionID",$SessionID];
$EnrollData=$Cache->G($getCacheCondition);
$EnrollData=json_decode($EnrollData[0][0]['Content'],true);
/******* ▲ 获取比赛所有人的报名数据 ▲ *******/


/******* ▼ 获取比赛项目详细信息 ▼ **********/
$GamesItem_list=PDOQuery($dbcon,"SELECT * FROM games_item WHERE GamesID=?",[$GamesID],[PDO::PARAM_STR]);
$GamesItem_total=sizeof($GamesItem_list[0]);

$Item_list=PDOQuery($dbcon,"SELECT * FROM item_list",[],[]);
$Item_total=sizeof($Item_list[0]);

// 所有项目
for($j=0;$j<$Item_total;$j++){
  array_push($ItemIDs,$Item_list[0][$j]['ItemID']);
  array_push($ItemNames,$Item_list[0][$j]['ItemName']);
  array_push($ItemYearGroup,$Item_list[0][$j]['YearGroup']);
}

// 当前比赛所有项目
for($k=0;$k<$GamesItem_total;$k++){
  $ItemID=$GamesItem_list[0][$k]['ItemID'];
  
  if(in_array($ItemID,$ItemIDs)){
    $Loc=array_search($ItemID,$ItemIDs);
    $ItemName=$ItemNames[$Loc];
    $YearGroup=$ItemYearGroup[$Loc];
    
    array_push($showItemID,$ItemID);
    array_push($showItemYearGroup,$YearGroup);
    array_push($showItemName,$ItemName);
  }
}

unset($ItemIDs);
unset($ItemNames);
unset($ItemYearGroup);
/******* ▲ 获取比赛项目详细信息 ▲ **********/


/********** ▼ 设置Excel内容 ▼ ***********/
$objPHPExcel = new PHPExcel();
$SheetID=0;
for($i=6;$i<13;$i++){
  $YearGroup=date("Y")-$i;
  $ItemNametoRow=array();
  
  // 设定当前操作的工作表
  if($SheetID!=0){
    // 建表
    $objPHPExcel->createSheet();
  }
  $objPHPExcel->setActiveSheetIndex($SheetID);

  // 表名
  $objPHPExcel->getActiveSheet()->setTitle($YearGroup.'年组');
  // 大表头
  $objPHPExcel->getActiveSheet()->setCellValue('A1', "广州市东风东路小学{$GamesName}报名表");
  // 详细表头
  $objPHPExcel->getActiveSheet()->setCellValue('A2',"姓名");
  $objPHPExcel->getActiveSheet()->setCellValue('B2',"性别");
  $objPHPExcel->getActiveSheet()->setCellValue('C2',"班别");
  $objPHPExcel->getActiveSheet()->setCellValue('D2',"证件号");
  $objPHPExcel->getActiveSheet()->setCellValue('E2',"手机");
  $RowsID=5;// 当前单元格列号
  $ColsID=2;// 当前单元格行号
  
  // 显示项目名称
  foreach($showItemName as $key=>$Value){
    if($showItemYearGroup[$key]!=$YearGroup){
      continue;
    }

    $RowsID++;
    $RowLetter=getLetter($RowsID);

    // 每个项目对应的列号
    array_push($ItemNametoRow,$Value);

    // 显示项目名称
    $objPHPExcel->getActiveSheet()->setCellValue($RowLetter."2",$Value);
  }
  
  // 合并大表头单元格
  $RowLetter=getLetter($RowsID);
  $objPHPExcel->getActiveSheet()->mergeCells('A1:'.$RowLetter.'1');
  
  // 显示运动员资料
  foreach($EnrollData as $Value){
    $ColsID++;

    // 运动员报的项目
    $EnrollItem=$Value['ItemName'];
    $EnrollItem2=explode(",",$EnrollItem);
    // 运动员年龄组
    $EnrollYearGroup=$Value['YearGroup'];
    $EnrollYearGroup2=explode(",",$EnrollYearGroup);
    $EnrollYearGroup3=$EnrollYearGroup2[0];
    
    // 不是当前年龄组
    if($EnrollYearGroup3!=$YearGroup){
    	$ColsID--;
    	continue;
    }
    
    $RealName=$Value['RealName'];
    $Sex=$Value['Sex'];
    $Phone=$Value['Phone'];
    $IDCard=$Value['IDCard'];
    $SchoolGrade=$Value['SchoolGrade'];
    $SchoolClass=$Value['SchoolClass'];
    $showClass=$SchoolGrade."年".$SchoolClass."班";
    
    $objPHPExcel->getActiveSheet()->setCellValue("A".$ColsID,$RealName);
    $objPHPExcel->getActiveSheet()->setCellValue("B".$ColsID,$Sex);
    $objPHPExcel->getActiveSheet()->setCellValue("C".$ColsID,$showClass);
    $objPHPExcel->getActiveSheet()->setCellValue("D".$ColsID,$IDCard);
    $objPHPExcel->getActiveSheet()->setCellValue("E".$ColsID,$Phone);
  
    // 每个项目
    foreach($EnrollItem2 as $Value2){
      $Loc=array_search($Value2,$ItemNametoRow);
      $RowLetter=getLetter($Loc+6);// 前五列是个人信息
      $objPHPExcel->getActiveSheet()->setCellValue($RowLetter.$ColsID,"√");
    }
  }
  
  // 下一张工作表
  $SheetID++;

  $Range='A1:'.getLetter($RowsID).$ColsID;
  $objPHPExcel->getActiveSheet()->getStyle($Range)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  $styleArray = array(
    'borders' => array(
      'allborders' => array(
        'style' => PHPExcel_Style_Border::BORDER_THIN,
      ),
    ),
  );
  $objPHPExcel->getActiveSheet()->getStyle($Range)->applyFromArray($styleArray);

  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('FFADADAD');
  $objPHPExcel->getActiveSheet()->getStyle('A2:'.getLetter($RowsID).'2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
  $objPHPExcel->getActiveSheet()->getStyle('A2:'.getLetter($RowsID).'2')->getFill()->getStartColor()->setARGB('FFD0D0D0');
}
/********** ▲ 设置Excel内容 ▲ ***********/


/********** ▼ 输出并下载文件 ▼ ***********/
ob_end_clean();
ob_start();
header("Expires:-1");
header("Cache-Control:no_cache");
header("Pragma:no-cache");
header("Cache-Control:must-revalIDate, post-check=0, pre-check=0"); 
header("Content-Type:application/force-download");
header("Content-Type:application/vnd.ms-excel;");
header("Content-Type:application/octet-stream");
header("Content-Type:application/download");
header("Content-Disposition:attachment;filename=".$FileName);
header("Content-Transfer-Encoding:binary");

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save('php://output');
/********** ▲ 输出并下载文件 ▲ ***********/

?>