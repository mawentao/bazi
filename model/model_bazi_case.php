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

	// 获取命例基础信息(此为所有分析的基础)
	public function getinfo($caseid)
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

	// 调试用
	public function debug($bazi)
    {   
        $lines = array();
        $lines[] = "命例ID: ".$bazi['caseid']." | ".
                    "姓名: ".$bazi['name']." | ".
                    "性别: ".$bazi['gender']." | ".
					"年龄: ".$bazi['age']." | ".
                    "生肖: ".$bazi['shengxiao']." | ".
                    "公历生日: ".$bazi['solar_calendar']." | ".
                    "农历生日: ".$bazi['lunar_calendar']." | ".
                    ""; 
        $lines[] = "生年: ".$bazi["sheng_nian"]." | ";
    
        //echo implode('<br>',$lines);
        //echo '<hr>';
		echo json_encode($bazi);
        die(0);
    }
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
