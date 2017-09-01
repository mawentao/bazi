<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
/**
 * 先天命盘分析
 *    装十神
 *    排大运流年
 **/
class bazi_analyze_mingpan
{
    public function analyze(&$baziCase)
    {   
		$bazi = &$baziCase->data;
		$rigan = $bazi['gan'][2]['z'];  //!< 日元
		$bazi['riYuan'] = $rigan;

		// 四柱八字信息(包括十神)
		$theory = C::m('#bazi#bazi_theory');

		for ($i=0;$i<4;++$i) {
			$gan = &$bazi['gan'][$i];	//!< 柱干
			$zhi = &$bazi['zhi'][$i];	//!< 柱支
			// 天干含义
			$gan['info'] = $theory->gan_map[$gan['z']];
			// 其他三柱天干对照日元的十神信息
			if ($i!=2) {
				$gan['shishen'] = $theory->get_shishen($rigan,$gan['z']);
			}
			// 地支含义(包括地支藏干及藏干十神信息)
			$zhi['info'] = self::get_zhi_info($zhi['z'],$rigan);
			$zhi['shishen'] = $zhi['info']['canggan'][0]['shishen'];  //!< 地支十神为主气藏干十神
			// 调整主气藏干顺序(如果是3个,把主气放在中间)
			$zhi['info']['canggan'][0]['isZhuQi'] = 1;
			if (count($zhi['info']['canggan'])>2) {
				$tmp = $zhi['info']['canggan'][0];
				$zhi['info']['canggan'][0] = $zhi['info']['canggan'][1];
				$zhi['info']['canggan'][1] = $tmp;
			}
			// 计算地势(日干在地支的十二长生状态,生旺衰)
			$zhi['dishi'] = $theory->get_dishi($rigan,$zhi['z']);
		}
		// 空亡信息
		$ri_jiazi = $rigan.$bazi['zhi'][2]['z'];
		$bazi['kongwang'] = $theory->get_kongwang($ri_jiazi);
		// 时令信息(旺相休囚死)
		$yueZhiInfo = &$bazi['zhi'][1]['info'];
		$yueZhiWuXing = $yueZhiInfo['wuxing'];
		$yueZhiWuXingInfo = $theory->wuxing_map[$yueZhiWuXing];	//!< 月支五行
		$yueZhiInfo['wuxingInfo'] = $yueZhiWuXingInfo;
		// 月支五行时令
		$yueZhiWuXingShiLing = $theory->shiling_map[$yueZhiWuXingInfo['siji']];
		$bazi['shiLing'] = array();
		$bazi['wangShuai'] = array (  //!< 日主旺衰强弱判断
			'deLing' => 0,	//!< 得时令
			'deShi'  => 0,	//!< 得势
			'deDi'   => 0,	//!< 得地
		);
		$wxsqx=array('旺','相','休','囚','死');
		$riGanInfo = $bazi['gan'][2]['info'];
		foreach ($yueZhiWuXingShiLing as $k=>$wx) {
			$bazi['shiLing'][$wxsqx[$k]] = $wx;
			if ($wx==$riGanInfo['wuxing']) {
				$bazi['wangShuai']['deLing'] = $wxsqx[$k];
				$yueZhiInfo['deLing'] = $wxsqx[$k];
			}
		}
		// 日元旺衰强弱判断
		self::calWangShuaiQiangRuo($bazi);
		// 排大运
		self::paiDaYun($bazi);
		// 排流年
		self::paiLiuNian($bazi);


		return;
		
		// 排大运
		$this->pai_dayun($bazi);
		// 排流年
		$this->pai_liunian($bazi);

		///////////////////////////////////////////
		// 获取日干十神对应的干
		$this->get_shishenmap($bazi);
		///////////////////////////////////////////

    }   

