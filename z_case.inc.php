<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
require_once dirname(__FILE__).'/class/env.class.php';
$params['ajaxapi'] = bazi_env::get_plugin_path()."/index.php?version=4&module=";
$tplVars = array(
    'siteurl' => bazi_env::get_siteurl(),
    'plugin_path' => bazi_env::get_plugin_path(),
);
bazi_utils::loadtpl(dirname(__FILE__).'/template/views/z_case.tpl', $params, $tplVars);
bazi_env::getlog()->trace("show admin page [z_case] success");
