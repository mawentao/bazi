<?php
if (!defined('IN_BAZI_API')) {
    exit('Access Denied');
}
/**
 * 万年历数据库建设模块
 **/
require './source/class/class_core.php';
$discuz = C::app();
$discuz->init();
require_once BAZI_PLUGIN_PATH."/class/env.class.php";

////////////////////////////////////
// action的用户组列表（空表示全部用户组）
$actionlist = array(
	'query_all' => array(1),   //!< 命例管理
	'save' => array(),   //!< 保存命例
	'del' => array(),    //!< 删除命例
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
function query_all() { return C::t('#bazi#bazi_case')->query_all(); }

// 保存命例
function save() { return C::t('#bazi#bazi_case')->save(); }

// 删除命例
function del()
{
    $caseid = bazi_validate::getNCParameter('caseid','caseid','integer');
    return C::t('#bazi#bazi_case')->update($caseid,array('isdel'=>1));
}

// vim600: sw=4 ts=4 fdm=marker syn=php
?>
