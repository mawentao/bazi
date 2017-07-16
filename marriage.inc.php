<?php
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
require_once dirname(__FILE__)."/class/env.class.php";

$idstr = $_GET['m'];
$marriageInfo = C::m('#bazi#bazi_marriage')->decode_marriage($idstr);

if (empty($marriageInfo) || !$marriageInfo['male_case_id']) {
	die("此链接已过期");
}

print_r($marriageInfo);

die(0);


///////////////////////////////////////////////
// ID加密后的密文中会有+字符,
// 此特殊字符在url参数传递中会被解码成空格,
// 故作此兼容处理
$idstr = preg_replace("/ /i",'+',$idstr);
///////////////////////////////////////////////
$caseid = C::m('#bazi#bazi_authcode')->decode_id($idstr);

$nav = !isset($_GET['nav']) ? 0 : intval($_GET['nav']);

// 获取命例信息
//$bazi = C::m('#bazi#bazi_case')->foresee($caseid);
$bazi = C::m('#bazi#bazi_case')->getinfo($caseid);
if (empty($bazi)) {
	die("命例不存在或已删除");
}

// 命盘分析(装十神,排大运等)
bazi_env::c('analyze_mingpan')->analyze($bazi);
//die(json_encode($bazi));

// 取纳音
bazi_env::c('analyze_nayin')->analyze($bazi);

// 五行力量分析
bazi_env::c('analyze_wuxing')->analyze($bazi);


// 八字格局分析
bazi_env::c('analyze_geju')->analyze($bazi);

// 总论(日元论)
bazi_env::c('analyze_outline')->analyze($bazi);
// 八字提要（摘自《千里命稿》）
bazi_env::c('analyze_tiyao')->analyze($bazi);
// 八字五行关系图（合、冲、克、害、刑、破）
bazi_env::c('analyze_wuxing_graph')->analyze($bazi);
// 神煞分析
bazi_env::c('analyze_shensha')->analyze($bazi);

///////////////////////////////////////////////////
// 论婚恋
bazi_env::c('analyze_hunlian_base')->analyze($bazi);
// 论婚恋流年
bazi_env::c('analyze_hunlian_liunian')->analyze($bazi);


///////////////////////////////////////////////////

//C::m('#bazi#bazi_case')->debug($bazi);


$plugin_path = bazi_env::get_plugin_path();

include template("bazi:foresee");
