<?php
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
require_once dirname(__FILE__)."/class/env.class.php";

// 登录检查
if(!$_G['uid']){
	include template("bazi:login");
    exit();
}

// 设置
$setting = C::m('#bazi#bazi_setting')->get();
$page_style = $setting['page_style'];
// 导航列表
$navlist = C::m('#bazi#bazi_nav_setting')->getenablelist();
$navlist = json_encode($navlist);

/*
$filename = basename(__FILE__);
list($controller) = explode('.',$filename);
include template("bazi:".strtolower($controller));
*/
$plugin_path = bazi_env::get_plugin_path();
include template("bazi:console");
