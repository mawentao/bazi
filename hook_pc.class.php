<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
require_once dirname(__FILE__).'/class/env.class.php';

class plugin_bazi
{
    public function common()
    {
		global $_G;
		// 未开启屏蔽其他页面开关
		$setting = C::m('#bazi#bazi_setting')->get();
		if (!$setting['disable_discuz']) return;
		// 登录页面不屏蔽
		if (strpos($_SERVER['PHP_SELF'],"member.php")!==false && isset($_GET['mod']) && $_GET['mod']=="logging") {
			return;
		}
		// 只允许打开以下插件页面
		$enable_pluginids = array(
			'bazi','wxconnect',
		);
		$pluginid = '';
		if (isset($_GET['id'])) {list($pluginid) = explode(':',$_GET['id']);}
		if (strpos($_SERVER['PHP_SELF'],"plugin.php")!==false && in_array($pluginid,$enable_pluginids)) {
			return;
		}
		// 启用SEO设置的处理
		$bazi_url = bazi_env::get_siteurl()."/plugin.php?id=bazi";
		if (in_array('plugin',$_G['setting']['rewritestatus'])) {
			$bazi_url = bazi_env::get_siteurl()."/bazi-bazi.html";
			foreach ($enable_pluginids as $plugin) {
				if (preg_match("/$plugin-[\w]*\.html$/i",$_SERVER['REQUEST_URI'])) {
					return;
				}
			}
		}
		// 跳转到本插件页面
		header("Location: $bazi_url");
		exit(0);
    }
}

