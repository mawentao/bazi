<?php
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
require_once dirname(__FILE__)."/class/env.class.php";

$caseid = $_GET['caseid'];
$nav = !isset($_GET['nav']) ? 0 : intval($_GET['nav']);

// 获取命例信息
//$bazi = C::m('#bazi#bazi_case')->foresee($caseid);
$bazi = C::m('#bazi#bazi_case')->getinfo($caseid);
if (empty($bazi)) {
	die("命例不存在或已删除");
}

// 命盘分析(装十神,排大运等)
bazi_env::c('analyze_mingpan')->analyze($bazi);
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

///////////////////////////////////////////////////

//C::m('#bazi#bazi_case')->debug($bazi);


$plugin_path = bazi_env::get_plugin_path();

include template("bazi:foresee");
