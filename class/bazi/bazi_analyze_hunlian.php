<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
/**
 * 婚恋分析
 * 主要看5点:
 *    1. 配偶星
 *    2. 配偶宫
 *    3. 桃花星
 *    4. 伤官星
 *    5. 日柱
 **/
class bazi_analyze_hunlian
{
    public static function analyze(&$baziCase)
    {
		$bazi = &$baziCase->data;
        self::see_spouse($bazi);        //!< 配偶星
        $bazi['hunLian'] = array();
        self::see_gong($bazi);          //!< 配偶宫
        self::see_taohua($bazi);        //!< 桃花
        self::see_shangguan($bazi);     //!< 伤官星
        self::see_shensha($bazi);       //!< 日柱神煞
	}

    // 配偶星
    private static function see_spouse(&$bazi)
    {/*{{{*/
        //1. 获取命主的配偶星(正,偏)
        $shenmap = $bazi['myShiShenMap'];
        $spouse_star = array (
            '正' => array(),
            '偏' => array(),
        );  
        // 女命
        if ($bazi['gender']=='女') {
            $spouse_star['正'] = $shenmap['正官']['gan'];
            $spouse_star['偏'] = $shenmap['七杀']['gan'];
        }   
        // 男命
        else {
            $spouse_star['正'] = $shenmap['正财']['gan'];
            $spouse_star['偏'] = $shenmap['偏财']['gan'];
        }   
        $gan = $spouse_star['正'];
        $ganinfo = bazi_base::$GAN_MAP[$gan]; 
        $spouse_star['wuxing'] = $ganinfo['wuxing'];
        $bazi['spouse'] = $spouse_star;
        self::analyze_spouse($bazi);
    }/*}}}*/

    // 分析配偶星
    private static function analyze_spouse(&$bazi)
    {/*{{{*/
        $bazi['spouse']['jue'] = array();
        $myShiShenMap = &$bazi['myShiShenMap'];
        $zhengStar = $bazi['gender']=='男' ? '正财' : '正官';
        $pianStar = $bazi['gender']=='男' ? '偏财' : '七杀';
        $zhengStatInGan = $myShiShenMap[$zhengStar]['statInGan'];  //!< 天干中正配偶星次数
        $zhengStatInZhi = $myShiShenMap[$zhengStar]['statInZhi'];  //!< 地支中正配偶星次数
        $pianStatInGan = $myShiShenMap[$pianStar]['statInGan'];  //!< 天干中正配偶星次数
        $pianStatInZhi = $myShiShenMap[$pianStar]['statInZhi'];  //!< 地支中正配偶星次数
        $all = $zhengStatInGan+$zhengStatInZhi+$pianStatInGan+$pianStatInZhi;
        $allZheng = $zhengStatInGan + $zhengStatInZhi;
        $allPian = $pianStatInGan + $pianStatInZhi;

        $bazi['spouse']['zhengStat'] = $allZheng;   //!< 正配偶次数
        $bazi['spouse']['pianStat'] = $allPian;     //!< 偏配偶次数

        //1. 配偶星不显
        if ($all==0) {
            $bazi['spouse']['jue'][] = bazi_jue::getByJueId(200001);
        }
        //2. 命局配偶星多
        if ($all>=3) {
            $bazi['spouse']['jue'][] = bazi_jue::getByJueId(200002);
        }

        //98. 男命
        if ($bazi['gender']=='男') {
            // 男命正偏财透天干
            if ($zhengStatInGan>0 && $pianStatInGan>0) {
                $bazi['spouse']['jue'][] = bazi_jue::getByJueId(200004);
            }
            // 比劫重重
            $bijian = $myShiShenMap['比肩'];
            $jiecai = $myShiShenMap['劫财'];
            $bijianStat = $bijian['statInGan']+$bijian['statInZhi'];
            $jiecaiStat = $jiecai['statInGan']+$jiecai['statInZhi'];
            if ($bijianStat>=2 && $jiecaiStat>=2) {
                $bazi['spouse']['jue'][] = bazi_jue::getByJueId(200005);
            }
        }
        //99. 女命
        else {
            // 女命官杀混杂
            if ($allZheng>0 && $allPian>0) {
                $bazi['spouse']['jue'][] = bazi_jue::getByJueId(200003);
            }
        }
    }/*}}}*/

    // 看配偶宫
    private static function see_gong(&$bazi)
    {/*{{{*/
        $bazi['hunLian']['gong']['jue'] = array();

        $rizhiStat = array (
            '合' => 0,
            '冲' => 0,
            '刑' => 0,
            '破' => 0,
            '害' => 0,
        );
        foreach ($bazi['graph']['links'] as $k => $relations) {
            foreach ($relations as $relation) {
                $role1 = $relation[0];
                $role2 = $relation[1];
                $rel = $relation[2];
                if ($role1=='日支' || $role2=='日支') {
                    ++$rizhiStat[$rel];
                }
            }
        }
        if ($rizhiStat['冲']+$rizhiStat['刑']>0) {
            $bazi['hunLian']['gong']['jue'][] = bazi_jue::getByJueId(200101);
        }
        if ($rizhiStat['合']>1) {
            $bazi['hunLian']['gong']['jue'][] = bazi_jue::getByJueId(200102);
        }
        //die(json_encode($bazi['hunLian']['gong']['jue']));
    }/*}}}*/

    // 看桃花
    private static function see_taohua(&$bazi)
    {/*{{{*/
        $bazi['hunLian']['taohua']['jue'] = array();
        $hunLianTaoHua = array('沐浴桃花','内桃花','外桃花');
        $bazi['hunLian']['taohua']['jue'] = array();
        foreach ($bazi['shensha'] as &$sha) {
            $shaname = $sha['name'];
            if (in_array($shaname,$hunLianTaoHua)) {
                $bazi['hunLian']['taohua']['jue'][] = $sha;
            }
        }
    }/*}}}*/

    // 伤官星
    private static function see_shangguan(&$bazi)
    {/*{{{*/
        $jue = array();
        $myShiShenMap = &$bazi['myShiShenMap'];
        $shangGuan = $myShiShenMap['伤官'];
        $zhengGuan = $myShiShenMap['正官'];

        // 伤官星多
        if ($shangGuan['statInGan']+$shangGuan['statInZhi']>=2) {
            $jue[] = bazi_jue::getByJueId(200401);
        }
        //2. 女命
        if ($bazi['gender']=='女') {
            if ($shangGuan['statInGan']>0 && $zhengGuan['statInGan']>0) {
                $jue[] = bazi_jue::getByJueId(200402);
            }
            if ($bazi['zhi'][1]['shishen']['name']=='伤官') {
                $jue[] = bazi_jue::getByJueId(200403);   //!< 月令伤官
                if ($zhengGuan['statInGan']>0) {
                    $jue[] = bazi_jue::getByJueId(200402);
                }
            }
        }

        $bazi['hunLian']['shangguan']['jue'] = &$jue;
//die(json_encode($bazi['hunlian']['shangguan']['jue']));
    }/*}}}*/

    // 看日柱神煞
    private static function see_shensha(&$bazi)
    {/*{{{*/
        $hunLianShas = array('阴阳差错煞','孤鸾煞','红艳煞');
        $bazi['hunLian']['shensha']['jue'] = array();
        foreach ($bazi['shensha'] as &$sha) {
            $shaname = $sha['name'];
            if (in_array($shaname,$hunLianShas)) {
                $bazi['hunLian']['shensha']['jue'][] = $sha;
            }
        }
    }/*}}}*/

}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
