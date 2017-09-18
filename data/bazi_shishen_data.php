<?php
/**
 * 安装程序调用,十神表数据
 **/

$table = DB::table('bazi_dict_shishen');
$cols = '(`name`,`short_name`,`sameyy`)';
$sql = "INSERT IGNORE INTO $table $cols VALUES ".<<<EOF
('比肩','比',1),
('劫财','劫',0),
('食神','食',1),
('伤官','伤',0),
('正财','财',0),
('偏财','才',1),
('正官','官',0),
('七杀','杀',1),
('正印','印',0),
('偏印','枭',1)
EOF;
runquery($sql);

