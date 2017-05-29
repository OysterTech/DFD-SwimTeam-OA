<?php
header("Content-Type: text/html;charset=utf-8");
include '../res/Plugin/PHPExcel/PHPExcel.php';
include '../res/Plugin/PHPExcel/PHPExcel/Writer/Excel2007.php';
$objPHPExcel = new PHPExcel();

$GamesName=isset($_GET['GamesName'])?$_GET['GamesName']:toAlertDie("500","参数错误！\\n请从正确途径进入本页面！");
$FileName="东风东游泳队-".$GamesName."报名表.xlsx";

$Cache_sql="SELECT * FROM cache_"
// 设定当前操作第1张表
$objPHPExcel->setActiveSheetIndex(0);
// 表名
$objPHPExcel->getActiveSheet()->setTitle('广州市东风东路小学');
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'String');

// 输出Excel表格到浏览器下载
ob_end_clean();
header('Cache-Control: max-age=1');
header("Pragma: public");
header("Expires: 0");
header("Cache-Control:must-revalidate, post-check=0, pre-check=0"); 
header("Content-Type:application/force-download");
header("Content-Type: application/vnd.ms-excel;");
header("Content-Type:application/octet-stream");
header("Content-Type:application/download");
header("Content-Disposition:attachment;filename=".$FileName);
header("Content-Transfer-Encoding:binary");

$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
$objWriter->save('php://output');

?>