<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
require_once dirname(__FILE__).'/class/env.class.php';
$plugin = "bazi";
$plugin_enabled = 0;
if(isset($_G['setting']['plugins']['available']) && in_array($plugin, $_G['setting']['plugins']['available'])){
    $plugin_enabled = 1;
}
if(isset($_GET['log']) && $_GET['log']){
	header("Content-type:text/plain;charset=utf-8");
	$dateStr = date('Ym');
	if(isset($_POST['date'])){
		$dateStr = $_POST['date'];
	}
	$file = rtrim(DISCUZ_ROOT, '/') . '/data/log/' . $dateStr . "_$plugin.php";
	if(is_readable($file)){
		$tmp = @file($file);
		$cnt = count($tmp);
		$lines = array();
		for($i = 0; $i < $cnt; $i++){
			$line = trim($tmp[$i]);
			if(!empty($line)){
				$lines[] = $tmp[$i];
			}
		}
		$cnt = count($lines);
		$i = 0;
		$total = 1024;
		if(isset($_GET['count']) && $_GET['count']){
			$total = intval($_GET['count']);
		}
		if($cnt >= $total){
			$i = $cnt - $total;
		}
		for(;$i < $cnt; $i++){
			echo $lines[$i];
		}
	}else{
		echo 'such log file does not exists or not readable [ log file: ' . '${DISCUZ_ROOT}/data/log/' . $dateStr . "_$plugin.php" . ' ]';
	}
	die(0);
}
$result = array (
    'env' => array (
        "charset"         => $_G['charset'],
        "discuz_version"  => $_G['setting']['version'],
        "php_version"     => phpversion(),
        'server_name'     => php_uname(),
        'server_software' => $_SERVER['SERVER_SOFTWARE'],
    ),  
    'site' => array (
        'siteurl'     => bazi_env::get_siteurl(),
        'sitename'    => bazi_env::get_sitename(),
        'admin_email' => bazi_env::get_admin_email(),
    ), 
    'bazi' => array(
        'plugin_version' => $_G['setting']['plugins']['version']["bazi"],
        'plugin_enabled' => $plugin_enabled,
    ),
);
bazi_env::result($result);
?>
