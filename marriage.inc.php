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
    $male_case_id = $marriageInfo['male_case_id'];
	$maleCase = new bazi_case($male_case_id);
    $maleCase->analyzeAll();
	$maleCase = $maleCase->toArray();
	// 女命分析
    $female_case_id = $marriageInfo['female_case_id'];
	$femaleCase = new bazi_case($female_case_id);
	$femaleCase->analyzeAll();
	$femaleCase = $femaleCase->toArray();
	// 合婚分析
	$marriage = bazi_marriage::analyze($maleCase,$femaleCase);
	//die(json_encode($marriage));
} catch(Exception $e) {
	die($e->getMessage());
}
$setting = C::m('#bazi#bazi_setting')->get();
$plugin_path = bazi_env::get_plugin_path();
include template("bazi:marriage");
$uid=$_G['uid'];
$username=$_G['username'];
bazi_env::getlog()->trace("bazi:marriage $uid|$username|$idstr");
C::t('#bazi#bazi_log')->write("bazi:marriage&m=$idstr|maleCaseId:$male_case_id|femaleCaseId:$female_case_id");
