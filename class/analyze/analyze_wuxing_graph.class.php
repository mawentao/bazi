<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
/**
 * 八字五行关系图（合、冲、克、害、刑、破）
 * bazi_env::c('analyze_wuxing_graph')->analyze($bazi);
 **/
class analyze_wuxing_graph
{
    public function analyze(&$bazi)
    {
		$res = array (
			'nodes' => array(
				array('role'=>'年干',
					'name'=>$bazi['nian_gan'],'wuxing'=>$bazi['nian_gan_info']['wuxing'],
					'shishen'=>$bazi['nian_gan_info']['shishen'],
				),
				array('role'=>'年支',
					'name'=>$bazi['nian_zhi'],'wuxing'=>$bazi['nian_zhi_info']['wuxing'],
					'shishen'=>$bazi['nian_zhi_info']['shishen'],
				),
				array('role'=>'月干',
					'name'=>$bazi['yue_gan'],'wuxing'=>$bazi['yue_gan_info']['wuxing'],
					'shishen'=>$bazi['yue_gan_info']['shishen'],
				),
				array('role'=>'月支',
					'name'=>$bazi['yue_zhi'],'wuxing'=>$bazi['yue_zhi_info']['wuxing'],
					'shishen'=>$bazi['yue_zhi_info']['shishen'],
				),
				array('role'=>'日干',
					'name'=>$bazi['ri_gan'],'wuxing'=>$bazi['ri_gan_info']['wuxing'],
				),
				array('role'=>'日支',
					'name'=>$bazi['ri_zhi'],'wuxing'=>$bazi['ri_zhi_info']['wuxing'],
					'shishen'=>$bazi['ri_zhi_info']['shishen'],
				),
				array('role'=>'时干',
					'name'=>$bazi['hour_gan'],'wuxing'=>$bazi['hour_gan_info']['wuxing'],
					'shishen'=>$bazi['hour_gan_info']['shishen'],
				),
				array('role'=>'时支',
					'name'=>$bazi['hour_zhi'],'wuxing'=>$bazi['hour_zhi_info']['wuxing'],
					'shishen'=>$bazi['hour_zhi_info']['shishen'],
				),
			),
			'links' => array(
				'gan_he'     => array(),   //!< 天干合
				'gan_chong'  => array(),   //!< 天干冲
				//'zhi_hui'    => array(),   //!< 地支相会
				//'zhi_san_he' => array(),   //!< 地支三合
			),
		);
		$relation = C::m('#bazi#bazi_relation');
		// 天干合冲关系
		$gan_he_chong = $relation->gan_he_chong($res['nodes']);		
		$res['links']['gan_he'] = $gan_he_chong['he'];
		$res['links']['gan_chong'] = $gan_he_chong['chong'];
		// 同柱干支关系
		$nodemap = array();
		foreach ($res['nodes'] as $nd) {
			$nodemap[$nd['role']] = $nd;
		}
		$gan_zhi_tongzhu = $relation->gan_zhi_tongzhu($nodemap);
		$res['links']['sheng'] = $gan_zhi_tongzhu['sheng'];
		$res['links']['ke'] = $gan_zhi_tongzhu['ke'];
		$res['links']['zihe'] = $gan_zhi_tongzhu['zihe'];
		// 地支三会三合关系
		$zhihui_relation = $relation->zhi_hui_he($nodemap);
		$res['links']['zhi_hui'] = $zhihui_relation['zhi_hui'];
		$res['links']['zhi_sanhe'] = $zhihui_relation['zhi_sanhe'];
		// 地支半合六合暗合
		$ra = $relation->zhi_liuhe($nodemap);
		$res['links']['zhi_liuhe'] = $ra['zhi_liuhe'];
		$res['links']['zhi_anhe'] = $ra['zhi_anhe'];
		if (empty($res['links']['zhi_sanhe'])) {
			// 如果没有地址三合才论半合
			$res['links']['zhi_banhe'] = $ra['zhi_banhe'];
		}
		// 地支相刑
		$ra = $relation->zhi_xing($nodemap);
		$res['links']['zhi_xing'] = $ra['zhi_xing'];

		// 地支相冲、害、破关系
		$zhichong_relation = $relation->zhi_chong($nodemap);
		$res['links']['zhi_chong'] = $zhichong_relation['zhi_chong'];
		$res['links']['zhi_hai'] = $zhichong_relation['zhi_hai'];
		$res['links']['zhi_po'] = $zhichong_relation['zhi_po'];    

		$bazi['wuxing_graph'] = &$res;
	}

}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
