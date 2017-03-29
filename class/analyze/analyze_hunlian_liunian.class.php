<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
/**
 * 论婚恋 - 流年分析 （婚恋基础分析之后）
 * bazi_env::c('analyze_hunlian_liunian')->analyze($bazi);
 **/
class analyze_hunlian_liunian
{
    public function analyze(&$bazi)
    {
		$this->see_hunlian_liunian($bazi);
	}

	// 婚恋流年
	private function see_hunlian_liunian(&$bazi)
	{
		$hunlian_liunian = array();
		$sheng_nian = $bazi['sheng_nian'];
		for ($age=20; $age<=44; ++$age) {
			$nian = $sheng_nian + $age;
			$hunlian_liunian[$nian] = array (
				'year' => $nian,
				'gan_he' => $this->see_gan_he($bazi,$nian),           	//!< 日干相合
				'zhi_he' => $this->see_zhi_he($bazi,$nian),             //!< 日支相合（合配偶宫）
				'zhi_chong' => $this->see_zhi_chong($bazi,$nian),       //!< 日支相冲（冲配偶宫）
				'gan_taohua' => $this->see_zheng_taohua($bazi,$nian), 	//!< 日干桃花（正桃花）
				'zhi_taohua' => $this->see_taohua($bazi,$nian),  		//!< 日支桃花
			);
		}
		//die(json_encode($hunlian_liunian));
		$bazi['hunlian']['liunian'] = &$hunlian_liunian;

//		die(json_encode($hunlian_liunian));
	}

	// 看日干相合
	private function see_gan_he(&$bazi,$year)
	{
		$rigan = $bazi['ri_gan'];
		$liunian_gan = $bazi['liunian'][$year]['gan'];
		$k1 = $rigan.$liunian_gan;
		$k2 = $liunian_gan.$rigan;	
		$gan_he_map = C::m('#bazi#bazi_relation')->gan_he_map;
		if (isset($gan_he_map[$k1]) || isset($gan_he_map[$k2])) {
			return 1;
		}
		return 0;
	}

	// 看日支相合
	private function see_zhi_he(&$bazi,$year)
	{
		$rizhi = $bazi['ri_zhi'];
		$liunian_zhi = $bazi['liunian'][$year]['zhi'];
		$k1 = $rizhi.$liunian_zhi;
		$k2 = $liunian_zhi.$rizhi;	
		$zhi_banhe_map = C::m('#bazi#bazi_relation')->zhi_banhe_map;
		$zhi_liuhe_map = C::m('#bazi#bazi_relation')->zhi_liuhe_map;
		if (isset($zhi_banhe_map[$k1]) || isset($zhi_banhe_map[$k2])) {
			return '半合';
		}
		if (isset($zhi_liuhe_map[$k1]) || isset($zhi_liuhe_map[$k2])) {
			return '合';
		}
		return 0;
	}

	// 看日支冲
	private function see_zhi_chong(&$bazi,$year)
	{
		$rizhi = $bazi['ri_zhi'];
		$liunian_zhi = $bazi['liunian'][$year]['zhi'];
		$k1 = $rizhi.$liunian_zhi;
		$k2 = $liunian_zhi.$rizhi;
		$arr = array(
			'zhi_chong_map' => '支冲',
		);
		foreach ($arr as $rt => $v) {
			$map = &C::m('#bazi#bazi_relation')->$rt;
			if (isset($map[$k1]) || isset($map[$k2])) {
				return $v;
			}
		}
		return 0;
	}

	// 看日干桃花
	private function see_zheng_taohua(&$bazi,$year)
	{
		$zheng_taohua_map = array (
			'甲'=>'卯亥', '丙'=>'申子', '戊'=>'戌午', '庚'=>'巳亥', '壬'=>'戌午',
			'乙'=>'卯亥', '丁'=>'申子', '己'=>'戌午', '辛'=>'巳亥', '癸'=>'戌午',
		);
		$rigan = $bazi['ri_gan'];
		$liunian_zhi = $bazi['liunian'][$year]['zhi'];
		$str = $zheng_taohua_map[$rigan];
		if (mb_strstr($str,$liunian_zhi,0,'UTF-8')===false) {
			return 0;
		}
		return 1;
	}

	// 看日支桃花
	private function see_taohua(&$bazi,$year)
	{
		$rizhi = $bazi['ri_zhi'];
		$liunian_info = $bazi['liunian'][$year];
		$liunian_zhi = $liunian_info['zhi'];

		$taohuamap = array (
			'寅'=>'卯','午'=>'卯','戌'=>'卯',
			'巳'=>'午','酉'=>'午','丑'=>'午',
			'申'=>'酉','子'=>'酉','辰'=>'酉',
			'亥'=>'子','卯'=>'子','未'=>'子',
		);
			
		if ($taohuamap[$rizhi]==$liunian_zhi) {
			return 1;
		}
		return 0;
	}	


	// 分析流年的桃花指数
	public function analyze_taohua_for_liunian(&$bazi,$year)
	{
		$taohua = 0;
		$liunian_info = $bazi['liunian'][$year];
		$liunian_gan = $liunian_info['gan'];
		$liunian_gan_wuxing = $liunian_info['gan_info']['wuxing'];
		$liunian_zhi = $liunian_info['zhi'];
		$liunian_zhi_wuxing = $liunian_info['zhi_info']['wuxing'];


		//1. 看是否显现配偶星
		$spouse_star = $bazi['hunlian']['spouse_star'];	
		$spouse_star_wuxing = $spouse_star['wuxing'];
		if ($liunian_gan_wuxing==$spouse_star_wuxing || $liunian_zhi_wuxing==$spouse_star_wuxing) {
			//$taohua++;
		}

		return $taohua;
	}

}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
