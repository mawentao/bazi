<?php
if (!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
/**
 * 八字性格表
 **/
class table_bazi_personality extends discuz_table
{
    public function __construct() {
		$this->_table = 'bazi_personality';
		$this->_pk = 'pid';
		parent::__construct();
	}

    // 获取性格特质列表
    public function get_personality_list(&$bazicase,$type,$top=5)
    {
        // 性别特征
        $male = $bazicase['gender']=='男' ? 1 : 0;
        $female = 1 - $male;
        // 五行特征
        $wuxingDict = &$bazicase['dict']['wuxing'];
        $wx_mu   = $wuxingDict['木']['power'] / 100;
        $wx_huo  = $wuxingDict['火']['power'] / 100;
        $wx_tu   = $wuxingDict['土']['power'] / 100;
        $wx_jin  = $wuxingDict['金']['power'] / 100;
        $wx_shui = $wuxingDict['水']['power'] / 100;
        // 十神特征
        $shenDict = &$bazicase['dict']['shishen'];
        $shen_bijian = $shenDict['比肩']['power'] / 100;
        $shen_jiecai = $shenDict['劫财']['power'] / 100;
        $shen_shishen = $shenDict['食神']['power'] / 100;
        $shen_shangguan = $shenDict['伤官']['power'] / 100;
        $shen_zhengcai = $shenDict['正财']['power'] / 100;
        $shen_piancai = $shenDict['偏财']['power'] / 100;
        $shen_zhengguan = $shenDict['正官']['power'] / 100;
        $shen_qisha = $shenDict['七杀']['power'] / 100;
        $shen_zhengyin = $shenDict['正印']['power'] / 100;
        $shen_pianyin = $shenDict['偏印']['power'] / 100;

        $table = DB::table('bazi_personality');
        $sql = <<<EOF
SELECT `word`,`desc`,
(f_gender_male*$male + f_gender_female*$female + 
f_wx_mu*$wx_mu + f_wx_huo*$wx_huo + f_wx_tu*$wx_tu + f_wx_jin*$wx_jin + f_wx_shui*$wx_shui +
f_shen_bijian*$shen_bijian + f_shen_jiecai*$shen_jiecai + 
f_shen_shishen*$shen_shishen + f_shen_shangguan*$shen_shangguan +
f_shen_zhengcai*$shen_zhengcai + f_shen_piancai*$shen_piancai +
f_shen_zhengguan*$shen_zhengguan + f_shen_qisha*$shen_qisha +
f_shen_zhengyin*$shen_zhengyin + f_shen_pianyin*$shen_pianyin)
as v
FROM $table
WHERE type='$type'
ORDER BY v DESC
LIMIT 0,$top
EOF;
        return DB::fetch_all($sql);
    }
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
