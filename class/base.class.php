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

    // 空亡表
    public static $KONG_WANG_MAP = array (
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

	// 天干二元关系(按天干顺序组成KEY)
	public static $GAN_RELATIONS = array(
		'甲己'=>array('合土'), '甲庚'=>array('冲'),
		'乙庚'=>array('合金'), '乙辛'=>array('冲'),
		'丙辛'=>array('合水'), '丙壬'=>array('冲'),
		'丁壬'=>array('合木'), '丁癸'=>array('冲'),
		'戊癸'=>array('合火'),
	);

	// 地支二元关系(按地支顺序组成KEY)
	public static $ZHI_RELATIONS = array(
		'子丑'=>array('合'),      '子午'=>array('冲'),      '子未'=>array('害'),      '子酉'=>array('破'), '子申'=>array('半合'),'子辰'=>array('半合'),
		'丑辰'=>array('破'),      '丑午'=>array('害'),      '丑未'=>array('刑','冲'), '丑戌'=>array('刑'), '丑酉'=>array('半合'),
        '寅巳'=>array('刑','害'), '寅申'=>array('刑','冲'), '寅亥'=>array('合','破'), '寅午'=>array('半合'), 
        '卯辰'=>array('害'),      '卯午'=>array('破'),      '卯酉'=>array('冲'),      '卯戌'=>array('合'), '卯未'=>array('半合'),'卯亥'=>array('半合'),
        '辰辰'=>array('刑'),      '辰酉'=>array('合'),      '辰戌'=>array('冲'),
		'巳申'=>array('合','刑','破'), '巳亥'=>array('冲'), '巳酉'=>array('半合'),
		'午午'=>array('刑'),	  '午未'=>array('合'),      '午戌'=>array('半合'),
		'未戌'=>array('刑','破'),
		'申亥'=>array('害'),
		'酉酉'=>array('刑'),
		'酉戌'=>array('害'),
		'亥亥'=>array('刑'),
	);

	// 地址三元关系(按地支顺序组成KEY)
	public static $ZHI_RELATIONS_TRI = array (
        '寅卯辰'=>'三会木', '巳午未'=>'三会火', '申酉戌'=>'三会金', '子丑亥'=>'三会水',
		'子辰申'=>'三合水', '卯未亥'=>'三合木', '寅午戌'=>'三合火','丑巳酉'=>'三合金',
	);

    // 十神映射表(KEY:日干-其他干) 
    public static $SHI_SHEN_TABLE_MAP = array (
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

    // 天干在地支的十二长生状态
    // 长生,沐浴,冠带,临官,帝旺,衰,病,死,墓,胎,绝,养
    public static function zhangsheng_state($gan,$zhi)
    {/*{{{*/
        $ganinfo = self::$GAN_MAP[$gan];
		$zhiinfo = self::$ZHI_MAP[$zhi];
		$zhangsheng_zhi = $ganinfo['zhangsheng'];
		$shengzhiinfo = self::$ZHI_MAP[$zhangsheng_zhi];
		$offset = $zhiinfo['num']-$shengzhiinfo['num'];        //!< 阳天干顺行
		if ($ganinfo['yy']=='阴') {
			$offset = $shengzhiinfo['num']-$zhiinfo['num'];    //!< 阴天干逆行
		}
		if ($offset<0) $offset+=12;
		$zsj = array('长生','沐浴','冠带','临官','帝旺','衰','病','死','墓','绝','胎','养');
		return $zsj[$offset];
    }/*}}}*/
}

// vim600: sw=4 ts=4 fdm=marker syn=php
?>
