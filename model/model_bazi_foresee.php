<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
/**
 * 八字论断篇
 * C::m('#bazi#bazi_case')->func()
 **/
class model_bazi_foresee
{
	public function foresee(&$bazi)
	{
		$foresee = array(
			'outline' => $this->foresee_outline($bazi),
			'shensha' => C::m('#bazi#bazi_shensha')->check_all($bazi),
		);
		return $foresee;
	}

	// 总论
	private function foresee_outline(&$bazi) 
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

//die($str);
		return $str;
	}
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
