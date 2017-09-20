<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
/**
 * 论婚恋 - 流年分析 （婚恋基础分析之后）
 **/
class bazi_analyze_hunlian_liunian
{
    public static function analyze(&$baziCase)
    {
        $bazi = &$baziCase->data;
		self::see_hunlian_liunian($bazi);
	}

	// 婚恋流年
	private static function see_hunlian_liunian(&$bazi)
	{
		$hunlian_liunian = array();
		$sheng_nian = $bazi['birthYear'];
		for ($age=20; $age<=44; ++$age) {
			$nian = $sheng_nian + $age;
			$hunlian_liunian[$nian] = array (
				'year' => $nian,
				'gan_he' => self::see_gan_he($bazi,$nian),           //!< 流年天干是否与日干相合
				'zhi_he' => self::see_zhi_he($bazi,$nian),             //!< 日支相合（合配偶宫）
				'zhi_chong' => self::see_zhi_chong($bazi,$nian),       //!< 日支刑冲（刑冲配偶宫）
				'gan_taohua' => self::see_zheng_taohua($bazi,$nian), 	//!< 日干桃花（正桃花）
				'zhi_taohua' => self::see_taohua($bazi,$nian),  		//!< 日支桃花
			);
		}
		$bazi['hunLian']['liunian'] = &$hunlian_liunian;
		//die(json_encode($bazi['hunLian']));
	}


	// 看日干相合
	private static function see_gan_he(&$bazi,$year)
	{/*{{{*/
		$rigan = $bazi['riYuan'];
		$liunian_gan = $bazi['liunian'][$year]['gan'];
        $arr = array($rigan,$liunian_gan);
        $arr = bazi_base::sort_gans($arr);
        $k = implode('',$arr);
        $arr = bazi_base::$GAN_RELATIONS[$k];
        $r = implode('|',$arr);
        if ($r && strpos($r,'合')!==false) {
            return 1;
        }
        return 0;
	}/*}}}*/

	// 看日支相合
	private function see_zhi_he(&$bazi,$year)
	{/*{{{*/
		$rizhi = $bazi['zhi'][2]['z'];
		$liunian_zhi = $bazi['liunian'][$year]['zhi'];
        $arr = array($rizhi,$liunian_zhi);
        $arr = bazi_base::sort_zhis($arr);
        $k = implode('',$arr);
        $arr = bazi_base::$ZHI_RELATIONS[$k];
        $r = implode('|',$arr);
        if ($r && strpos($r,'合')!==false) {
            return 1;
        }
        return 0;
	}/*}}}*/

	// 看日支刑冲
	private function see_zhi_chong(&$bazi,$year)
	{
        $rizhi = $bazi['zhi'][2]['z'];
		$liunian_zhi = $bazi['liunian'][$year]['zhi'];
        if ($rizhi==$liunian_zhi) return 0;

        $arr = array($rizhi,$liunian_zhi);
        $arr = bazi_base::sort_zhis($arr);
        $k = implode('',$arr);
        $arr = bazi_base::$ZHI_RELATIONS[$k];
        $r = implode('|',$arr);
        if (strpos($r,'冲')!==false || strpos($r,'刑')!==false) {
            return 1;
        }
		return 0;
	}

	// 看日干桃花
	private static function see_zheng_taohua(&$bazi,$year)
	{/*{{{*/
		$zheng_taohua_map = array (
			'甲'=>'卯亥', '丙'=>'申子', '戊'=>'戌午', '庚'=>'巳亥', '壬'=>'戌午',
			'乙'=>'卯亥', '丁'=>'申子', '己'=>'戌午', '辛'=>'巳亥', '癸'=>'戌午',
		);
		$rigan = $bazi['riYuan'];
		$liunian_zhi = $bazi['liunian'][$year]['zhi'];
		$str = $zheng_taohua_map[$rigan];
		if (mb_strstr($str,$liunian_zhi,0,'UTF-8')===false) {
			return 0;
		}
		return 1;
	}/*}}}*/

	// 看日支桃花
	private static function see_taohua(&$bazi,$year)
	{/*{{{*/
		$rizhi = $bazi['zhi'][2]['z'];
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
	}/*}}}*/

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
