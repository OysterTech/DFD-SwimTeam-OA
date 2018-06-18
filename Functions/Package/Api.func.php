<?php

/**
* -----------------------------------------
* @name PHP公用函数库 6 API接口操作函数
* @copyright 版权所有：小生蚝 <master@xshgzs.com>
* @create 创建时间：2018-06-11
* @modify 最后修改时间：2018-06-11
* -----------------------------------------
*/

/**
* -------------------------------------
* returnApiData 接口返回json数据
* -------------------------------------
* @param INT    响应代码
* @param String 响应信息
* @param Array  业务返回数据
* -------------------------------------
**/
function returnApiData($code=0,$msg="",$data=array())
{
	$ret=array();
	$ret['code']=$code;
	$ret['msg']=$msg;
	$ret['data']=$data;
	$ret=json_encode($ret);
	return $ret;
}
