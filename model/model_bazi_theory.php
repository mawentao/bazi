<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
/**
 * 八字理论相关模块
 * C::m('#bazi#bazi_theory')->func()
 **/
class model_bazi_theory
{
	private $calendar_map;
	// 五行信息
	public $wuxing_map = array (
		'木' => array('siji'=>'春','sheng'=>'火','ke'=>'土'),
		'火' => array('siji'=>'夏','sheng'=>'土','ke'=>'水'),
		'土' => array('siji'=>'四季','sheng'=>'金','ke'=>'火'),
		'金' => array('siji'=>'秋','sheng'=>'水','ke'=>'金'),
		'水' => array('siji'=>'冬','sheng'=>'木','ke'=>'木'),
	);
	// 时令表(旺相休囚死)
	public $shiling_map = array (
		'春' => array('木','火','水','金','土'),
		'夏' => array('火','土','木','水','金'),
		'秋' => array('金','水','土','火','木'),
		'冬' => array('水','木','金','土','火'),
		'四季' => array('土','金','火','木','水'),
	);
	// 天干信息
	public $gan_map = array (
		'甲' => array('num'=>0,'yy'=>'阳','wuxing'=>'木','zhangsheng'=>'亥'),
		'乙' => array('num'=>1,'yy'=>'阴','wuxing'=>'木','zhangsheng'=>'午'),
		'丙' => array('num'=>2,'yy'=>'阳','wuxing'=>'火','zhangsheng'=>'寅'),
		'丁' => array('num'=>3,'yy'=>'阴','wuxing'=>'火','zhangsheng'=>'酉'),
		'戊' => array('num'=>4,'yy'=>'阳','wuxing'=>'土','zhangsheng'=>'寅'),
		'己' => array('num'=>5,'yy'=>'阴','wuxing'=>'土','zhangsheng'=>'酉'),
		'庚' => array('num'=>6,'yy'=>'阳','wuxing'=>'金','zhangsheng'=>'巳'),
		'辛' => array('num'=>7,'yy'=>'阴','wuxing'=>'金','zhangsheng'=>'子'),
		'壬' => array('num'=>8,'yy'=>'阳','wuxing'=>'水','zhangsheng'=>'申'),
		'癸' => array('num'=>9,'yy'=>'阴','wuxing'=>'水','zhangsheng'=>'卯'),
	);
	// 地支信息
	public $zhi_map = array (
		'子' => array('num'=>0,'yy'=>'阳','wuxing'=>'水','canggan'=>array('癸')),
		'丑' => array('num'=>1,'yy'=>'阴','wuxing'=>'土','canggan'=>array('己','癸','辛')),
		'寅' => array('num'=>2,'yy'=>'阳','wuxing'=>'木','canggan'=>array('甲','丙','戊')),
		'卯' => array('num'=>3,'yy'=>'阴','wuxing'=>'木','canggan'=>array('乙')),
		'辰' => array('num'=>4,'yy'=>'阳','wuxing'=>'土','canggan'=>array('戊','乙','癸')),
		'巳' => array('num'=>5,'yy'=>'阴','wuxing'=>'火','canggan'=>array('丙','庚','戊')),
		'午' => array('num'=>6,'yy'=>'阳','wuxing'=>'火','canggan'=>array('丁','己')),
		'未' => array('num'=>7,'yy'=>'阴','wuxing'=>'土','canggan'=>array('己','丁','乙')),
		'申' => array('num'=>8,'yy'=>'阳','wuxing'=>'金','canggan'=>array('庚','壬','戊')),
		'酉' => array('num'=>9,'yy'=>'阴','wuxing'=>'金','canggan'=>array('辛')),
		'戌' => array('num'=>10,'yy'=>'阳','wuxing'=>'土','canggan'=>array('戊','辛','丁')),
		'亥' => array('num'=>11,'yy'=>'阴','wuxing'=>'水','canggan'=>array('壬','甲')),
	);
	// 十神信息
	public $shishen_map = array (
		'比肩' => array('name'=>'比肩','short_name'=>'比'),
		'劫财' => array('name'=>'劫财','short_name'=>'劫'),
		'食神' => array('name'=>'食神','short_name'=>'食'),
		'伤官' => array('name'=>'伤官','short_name'=>'伤'),
		'正财' => array('name'=>'正财','short_name'=>'财'),
		'偏财' => array('name'=>'偏财','short_name'=>'才'),
		'正官' => array('name'=>'正官','short_name'=>'官'),
		'七杀' => array('name'=>'七杀','short_name'=>'杀'),
		'正印' => array('name'=>'正印','short_name'=>'印'),
		'偏印' => array('name'=>'偏印','short_name'=>'枭'),
	);
	// 十神映射表(日干-其他干)
	public $shishen_table = array (
		'甲甲'=>'比肩','甲乙'=>'劫财','甲丙'=>'食神','甲丁'=>'伤官','甲戊'=>'偏财','甲己'=>'正财','甲庚'=>'七杀','甲辛'=>'正官','甲壬'=>'偏印','甲癸'=>'正印',
		'乙甲'=>'劫财','乙乙'=>'比肩','乙丙'=>'伤官','乙丁'=>'食神','乙戊'=>'正财','乙己'=>'偏财','乙庚'=>'正官','乙辛'=>'七杀','乙壬'=>'正印','乙癸'=>'偏印',
		'丙甲'=>'偏印','丙乙'=>'正印','丙丙'=>'比肩','丙丁'=>'劫财','丙戊'=>'食神','丙己'=>'伤官','丙庚'=>'偏财','丙辛'=>'正财','丙壬'=>'七杀','丙癸'=>'正官',
		'丁甲'=>'正印','丁乙'=>'偏印','丁丙'=>'劫财','丁丁'=>'比肩','丁戊'=>'伤官','丁己'=>'食神','丁庚'=>'正财','丁辛'=>'偏财','丁壬'=>'正官','丁癸'=>'七杀',
		'戊甲'=>'七杀','戊乙'=>'正官','戊丙'=>'偏印','戊丁'=>'正印','戊戊'=>'比肩','戊己'=>'劫财','戊庚'=>'食神','戊辛'=>'伤官','戊壬'=>'偏财','戊癸'=>'正财',
		'己甲'=>'正官','己乙'=>'七杀','己丙'=>'正印','己丁'=>'偏印','己戊'=>'劫财','己己'=>'比肩','己庚'=>'伤官','己辛'=>'食神','己壬'=>'正财','己癸'=>'偏财',
		'庚甲'=>'偏财','庚乙'=>'正财','庚丙'=>'七杀','庚丁'=>'正官','庚戊'=>'偏印','庚己'=>'正印','庚庚'=>'比肩','庚辛'=>'劫财','庚壬'=>'食神','庚癸'=>'伤官',
		'辛甲'=>'正财','辛乙'=>'偏财','辛丙'=>'正官','辛丁'=>'七杀','辛戊'=>'正印','辛己'=>'偏印','辛庚'=>'劫财','辛辛'=>'比肩','辛壬'=>'伤官','辛癸'=>'食神',
		'壬甲'=>'食神','壬乙'=>'伤官','壬丙'=>'偏财','壬丁'=>'正财','壬戊'=>'七杀','壬己'=>'正官','壬庚'=>'偏印','壬辛'=>'正印','壬壬'=>'比肩','壬癸'=>'劫财',
		'癸甲'=>'伤官','癸乙'=>'食神','癸丙'=>'正财','癸丁'=>'偏财','癸戊'=>'正官','癸己'=>'七杀','癸庚'=>'正印','癸辛'=>'偏印','癸壬'=>'劫财','癸癸'=>'比肩',
	);
	// 六十甲子空亡表
	public $jiazi_kongwang_map = array (
		'甲子'=>'戌,亥', '甲戌'=>'申,酉', '甲申'=>'午,未', '甲午'=>'辰,巳', '甲辰'=>'寅,卯', '甲寅'=>'子,丑',
		'乙丑'=>'戌,亥', '乙亥'=>'申,酉', '乙酉'=>'午,未', '乙未'=>'辰,巳', '乙巳'=>'寅,卯', '乙卯'=>'子,丑',
		'丙寅'=>'戌,亥', '丙子'=>'申,酉', '丙戌'=>'午,未', '丙申'=>'辰,巳', '丙午'=>'寅,卯', '丙辰'=>'子,丑',
		'丁卯'=>'戌,亥', '丁丑'=>'申,酉', '丁亥'=>'午,未', '丁酉'=>'辰,巳', '丁未'=>'寅,卯', '丁巳'=>'子,丑',
		'戊辰'=>'戌,亥', '戊寅'=>'申,酉', '戊子'=>'午,未', '戊戌'=>'辰,巳', '戊申'=>'寅,卯', '戊午'=>'子,丑',
		'己巳'=>'戌,亥', '己卯'=>'申,酉', '己丑'=>'午,未', '己亥'=>'辰,巳', '己酉'=>'寅,卯', '己未'=>'子,丑',
		'庚午'=>'戌,亥', '庚辰'=>'申,酉', '庚寅'=>'午,未', '庚子'=>'辰,巳', '庚戌'=>'寅,卯', '庚申'=>'子,丑',
		'辛未'=>'戌,亥', '辛巳'=>'申,酉', '辛卯'=>'午,未', '辛丑'=>'辰,巳', '辛亥'=>'寅,卯', '辛酉'=>'子,丑',
		'壬申'=>'戌,亥', '壬午'=>'申,酉', '壬辰'=>'午,未', '壬寅'=>'辰,巳', '壬子'=>'寅,卯', '壬戌'=>'子,丑',
		'癸酉'=>'戌,亥', '癸未'=>'申,酉', '癸巳'=>'午,未', '癸卯'=>'辰,巳', '癸丑'=>'寅,卯', '癸亥'=>'子,丑',
	);

