<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
/**
 * 总论
 * bazi_env::c('analyze_outline')->analyze($bazi);
 **/
class analyze_outline
{
    public function analyze(&$bazi)
    {
		$riyuan = '<strong>'.$bazi['ri_gan'].$bazi['ri_gan_info']['wuxing']."</strong>";
		$yuezhi = $bazi['yue_zhi'];

		$yue_zhi_info = &$bazi['yue_zhi_info'];
		$yue_zhi_wuxing = $yue_zhi_info['wuxing_info'];
		$siji = $yue_zhi_wuxing['siji']!='四季' ? "（".$yue_zhi_wuxing['siji']."天）" : '';

		$de_ling = $bazi['yue_zhi_info']['de_ling'];
		$de_ling_str = '不得时令';
		if ($de_ling=='旺') $de_ling_str="当令";
		if ($de_ling=='相') $de_ling_str="得令";

		$str = $riyuan."日元生于".$yuezhi."月".$siji."，月令为<em>".$de_ling."</em>，".$riyuan.$de_ling_str."，日元身<em>".$bazi['wang_shuai']['riyuan']."</em>。";


		$bazi['outline'] = $str;
	}
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
