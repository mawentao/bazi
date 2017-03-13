<?php
if (!defined('IN_BAZI_API')) {
    exit('Access Denied');
}
/**
 * 万年历模块
 **/
require './source/class/class_core.php';
$discuz = C::app();
$discuz->init();
require_once BAZI_PLUGIN_PATH."/class/env.class.php";

////////////////////////////////////
// action的用户组列表（空表示全部用户组）
$actionlist = array(
	'fetchall_in_year' => array(),    //!< 获取一年的日历
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

// 获取一年的日历
function fetchall_in_year()
{
	$lunar_month_arr = array('正月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','腊月');
	$lunar_day_arr = array(
		'初一','初二','初三','初四','初五','初六','初七','初八','初九','初十',
		'十一','十二','十三','十四','十五','十六','十七','十八','十九','二十',
		'廿一','廿二','廿三','廿四','廿五','廿六','廿七','廿八','廿九','三十',
	);
	$animal_map = array (
		'子'=>'鼠','丑'=>'牛','寅'=>'虎','卯'=>'兔','辰'=>'龙','巳'=>'蛇',
		'午'=>'马','未'=>'羊','申'=>'猴','酉'=>'鸡','戌'=>'狗','亥'=>'猪',
	);

	$year = bazi_validate::getNCParameter("year","year","integer");
	$sql = "SELECT * FROM ".DB::table('bazi_calendar')." WHERE year(solar_calendar)=$year ORDER BY solar_calendar ASC";
	$list = DB::fetch_all($sql);
	foreach ($list as &$row) {
		// 阴历年
		$row['lunar_year'] = intval(substr($row['lunar_calendar'],0,4));
		$row['lunar_year_animal'] = $animal_map[$row['nian_zhi']];
		// 阴历月份
		$row['isTerm'] = intval($row['lunar_calendar'])%2;
		$lunar_month = intval(substr($row['lunar_calendar'],4,2));
		$row['lunar_month'] = $lunar_month_arr[$lunar_month-1];
		if ($row['isTerm']) $row['lunar_month'] = '闰'.$row['lunar_month'];
		// 阴历日
		$lunar_day = intval(substr($row['lunar_calendar'],6,2));
		$row['lunar_day'] = $lunar_day_arr[$lunar_day-1];
		// 干支
		$row['gz_year'] = $row['nian_gan'].$row['nian_zhi'];
		unset($row['nian_gan']);
		unset($row['nian_zhi']);
		$row['gz_month'] = $row['yue_gan'].$row['yue_zhi'];
		unset($row['yue_gan']);
		unset($row['yue_zhi']);
		$row['gz_day'] = $row['ri_gan'].$row['ri_zhi'];
		unset($row['ri_gan']);
		unset($row['ri_zhi']);
	}

    return $list;
}

// vim600: sw=4 ts=4 fdm=marker syn=php
?>