	// 日元旺衰强弱判断
	public function calWangShuaiQiangRuo(&$bazi)
	{/*{{{*/
		// 得令
		$lingzhi = array('旺'=>3,'相'=>2,'休'=>-1,'囚'=>-2,'死'=>-3);
		$de_ling = $lingzhi[$bazi['wangShuai']['deLing']];
		// 得地&得势
		$de_di = 0;
		$de_shi = 0;
		$bizhu_shishen = array('正印','偏印','比肩','劫财');  //!< 对日元有生扶比助的十神
		$bizhu_dishi = array('长生','冠带','禄','帝旺');   //!< 对日元有利的地势
		$haoxie_dishi = array('衰','病','死','绝');   //!< 对日元不利的地势
		for ($i=0;$i<4;++$i) {
			// 得地
			$zhiDiShi = $bazi['zhi'][$i]['dishi'];
			if (in_array($zhiDiShi,$bizhu_dishi)) ++$de_di;
			else if (in_array($zhiDiShi,$haoxie_dishi)) --$de_di;
			// 得势
			if ($i!=2) {
				$ganShiShen = $bazi['gan'][$i]['shishen']['name'];
				if (in_array($ganShiShen,$bizhu_shishen)) ++$de_shi;
				else --$de_shi;
			}
		}
		// 判断日元旺衰
		$weight = array(
			'de_ling' => 0.5,  //!< 得时  3*0.5 = 1.5
			'de_di' => 0.3,    //!< 得地  3*0.3 = 0.9
			'de_shi' => 0.15,  //!< 得势  4*0.15= 0.6
		);
		$exp = $de_ling."x".$weight['de_ling']."+".
               $de_di."x".$weight['de_di']."+".
               $de_shi."x".$weight['de_shi'];
		$bazi['wangShuai'] = array (
			'deLing'  => $de_ling*$weight['de_ling'],
			'deDi'    => $de_di*$weight['de_di'],
			'deShi'   => $de_shi*$weight['de_shi'],
			'formula' => $exp,
			'score'   => $de_ling*$weight['de_ling']+$de_di*$weight['de_di']+$de_shi*$weight['de_shi'],
		);
		$score = $bazi['wangShuai']['score'];
		$v = 0;
		if ($score<=-2.5) $v = 0;
		else if ($score<-1.5) $v = 1;
		else if ($score<0.5) $v = 2;
		else if ($score<1.5) $v = 3;
		else if ($score<2.5) $v = 4;
		else $v = 5;
		$map = array('衰极','衰','弱','强','旺','旺极');
		$bazi['wangShuai']['riYuan'] = $map[$v];
	}/*}}}*/

	// 排大运
	public function paiDaYun(&$bazi)
	{/*{{{*/
		$rigan   = $bazi["gan"][2]['z']; 	//!< 日元
		$yue_gan = $bazi['gan'][1]['z'];	//!< 月干
		$yue_zhi = $bazi['zhi'][1]['z'];	//!< 月支
		$gender  = $bazi['gender'];
		$nian_gan_yy = $bazi['gan'][0]['info']['yy'];    //!< 年干阴阳属性
		$sort = 1;                                       //!< 阳男阴女顺排
		if (($nian_gan_yy=='阴' && $gender=='男') ||     //!< 阴男阳女逆排
		    ($nian_gan_yy=='阳' && $gender=='女')) {
			$sort = -1;
		}
		// 计算大运起始年份
		$sheng_nian = $bazi['birthYear'];    //!< 命主出生年份
		$qiyun_nian = C::t('#bazi#bazi_calendar')->get_qiyun_nian($bazi['birthDay'],$sort);
		$bazi['qiYunNian'] = $qiyun_nian;
		$bazi['qiYunSui']  = $qiyun_nian-$sheng_nian;
		// 排大运
		$bazi['dayun']=C::m('#bazi#bazi_theory')->get_ganzhi_seq($yue_gan,$yue_zhi,$sort,9);
		$i = 0;
		foreach ($bazi['dayun'] as &$row) {
			$row['nian'] = $qiyun_nian + $i*10;        //!< 10年换运
			$row['age']  = $bazi['qiYunSui'] + $i*10;  //!< 岁数
			$row['ganInfo'] = self::get_gan_info($row['gan'], $rigan);
			$row['zhiInfo'] = self::get_zhi_info($row['zhi'], $rigan);
			$row['liunian'] = array();
			for ($offset=0;$offset<10;++$offset) {
				$row['liunian'][] = $row['nian']+$offset;
			}
			++$i;
		}
		//die(json_encode($bazi['dayun']));
	}/*}}}*/

