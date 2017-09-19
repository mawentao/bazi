<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
/**
 * 八字合婚分析
 **/
class bazi_marriage
{
	// case1: 男命例
	// case2: 女命例
	public static function analyze(&$case1,&$case2)
	{
		$graph = array (
			'nodes' => array(
				'男年支' => self::getZhiNodeInfo($case1,0),
				'男月支' => self::getZhiNodeInfo($case1,1),
				'男日支' => self::getZhiNodeInfo($case1,2),
				'男时支' => self::getZhiNodeInfo($case1,3),
				'女年支' => self::getZhiNodeInfo($case2,0),
				'女月支' => self::getZhiNodeInfo($case2,1),
				'女日支' => self::getZhiNodeInfo($case2,2),
				'女时支' => self::getZhiNodeInfo($case2,3),
			),
			'relations' => array(),
		);

		$sz = array('年','月','日','时');
		foreach ($sz as $z) {
			$source = '男'.$z.'支';
			foreach ($sz as $z) {
				$target = '女'.$z.'支';
				$arr = bazi_base::sort_zhis(array($graph['nodes'][$source]['name'],$graph['nodes'][$target]['name']));
				$key = implode('',$arr);
				$relations = bazi_base::$ZHI_RELATIONS[$key];
				if (!empty($relations)) {
					foreach ($relations as $relation) {
						if (!isset($graph['relations'][$relation])) {
							$graph['relations'][$relation] = array();
						}
						$graph['relations'][$relation][] = array($source,$target);
					}
				}
			}
		}

		// 三合局关系
		// 男命两地支 + 女命一地支
		for ($i=0;$i<3;++$i) {
			$s1 = '男'.$sz[$i].'支';
			for ($j=$i+1;$j<4;++$j) {
				$s2 = '男'.$sz[$j].'支';
				foreach ($sz as $z) {
					$s3 = '女'.$z.'支';
					$arr = array($graph['nodes'][$s1]['name'],$graph['nodes'][$s2]['name'],$graph['nodes'][$s3]['name']);
					$sar = bazi_base::sort_zhis($arr);
					$key = implode('',$sar);
					$relation = bazi_base::$ZHI_RELATIONS_TRI[$key];
					if ($relation && strpos($relation,'三合')!==false) {
						$graph['relations']['三合'][] = array($s1,$s2,$s3);
					}
				}
			}
		}
		// 女命两地支 + 男命一地支
		for ($i=0;$i<3;++$i) {
			$s1 = '女'.$sz[$i].'支';
			for ($j=$i+1;$j<4;++$j) {
				$s2 = '女'.$sz[$j].'支';
				foreach ($sz as $z) {
					$s3 = '男'.$z.'支';
					$arr = array($graph['nodes'][$s1]['name'],$graph['nodes'][$s2]['name'],$graph['nodes'][$s3]['name']);
					$sar = bazi_base::sort_zhis($arr);
					$key = implode('',$sar);
					$relation = bazi_base::$ZHI_RELATIONS_TRI[$key];
					if ($relation && strpos($relation,'三合')!==false) {
						$graph['relations']['三合'][] = array($s1,$s2,$s3);
					}
				}
			}
		}



		return array(
			'graph' => &$graph
		);
	}

	// 返回合婚分析图中的节点信息
	private static function getZhiNodeInfo(&$case,$i)
	{/*{{{*/
        $zhi = $case['zhi'][$i];
        $dictDiZhi = $case['dict']['dizhi'];
		return array (
			'name' => $zhi['z'],
			'wuxing' => $zhi['wuxing'],
			'shengxiao' => $dictDiZhi[$zhi['z']]['shengxiao'],
		);
	}/*}}}*/

	// 获取两地支的关系
	private static function getZhiRelation($z1,$z2)
	{
		$map = C::m('#bazi#bazi_theory')->zhiRelationMap;
		$k = $z1.$z2;
		return (isset($map[$k])) ? $map[$k] : '';
	}
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
