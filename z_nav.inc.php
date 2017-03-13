<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
require_once dirname(__FILE__).'/class/env.class.php';

// 插件设置
$params = C::m('#bazi#bazi_nav_setting')->get();

// 保存设置
if (isset($_POST["reset"])) {
	if ($_POST["reset"]==1) {
		unset($params['navlist']);
	} else {
		$list = array();
		foreach ($_POST['displayorder'] as $k => $displayorder) {
			$list[] = array (
				'displayorder' => $displayorder,
				'icon' => $_POST['icon'][$k],
				'text' => $_POST['text'][$k],
				'href' => $_POST['href'][$k],
				'newtab' => $_POST['newtab'][$k],
				'enable' => $_POST['enable'][$k],
			);
		}
		// 按displayorder排序
		bazi_utils::array_sort_by($list,'displayorder','ASC');
		$params['navlist'] = $list;
	}
	C::t('common_setting')->update('bazi_nav_setting',$params);
    updatecache('setting');
    $landurl = 'action=plugins&operation=config&do='.$pluginid.'&identifier=bazi&pmod=z_nav';
	cpmsg('plugins_edit_succeed', $landurl, 'succeed');
}

$params['ajaxapi'] = bazi_env::get_plugin_path()."/index.php?version=4&module=";
$tplVars = array(
    'siteurl' => bazi_env::get_siteurl(),
    'plugin_path' => bazi_env::get_plugin_path(),
);
bazi_utils::loadtpl(dirname(__FILE__).'/template/views/z_nav.tpl', $params, $tplVars);
bazi_env::getlog()->trace("show admin page [z_nav] success");
