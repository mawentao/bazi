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
		$hunlian = array(
			'spouse_name' => $bazi['gender']=='男' ? '妻星' : '夫星',
			'spouse_star' => $this->see_spouse($bazi),	//!< 配偶星
		);
		// 婚恋诀
		$funs = array (
			'fuqigong',   //!< 看夫妻宫
			'tiangan',	  //!< 看天干
			'shensha',	  //!< 看神煞
		);
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
		$hunlian['spouse_juemap'] = &$juemap;
		// 返回
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

		$gan = $spouse_star['正'];
		$ganinfo = C::m('#bazi#bazi_theory')->gan_map[$gan];
		$spouse_star['wuxing'] = $ganinfo['wuxing'];
		return $spouse_star;
	}/*}}}*/

	// 夫妻宫刑冲
	private function jue_fuqigong(&$bazi)
	{/*{{{*/
		$res = array();
		$links = &$bazi['wuxing_graph']['links'];
		// 冲
		foreach ($links['zhi_chong'] as $lk) {
			if ($lk[0]=='日支' || $lk[1]=='日支') {
				$res[] = array(
					'jixiong' => 'xiong',
					'name' => '夫妻宫被冲',
					'jue' => '夫妻宫被冲，婚姻动荡不顺。',
				);
			}
		}
		// 刑
		foreach ($links['zhi_xing'] as $lk) {
			if ($lk[0]=='日支' || $lk[1]=='日支') {
				$res[] = array(
					'jixiong' => 'xiong',
					'name' => '夫妻宫被刑',
					'jue' => '夫妻宫被刑（'.$lk[2].'），婚姻动荡不顺。',
				);
			}
		}
		// 羊刃
		if ($bazi['ri_zhi_info']['dishi']=='帝旺') {
			$res[] = array(
				'jixiong' => 'xiong',
				'name' => '日坐羊刃',
				'jue' => '日坐羊刃，婚姻波折，早婚易离。',
			);
		}
		//die(json_encode($bazi));
		return $res;
	}/*}}}*/

	// 看天干
	private function jue_tiangan(&$bazi)
	{/*{{{*/
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
			if ($shenmap['比肩']>0 && $shenmap['劫财']>0) {
				$res[] = array(
					'jixiong' => 'xiong',
					'name' => '比劫重重',
					'jue' => '男命比劫重重易克妻。',
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
			/*
			if ($shenmap['正官']>0 && $shenmap['伤官']>0) {
				$res[] = array(
					'jixiong' => 'xiong',
					'name' => '伤官见官',
					'jue' => '女命伤官见官必克夫。',
				);
			}*/
		}
		//die(json_encode($shenmap));
		return $res;
	}/*}}}*/

	// 看神煞
	private function jue_shensha(&$bazi)
	{/*{{{*/
		$res = array();
		$shaarr = array('阴阳差错煞','红艳煞','孤鸾煞','桃花','孤辰','寡宿');
		foreach ($bazi['shensha'] as $type => &$list) {
			$jixiong = trim($type,'sha');
			foreach ($list as &$item) {
				$sha_name = $item['name'];

				if (mb_strpos($sha_name,'桃花',0,'utf-8')!==false) {
					$sha_name = '桃花';
					$type = 'ji';
				}

				if (in_array($sha_name,$shaarr)) {
					$res[] = array(
						'jixiong' => $jixiong,
						'name' => $item['name'],
						'jue' => $item['jue'],
					);
				}
			}
		}
		return $res;
	}/*}}}*/

}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
