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
	array('name'=>'甲','wuxing'=>'木','role'=>'nian_gan'),
	array('name'=>'子','wuxing'=>'水','role'=>'nian_zhi'),
	array('name'=>'丙','wuxing'=>'火','role'=>'yue_gan'),
	array('name'=>'午','wuxing'=>'火','role'=>'yue_zhi'),
	array('name'=>'戊','wuxing'=>'土','role'=>'ri_gan'),
	array('name'=>'寅','wuxing'=>'木','role'=>'ri_zhi'),
	array('name'=>'癸','wuxing'=>'水','role'=>'shi_gan'),
	array('name'=>'丑','wuxing'=>'土','role'=>'shi_zhi'),
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


	// 天干相合关系
	public function gan_he(&$nodes)
	{
		$relations = array();
		foreach ($nodes as $node1) {
			$role1 = $node1['role'];
			if (strstr($role1,'zhi')!==false) continue;
			foreach ($nodes as $node2) {
				$role2 = $node2['role'];
				if ($role1==$role2 || strstr($role2,'zhi')!==false) continue;
				$nn = $node1['name'].$node2['name'];
				if (isset($this->gan_he_map[$nn])) {
					$im = $this->gan_he_map[$nn];
					$relations[] = array (
						$role1,$role2,$im['wuxing']
					);
				}
			}
		}
		return $relations;
	}
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
