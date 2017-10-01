<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
/**
 * 心性分析
 *    主要看旺的五行和十神
 **/
class bazi_analyze_personality
{
    public static function analyze(&$baziCase)
    {
		$bazi = &$baziCase->data;
        $t = C::t('#bazi#bazi_personality');
        $top = 5;
        $bazi['personality'] = array (
            'positive' => $t->get_personality_list($bazi,1,$top),  //!< 性格优势
            'negative' => $t->get_personality_list($bazi,2,$top),  //!< 性格劣势
        );
	}
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