	// 获取时辰对应的地支
	public function get_hour_zhi($hour) 
	{/*{{{*/
		$map = array (
			'0'=>'子','1'=>'丑','2'=>'丑','3'=>'寅','4'=>'寅','5'=>'卯','6'=>'卯',
			'7'=>'辰','8'=>'辰','9'=>'巳','10'=>'巳','11'=>'午','12'=>'午',
			'13'=>'未','14'=>'未','15'=>'申','16'=>'申','17'=>'酉','18'=>'酉',
			'19'=>'戌','20'=>'戌','21'=>'亥','22'=>'亥','23'=>'子',
		);
		return $map[$hour];
	}/*}}}*/

	// 计算时干
    // 设日干为a，时支为b，时干x = (2*(a%5)+b) % 10;
	public function get_hour_gan($solar_calendar,$hour)
	{/*{{{*/
		if (!isset($this->calendar_map[$solar_calendar])) {
			$this->calendar_map[$solar_calendar] = C::t('#bazi#bazi_calendar')->fetch_by_pk($solar_calendar);
		}
		$calendar = &$this->calendar_map[$solar_calendar];
		$hour_zhi = $this->get_hour_zhi($hour);
		$ri_gan_num = $this->gan_map[$calendar['ri_gan']]['num'];
		$shi_zhi_num = $this->zhi_map[$hour_zhi]['num'];
		$x = (2*($ri_gan_num%5) + $shi_zhi_num) % 10;
		$gan = array('甲','乙','丙','丁','戊','己','庚','辛','壬','癸');
		$hour_gan = $gan[$x];
		return $hour_gan;
	}/*}}}*/

