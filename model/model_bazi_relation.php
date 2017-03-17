<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
/**
 * 八字理论之干支五行之间的合害刑冲关系分析
 * C::m('#bazi#bazi_relation')->func()
 *
 * nodes数据结构统一为:
array (
	array('name'=>'甲','wuxing'=>'木','role'=>'年干'),
	array('name'=>'子','wuxing'=>'水','role'=>'年支'),
	array('name'=>'丙','wuxing'=>'火','role'=>'月干'),
	array('name'=>'午','wuxing'=>'火','role'=>'月支'),
	array('name'=>'戊','wuxing'=>'土','role'=>'日干'),
	array('name'=>'寅','wuxing'=>'木','role'=>'日支'),
	array('name'=>'癸','wuxing'=>'水','role'=>'时干'),
	array('name'=>'丑','wuxing'=>'土','role'=>'时支'),
);
 *
 **/
class model_bazi_relation
{
	// 天干五合
	private $gan_he_map = array (
		'甲己' => array('wuxing'=>'土'),
		'乙庚' => array('wuxing'=>'金'),
		'丙辛' => array('wuxing'=>'水'),
		'丁壬' => array('wuxing'=>'木'),
		'戊癸' => array('wuxing'=>'火'),
	);
	// 天干相冲
	private $gan_chong_map = array ('庚甲'=>1,'辛乙'=>1,'壬丙'=>1,'丁癸'=>1);
	// 五行相生
	private $wuxing_sheng = array ('木火'=>1,'火土'=>1,'土金'=>1,'金水'=>1,'水木'=>1);
	// 五行相克
	private $wuxing_ke = array ('木土'=>1,'土水'=>1,'水火'=>1,'火金'=>1,'金木'=>1);


	// 天干合冲关系
	public function gan_he_chong(&$nodes)
	{/*{{{*/
		$relation_he = array();
		$relation_chong = array();
		foreach ($nodes as $node1) {
			$role1 = $node1['role'];
			if (strstr($role1,'支')!==false) continue;
			foreach ($nodes as $node2) {
				$role2 = $node2['role'];
				if ($role1==$role2 || strstr($role2,'支')!==false) continue;
				$nn = $node1['name'].$node2['name'];
				if (isset($this->gan_he_map[$nn])) {
					$im = $this->gan_he_map[$nn];
					$relation_he[] = array (
						$role1,$role2,$im['wuxing']
					);
				}
				if (isset($this->gan_chong_map[$nn])) {
					$im = $this->gan_he_map[$nn];
					$relation_chong[] = array (
						$role1,$role2,'冲'
					);
				}
			}
		}
		return array (
			'he' => $relation_he,
			'chong' => $relation_chong,
		);
	}/*}}}*/

	// 同柱干支关系
	public function gan_zhi_tongzhu(&$nodemap)
	{
		$relation_sheng = array();
		$relation_ke = array();
		$arr = array('年','月','日','时');
		foreach ($arr as $z) {
			$role_gan = $z.'干';
			$role_zhi = $z.'支';
			if (!isset($nodemap[$role_gan])) continue;
			$gan_wuxing = $nodemap[$role_gan]['wuxing'];
			$zhi_wuxing = $nodemap[$role_zhi]['wuxing'];
			$k1 = $gan_wuxing.$zhi_wuxing;
			$k2 = $zhi_wuxing.$gan_wuxing;
			if (isset($this->wuxing_sheng[$k1])) {
				$relation_sheng[] = array($role_gan,$role_zhi,'生');
			}
			if (isset($this->wuxing_ke[$k1])) {
				$relation_ke[] = array($role_gan,$role_zhi,'克');
			}
			if (isset($this->wuxing_sheng[$k2])) {
				$relation_sheng[] = array($role_zhi,$role_gan,'生');
			}
			if (isset($this->wuxing_ke[$k2])) {
				$relation_ke[] = array($role_zhi,$role_gan,'克');
			}
		}
//print_r($relation_sheng); die(0);
		return array (
			'sheng' => $relation_sheng,
			'ke' => $relation_ke,
		);
	}

}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
