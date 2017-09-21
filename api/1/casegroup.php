<?php
if (!defined('IN_BAZI_API')) {
    exit('Access Denied');
}
/**
 * 分组模块
 **/
require './source/class/class_core.php';
$discuz = C::app();
$discuz->init();
require_once BAZI_PLUGIN_PATH."/class/env.class.php";

////////////////////////////////////
// action的用户组列表（空表示全部用户组）
$actionlist = array(
	'query' => array(1),   //!< 管理后台
    'mygroups' => array(), //!< 获取我可用的全部分组列表
    'save' => array(),     //!< 保存记录
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

// 命例管理
function query() { return C::t('#bazi#bazi_case_group')->query(); }

// 获取我可用的全部分组列表
function mygroups() { global $uid; return C::t('#bazi#bazi_case_group')->getall($uid);}

// 用户提交命例
function submit()
{/*{{{*/
	global $_G;
	$name   = bazi_validate::getNCParameter('name','name','string');
	$gender = bazi_validate::getNCParameter('gender','gender','string');
	if ($gender!='x') $gender='y';
	$date   = bazi_validate::getNCParameter('date','date','string');
	$hour   = bazi_validate::getNCParameter('hour','hour','integer');
    $tm = strtotime($date);
	$solar_calendar = date('Y-m-d',$tm);
	$bid = C::t('#bazi#bazi_birth')->get_bid($solar_calendar,$gender,$hour);
	$caseid = C::t('#bazi#bazi_case')->add($bid,$name,$desc);
	if ($caseid==0) throw new Exception("服务器忙，请稍候再试。");
	return C::m('#bazi#bazi_authcode')->encode_id($caseid);
}/*}}}*/

// 保存记录
function save() { return C::t('#bazi#bazi_case_group')->save(); }

// 删除记录
function del()
{
    $cgid = bazi_validate::getNCParameter('cgid','cgid','integer');
    return C::t('#bazi#bazi_case_group')->update($cgid,array('isdel'=>1));
}

// vim600: sw=4 ts=4 fdm=marker syn=php
?>
