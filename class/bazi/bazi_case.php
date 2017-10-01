<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
require_once 'bazi_jue.php';
require_once 'bazi_analyze_mingpan.php';
require_once 'bazi_analyze_nayin.php';
require_once 'bazi_analyze_yongshen.php';
require_once 'bazi_analyze_geju.php';


require_once 'bazi_analyze_shensha.php';
require_once 'bazi_analyze_graph.php';
require_once 'bazi_analyze_wuxing.php';

require_once 'bazi_analyze_hunlian.php';
require_once 'bazi_analyze_hunlian_liunian.php';
require_once 'bazi_marriage.php';

require_once 'bazi_analyze_personality.php';

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
		
        // 生辰信息
		$d['solarCalendar'] = $d['solar_calendar']; unset($d['solar_calendar']);
		$d['lunarCalendar'] = $d['lunar_calendar']; unset($d['lunar_calendar']);
		$d['birthYear'] = $d['sheng_nian']; unset($d['sheng_nian']);
		$d['birthDay'] = $d['birthday']; unset($d['birthday']);
		$d['birthAnimal'] = $d['shengxiao']; unset($d['shengxiao']);
		$d['birthTerm'] = $d['term']; unset($d['term']);
		$d['birthFestival'] = $d['festival']; unset($d['festival']);
        // 日干月令
        $d['riYuan'] = $d['ri_gan'];
        $d['yueLing'] = $d['yue_zhi'];
        // 空亡
        $rizhu = $d['ri_gan'].$d['ri_zhi'];
        $kongwang = bazi_base::$KONG_WANG_MAP[$rizhu];
        $d['kongWang'] = explode(',',$kongwang);
        // 八字结构化
		$gz = array('gan','zhi');
		$sz = array('nian','yue','ri','hour');
		foreach ($gz as $g) {
			$d[$g] = array();
			foreach ($sz as $s) {
				$d[$g][] = array('z'=>$d[$s.'_'.$g]);
				unset($d[$s.'_'.$g]);
			}
		}
        // 加载各字典表
        $d['dict'] = array (
            'wuxing'  => C::t('#bazi#bazi_dict_wuxing')->getMap(),
            'tiangan' => C::t('#bazi#bazi_dict_tiangan')->getMap(),
            'dizhi'   => C::t('#bazi#bazi_dict_dizhi')->getMap(),
            'shishen' => C::t('#bazi#bazi_dict_shishen')->getMap(),
        );
	}/*}}}*/

    // 全盘分析
    public function analyzeAll()
    {
        // 基础分析
        $this->analyzeMinpan();   //!< 八字命盘
        $this->analyzeNayin();    //!< 纳音
        $this->analyzeGraph();    //!< 生克合化,冲刑破害关系图
        $this->analyzeShenSha();  //!< 神煞

        // 进阶分析
        $this->analyzeGeJu();     //!< 定格局
        $this->analyzeYongShen(); //!< 旺衰分析&找用神
//die(json_encode($this->data));

        // 论断
        $this->analyzeHunLian();        //!< 婚恋
        $this->analyzePersonality();    //!< 心性
    }

	// 命盘分析(装十神,排大运等)
	public function analyzeMinpan()
	{/*{{{*/
		bazi_analyze_mingpan::analyze($this);
	}/*}}}*/

    // 旺衰分析&找用神
    public function analyzeYongShen()
	{/*{{{*/
		bazi_analyze_yongshen::analyze($this);
	}/*}}}*/

    // 八字格局
    public function analyzeGeJu()
	{/*{{{*/
		bazi_analyze_geju::analyze($this);
	}/*}}}*/

    // 五行旺衰分析
    public function analyzeWuxing()
    {/*{{{*/
        bazi_analyze_wuxing::analyze($this);
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
        bazi_analyze_hunlian_liunian::analyze($this);
    }/*}}}*/

    // 心性分析
    public function analyzePersonality()
    {/*{{{*/
        bazi_analyze_personality::analyze($this);
    }/*}}}*/

	// 转成Array
	public function toArray() {return $this->data;}
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
