<?php
/*******************************************************
 * 此脚本文件用于插件的安装
 * 提示：可使用runquery() 函数执行SQL语句
 *       表名可以直接写“cdb_”
 * 注意：需在导出的 XML 文件结尾加上此脚本的文件名
 *******************************************************/
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$curpath = dirname(__FILE__);
$addtime = $modtime = date('Y-m-d H:i:s');
$nowtm = time();

// install db
// 用户访问日志表
$table = DB::table('bazi_log');
/*{{{*/
$sql = "CREATE TABLE IF NOT EXISTS $table ". <<<EOF
(
`logid` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID(自增主键)',
`logtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '日志时间',
`uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
`client_ip` varchar(32) NOT NULL DEFAULT '' COMMENT '来访IP',
`log_content` varchar(4096) NOT NULL DEFAULT '' COMMENT '日志内容',
PRIMARY KEY (`logid`),
KEY `idx_logtime_uid` (`logtime`,`uid`)
) ENGINE=InnoDB
EOF;
runquery($sql);
runquery("ALTER TABLE `$table` ENGINE=INNODB");
/*}}}*/

// 万年历
$table = DB::table('bazi_calendar');
/*{{{*/
$sql = "CREATE TABLE IF NOT EXISTS `$table` ".<<<EOF
(
solar_calendar date NOT NULL DEFAULT '0000-00-00' COMMENT '阳历日期(主键)', 
lunar_calendar int unsigned not null default '0' COMMENT '阴历日期(唯一键，格式：yyyymmddX, X为1表示闰月)',
week tinyint(1) unsigned not null default '0' COMMENT '星期(0~6,0为周日)',
nian_gan char(1) not null default '' comment '年干(甲-癸)',
nian_zhi char(1) not null default '' comment '年支(子-亥)',
yue_gan char(1) not null default '' comment '月干(甲-癸)',
yue_zhi char(1) not null default '' comment '月支(子-亥)',
ri_gan char(1) not null default '' comment '日干(甲-癸)',
ri_zhi char(1) not null default '' comment '日支(子-亥)',
term varchar(8) not null default '' comment '二十四节气',
festival varchar(32) not null default '' comment '节日',
PRIMARY KEY (`solar_calendar`),
UNIQUE KEY `uk_yin_date` (`lunar_calendar`)
) ENGINE=MyISAM COMMENT '万年历数据表'
EOF;
runquery($sql);
/*}}}*/
// 导入万年历数据库
echo "导入万年历...\n";
$dbfile = dirname(__FILE__)."/data/bazi_calendar.db";
$fi = fopen($dbfile, "r");
if (!$fi) { die("读取文件失败: $dbfile"); }
while (!feof($fi)) {
	$line = trim(fgets($fi));
	if($line == ""){continue;}
	list($solar_calendar,$lunar_calendar,$week,$nian_gan,$nian_zhi,$yue_gan,$yue_zhi,$ri_gan,$ri_zhi,$term,$festival) = explode("\t",$line);
	$sql = "INSERT IGNORE INTO $table VALUES ".
		   "('$solar_calendar','$lunar_calendar','$week','$nian_gan','$nian_zhi','$yue_gan','$yue_zhi','$ri_gan','$ri_zhi','$term','$festival')";
	//DB::query($sql);
}

// 八字提要表
$table = DB::table('bazi_tiyao');
/*{{{*/
$sql = "CREATE TABLE IF NOT EXISTS `$table` ".<<<EOF
(
`tk` char(3) NOT NULL DEFAULT '' COMMENT '八字提要ID(日月时)',
`tv` text NOT NULL DEFAULT '' COMMENT '八字提要内容',
PRIMARY KEY (`tk`)
) ENGINE=MyISAM COMMENT '八字提要表《千里命稿》'
EOF;
runquery($sql);
/*}}}*/
/*
$dbfile = dirname(__FILE__)."/data/bazi_tiyao.db";
$fi = fopen($dbfile, "r");
if (!$fi) { die("读取文件失败: $dbfile"); }
while (!feof($fi)) {
	$line = trim(fgets($fi));
	if($line == ""){continue;}
	list($tk,$tv) = explode("\t",$line);
	$sql = "INSERT IGNORE INTO ".DB::table('bazi_tiyao')." VALUES ".
		   "('$tk','$tv')";
	DB::query($sql);
}
*/

// 五行表
$table = DB::table('bazi_dict_wuxing');
/*{{{*/
$sql = "CREATE TABLE IF NOT EXISTS `$table` ".<<<EOF
(
`name` varchar(4) NOT NULL DEFAULT '' COMMENT '天干名称',
`sheng` varchar(2) NOT NULL DEFAULT '' COMMENT '生',
`ke` varchar(2) NOT NULL DEFAULT '' COMMENT '克',
`siji` varchar(8) NOT NULL DEFAULT '' COMMENT '季节',
`position` varchar(2) NOT NULL DEFAULT '' COMMENT '方位',
PRIMARY KEY (`name`)
) ENGINE=MyISAM COMMENT '五行表'
EOF;
runquery($sql);
/*}}}*/
include_once($curpath."/data/bazi_wuxing_data.php");

