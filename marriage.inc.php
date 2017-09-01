<?php
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
require_once dirname(__FILE__)."/class/env.class.php";


try {
	$idstr = $_GET['m'];
	$marriageInfo = C::m('#bazi#bazi_marriage')->decode_marriage($idstr);
	if (empty($marriageInfo) || !$marriageInfo['male_case_id']) {
		throw new Exception("此链接已过期");
	}
	// 男命分析
	$maleCase = new bazi_case($marriageInfo['male_case_id']);
	$maleCase->analyzeMinpan();
	$maleCase->analyzeNayin();
	$maleCase = $maleCase->toArray();
	// 女命分析
	$femaleCase = new bazi_case($marriageInfo['female_case_id']);
	$femaleCase->analyzeMinpan();
	$femaleCase->analyzeNayin();
	$femaleCase = $femaleCase->toArray();
	// 合婚分析
	$marriage = bazi_marriage::analyze($maleCase,$femaleCase);
	//die(json_encode($marriage));
} catch(Exception $e) {
	die($e->getMessage());
}

$plugin_path = bazi_env::get_plugin_path();
include template("bazi:marriage");
$uid=$_G['uid'];
$username=$_G['username'];
bazi_env::getlog()->trace("bazi:marriage $uid|$username|$idstr");
