<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
/**
 * 八字命理学中的各种映射表
 **/
class bazi_base
{
	// 五行信息表
	public static $WUXING_MAP = array (
		'木' => array('sheng'=>'火','ke'=>'土','siji'=>'春',  'pos'=>'东'),
		'火' => array('sheng'=>'土','ke'=>'水','siji'=>'夏',  'pos'=>'南'),
		'土' => array('sheng'=>'金','ke'=>'火','siji'=>'四季','pos'=>'中'),
		'金' => array('sheng'=>'水','ke'=>'金','siji'=>'秋',  'pos'=>'西'),
		'水' => array('sheng'=>'木','ke'=>'木','siji'=>'冬',  'pos'=>'北'),
	);
	// 天干信息表
	public static $GAN_MAP = array (
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
	// 地支信息表
	public static $ZHI_MAP = array (
		'子' => array('num'=>0, 'yy'=>'阳','wuxing'=>'水','shengxiao'=>'鼠','canggan'=>array('癸')),
		'丑' => array('num'=>1, 'yy'=>'阴','wuxing'=>'土','shengxiao'=>'牛','canggan'=>array('己','癸','辛')),
		'寅' => array('num'=>2, 'yy'=>'阳','wuxing'=>'木','shengxiao'=>'虎','canggan'=>array('甲','丙','戊')),
		'卯' => array('num'=>3, 'yy'=>'阴','wuxing'=>'木','shengxiao'=>'兔','canggan'=>array('乙')),
		'辰' => array('num'=>4, 'yy'=>'阳','wuxing'=>'土','shengxiao'=>'龙','canggan'=>array('戊','乙','癸')),
		'巳' => array('num'=>5, 'yy'=>'阴','wuxing'=>'火','shengxiao'=>'蛇','canggan'=>array('丙','庚','戊')),
		'午' => array('num'=>6, 'yy'=>'阳','wuxing'=>'火','shengxiao'=>'马','canggan'=>array('丁','己')),
		'未' => array('num'=>7, 'yy'=>'阴','wuxing'=>'土','shengxiao'=>'羊','canggan'=>array('己','丁','乙')),
		'申' => array('num'=>8, 'yy'=>'阳','wuxing'=>'金','shengxiao'=>'猴','canggan'=>array('庚','壬','戊')),
		'酉' => array('num'=>9, 'yy'=>'阴','wuxing'=>'金','shengxiao'=>'鸡','canggan'=>array('辛')),
		'戌' => array('num'=>10,'yy'=>'阳','wuxing'=>'土','shengxiao'=>'狗','canggan'=>array('戊','辛','丁')),
		'亥' => array('num'=>11,'yy'=>'阴','wuxing'=>'水','shengxiao'=>'猪','canggan'=>array('壬','甲')),
	);
	// 天干二元关系(按天干顺序组成KEY)
	public static $GAN_RELATIONS = array(
		'甲己'=>array('合土'), '甲庚'=>array('冲'),
		'乙庚'=>array('合金'), '乙辛'=>array('冲'),
		'丙辛'=>array('合水'), '丙壬'=>array('冲'),
		'丁壬'=>array('合木'), '癸丁'=>array('冲'),
		'戊癸'=>array('合火'),
	);



	// 地支二元关系(按地支顺序组成KEY)
	public static $ZHI_RELATIONS = array(
		'子丑'=>array('合'),      '子午'=>array('冲'),      '子未'=>array('害'),      '子酉'=>array('破'),
		'丑辰'=>array('破'),      '丑午'=>array('害'),      '丑未'=>array('刑','冲'), '丑戌'=>array('刑'),
        '寅巳'=>array('刑','害'), '寅申'=>array('刑','冲'), '寅亥'=>array('合','破'),
        '卯辰'=>array('害'),      '卯午'=>array('破'),      '卯酉'=>array('冲'),      '卯戌'=>array('合'),
        '辰辰'=>array('刑'),      '辰酉'=>array('合'),      '辰戌'=>array('冲'),
		'巳申'=>array('合','刑','破'), '巳亥'=>array('冲'),
		'午午'=>array('刑'),	  '午未'=>array('合'),
		'未戌'=>array('刑','破'),
		'申亥'=>array('害'),
		'酉酉'=>array('刑'),
		'酉戌'=>array('害'),
		'亥亥'=>array('刑'),
	);

	// 地址三元关系(按地支顺序组成KEY)
	public static $ZHI_RELATIONS_TRI = array (
		'子辰申'=>'三合水', '卯未亥'=>'三合木', '寅午戌'=>'三合火','丑巳酉'=>'三合金',
	);


	// 天干排序
	public static function sort_gans(array $gans)
	{/*{{{*/
		$res = array();
		$ganmap = array();
		$nums = array();
		foreach ($gans as $gan) {
			$gannum = self::$GAN_MAP[$gan]['num']; //!< 天干对应的编号
			$nums[] = $gannum;
			$ganmap[$gannum] = $gan;
		}
		sort($nums);
		foreach ($nums as $num) {
			$res[] = $ganmap[$num];
		}
		return $res;
	}/*}}}*/

	// 地支排序
	public static function sort_zhis(array $zhis)
	{/*{{{*/
		$res = array();
		$zhimap = array();
		$nums = array();
		foreach ($zhis as $zhi) {
			$zhinum = self::$ZHI_MAP[$zhi]['num']; //!< 地支对应的编号
			$nums[] = $zhinum;
			$zhimap[$zhinum] = $zhi;
		}
		sort($nums);
		foreach ($nums as $num) {
			$res[] = $zhimap[$num];
		}
		return $res;
	}/*}}}*/
}

// vim600: sw=4 ts=4 fdm=marker syn=php
?>
