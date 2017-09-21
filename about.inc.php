<?php
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
require_once dirname(__FILE__)."/class/env.class.php";

try {
	$setting = C::m('#bazi#bazi_setting')->get();
	$page_style = $setting['page_style'];
	$plugin_path = bazi_env::get_plugin_path();
} catch(Exception $e) {
	die($e->getMessage());
}

include template("bazi:about");
$uid=$_G['uid'];
$username=$_G['username'];
bazi_env::getlog()->trace("bazi:about $uid|$username");
