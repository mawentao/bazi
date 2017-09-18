<?php
/**
 * 安装程序调用,五行表数据
 **/

$table = DB::table('bazi_dict_wuxing');
$cols = '(`name`,`sheng`,`ke`,`siji`,`position`)';
$sql = "INSERT IGNORE INTO $table $cols VALUES ".<<<EOF
('木','火','土','春天','东方'),
('火','土','金','夏天','南方'),
('土','金','水','四季','中央'),
('金','水','木','秋天','西方'),
('水','木','火','冬天','北方')
EOF;
runquery($sql);

