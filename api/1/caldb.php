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
	'updata' => array(),        //!< 上传数据
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

// 上传数据
function updata()
{
	$data = array (
		'solar_calendar' => $_POST['cYear']."-".$_POST['cMonth']."-".$_POST['cDay'],
		'lunar_calendar' => get_lunar_calendar(),
		'week' => $_POST['nWeek']>=7 ? 0 : $_POST['nWeek'],
		'nian_gan' => get_gan($_POST['gzYear']),
		'nian_zhi' => get_zhi($_POST['gzYear']),
		'yue_gan' => get_gan($_POST['gzMonth']),
		'yue_zhi' => get_zhi($_POST['gzMonth']),
		'ri_gan' => get_gan($_POST['gzDay']),
		'ri_zhi' => get_zhi($_POST['gzDay']),
		'term' => $_POST['Term']!="" ? trim($_POST['Term']) : "",
		'festival' => trim($_POST['festival']),
	);
	extract($data);
	$sql = "INSERT IGNORE INTO ".DB::table('bazi_calendar')." VALUES ".
		   "('$solar_calendar','$lunar_calendar','$week','$nian_gan','$nian_zhi','$yue_gan','$yue_zhi','$ri_gan','$ri_zhi','$term','$festival')";
	DB::query($sql);
	$data['sql'] = $sql;
    return $data;
}



function get_lunar_calendar()
{/*{{{*/
	$lYear = intval($_POST['lYear']);
	$lMonth = intval($_POST['lMonth']);
	$lDay = intval($_POST['lDay']);
	$lunar_calendar = $lYear;
	$lunar_calendar.= $lMonth<10 ? "0$lMonth" : $lMonth;
	$lunar_calendar.= $lDay<10 ? "0$lDay" : $lDay;
	$lunar_calendar.= $_POST['isLeap']=='true' ? 1 : 0;
	return $lunar_calendar;
}/*}}}*/

// 获取天干编号
function get_gan($gz)
{/*{{{*/
	$ganmap = array(
		'甲'=>0, '乙'=>1, '丙'=>2, '丁'=>3, '戊'=>4, 
		'己'=>5, '庚'=>6, '辛'=>7, '壬'=>8, '癸'=>9,
	);
	$k = mb_substr($gz,0,1,'utf-8');
	return $k; //isset($ganmap[$k]) ? $ganmap[$k] : 17;
}/*}}}*/

// 获取地支编号
function get_zhi($gz)
{/*{{{*/
	$ganmap = array(
		'子'=>0, '丑'=>1, '寅'=>2, '卯'=>3, '辰'=>4, '巳'=>5, 
		'午'=>6, '未'=>7, '申'=>8, '酉'=>9, '戌'=>10,'亥'=>11,
	);
	$k = mb_substr($gz,1,1,'utf-8');
	return $k; //isset($ganmap[$k]) ? $ganmap[$k] : 17;
}/*}}}*/


// vim600: sw=4 ts=4 fdm=marker syn=php
?>
