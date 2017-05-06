<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
/**
 * 八字格局
 * bazi_env::c('analyze_geju')->analyze($bazi);
 **/
class analyze_geju
{
    public function analyze(&$bazi)
    {
		$bazi['geju'] = array (
			'name' => '',   //!< 格局名称
			'cate' => '',	//!< 格局类别
		);

		$gejuarr = array(
			'tongxinge',	//!< 同心格（母吾同心，子吾同心）
			'banbige',		//!< 半壁格
			'shuangzhuge',	//!< 双柱格
			'lurenge',		//!< 禄刃格
			'zhengge',		//!< 正格
		);

		foreach ($gejuarr as $gj) {
			$fun = "check_".$gj;
			if ($this->$fun($bazi)) {
				break;
			}
		}

		//die(json_encode($bazi['geju']));
	}

	// 变格-半壁格：两种五行各占一半，且力量相当
	private function check_banbige(&$bazi)
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
	private function check_shuangzhuge(&$bazi)
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
	private function check_tongxinge(&$bazi)
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
	private function check_lurenge(&$bazi)
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
	private function check_zhengge(&$bazi)
	{/*{{{*/
		$tougan = array (
			$bazi['nian_gan_info']['shishen']['name'],
			$bazi['yue_gan_info']['shishen']['name'],
			$bazi['hour_gan_info']['shishen']['name'],
		);
		$yueling = $bazi['yue_zhi_info']['shishen']['name'];  //!< 月令主气藏干		
		if (in_array($yueling,$tougan)) {
			$bazi['geju']['name'] = $yueling.'格';
			$bazi['geju']['cate'] = '正格';
			return true;
		}
		foreach ($bazi['yue_zhi_info']['canggan'] as $canggan) {
			$ss = $canggan['shishen']['name'];
			if (in_array($ss,$tougan)) {
				if ($ss=='比肩') $ss = "建禄";
				else if ($ss=='劫财') $ss = "羊刃";
				$bazi['geju']['name'] = $ss.'格';
				$bazi['geju']['cate'] = '正格';
				return true;
			}
		}
		/////////////////////////////////////////////////
		// 啥都不是取月支主气定格局
		$bazi['geju']['name'] = $yueling.'格';
		$bazi['geju']['cate'] = '正格';
		return true;

		/////////////////////////////////////////////////
		//return false;
	}/*}}}*/

}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
