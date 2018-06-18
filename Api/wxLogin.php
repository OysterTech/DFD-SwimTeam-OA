<?php
/**
 * wxLogin 微信登录API
 * @author Jerry Cheung
 * @create 2018-06-09
 */

//require_once("../Functions/PDOConn.php");
require_once("../Functions/Package/Api.func.php");

define("APPID","wxd99996ad603a4750");
define("SECRET","");

if(isset($_GET) && $_GET){
	$code=$_GET['code'];
	
	// 通过微信接口获取用户openID和sessionKey
	$url='https://api.weixin.qq.com/sns/jscode2session?appid='.APPID.'&secret='.SECRET.'&js_code='.$code.'&grant_type=authorization_code';
	$ch=curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_HEADER,0);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
	$output=curl_exec($ch);
	curl_close($ch);
	
	// 处理获取到的数据
	$data=json_decode($output,TRUE);
	$openID=$data['openid'];
	$sessionKey=$data['session_key'];
	$mpToken=sha1(sha1($sessionKey).md5($openID));
	
	// 返回数据
	$ret['mpToken']=$mpToken;
	$ret['openID']=$openID;

	$ret=returnApiData(1,"success",$ret);
	echo $ret;
}
?>
