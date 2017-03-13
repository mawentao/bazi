<?php
/*******************************************************
 * 此脚本文件用于插件的卸载
 * 提示：可使用runquery() 函数执行SQL语句
 *       表名可以直接写“cdb_”
 * 注意：需在导出的 XML 文件结尾加上此脚本的文件名
 *******************************************************/
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$tables = array (
);
foreach ($tables as $tb) {
    $sql = "DROP TABLE IF EXISTS `".DB::table($tb)."`";
    runquery($sql);
}

$finish = TRUE;
?>