	// 排流年
	public function paiLiuNian(&$bazi)
	{/*{{{*/
		$liunian = array();					//!< 流年map,key为年份，如2000
		$qiyun_nian = $bazi['qiYunNian'];  	//!< 起运年(从起运年开始排)
		$qiyun_sui = $bazi['qiYunSui'];    	//!< 起运岁
		$rigan = $bazi["riYuan"];           //!< 日元

		// 获取出生那年的干支
		$theory = C::m('#bazi#bazi_theory');
		$sheng_nian_gan_zhi = $theory->get_gan_zhi_of_year($bazi['birthYear']);
		$gan = $sheng_nian_gan_zhi['gan'];	//!< 出生那年的天干（不一定是八字中的年干）
		$zhi = $sheng_nian_gan_zhi['zhi'];  //!< 出生那边的地支（不一定是八字中的年支）

		// 流年干支关系
		$relation = C::m('#bazi#bazi_relation');

		// 从起大运年开始排，直到大运排完
		for ($i=0;$i<10;++$i) {
			$k=0;
			foreach ($bazi['dayun'] as $row) {
				$idx = $qiyun_sui + $i + ($k*10);
				$nian = $row['nian'] + $i;
				// 流年信息
				$ganzhi = $theory->get_gan_zhi($gan,$zhi,$idx);
				$ganzhi['nian'] = $nian;
				$ganzhi['age'] = $nian-$bazi['sheng_nian'];      //!< 岁数
				$ganzhi['ganInfo'] = self::get_gan_info($ganzhi['gan'], $rigan);
				$ganzhi['zhiInfo'] = self::get_zhi_info($ganzhi['zhi'], $rigan);
				$ganzhi['dayun_idx'] = $i;			//!< 大运索引

				// 流年干支生克关系
				$gz = $ganzhi['ganInfo']['wuxing'].$ganzhi['zhiInfo']['wuxing'];
				$zg = $ganzhi['zhiInfo']['wuxing'].$ganzhi['ganInfo']['wuxing'];
				if ($relation->wuxing_sheng[$gz]) { $ganzhi['sheng']='gz'; }
				if ($relation->wuxing_sheng[$zg]) { $ganzhi['sheng']='zg'; }
				if ($relation->wuxing_ke[$gz]) { $ganzhi['ke']='gz'; }
				if ($relation->wuxing_ke[$zg]) { $ganzhi['ke']='zg'; };

				$liunian[$nian] = $ganzhi;
				++$k;
			}
		}

		$bazi['liunian'] = $liunian;
	}/*}}}*/


	// 获取天干的详细信息(包括十神)
	private function get_gan_info($gan,$rigan)
	{/*{{{*/
		$theory = C::m('#bazi#bazi_theory');
		$res = $theory->gan_map[$gan];
		$res['shishen'] = $theory->get_shishen($rigan,$gan);
		return $res;
	}/*}}}*/

	// 获取地支的详细信息(包括十神)
	private function get_zhi_info($zhi,$rigan)
	{/*{{{*/
		$theory = C::m('#bazi#bazi_theory');
		$zhi_info = $theory->zhi_map[$zhi];
		foreach ($zhi_info['canggan'] as &$row) {
			$zhigan = $row;
			$zhiganinfo = $theory->gan_map[$zhigan];
			$row = array(
				'gan' => $zhigan,
				'wuxing' => $zhiganinfo['wuxing'],
				'shishen' => $theory->get_shishen($rigan,$zhigan),
			);
		}
		return $zhi_info;
	}/*}}}*/

	// 获取日干十神对应的干
	private function get_shishenmap(&$bazi)
	{/*{{{*/
		$theory = C::m('#bazi#bazi_theory');
		$ri_gan = $bazi['ri_gan'];
		$gan_arr = array_keys($theory->gan_map);
		$shenmap = array();
		foreach ($gan_arr as $gan) {
			$k = $ri_gan.$gan;
			$shishen = $theory->shishen_table[$k];
			$shenmap[$shishen] = $gan;
		}
		$bazi['shi_shen_map'] = &$shenmap;
	}/*}}}*/


}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
