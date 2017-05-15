<?php
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
require_once dirname(__FILE__)."/class/env.class.php";

/*
// 登录检查
if(!$_G['uid']){
	//C::m("#didisso#didisso_member")->dologin(86);

    //showmessage("to_login", '', array(), array('login' => true));
	$login = bazi_env::get_siteurl()."/member.php?mod=logging&action=login";
    header("Location: $login");
    exit();
}
*/

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

include template("bazi:bazi");
