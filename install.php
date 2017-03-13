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

$addtime = $modtime = date('Y-m-d H:i:s');
$nowtm = time();

// install db
// 万年历
$sql = "CREATE TABLE IF NOT EXISTS `".DB::table('bazi_calendar')."` ".<<<EOF
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
// 导入万年历数据库
echo "导入万年历...\n";
$dbfile = dirname(__FILE__)."/data/bazi_calendar.db";
$fi = fopen($dbfile, "r");
if (!$fi) { die("读取文件失败: $dbfile"); }
while (!feof($fi)) {
	$line = trim(fgets($fi));
	if($line == ""){continue;}
	list($solar_calendar,$lunar_calendar,$week,$nian_gan,$nian_zhi,$yue_gan,$yue_zhi,$ri_gan,$ri_zhi,$term,$festival) = explode("\t",$line);
	$sql = "INSERT IGNORE INTO ".DB::table('bazi_calendar')." VALUES ".
		   "('$solar_calendar','$lunar_calendar','$week','$nian_gan','$nian_zhi','$yue_gan','$yue_zhi','$ri_gan','$ri_zhi','$term','$festival')";
	DB::query($sql);
}

// 八字表
$sql = "CREATE TABLE IF NOT EXISTS `".DB::table('bazi_birth')."` ".<<<EOF
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

// 命例
$sql = "CREATE TABLE IF NOT EXISTS `".DB::table('bazi_case')."` ".<<<EOF
(
`caseid` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '命例ID(自增主键)',
`bid` char(10) NOT NULL DEFAULT '' COMMENT '八字ID',
`name` varchar(64) NOT NULL DEFAULT '' comment '姓名',
`ctime` datetime NOT NULL DEFAULT "0000-00-00 00:00:00" comment '创建日期',
`mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
`isdel` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '删除标志(0:未删,1:已删)',
PRIMARY KEY (`caseid`),
KEY `idx_bid` (`bid`),
KEY `idx_name_isdel` (`name`,`isdel`)
) ENGINE=MyISAM COMMENT '命例表'
EOF;
runquery($sql);
$sql="INSERT IGNORE INTO ".DB::table('bazi_case')." VALUES ".
     "('1','19870628午y','马文涛','$addtime','$addtime',0)";
runquery($sql);


$finish = TRUE;
?>
