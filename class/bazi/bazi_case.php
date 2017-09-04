<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
require_once 'bazi_jue.php';
require_once 'bazi_analyze_mingpan.php';
require_once 'bazi_analyze_nayin.php';
require_once 'bazi_analyze_graph.php';
require_once 'bazi_analyze_shensha.php';
require_once 'bazi_analyze_hunlian.php';
require_once 'bazi_marriage.php';
/**
 * 八字分析核心类
 **/
class bazi_case
{
	public $data=array();

	public function __construct($caseid) 
	{/*{{{*/
		$d = &$this->data;
		$d = C::m('#bazi#bazi_case')->getinfo($caseid);
        if (empty($d)) {
            throw new Exception('此命例不存在或已删除');
        }
		$d['caseid'] = C::m('#bazi#bazi_authcode')->encode_id($d['caseid']);
		unset($d['bid']);
		unset($d['week']);
		
		$d['solarCalendar'] = $d['solar_calendar']; unset($d['solar_calendar']);
		$d['lunarCalendar'] = $d['lunar_calendar']; unset($d['lunar_calendar']);
		$d['birthYear'] = $d['sheng_nian']; unset($d['sheng_nian']);
		$d['birthDay'] = $d['birthday']; unset($d['birthday']);
		$d['birthAnimal'] = $d['shengxiao']; unset($d['shengxiao']);
		$d['birthTerm'] = $d['term']; unset($d['term']);
		$d['birthFestival'] = $d['festival']; unset($d['festival']);

		$gz = array('gan','zhi');
		$sz = array('nian','yue','ri','hour');
		foreach ($gz as $g) {
			$d[$g] = array();
			foreach ($sz as $s) {
				$d[$g][] = array('z'=>$d[$s.'_'.$g]);
				unset($d[$s.'_'.$g]);
			}
		}
	}/*}}}*/

	// 命盘分析(装十神,排大运等)
	public function analyzeMinpan()
	{/*{{{*/
		bazi_analyze_mingpan::analyze($this);
	}/*}}}*/

	// 纳音分析
	public function analyzeNayin()
	{/*{{{*/
		bazi_analyze_nayin::analyze($this);
	}/*}}}*/

    // 八字(冲克关系)图分析
    public function analyzeGraph()
	{/*{{{*/
		bazi_analyze_graph::analyze($this);
	}/*}}}*/

    // 神煞分析
    public function analyzeShenSha()
	{/*{{{*/
		bazi_analyze_shensha::analyze($this);
	}/*}}}*/

    // 婚恋分析
    public function analyzeHunLian()
    {/*{{{*/
        bazi_analyze_hunlian::analyze($this);
    }/*}}}*/

	// 转成Array
	public function toArray() {return $this->data;}
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
