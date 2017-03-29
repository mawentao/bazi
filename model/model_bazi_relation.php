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
	public $gan_he_map = array (
		'甲己' => array('wuxing'=>'土'),
		'乙庚' => array('wuxing'=>'金'),
		'丙辛' => array('wuxing'=>'水'),
		'丁壬' => array('wuxing'=>'木'),
		'戊癸' => array('wuxing'=>'火'),
	);
	// 天干相冲
	private $gan_chong_map = array ('庚甲'=>1,'辛乙'=>1,'壬丙'=>1,'丁癸'=>1);
	// 五行相生
	public $wuxing_sheng = array ('木火'=>1,'火土'=>1,'土金'=>1,'金水'=>1,'水木'=>1);
	// 五行相克
	public $wuxing_ke = array ('木土'=>1,'土水'=>1,'水火'=>1,'火金'=>1,'金木'=>1);
	// 地支三会
	private $zhi_hui_map = array(
		'寅卯辰' => array('wuxing'=>'木'),
		'巳午未' => array('wuxing'=>'火'),
		'申酉戌' => array('wuxing'=>'金'),
		'亥子丑' => array('wuxing'=>'水'),
		//'申午卯' => array('wuxing'=>'金'),   //!< 我的测试
	);
	// 地支三合
	private $zhi_sanhe_map = array(
		'亥卯未' => array('wuxing'=>'木'),
		'寅午戌' => array('wuxing'=>'火'),
		'巳酉丑' => array('wuxing'=>'金'),
		'申子辰' => array('wuxing'=>'水'),
		//'申午卯' => array('wuxing'=>'金'),   //!< 我的测试
	);
	// 地支半合
	public $zhi_banhe_map = array('亥卯'=>'木','卯未'=>'木','寅午'=>'火','午戌'=>'火','巳酉'=>'金','酉丑'=>'金','申子'=>'水','子辰'=>'水');
	// 地支六合
	public $zhi_liuhe_map = array('子丑'=>'土','寅亥'=>'木','卯戌'=>'火','辰酉'=>'金','巳申'=>'水','午未'=>'火土');
	// 地支暗合
	public $zhi_anhe_map = array('申卯'=>'金','寅丑'=>'土','午亥'=>'木');
	// 同柱自合
	private $gan_zhi_he_map = array('戊子'=>1,'辛巳'=>1,'壬午'=>1,'丁亥'=>1);
	// 地支相冲
	public $zhi_chong_map = array ('子午'=>1,'丑未'=>1,'寅申'=>1,'卯酉'=>1,'辰戌'=>1,'巳亥'=>1);
	// 地支相害
	public $zhi_hai_map = array ('子未'=>1,'丑午'=>1,'寅巳'=>1,'卯辰'=>1,'申亥'=>1,'酉戌'=>1);
	// 地支相破
	private $zhi_po_map = array ('子酉'=>1,'午卯'=>1); //,'寅亥'=>1,'辰丑'=>1,'戌未'=>1,'申巳'=>1);  
	// 地支相刑
	private $zhi_xing_map = array (
		'寅巳'=>'无恩之刑','巳申'=>'无恩之刑','申寅'=>'无恩之刑',
		'未丑'=>'恃势之刑','丑戌'=>'恃势之刑','戌未'=>'恃势之刑',
		'子卯'=>'无礼之刑',
		'辰'=>'自刑','午'=>'自刑','酉'=>'自刑','亥'=>'自刑',
	);

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
	{/*{{{*/
		$relation_sheng = array();
		$relation_ke = array();
		$relation_zihe = array();   //!< 同柱自合
		$arr = array('年','月','日','时');
		foreach ($arr as $z) {
			$role_gan = $z.'干';
			$role_zhi = $z.'支';
			if (!isset($nodemap[$role_gan])) continue;
			// 同柱自合
			$ganzhi = $nodemap[$role_gan]['name'].$nodemap[$role_zhi]['name'];
			if (isset($this->gan_zhi_he_map[$ganzhi])) {
				$relation_zihe[] = array($role_gan,$role_zhi,'自合');
				continue;    //!< 自合也是一种相克关系，当有自合时，就不论生克了
			}
			// 生克
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
//print_r($relation_zihe); die(0);
		return array (
			'sheng' => $relation_sheng,
			'ke' => $relation_ke,
			'zihe' => $relation_zihe,
		);
	}/*}}}*/

	// 地支三会三合(不需要位置紧邻)
	public function zhi_hui_he(&$nodemap)
	{/*{{{*/
		$relation_hui = array();
		$relation_sanhe = array();
		$arr = array('年','月','日','时');
		foreach ($arr as $z1) {
			$role1 = $z1.'支';
			$zhi1 = $nodemap[$role1]['name'];
			foreach ($arr as $z2) {
				$role2 = $z2.'支';
				$zhi2 = $nodemap[$role2]['name'];
				if ($zhi1==$zhi2) continue;
				foreach ($arr as $z3) {
					$role3 = $z3.'支';
					$zhi3 = $nodemap[$role3]['name'];
					if ($zhi3==$zhi1 || $zhi3==$zhi2) continue;
					$k = $zhi1.$zhi2.$zhi3;
					if (isset($this->zhi_hui_map[$k])) {
						$relation_hui[] = array($role1,$role2,$role3,$this->zhi_hui_map[$k]['wuxing']);
					}
					if (isset($this->zhi_sanhe_map[$k])) {
						$relation_sanhe[] = array($role1,$role2,$role3,$this->zhi_sanhe_map[$k]['wuxing']);
					}
					//echo "$k ";
				}
			}
		}
		return array (
			'zhi_hui' => $relation_hui,
			'zhi_sanhe' => $relation_sanhe,
		);
	}/*}}}*/

	// 地支半合六合暗合（需位置紧邻）
	public function zhi_liuhe(&$nodemap)
	{/*{{{*/
		$relation_liuhe = array();
		$relation_banhe = array();
		$relation_anhe = array();
		$arr = array(
			array('年支','月支'),
			array('月支','日支'),
			array('日支','时支'),
		);
		foreach ($arr as $z) {
			$role1 = $z[0];
			$role2 = $z[1];
			$zhi1 = $nodemap[$role1]['name'];
			$zhi2 = $nodemap[$role2]['name'];
			$k = $zhi1.$zhi2;
			if (isset($this->zhi_liuhe_map[$k])) {
				$relation_liuhe[] = array($role1,$role2,$this->zhi_liuhe_map[$k]);
			}
			if (isset($this->zhi_banhe_map[$k])) {
				$relation_banhe[] = array($role1,$role2,$this->zhi_banhe_map[$k]);
			}
			if (isset($this->zhi_anhe_map[$k])) {
				$relation_anhe[] = array($role1,$role2,$this->zhi_anhe_map[$k]);
			}
			$k = $zhi2.$zhi1;
			if (isset($this->zhi_liuhe_map[$k])) {
				$relation_liuhe[] = array($role1,$role2,$this->zhi_liuhe_map[$k]);
			}
			if (isset($this->zhi_banhe_map[$k])) {
				$relation_banhe[] = array($role1,$role2,$this->zhi_banhe_map[$k]);
			}
			if (isset($this->zhi_anhe_map[$k])) {
				$relation_anhe[] = array($role1,$role2,$this->zhi_anhe_map[$k]);
			}
		}
		return array (
			'zhi_liuhe' => $relation_liuhe,
			'zhi_banhe' => $relation_banhe,
			'zhi_anhe' => $relation_anhe,
		);
	}/*}}}*/
	
	// 地支相刑
	public function zhi_xing(&$nodemap)
	{/*{{{*/
		$relation_xing = array();
		$arr = array('年','月','日','时');
		foreach ($arr as $z1) {
			$role1 = $z1.'支';
			$zhi1 = $nodemap[$role1]['name'];
			$k = $zhi1;
			if (isset($this->zhi_xing_map[$k])) {
				// 自刑
				$relation_xing[] = array($role1,$role1,$this->zhi_xing_map[$k]);
				continue;   
			}
			foreach ($arr as $z2) {
				$role2 = $z2.'支';
				$zhi2 = $nodemap[$role2]['name'];
				if ($zhi1==$zhi2) continue;
				$k = $zhi1.$zhi2;
				if (isset($this->zhi_xing_map[$k])) {
					$relation_xing[] = array($role1,$role2,$this->zhi_xing_map[$k]);
				}
			}
		}
		return array (
			'zhi_xing' => $relation_xing,
		);
	}/*}}}*/

	// 地支相冲、害、破
	public function zhi_chong(&$nodemap)
	{/*{{{*/
		$relation_chong = array();
		$relation_hai = array();
		$relation_po = array();
		$arr = array('年','月','日','时');
		foreach ($arr as $z1) {
			$role1 = $z1.'支';
			$zhi1 = $nodemap[$role1]['name'];
			foreach ($arr as $z2) {
				$role2 = $z2.'支';
				$zhi2 = $nodemap[$role2]['name'];
				if ($zhi1==$zhi2) continue;
				$k = $zhi1.$zhi2;
				if (isset($this->zhi_chong_map[$k])) {
					$relation_chong[] = array($role1,$role2,'冲');
				}
				if (isset($this->zhi_hai_map[$k])) {
					$relation_hai[] = array($role1,$role2,'害');
				}
				if (isset($this->zhi_po_map[$k])) {
					$relation_po[] = array($role1,$role2,'破');
				}
			}
		}
		return array (
			'zhi_chong' => $relation_chong,
			'zhi_hai' => $relation_hai,
			'zhi_po' => $relation_po,
		);
	}/*}}}*/

	

}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
