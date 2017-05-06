<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
/**
 * 八字五行力量分析
 * bazi_env::c('analyze_wuxing')->analyze($bazi);
 **/
class analyze_wuxing
{
    public function analyze(&$bazi)
    {
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
		foreach ($bazi['shiling'] as $state=>$wx) {
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
		$zhuarr = array('nian','yue','ri','hour');
		// 四柱所透天干
		$tou_tian_gan = array ();
		foreach ($zhuarr as $z) { 
			$gan = $bazi[$z."_gan"];
			$tou_tian_gan[$gan] = $z;
		}
		foreach ($wxganmap as $wx => $ganarr) {
			$wxscore = array();
			foreach ($ganarr as $gan) {
				$score = 0;
				// 遍历四柱地支
				foreach ($zhuarr as $z) {
					$sc = $this->dili_score($gan,$bazi[$z.'_zhi'],$bazi[$z.'_zhi_info'],$tou_tian_gan);  //!< 干在地支的得分 
					$score += $sc;
					if (!isset($wxscore[$z]) || $wxscore[$z]==0) {
						$wxscore[$z] = $sc;
					}
				}
				$ganmap[$gan]['score_dili'] = $score;
				$ganmap[$gan]['score'] += $score;
			}
			$score = 0;
			foreach ($wxscore as $c) $score+=$c;
			$wxmap[$wx]['score_dili'] = $score;
			$wxmap[$wx]['score'] += $score;
		}
		////////////////////////////////////////////////////////////////////////////
		//4.人和分（总分：160，八字各20分）
		//算法：同我得20分，生我得10分
		////////////////////////////////////////////////////////////////////////////
		$shengwo = array ('木'=>'水','火'=>'木','土'=>'火','金'=>'土','水'=>'金');
		foreach ($wxganmap as $wx => $ganarr) {
			$score = 0;
			$swwx = $shengwo[$wx];
			foreach ($zhuarr as $z) {
				// 天干五行
				$gan_wuxing = $bazi[$z.'_gan_info']['wuxing'];
				if ($gan_wuxing==$wx) $score+=20;
				if ($gan_wuxing==$swwx) $score+=10;
				// 地支五行
				$zhi_wuxing = $bazi[$z.'_zhi_info']['wuxing'];
				if ($zhi_wuxing==$wx) $score+=20;
				if ($zhi_wuxing==$swwx) $score+=10;
			}
			$wxmap[$wx]['score_renhe'] = $score;
			$wxmap[$wx]['score'] += $score;
			foreach ($ganarr as $gan) {
				$ganmap[$gan]['score_renhe'] = $score;
				$ganmap[$gan]['score'] += $score;
			}
		}

		//die(json_encode($wxmap));
		//die(json_encode($ganmap));
	}


	// 计算单个地支的地利分
	private function dili_score($gan,$zhi,$zhiinfo,$tou_tian_gan) 
	{
		// 判断是否有根
		foreach ($zhiinfo['canggan'] as $cg) {
			if ($cg['gan']==$gan) {
				return 25;
			}
		}
		// 判断是否有气
		if (isset($tou_tian_gan[$gan])) {
			$qimap = array('长生'=>1,'沐浴'=>1,'冠带'=>1,'禄'=>1,'帝旺'=>1);
			$dishi = C::m('#bazi#bazi_theory')->get_dishi($gan,$zhi);
			if (isset($qimap[$dishi])) return 25;
		}
		// 既无根又无气
		return 0;
	}
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