	// 计算十神
	public function get_shishen($rigan,$gan)
	{/*{{{*/
/*
		$shishenarr = array('比肩','劫财','食神','伤官','偏财','正财','七杀','正官','偏印','正印');
		$riganinfo = $this->gan_map[$rigan];
		$ganinfo = $this->gan_map[$gan];
		$dif = $ganinfo['num']-$riganinfo['num'];
		$idx = 0;
		if ($riganinfo['yy']=='阴' && ($dif==-1 || $dif%2==1)) {
			$dif += 2;
		} 
		if ($dif<0) $dif+=10;
		$shishen = $shishenarr[$dif];
		return $this->shishen_map[$shishen];
		$str = $rigan.$gan;
*/
		$shishen = $this->shishen_table[$rigan.$gan];
		return $this->shishen_map[$shishen];
	}/*}}}*/

	// 计算地势(生旺衰)
	public function get_dishi($rigan,$zhi)
	{/*{{{*/
		$ganinfo = $this->gan_map[$rigan];
		$zhiinfo = $this->zhi_map[$zhi];
		$zhangsheng_zhi = $ganinfo['zhangsheng'];
		$shengzhiinfo = $this->zhi_map[$zhangsheng_zhi];
		$offset = $zhiinfo['num']-$shengzhiinfo['num'];        //!< 阳天干顺行
		if ($ganinfo['yy']=='阴') {
			$offset = $shengzhiinfo['num']-$zhiinfo['num'];    //!< 阴天干逆行
		}
		if ($offset<0) $offset+=12;
		$zsj = array('长生','沐浴','冠带','禄','帝旺','衰','病','死','墓','绝','胎','养');
		return $zsj[$offset];
	}/*}}}*/