// 天干表
$table = DB::table('bazi_dict_tiangan');
/*{{{*/
$sql = "CREATE TABLE IF NOT EXISTS `$table` ".<<<EOF
(
`name` varchar(4) NOT NULL DEFAULT '' COMMENT '天干名称',
`num` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '数字编号',
`yy` varchar(2) NOT NULL DEFAULT '' COMMENT '阴阳属性',
`wuxing` varchar(2) NOT NULL DEFAULT '' COMMENT '五行属性',
`zhangsheng` varchar(2) NOT NULL DEFAULT '' COMMENT '长生地支',
PRIMARY KEY (`name`),
UNIQUE KEY (`num`)
) ENGINE=MyISAM COMMENT '天干表'
EOF;
runquery($sql);
/*}}}*/
include_once($curpath."/data/bazi_tiangan_data.php");

// 地支表
$table = DB::table('bazi_dict_dizhi');
/*{{{*/
$sql = "CREATE TABLE IF NOT EXISTS `$table` ".<<<EOF
(
`name` varchar(4) NOT NULL DEFAULT '' COMMENT '天干名称',
`num` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '数字编号',
`yy` varchar(2) NOT NULL DEFAULT '' COMMENT '阴阳属性',
`wuxing` varchar(2) NOT NULL DEFAULT '' COMMENT '五行属性',
`shengxiao` varchar(2) NOT NULL DEFAULT '' COMMENT '生肖',
`canggan` varchar(8) NOT NULL DEFAULT '' COMMENT '地支藏天干(按主中余气排序,逗号分割)',
PRIMARY KEY (`name`),
UNIQUE KEY (`num`)
) ENGINE=MyISAM COMMENT '地支表'
EOF;
runquery($sql);
/*}}}*/
include_once($curpath."/data/bazi_dizhi_data.php");

// 十神表
$table = DB::table('bazi_dict_shishen');
/*{{{*/
$sql = "CREATE TABLE IF NOT EXISTS `$table` ".<<<EOF
(
`name` varchar(4) NOT NULL DEFAULT '' COMMENT '十神名称',
`short_name` varchar(2) NOT NULL DEFAULT '' COMMENT '十神简称',
`sameyy` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否与日主同阴阳(0:否,1:是)',
PRIMARY KEY (`name`),
UNIQUE KEY (`short_name`)
) ENGINE=MyISAM COMMENT '十神表'
EOF;
runquery($sql);
/*}}}*/
include_once($curpath."/data/bazi_shishen_data.php");

// 八字论断口诀
$table = DB::table('bazi_jue');
/*{{{*/
$sql = "CREATE TABLE IF NOT EXISTS `$table` ".<<<EOF
(
`jueid` int unsigned NOT NULL DEFAULT '0' COMMENT '八字诀ID',
`cate` varchar(64) NOT NULL DEFAULT '综合' COMMENT '类别', 
`nature` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性质(-1:凶,0:中性,1:吉利)',
`name` varchar(256) NOT NULL DEFAULT '' COMMENT '八字诀名称',
`desc` varchar(256) NOT NULL DEFAULT '' COMMENT '八字诀字面描述',
PRIMARY KEY (`jueid`),
UNIQUE KEY `uk_name` (`name`)
) ENGINE=MyISAM COMMENT '八字论断口诀表'
EOF;
runquery($sql);
/*}}}*/
include_once($curpath."/data/bazi_jue_data.php");

// 八字表
$table = DB::table('bazi_birth');
/*{{{*/
$sql = "CREATE TABLE IF NOT EXISTS `$table` ".<<<EOF
(
`bid` char(10) NOT NULL DEFAULT '' COMMENT '八字ID(yyyymmdd[子-亥][x|y])',
`solar_calendar` date NOT NULL DEFAULT '0000-00-00' COMMENT '阳历日期',
`gender` char(1) NOT NULL DEFAULT 'y' COMMENT '性别(x:女,y:男)',
`hour` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '时(0~23)',
`hour_gan` char(1) NOT NULL DEFAULT '子' COMMENT '时干(甲-癸)',
`hour_zhi` char(1) NOT NULL DEFAULT '子' COMMENT '时支(子-亥)',
`ctime` datetime NOT NULL DEFAULT "0000-00-00 00:00:00" comment '创建日期',
`mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
PRIMARY KEY (`bid`)
) ENGINE=MyISAM COMMENT '八字表'
EOF;
runquery($sql);
$sql="INSERT IGNORE INTO ".DB::table('bazi_birth')." VALUES ".
     "('19870628午y','1987-06-28','y','12','戊','午','$addtime','$addtime')";
runquery($sql);
/*}}}*/

// 命例表
$table = DB::table('bazi_case');
/*{{{*/
$sql = "CREATE TABLE IF NOT EXISTS `$table` ".<<<EOF
(
`caseid` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '命例ID(自增主键)',
`bid` char(10) NOT NULL DEFAULT '' COMMENT '八字ID',
`name` varchar(64) NOT NULL DEFAULT '' comment '姓名',
`desc` varchar(256) NOT NULL DEFAULT '' comment '备注',
`uid` mediumint(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT '所属用户(dzuid)',
`ctime` datetime NOT NULL DEFAULT "0000-00-00 00:00:00" comment '创建日期',
`mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
`isdel` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '删除标志(0:未删,1:已删)',
PRIMARY KEY (`caseid`),
KEY `idx_bid` (`bid`),
KEY `idx_name_isdel` (`name`,`isdel`),
KEY `idx_uid_isdel_name` (`uid`,`isdel`,`name`)
) ENGINE=MyISAM COMMENT '命例表'
EOF;
runquery($sql);
$sql="INSERT IGNORE INTO ".DB::table('bazi_case')." VALUES ".
     "('1','19870628午y','马文涛','',1,'$addtime','$addtime',0)";
runquery($sql);
/*}}}*/

$finish = TRUE;
?>
