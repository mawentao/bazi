<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
/**
 * 八字格局
 **/
class bazi_analyze_geju
{
    public static function analyze(&$baziCase)
    {
        $bazi = &$baziCase->data;

		$bazi['geju'] = array (
			'name'  => '',  //!< 格局名称
			'cate'  => '',	//!< 格局类别
            'alias' => '',  //!< 别名
		);



		$gejuarr = array(
//			'tongxinge',	//!< 同心格（母吾同心，子吾同心）
//			'banbige',		//!< 半壁格
//			'shuangzhuge',	//!< 双柱格
//			'lurenge',		//!< 禄刃格
			'zhengge',		//!< 正格
		);

		foreach ($gejuarr as $gj) {
			$fun = "check_".$gj;
			if (self::$fun($bazi)) {
				break;
			}
		}

		//die(json_encode($bazi));
	}

	// 变格-半壁格：两种五行各占一半，且力量相当
	private static function check_banbige(&$bazi)
	{/*{{{*/
		$wuxing_map = array();
		$zhuarr = array('nian','yue','ri','hour');
		foreach ($zhuarr as $zhu) {
			$wx = $bazi[$zhu.'_gan_info']['wuxing'];
			$wuxing_map[$wx]++;
			$wx = $bazi[$zhu.'_zhi_info']['wuxing'];
			$wuxing_map[$wx]++;
		}
		foreach ($wuxing_map as $v) {
			if ($v!=4) return false;
		}
		$bazi['geju']['name'] = '双柱格';
		$bazi['geju']['cate'] = '两神成象格';
		return true;
	}/*}}}*/

	// 变格-双柱格
	private static function check_shuangzhuge(&$bazi)
	{/*{{{*/
		$nianzhu = $bazi['nian_gan'].$bazi['nian_zhi']; //!< 年柱
		$yuezhu = $bazi['yue_gan'].$bazi['yue_zhi'];    //!< 月柱
		$rizhu = $bazi['ri_gan'].$bazi['ri_zhi'];       //!< 日柱
		$shizhu = $bazi['hour_gan'].$bazi['hour_zhi'];  //!< 时柱
		if (($nianzhu==$yuezhu && $rizhu==$shizhu) ||
			($nianzhu==$rizhu && $yuezhu==$shizhu) ||
			($nianzhu==$shizhu && $yuezhu==$rizhu)
		) {
			$bazi['geju']['name'] = '双柱格';
			$bazi['geju']['cate'] = '两神成象格';
			return true;
		}
		return false;
	}/*}}}*/

	// 变格-同心格（母吾同心，子吾同心）
	private static function check_tongxinge(&$bazi)
	{/*{{{*/
		$shishen_map = array();
		$zhuarr = array('nian','yue','ri','hour');
		foreach ($zhuarr as $zhu) {
			if ($zhu!='日') {
				$ss = $bazi[$zhu.'_gan_info']['shishen']['name'];
				$shishen_map[$ss]++;
			}
			$ss = $bazi[$zhu.'_zhi_info']['shishen']['name'];
			$shishen_map[$ss]++;
		}
		$keys = array_keys($shishen_map);
		$yuelingshishen = $bazi['yue_zhi_info']['shishen']['name'];  //!< 月令十神

		// 判断母吾同心格
		$ssarr = array('比肩','劫财','正印','偏印');
		$mwtx = true;
		foreach ($keys as $k) {
			if (!in_array($k,$ssarr)) {
				$mwtx=false;
				break;
			}
		}
		if ($mwtx) {
			$bazi['geju']['name'] = '母吾同心格';
			$bazi['geju']['cate'] = '两神成象格';
			return true;
		}

		// 判断是否子吾同心格
		$mwtx = true;
		$ssarr = array('比肩','劫财','食神','伤官');
		foreach ($keys as $k) {
			if (!in_array($k,$ssarr)) {
				$mwtx=false;
				break;
			}
		}
		if ($mwtx) {
			$bazi['geju']['name'] = '子吾同心格';
			$bazi['geju']['cate'] = '两神成象格';
			return true;
		}
		return false;
	}/*}}}*/

	// 禄刃格
	private static function check_lurenge(&$bazi)
	{/*{{{*/
		$yue_ling_di_shi = $bazi['yue_zhi_info']['dishi'];
		if ($yue_ling_di_shi == '禄') {
			$bazi['geju']['name'] = '建禄格';
			$bazi['geju']['cate'] = '正格';
			return true;
		}
		if ($yue_ling_di_shi == '帝旺') {
			$bazi['geju']['name'] = '羊刃格';
			$bazi['geju']['cate'] = '正格';
			return true;
		}
		return false;
	}/*}}}*/

	// 正格
	private static function check_zhengge(&$bazi)
	{/*{{{*/
        $yueLing = $bazi['zhi'][1]['z'];                //!< 月令
        $yueZhiInfo = $bazi['dict']['dizhi'][$yueLing]; //!< 月令地支详情
		$tougan = array (
			$bazi['gan'][0]['shishen'],
			$bazi['gan'][1]['shishen'],
			$bazi['gan'][3]['shishen'],
		);
        // 按月令主,中,余气排列
        foreach ($yueZhiInfo['canggan'] as $canggan) {
			$ss = $canggan['shishen'];
			if (in_array($ss,$tougan)) {
				if ($ss=='比肩') $ss = "建禄";
				else if ($ss=='劫财') $ss = "羊刃";
				$bazi['geju']['name'] = $ss.'格';
				$bazi['geju']['cate'] = '正格';
				return true;
			}
		}
		// 啥都不是取月支主气定格局
		$bazi['geju']['name'] = $yueLing.'格';
		$bazi['geju']['cate'] = '正格';
		return true;
	}/*}}}*/
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
