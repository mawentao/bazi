<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
/**
 * 八字命例
 * C::m('#bazi#bazi_case')->func()
 **/
class model_bazi_case
{
	// 数字映射
	private $lunar_number = array (
		'1'  => '一', '11' => '十一', '21' => '廿一', '31' => '三十一',
		'2'  => '二', '12' => '十二', '22' => '廿二', '32' => '三十二',
		'3'  => '三', '13' => '十三', '23' => '廿三', '33' => '三十三',
		'4'  => '四', '14' => '十四', '24' => '廿四',
		'5'  => '五', '15' => '十五', '25' => '廿五',
		'6'  => '六', '16' => '十六', '26' => '廿六',
		'7'  => '七', '17' => '十七', '27' => '廿七',
		'8'  => '八', '18' => '十八', '28' => '廿八',
		'9'  => '九', '19' => '十九', '29' => '廿九',
		'10' => '十', '20' => '二十', '30' => '三十',
	);
	// 地支到生肖的映射表
	private $zhi_2_shengxiao = array (
		'子'=>'鼠', '丑'=>'牛', '寅'=>'虎', '卯'=>'兔',
		'辰'=>'龙', '巳'=>'蛇',	'午'=>'马',	'未'=>'羊',
		'申'=>'猴',	'酉'=>'鸡',	'戌'=>'狗',	'亥'=>'猪',
	);

	// 八字命理预测核心函数
	public function foresee($caseid) 
	{
		$bazi = C::t('#bazi#bazi_case')->get_by_caseid($caseid);
		if (empty($bazi)) return array();
		// 性别
		$bazi['gender'] = $bazi['gender']=='x' ? '女' : '男';
		// 公历生日
		$tm = strtotime($bazi['solar_calendar']);
		$bazi['sheng_nian'] = intval(date('Y',$tm));   //!< 出生年份
		$bazi['birthday'] = date('Y-m-d',$tm);
		$bazi['solar_calendar'] = $this->get_solar_calendar($bazi['solar_calendar'],$bazi['week']);
		// 农历生日
		$bazi['lunar_calendar'] = $this->get_lunar_calendar($bazi);
		// 生肖
		$bazi['shengxiao'] = $this->zhi_2_shengxiao[$bazi['nian_zhi']];
		// 四柱八字信息(包括十神)
		$theory = C::m('#bazi#bazi_theory');
		$zhu = array('nian','yue','ri','hour');
		$rigan = $bazi["ri_gan"]; //!< 日元
		foreach ($zhu as $z) {
			$gan = $bazi[$z."_gan"];
			$zhi = $bazi[$z."_zhi"];
			if ($zhu=='ri') {
				$bazi[$z."_gan_info"] = $theory->gan_map[$gan];            //!< 日干只需获取天干信息 
			} else {
				$bazi[$z."_gan_info"] = $this->get_gan_info($gan,$rigan);  //!< 其他三柱天干还需要获取十神信息
			}
			$bazi[$z."_zhi_info"] = $this->get_zhi_info($zhi,$rigan);	   //!< 地支信息(包括地支藏干及藏干十神信息)
			// 调整主气藏干顺序
			$zhi_info = &$bazi[$z."_zhi_info"];
			$zhi_info['canggan'][0]['is_zhuqi'] = 1;
			if (count($zhi_info['canggan'])>2) {
				$tmp = $zhi_info['canggan'][0];
				$zhi_info['canggan'][0] = $zhi_info['canggan'][1];
				$zhi_info['canggan'][1] = $tmp;
			}
			// 计算地势(日干在地支的十二长生状态)
			$bazi[$z."_zhi_info"]['dishi'] = $theory->get_dishi($rigan,$zhi);   //!< 地势(生旺衰)
		}
		// 时令信息(旺相休囚死)
		$yue_zhi = $bazi['yue_zhi_info'];
		$yue_zhi_wuxing = $yue_zhi['wuxing'];
		$wuxing_info = $theory->wuxing_map[$yue_zhi_wuxing];  //!< 月支五行

		$bazi['yue_zhi_info']['wuxing_info'] = $wuxing_info;


		$wuxing_shiling = $theory->shiling_map[$wuxing_info['siji']];
		$bazi['shiling'] = array();
		$bazi['wang_shuai'] = array (  //!< 日主旺衰强弱判断
			'de_ling' => 0,  //!< 得时令
			'de_shi' => 0,   //!< 得势
			'de_di' => 0,	 //!< 得地
		);
		$wxsqx=array('旺','相','休','囚','死');
		foreach ($wuxing_shiling as $k=>$wx) {
			$bazi['shiling'][$wxsqx[$k]] = $wx;
			if ($wx==$bazi['ri_gan_info']['wuxing']) {
				$bazi['wang_shuai']['de_ling'] = $wxsqx[$k];
				$bazi['yue_zhi_info']['de_ling'] = $wxsqx[$k];
			}
		}
		// 日元旺衰强弱判断
		$this->wang_shuai_qiang_ruo($bazi);
		// 排大运
		$this->pai_dayun($bazi);
		// 排流年
		$this->pai_liunian($bazi);

		return $bazi;
	}

