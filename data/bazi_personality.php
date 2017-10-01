<?php
/**
 * 性格特征表
 **/
$table = DB::table('bazi_personality');
$sql = "CREATE TABLE IF NOT EXISTS `$table` ".<<<EOF
(
`pid` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID(自增主键)',
`word` varchar(64) NOT NULL DEFAULT '' COMMENT '性格关键词',
`desc` varchar(256) NOT NULL DEFAULT '' COMMENT '性格描述',
`type` tinyint(1) unsigned not null default '1' COMMENT '类型(1:性格优势;2:性格劣势)',

`f_gender_male` float NOT NULL DEFAULT '0' COMMENT '性别特征-男性',
`f_gender_female` float NOT NULL DEFAULT '0' COMMENT '性别特征-女性',
`f_wx_mu` float NOT NULL DEFAULT '0' COMMENT '五行特征-木',
`f_wx_huo` float NOT NULL DEFAULT '0' COMMENT '五行特征-火',
`f_wx_tu` float NOT NULL DEFAULT '0' COMMENT '五行特征-土',
`f_wx_jin` float NOT NULL DEFAULT '0' COMMENT '五行特征-金',
`f_wx_shui` float NOT NULL DEFAULT '0' COMMENT '五行特征-水',
`f_shen_bijian` float NOT NULL DEFAULT '0' COMMENT '十神特征-比肩',
`f_shen_jiecai` float NOT NULL DEFAULT '0' COMMENT '十神特征-劫财',
`f_shen_shishen` float NOT NULL DEFAULT '0' COMMENT '十神特征-食神',
`f_shen_shangguan` float NOT NULL DEFAULT '0' COMMENT '十神特征-伤官',
`f_shen_zhengcai` float NOT NULL DEFAULT '0' COMMENT '十神特征-正财',
`f_shen_piancai` float NOT NULL DEFAULT '0' COMMENT '十神特征-偏财',
`f_shen_zhengguan` float NOT NULL DEFAULT '0' COMMENT '十神特征-正官',
`f_shen_qisha` float NOT NULL DEFAULT '0' COMMENT '十神特征-七杀',
`f_shen_zhengyin` float NOT NULL DEFAULT '0' COMMENT '十神特征-正印',
`f_shen_pianyin` float NOT NULL DEFAULT '0' COMMENT '十神特征-偏印',
PRIMARY KEY (`pid`),
UNIQUE KEY (`word`),
KEY `idx_type` (`type`)
) ENGINE=MyISAM COMMENT '性格特征表'
EOF;
runquery($sql);

/* --------------------- Data -------------------- */
$cols = '';
$sql = "INSERT IGNORE INTO $table $cols VALUES ".<<<EOF
(1,'仁慈','木主仁，五行木旺，为人心底善良，性情随和，乐善好施，富有同情心。',1, 0,0, 1,0,0,0,0, 0,0, 0,0, 0,0, 0,0, 0,0),
(2,'重情重义','火主礼，五行火旺，为人重情重义，待人热情有礼。',1, 0,0, 0,1,0,0,0, 0,0, 0,0, 0,0, 0,0, 0,0),
(3,'诚信敦厚','土主信，五行土旺，为人诚信敦厚，踏实稳重，忠诚至诚。',1, 0,0, 0,0,1,0,0, 0,0, 0,0, 0,0, 0,0, 0,0),
(4,'重义','金主义，五行金旺，为人义理分明，仗义疏财，充满侠义豪情。',1, 0,0, 0,0,0,1,0, 0,0, 0,0, 0,0, 0,0, 0,0),
(5,'聪明睿智','水主智，五行水旺，为人聪明睿智，反应机巧。',1, 0,0, 0,0,0,0,1, 0,0, 0,0, 0,0, 0,0, 0,0)
EOF;
runquery($sql);