	// 获取年历序列
	public function get_ganzhi_seq($gan_begin,$zhi_begin,$sort=1,$n=10)
	{/*{{{*/
		$sort = $sort>0 ? 1 : -1;
		$ganarr = array_keys($this->gan_map);
		$zhiarr = array_keys($this->zhi_map);
		$gan_begin_num = $this->gan_map[$gan_begin]['num'];
		$zhi_begin_num = $this->zhi_map[$zhi_begin]['num'];
		$res = array();
		for ($i=1;$i<=$n;++$i) {
			$dlt = $sort * $i;
			$gn = $gan_begin_num + $dlt;
			$zn = $zhi_begin_num + $dlt;
			if ($gn<0) $gn+=10;
			if ($zn<0) $zn+=12;
			$res[] = array (
				'gan' => $ganarr[($gn)%10],
				'zhi' => $zhiarr[($zn)%12],
			);
		}
		return $res;
	}/*}}}*/

	// 获取第n个干支
	public function get_gan_zhi($gan,$zhi,$n,$sort=1)
	{/*{{{*/
		$ganarr = array_keys($this->gan_map);
		$zhiarr = array_keys($this->zhi_map);
		$gan_num = $this->gan_map[$gan]['num'];
		$zhi_num = $this->zhi_map[$zhi]['num'];
		$gn = $gan_num + ($n*$sort);
		$zn = $zhi_num + ($n*$sort);
		return array (
			'gan' => $ganarr[($gn)%10],
			'zhi' => $zhiarr[($zn)%12],
		);
	}/*}}}*/

	// 获取某一年的干支
    // 公历年初可能还是去年的干支,取一年中间6月1号那天的干支为该年的干支
	public function get_gan_zhi_of_year($year)
	{/*{{{*/
		$calendar = C::t('#bazi#bazi_calendar')->fetch_by_pk("$year-06-01");
		return array (
			'gan' => $calendar['nian_gan'],
			'zhi' => $calendar['nian_zhi'],
		);
	}/*}}}*/

	// 获取空亡地支
	public function get_kongwang($jiazi)
	{/*{{{*/
		$s = $this->jiazi_kongwang_map[$jiazi];
		return explode(',',$s);
	}/*}}}*/
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
