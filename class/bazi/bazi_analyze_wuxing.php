<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
/**
 * 八字五行力量分析
 **/
class bazi_analyze_wuxing
{
    public static function analyze(&$baziCase)
    {
        $bazi = &$baziCase->data;
		//1. 初始化
		$wxmap = array();   //!< 五行打分
		$wxarr = array('木','火','土','金','水');
		foreach ($wxarr as $wx) {
			$wxmap[$wx] = array (
				'score' => 0,			//!< 总分（满分360）
				'score_tianshi' => 0,	//!< 天时分（满分100分）
				'score_dili' => 0,		//!< 地利分（满分100分）
				'score_renhe' => 0,		//!< 人和分（满分160分）
			);
		}
		$ganmap = array();	//!< 天干打分
		$ganarr = array('甲','乙','丙','丁','戊','己','庚','辛','壬','癸');
		foreach ($ganarr as $gan) {
			$ganmap[$gan] = array (
				'score' => 0,			//!< 总分（满分360）
				'score_tianshi' => 0,	//!< 天时分（满分100分）
				'score_dili' => 0,		//!< 地利分（满分100分）
				'score_renhe' => 0,		//!< 人和分（满分160分）
			);
		}
		////////////////////////////////////////////////////////////////////////////
		//2. 天时分（总分：100）
		//算法：以五行在月令状态打分（旺:100、相:80、休:60、囚:40、死:20）
		////////////////////////////////////////////////////////////////////////////
		$statemap = array('旺'=>100,'相'=>80,'休'=>60,'囚'=>40,'死'=>20);
		$wxganmap = array(
			'木'=>array('甲','乙'),
			'火'=>array('丙','丁'),
			'土'=>array('戊','己'),
			'金'=>array('庚','辛'),
			'水'=>array('壬','癸'),
		);
		foreach ($bazi['shiLing'] as $state=>$wx) {
			$score = $statemap[$state];
			$wxmap[$wx]['score_tianshi'] = $score;
			$wxmap[$wx]['score'] += $score;
			foreach ($wxganmap[$wx] as $g) {
				$ganmap[$g]['score_tianshi'] = $score;
				$ganmap[$g]['score'] += $score;
			}
		}
		////////////////////////////////////////////////////////////////////////////
		//3.地利分（总分：100，年月日时支各25分）
		//算法：天干在地支是否有根或有气
		//	有根：地支有藏天干
		//	有气：四柱所透天干在该地支状态为以下之一：长生，沐浴，冠带，临官（禄），帝旺
		////////////////////////////////////////////////////////////////////////////
		// 四柱所透天干
		$tou_tian_gan = array ();
        foreach ($bazi['gan'] as $i => $ganInfo) {
            $gan = $ganInfo['z'];
            $tou_tian_gan[$gan]++;
        }
		foreach ($wxganmap as $wx => $ganarr) {
			$wxscore = 0;
            // 先计算五行的两个天干地势分
			foreach ($ganarr as $gan) {
				$score = 0;
                // 遍历四柱地支,计算地势分
                foreach ($bazi['zhi'] as $i => $zhiInfo) {
                    $sc = self::cal_dili_score($gan,$zhiInfo['z'],$zhiInfo,$tou_tian_gan);   //!< 计算天干在地支的得分
					$score += $sc;
                    $wxscore += $sc;
                }
				$ganmap[$gan]['score_dili'] = $score;
				$ganmap[$gan]['score'] += $score;
			}
            // 五行的地势分
			$wxmap[$wx]['score_dili'] = $wxscore;
			$wxmap[$wx]['score'] += $wxscore;
		}
		////////////////////////////////////////////////////////////////////////////
		//4.人和分（总分：160，八字各20分）
		//算法：同我得20分，生我得10分
		////////////////////////////////////////////////////////////////////////////
		$shengwo = array ('木'=>'水','火'=>'木','土'=>'火','金'=>'土','水'=>'金');
		foreach ($wxganmap as $wx => $ganarr) {
			$score = 0;
			$swwx = $shengwo[$wx];
            // 四柱天干五行
            foreach ($bazi['gan'] as &$im) {
				if ($im['wuxing']==$wx) $score+=20;
				if ($im['wuxing']==$swwx) $score+=10;
            }
            // 地支五行
            foreach ($bazi['zhi'] as &$im) {
				if ($im['wuxing']==$wx) $score+=20;
				if ($im['wuxing']==$swwx) $score+=10;
            }
			$wxmap[$wx]['score_renhe'] = $score;
			$wxmap[$wx]['score'] += $score;
			foreach ($ganarr as $gan) {
				$ganmap[$gan]['score_renhe'] = $score;
				$ganmap[$gan]['score'] += $score;
			}
		}
        
        $bazi['wuxingPower'] = &$wxmap;

        // 天干力量转化为十神力量
        $riyuan = $bazi['riYuan'];
        $bazi['shishenPower'] = array();
        // 计算比例
        $sum = 0;
        foreach ($ganmap as $gan => &$item) {
            $sum += $item['score'];
        }
        foreach ($ganmap as $gan => &$item) {
            $k = $riyuan.$gan;
            $shishen = bazi_base::$SHI_SHEN_TABLE_MAP[$k];
            $item['gan'] = $gan;
            $item['score'] = intval($item['score']*10000 / $sum);
            $item['score'] /= 100;
            $bazi['shishenPower'][$shishen] = $item;
        }
	}

    

	// 计算天干在单个地支的地利分
	private static function cal_dili_score($gan,$zhi,$zhiinfo,$tou_tian_gan) 
	{
		// 判断是否有根
		foreach ($zhiinfo['info']['canggan'] as $cg) {
			if ($cg['gan']==$gan) {
				return 25;
			}
		}
		// 判断是否有气(只有透出的天干才看是否有气)
		if (isset($tou_tian_gan[$gan])) {
			$qimap = array('长生'=>1,'沐浴'=>1,'冠带'=>1,'临官'=>1,'帝旺'=>1);
			$state = bazi_base::zhangsheng_state($gan,$zhi);
			if (isset($qimap[$state])) return 25;
		}
		// 既无根又无气
		return 0;
	}
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
