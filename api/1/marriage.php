<?php
if (!defined('IN_BAZI_API')) {
    exit('Access Denied');
}
/**
 * 合婚模块
 **/
require './source/class/class_core.php';
$discuz = C::app();
$discuz->init();
require_once BAZI_PLUGIN_PATH."/class/env.class.php";

////////////////////////////////////
// action的用户组列表（空表示全部用户组）
$actionlist = array(
	'merge' => array(),	//!< 合婚分析提交
);
////////////////////////////////////
$uid = $_G['uid'];
$username = $_G['username'];
$groupid = $_G["groupid"];
$action = isset($_GET['action']) ? $_GET['action'] : "get";

try {
    if (!isset($actionlist[$action])) {
        throw new Exception('unknow action');
    }
    $groups = $actionlist[$action];
    if (!empty($groups) && !in_array($groupid, $groups)) {
        throw new Exception('illegal request');
    }
    $res = $action();
    bazi_env::result(array("data"=>$res));
} catch (Exception $e) {
    bazi_env::result(array('retcode'=>100010,'retmsg'=>$e->getMessage()));
}

// 合婚分析提交
function merge()
{
	global $_G;
	$male_case_id = bazi_validate::getNCParameter('male_case_id','male_case_id','integer');
	$female_case_id = bazi_validate::getNCParameter('female_case_id','female_case_id','integer');
	return C::m('#bazi#bazi_marriage')->encode_marriage($male_case_id,$female_case_id);
}

// vim600: sw=4 ts=4 fdm=marker syn=php
?>
