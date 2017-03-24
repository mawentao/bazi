<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
/**
 * 论婚恋 - 基础分析
 * bazi_env::c('analyze_hunlian_base')->analyze($bazi);
 **/
class analyze_hunlian_base
{
    public function analyze(&$bazi)
    {
		$hunlian = array();
		// 男命
		if ($bazi['gender']=='男') {
			$hunlian = array(
				'spouse_name' => '妻星',
				'spouse_star' => $this->see_spouse($bazi),   //!< 配偶星
				'spouse_juemap' => $this->see_jue($bazi,array(
					'fuqigong_chong',   //!< 夫妻宫被冲
					'tiangan',			//!< 看天干
				)),
//				'hunlian_liunian' => $this->see_hunlian_liunian($bazi), //!< 看婚恋流年
			);
		} 
		// 女命 
		else {
			$hunlian = array(
				'spouse_name' => '夫星',
				'spouse_star' => $this->see_spouse($bazi),   	//!< 配偶星
				'spouse_juemap' => $this->see_jue($bazi,array(
					'fuqigong_chong',   //!< 夫妻宫被冲
					'tiangan',			//!< 看天干
				)),
//				'hunlian_liunian' => $this->see_hunlian_liunian($bazi), //!< 看婚恋流年
			);
		}

		$bazi['hunlian'] = &$hunlian;
	}

	// 配偶星
	private function see_spouse(&$bazi)
	{/*{{{*/
		$shenmap = $bazi['shi_shen_map'];
		$spouse_star = array (
			'正' => array(),
			'偏' => array(),
		);
		// 女命
		if ($bazi['gender']=='女') {
			$spouse_star['正'] = $shenmap['正官'];
			$spouse_star['偏'] = $shenmap['七杀'];
		}
		// 男命
		else {
			$spouse_star['正'] = $shenmap['正财'];
			$spouse_star['偏'] = $shenmap['偏财'];
		}
		return $spouse_star;
	}/*}}}*/

	// 婚恋诀
	private function see_jue(&$bazi,$funs)
	{/*{{{*/
		$juemap = array();
		foreach ($funs as $f) {
			$fun = "jue_$f";
			$res = $this->$fun($bazi);
			if (!empty($res)) {
				foreach ($res as $im) {
					$jx = $im['jixiong'];	//!< ji, zhong, xiong
					if (!isset($juemap[$jx])) $juemap[$jx] = array();
					unset($im['jixiong']);
					$juemap[$jx][] = $im;
				}
			}
		}
		return $juemap;
	}/*}}}*/

	// 夫妻宫被冲
	private function jue_fuqigong_chong(&$bazi)
	{/*{{{*/
		$res = array();
		$links = &$bazi['wuxing_graph']['links'];
		foreach ($links['zhi_chong'] as $lk) {
			if ($lk[0]=='日支' || $lk[1]=='日支') {
				$res[] = array(
					'jixiong' => 'xiong',
					'name' => '夫妻宫被冲',
					'jue' => '夫妻宫被冲，婚姻动荡不顺。',
				);
			}
		}
		//die(json_encode($links));
		return $res;
	}/*}}}*/

	// 看天干
	private function jue_tiangan(&$bazi)
	{
		$res = array();
		$nummap = array('零','一','两','三','四');
		// 统计各十神个数
		$shenmap = array();		 //!< 天干十神统计
		$allshenmap = array();   //!< 干支全部十神统计(地支只看主气藏干)
		foreach (array('nian','yue','hour') as $z) {
			$shen = $bazi[$z."_gan_info"]['shishen']['name'];
			$shenmap[$shen]++;
			$allshenmap[$shen]++;
			foreach ($bazi[$z."_zhi_info"]['canggan'] as $im) {
				if ($im['is_zhuqi']==1) {
					$allshenmap[$im['shishen']['name']]++;
				}
			}
		}
		// 男命
		if ($bazi['gender']=='男') {
			foreach (array('正财','偏财') as $s) {
				if ($shenmap[$s]>1) {
					$res[] = array(
						'jixiong' => 'zhong',
						'name'    => $nummap[$shenmap[$s]]."妻星透天干",
						'jue'     => $nummap[$shenmap[$s]]."妻星透天干，命主女人缘好，一生会经历多个女人。",
					);
				}
			}
			if ($shenmap['正财']>0 && $shenmap['偏财']>0) {
				$res[] = array(
					'jixiong' => 'xiong',
					'name' => '正偏财透天干',
					'jue' => '男命正偏财透天干，多作两度新郎。为人风流好色，容易出轨。',
				);
			}
		}
		// 女命
		else {
			if ($shenmap['正官']>0 && $shenmap['七杀']>0) {
				$res[] = array(
					'jixiong' => 'xiong',
					'name' => '官杀混杂',
					'jue' => '女命官杀混杂，风流好色，婚姻不顺。',
				);
			}
		}
		//die(json_encode($shenmap));
		return $res;
	}

	// 婚恋流年
	private function see_hunlian_liunian(&$bazi)
	{
		$hunlian_liunian = array();
		$sheng_nian = $bazi[sheng_nian];
		foreach ($bazi['liunian'] as &$rows) {
			foreach ($rows as &$item) {
				//$age = $item['nian'] - $
			}
		}
		die(json_encode($bazi));
	}
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