	// 获取公历生日
	public function get_solar_calendar($day,$week) 
	{/*{{{*/
		$tm = strtotime($day);
		$weekmap = array('日','一','二','三','四','五','六'); 
		return date("Y年m月d日",$tm)."（周".$weekmap[$week]."）";
	}/*}}}*/

	// 获取农历生日
	public function get_lunar_calendar(array $bazi)
	{/*{{{*/
		$lc = $bazi['lunar_calendar'];
		$m = intval(substr($lc,4,2));
		$m = $m==1 ? '正' : $this->lunar_number[$m];
		$d = intval(substr($lc,6,2));
		$d = $d<10 ? '初'.$this->lunar_number[$d] : $this->lunar_number[$d];
		$run = substr($lc,-1)=='0' ? '' : '闰';
		$res = $bazi['nian_gan'].$bazi['nian_zhi']."年".$run.$m."月".$d;
		return $res;
	}/*}}}*/
	
	// 日元旺衰强弱判断
	public function wang_shuai_qiang_ruo(&$bazi)
	{/*{{{*/
		$sizhu = array('nian','yue','ri','hour');
		// 得令
		$lingzhi = array('旺'=>3,'相'=>2,'休'=>-1,'囚'=>-2,'死'=>-3);
		$de_ling = $lingzhi[$bazi['wang_shuai']['de_ling']];
		// 得地&得势
		$de_di = 0;
		$de_shi = 0;
		$bizhu_shishen = array('正印','偏印','比肩','劫财');  //!< 对日元有生扶比助的十神
		$bizhu_dishi = array('长生','冠带','禄','帝旺');   //!< 对日元有利的地势
		$haoqie_dishi = array('衰','病','死','绝');   //!< 对日元不利的地势
		foreach ($sizhu as $zhu) {
			// 得地
			$zhi_di_shi = $bazi[$zhu.'_zhi_info']['dishi'];
			if (in_array($zhi_di_shi,$bizhu_dishi)) ++$de_di;
			else if (in_array($zhi_di_shi,$haoqie_dishi)) --$de_di;
			// 得势
			if ($zhu!='ri') {
				$gan_shi_shen = $bazi[$zhu.'_gan_info']['shishen']['name'];
				if (in_array($gan_shi_shen,$bizhu_shishen)) {
					++$de_shi;
				} else {
					--$de_shi;
				}
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
		$bazi['wang_shuai'] = array (
			'de_ling' => $de_ling*$weight['de_ling'],
			'de_di' => $de_di*$weight['de_di'],
			'de_shi' => $de_shi*$weight['de_shi'],
			'formula' => $exp,
			'score' => $de_ling*$weight['de_ling']+$de_di*$weight['de_di']+$de_shi*$weight['de_shi'],
		);
		$score = $bazi['wang_shuai']['score'];
		$v = 0;
		if ($score<=-2.5) $v = 0;
		else if ($score<-1.5) $v = 1;
		else if ($score<0.5) $v = 2;
		else if ($score<1.5) $v = 3;
		else if ($score<2.5) $v = 4;
		else $v = 5;
		$map = array('衰极','衰','弱','强','旺','旺极');
		$bazi['wang_shuai']['riyuan'] = $map[$v];
	}/*}}}*/

	// 排大运
	public function pai_dayun(&$bazi)
	{/*{{{*/
		$yue_gan = $bazi['yue_gan'];
		$yue_zhi = $bazi['yue_zhi'];
		$gender = $bazi['gender'];
		$nian_gan_yy = $bazi['nian_gan_info']['yy'];
		$sort = 1;                                       //!< 阳男阴女顺排
		if (($nian_gan_yy=='阴' && $gender=='男') ||     //!< 阴男阳女逆排
		    ($nian_gan_yy=='阳' && $gender=='女')) {
			$sort = -1;
		}
		// 计算大运起始年份
		$sheng_nian = $bazi['sheng_nian'];
		$qiyun_nian = C::t('#bazi#bazi_calendar')->get_qiyun_nian($bazi['birthday'],$sort);
		$bazi['qiyun_nian'] = $qiyun_nian;
		$bazi['qiyun_sui'] = $qiyun_nian-$sheng_nian;
		// 排大运
		$bazi['dayun']=C::m('#bazi#bazi_theory')->get_ganzhi_seq($yue_gan,$yue_zhi,$sort,9);
		$i = 0;
		$rigan = $bazi["ri_gan"]; //!< 日元
		$theory = C::m('#bazi#bazi_theory');
		foreach ($bazi['dayun'] as &$row) {
			$row['nian'] = $qiyun_nian + $i*10;  //!< 10年换运
			$row['age'] = $bazi['qiyun_sui'] + $i*10;  //!< 岁数
			$row['gan_info'] = $this->get_gan_info($row['gan'], $rigan);
			$row['zhi_info'] = $this->get_zhi_info($row['zhi'], $rigan);
			++$i;
		}
		//print_r($bazi['dayun']);
	}/*}}}*/

	// 排流年
	public function pai_liunian(&$bazi)
	{/*{{{*/
		$liunian = array();
		$qiyun_nian = $bazi['qiyun_nian'];  //!< 起运年(从起运年开始排)
		$qiyun_sui = $bazi['qiyun_sui'];    //!< 起运岁
		$rigan = $bazi["ri_gan"];           //!< 日元

		// 获取出生那年的干支
		$theory = C::m('#bazi#bazi_theory');
		$sheng_nian_gan_zhi = $theory->get_gan_zhi_of_year($bazi['sheng_nian']);
		$gan = $sheng_nian_gan_zhi['gan'];	//!< 出生那年的天干（不一定是八字中的年干）
		$zhi = $sheng_nian_gan_zhi['zhi'];  //!< 出生那边的地支（不一定是八字中的年支）

		// 每10年一柱
		for ($i=0;$i<10;++$i) {
			$rownian = array();
			$k=0;
			foreach ($bazi['dayun'] as $row) {
				$idx = $qiyun_sui + $i + ($k*10);
				$ganzhi = $theory->get_gan_zhi($gan,$zhi,$idx);
				$ganzhi['nian'] = $row['nian'] + $i;
				$ganzhi['gan_info'] = $this->get_gan_info($ganzhi['gan'], $rigan);
				$ganzhi['zhi_info'] = $this->get_zhi_info($ganzhi['zhi'], $rigan);
				$rownian[] = $ganzhi;
				++$k;
			}
			$liunian[] = $rownian;
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

}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
