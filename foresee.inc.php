<?php
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
require_once dirname(__FILE__)."/class/env.class.php";

$caseid = $_GET['caseid'];

// 排盘
$bazi = C::m('#bazi#bazi_case')->foresee($caseid);

if (empty($bazi)) {
	die("命例不存在或已删除");
}

// 论断
$foresee = C::m('#bazi#bazi_foresee')->foresee($bazi);



$plugin_path = bazi_env::get_plugin_path();

include template("bazi:foresee");
