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
			'wuxing_graph' => $this->wuxing_graph($bazi),
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

	// 五行生克关系分析
	private function wuxing_graph(&$bazi)
	{
		$res = array (
			'nodes' => array(
				array('name'=>$bazi['nian_gan'],'wuxing'=>$bazi['nian_gan_info']['wuxing'],'role'=>'年干'),
				array('name'=>$bazi['nian_zhi'],'wuxing'=>$bazi['nian_zhi_info']['wuxing'],'role'=>'年支'),
				array('name'=>$bazi['yue_gan'],'wuxing'=>$bazi['yue_gan_info']['wuxing'],'role'=>'月干'),
				array('name'=>$bazi['yue_zhi'],'wuxing'=>$bazi['yue_zhi_info']['wuxing'],'role'=>'月支'),
				array('name'=>$bazi['ri_gan'],'wuxing'=>$bazi['ri_gan_info']['wuxing'],'role'=>'日干'),
				array('name'=>$bazi['ri_zhi'],'wuxing'=>$bazi['ri_zhi_info']['wuxing'],'role'=>'日支'),
				array('name'=>$bazi['hour_gan'],'wuxing'=>$bazi['hour_gan_info']['wuxing'],'role'=>'时干'),
				array('name'=>$bazi['hour_zhi'],'wuxing'=>$bazi['hour_zhi_info']['wuxing'],'role'=>'时支'),
			),
			'links' => array(
				'gan_he'     => array(),   //!< 天干合
				'gan_chong'  => array(),   //!< 天干冲
				//'zhi_hui'    => array(),   //!< 地支相会
				//'zhi_san_he' => array(),   //!< 地支三合
			),
		);
		$relation = C::m('#bazi#bazi_relation');
		// 天干合冲关系
		$gan_he_chong = $relation->gan_he_chong($res['nodes']);		
		$res['links']['gan_he'] = $gan_he_chong['he'];
		$res['links']['gan_chong'] = $gan_he_chong['chong'];
		// 同柱干支关系
		$nodemap = array();
		foreach ($res['nodes'] as $nd) {
			$nodemap[$nd['role']] = $nd;
		}
		$gan_zhi_tongzhu = $relation->gan_zhi_tongzhu($nodemap);
		$res['links']['sheng'] = $gan_zhi_tongzhu['sheng'];
		$res['links']['ke'] = $gan_zhi_tongzhu['ke'];
		

//print_r($res['links']);
//die(0);
		return $res;
	}
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
