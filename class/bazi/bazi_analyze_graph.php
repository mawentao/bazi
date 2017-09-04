<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
/**
 * 八字关系图（合、冲、克、害、刑、破）
 *     1. 天干冲合
 *     2. 同柱干支生克关系
 *     3. 地支合、冲、克、害、刑、破
 **/
class bazi_analyze_graph
{
    public static function analyze(&$baziCase)
    {
        $bazi = &$baziCase->data;
        $bazi['graph'] = array(
            'nodes' => array(),   //!< 节点
            'links' => array(     //!< 关系边
                'gan_he'    => array(),   //!< 天干相合
                'gan_chong' => array(),   //!< 天干相冲
                'zhi_he'    => array(),   //!< 地支六合
                'zhi_chong' => array(),   //!< 地支相冲
                'zhi_xing'  => array(),   //!< 地支相刑
                'zhi_po'    => array(),   //!< 地支相破
                'zhi_hai'   => array(),   //!< 地支相害
            ),
        );
        //1. 八个节点:年月日时干支
        $zhuMap = array('年','月','日','时');
        for($i=0;$i<4;++$i) {
            foreach (array('gan','zhi') as $k) {
                $node = array (
                    'role' => $zhuMap[$i].($k=='gan'?'干':'支'),
                    'name' => $bazi[$k][$i]['z'],
                    'wuxing' => $bazi[$k][$i]['info']['wuxing'],
                    'shishen' => $bazi[$k][$i]['shishen'],
                );
                $bazi['graph']['nodes'][] = $node;
            }
        }
        //2. 节点关系
        self::ganRelation2($bazi);  //!< 天干间的二元关系(合,冲)
        self::zhiRelation2($bazi);  //!< 地支间的二元关系(六合,冲,刑,破,害)

        //die(json_encode($bazi['graph']));
	}

    // 天干冲合关系
    private static function ganRelation2(&$bazi)
    {/*{{{*/
        $links = &$bazi['graph']['links'];
        $zhuMap = array('年','月','日','时');
        // 计算
        for ($i=0;$i<4;++$i) {
            $gan1 = $bazi['gan'][$i];
            $role1 = $zhuMap[$i]."干";
            for ($j=$i+1;$j<4;++$j) {
                $gan2 = $bazi['gan'][$j];
                $role2 = $zhuMap[$j]."干";
                $arr = bazi_base::sort_gans(array($gan1['z'],$gan2['z']));
                $zz = implode('',$arr);
                $relations = bazi_base::$GAN_RELATIONS[$zz];
                if (!empty($relations)) {
                    foreach ($relations as $relation) {
                        $link = array($role1,$role2,$relation);
                        switch($relation) {
                            case '冲': $links['gan_chong'][] = $link; break;
                            default: $links['gan_he'][] = $link; break;
                        }
                    }
                }
            }
        }
    }/*}}}*/

    // 地支间的二元关系
    private static function zhiRelation2(&$bazi)
    {/*{{{*/
        $zhuMap = array('年','月','日','时');
        $links = &$bazi['graph']['links'];
        // 计算
        for ($i=0;$i<4;++$i) {
            $zhi1 = $bazi['zhi'][$i];
            $role1 = $zhuMap[$i]."支";
            for ($j=$i;$j<4;++$j) {
                $zhi2 = $bazi['zhi'][$j];
                $role2 = $zhuMap[$j]."支";

                if ($zhi1['z']==$zhi2['z']) continue;  //!< 不看自刑
                $zhiArr = bazi_base::sort_zhis(array($zhi1['z'],$zhi2['z']));
                $zz = implode('',$zhiArr);
                $relations = bazi_base::$ZHI_RELATIONS[$zz];
                if (!empty($relations)) {
                    foreach ($relations as $relation) {
                        $link = array($role1,$role2,$relation);
                        switch($relation) {
                            case '合': $links['zhi_he'][] = $link; break;
                            case '冲': $links['zhi_chong'][] = $link; break;
                            case '刑': $links['zhi_xing'][] = $link; break;
                            case '破': $links['zhi_po'][] = $link; break;
                            case '害': $links['zhi_hai'][] = $link; break;
                        }
                    }
                }
            }
        }
    }/*}}}*/

}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
