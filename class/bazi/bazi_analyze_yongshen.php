<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
/**
 * 八字旺衰分析&找用神
 *    装十神
 *    排大运流年
 **/
class bazi_analyze_yongshen
{
    public function analyze(&$baziCase)
    {   
		$bazi = &$baziCase->data;
        //1. 五行&天干&十神力量旺衰分析(!!!论命核心,此环节分析错误,全盘皆错!!!)
        self::analyzePower($bazi);
        //2. 力量排序
        self::analyzePowerSort($bazi);
        //3. 日元旺衰
        self::analyzeRiyuanPower($bazi);
        //4. 找用神,明喜忌
        self::analyzeYongShen($bazi);
        self::analyzeJiShen($bazi);
		return;
    }

    // 五行&天干&十神力量旺衰分析(!!!!旺衰核心算法!!!!)
    private static function analyzePower(&$bazi)
    {/*{{{*/
        $dictWuXingMap = &$bazi['dict']['wuxing'];
        $dictGanMap = &$bazi['dict']['tiangan'];
        $dictShiShenMap = &$bazi['dict']['shishen'];
        //1. 初始化力量值
        foreach ($dictWuXingMap as $wuxing => &$item) {
            $item['power'] = 0;
        }
        foreach ($dictGanMap as $gan => &$item) {
            $item['power'] = 0;
        }
        foreach ($dictShiShenMap as $shishen => &$item) {
            $item['power'] = 0;
        }
        //2. 五行力量
        $powerSum = 0;
        $lingPowerMap = array('旺'=>5,'相'=>4,'休'=>3,'囚'=>2,'死'=>1);
        $shengWoWuXingMap = array('木'=>'水','火'=>'木','土'=>'火','金'=>'土','水'=>'金');
        foreach ($dictWuXingMap as $wuxing => &$item) {
            $statAll = $item['statInGan']+$item['statInZhiCang'];  //!< 天干及地支藏干中的五行个数统计
            if ($statAll==0) continue;  //!< 五行未出现
            //2-1. 五行得令力量
            $lingPower = $lingPowerMap[$item['state']];
            //2-2. 五行得根力量
            $genPower = 0;
            if ($item['statInGan']>0) {  // 得根前提: 自己要透天干
                $genPower = $item['statInZhiCang'];
            }
            //2-3. 五行得生力量
            $shengPower = 0;
            if ($genPower>0) { // 得生力量前提: 自己要得根(注意:得令一定会得根)
                $shengWoXing = $shengWoWuXingMap[$wuxing];
                $shengWoXingInfo = $dictWuXingMap[$shengWoXing];
                $shengPower = $shengWoXingInfo['statInGan']+$shengWoXingInfo['statInZhi'];
            }
            //2-4. 五行得助力量
            $zhuPower = $item['statInGan']+$item['statInZhi']-1;
            // 力量综合算法
            $item['powerDis'] = array($lingPower,$genPower,$shengPower,$zhuPower);
            $item['power'] = $lingPower * 2 + $genPower * 1.5 + $shengPower * 1.2 + $zhuPower;
            $powerSum += $item['power'];
        }
        // 五行力量归一化(取百分比)
        foreach ($dictWuXingMap as $wuxing => &$item) {
            $item['power'] = round($item['power']*100 / $powerSum,2);
        }

        //3. 天干力量
        $powerSum = 0;
        foreach ($dictGanMap as $gan => &$item) {
            // 天干及地支藏干中均未出现
            if ($item['statInGan']+$item['statInZhiCang']==0) continue;
            //3-1. 得令力量
            $wuxing = $item['wuxing'];
            $wuxingInfo = $dictWuXingMap[$wuxing];
            $lingPower = $lingPowerMap[$wuxingInfo['state']];
            //3-2. 得根力量
            $genPower = 0;
            if ($item['statInGan']>0) { // 得根前提: 自己要透天干
                $genPower = $item['statInZhiCang'];
            }
            //3-4. 得生力量
            $shengPower = 0;
            if ($genPower>0) { // 得生力量前提: 自己要得根(注意:得令一定会得根)
                $shengWoXing = $shengWoWuXingMap[$wuxing];
                $shengWoXingInfo = $dictWuXingMap[$shengWoXing];
                $shengPower = $shengWoXingInfo['statInGan']+$shengWoXingInfo['statInZhi'];
            }
            //3-3. 得助力量
            $zhuPower = $wuxingInfo['statInGan']+$wuxingInfo['statInZhi']-1;

            // 力量综合算法
            $item['powerDis'] = array($lingPower,$genPower,$shengPower,$zhuPower);
            $item['power'] = $lingPower * 2 + $genPower * 1.5 + $shengPower * 1.2 + $zhuPower;
            $powerSum += $item['power'];
        }
        // 天干力量归一化(取百分比)
        foreach ($dictGanMap as $gan => &$item) {
            $item['power'] = round($item['power']*100 / $powerSum,2);
        }

        //4. 十神力量
        $powerSum = 0;
        foreach ($dictShiShenMap as $shishen => &$item) {
            // 天干及地支藏干中均未出现
            if ($item['statInGan']+$item['statInZhiCang']==0) continue;
            $gan = $item['gan'];
            $ganInfo = $dictGanMap[$gan];
            $item['power'] = $ganInfo['power'];   //!< 十神力量取对应天干的力量
            $powerSum += $item['power'];
        }
        // 十神力量归一化(取百分比)
        foreach ($dictShiShenMap as $shishen => &$item) {
            $item['power'] = round($item['power']*100 / $powerSum,2);
        }
    }/*}}}*/

    // 力量排序
    private static function analyzePowerSort(&$bazi)
    {/*{{{*/
        $bazi['powerSort'] = array (
            'wuxing'  => array(),
            'tiangan' => array(),
            'shishen' => array(),
        );
        $dictWuXingMap = &$bazi['dict']['wuxing'];
        $dictGanMap = &$bazi['dict']['tiangan'];
        $dictShiShenMap = &$bazi['dict']['shishen'];
        //1.
        foreach ($dictWuXingMap as $wuxing => &$item) {
            $bazi['powerSort']['wuxing'][] = array (
                'wuxing' => $wuxing,
                'power'  => $item['power'],
            );
        }
        foreach ($dictGanMap as $gan => &$item) {
            $bazi['powerSort']['tiangan'][] = array (
                'gan'   => $gan,
                'power' => $item['power'],
            );
        }
        foreach ($dictShiShenMap as $shishen => &$item) {
            $bazi['powerSort']['shishen'][] = array (
                'shishen' => $shishen,
                'power'   => $item['power'],
            );
        }
        //2. 排序
        foreach ($bazi['powerSort'] as &$arr) {
            bazi_utils::array_sort_by($arr,'power','DESC');
        }
    }/*}}}*/

    // 日元强弱
    private static function analyzeRiyuanPower(&$bazi)
    {/*{{{*/
        $bazi['riyuanPower'] = array (
            'power'  => array(),
            'powerSort' => 0,
            'powerLevel' => '',
        );
        $dictWuXingMap = &$bazi['dict']['wuxing'];
        $riyuan = $bazi['gan'][2];
        $rywx = $riyuan['wuxing'];
        $wuxingPower = $dictWuXingMap[$rywx];
        $bazi['riyuanPower']['power'] = $wuxingPower['power'];
        // 日元力量排序位置
        foreach ($bazi['powerSort']['wuxing'] as $i => &$im) {
            if ($im['wuxing']==$rywx) {
                $bazi['riyuanPower']['power'] = $im['power'];
                $bazi['riyuanPower']['powerSort'] = $i;
            }
        }
        // 定性
        $level = '';
        switch ($bazi['riyuanPower']['powerSort']) {
            case 0: $level='极旺'; break;
            case 1: $level='旺'; break;
            case 2: $level='弱'; break;
            case 3: $level='衰'; break;
            case 4: $level='极衰'; break;
        };
        if ($bazi['riyuanPower']['power'] >=30) {
            $level = '极旺';
        }
        $bazi['riyuanPower']['powerLevel'] = $level;
    }/*}}}*/

    // 用神
    private static function analyzeYongShen(&$bazi)
    {/*{{{*/
        $bazi['xiji'] = array (
            'yongshen'  => array(
                'wuxing' => array(),    //!< 喜用神五行(1-2个)
                'type' => '',           //!< 用神类型(专旺,通关,扶抑,调候)
            ),
            'jishen' => array(
                'wuxing' => array(),    //!< 忌神五行(1-2个)
            ),
        );
        $yongshen = &$bazi['xiji']['yongshen'];
        $jishen = &$bazi['xiji']['jishen'];
        $dictWuXingMap = &$bazi['dict']['wuxing'];
        $dictGanMap = &$bazi['dict']['tiangan'];
        $dictShiShenMap = &$bazi['dict']['shishen'];
        $wuxingsort = &$bazi['powerSort']['wuxing'];
        $power0 = $wuxingsort[0]['power'];  //!< 力量最强
        $power1 = $wuxingsort[1]['power'];  //!< 力量次强
        $power2 = $wuxingsort[2]['power'];
        $power3 = $wuxingsort[3]['power'];
        $power4 = $wuxingsort[4]['power'];  //!< 力量最弱
        $riyuanPower = $bazi['riyuanPower'];//!< 日元五行力量

        //1. 某一五行专旺
        if ($power0>=50) {
            $yongshen['wuxing'][] = $wuxingsort[0]['wuxing'];
            $yongshen['type'] = '专旺';
            return;
        }


        //2. 两行比较旺
        $wuxing0 = $wuxingsort[0]['wuxing'];    //!< 力量最强的五行
        $wuxing1 = $wuxingsort[1]['wuxing'];    //!< 力量次强的五行
        if ($power0+$power1>=50 && $power0-$power1<5) {
            $wuxingInfo0 = $dictWuXingMap[$wuxing0];
            $wuxingInfo1 = $dictWuXingMap[$wuxing1];
            // 通关用神(两行相克,取主克五行所生五行)
            if ($wuxingInfo0['ke'] == $wuxing1) {
                $yongshen['wuxing'][] = $wuxingInfo0['sheng'];
                $yongshen['type'] = '通关';
                return;
            }
            if ($wuxingInfo1['ke'] == $wuxing0) {
                $yongshen['wuxing'][] = $wuxingInfo1['sheng'];
                $yongshen['type'] = '通关';
                return;
            }
            // 两行相生,若有一行生于夏天或冬天,取调候用神
            if ($wuxing0=='火'||$wuxing1=='火') {
                $yongshen['wuxing'][] = '水';
                $yongshen['type'] = '调候';
                $jishen['wuxing'][] = '火';
                return;
            }
            if ($wuxing0=='水'||$wuxing1=='水') {
                $yongshen['wuxing'][] = '火';
                $yongshen['type'] = '调候';
                $jishen['wuxing'][] = '水';
                return;
            }
            // 两行相生,取克制两五行的两行为用神
            foreach ($dictWuXingMap as $wx => &$im) {
                if ($im['ke']==$wuxing0 || $im['ke']==$wuxing1) {
                    $yongshen['wuxing'][] = $wx;
                }
            }
            $yongshen['type'] = '扶抑';
        }

        //3. 扶抑用神
        $yongshen['type'] = '扶抑';
        //3-1. 日元身旺,需克泄耗
        $riyuan = $bazi['riYuan'];
        $riyuanWuxing = $dictGanMap[$riyuan]['wuxing'];
        $riyuanPowerSort = $bazi['riyuanPower']['powerSort'];
        $wxasc = array_reverse($wuxingsort);  // 五行力量从弱到强查看
        if ($riyuanPowerSort<=1) {
            foreach ($wxasc as $im) {
                $wx = $im['wuxing'];
                $wxInfo = $dictWuXingMap[$wx];
                $ws = $wxInfo['sheng'];
                // 不能是生助自己的五行
                if ($wx==$riyuanWuxing || $wxInfo['sheng']==$riyuanWuxing) { continue; }
                // 不能是最强或次强的五行
                if ($wx==$wuxing0 || $wx==$wuxing1) continue;
                // 不能是生助最强次强的五行
                if ($ws==$wuxing0 || $ws==$wuxing1) continue;
                $yongshen['wuxing'][] = $wx;
            }
        }
        //3-2. 日元身弱,需生助
        else {
            foreach ($wxasc as $im) {
                $wx = $im['wuxing'];
                $wxInfo = $dictWuXingMap[$wx];
                $ws = $wxInfo['sheng'];
                if ($wx==$riyuanWuxing || $ws==$riyuanWuxing) { //!< 只要生助自己
                    // 且该五行不是最强次强的五行
                    // 且该五行不是生助最强次强的五行
                    if ($wx==$wuxing0 || $wx==$wuxing1 || $ws==$wuxing0 || $ws==$wuxing1) {
                        continue;
                    }
                    $yongshen['wuxing'][] = $wx;
                }
            }
        }
    }/*}}}*/

    // 忌神
    private static function analyzeJiShen(&$bazi)
    {/*{{{*/
        $yongshen = &$bazi['xiji']['yongshen'];
        $jishen = &$bazi['xiji']['jishen'];
        foreach ($bazi['dict']['wuxing'] as $wx => &$im) {
            $im['xiji'] = '';
            if (in_array($wx,$yongshen['wuxing'])) {
                $im['xiji'] = '喜';
            }
            else if (in_array($im['ke'],$yongshen['wuxing'])) {
                if (!in_array($wx,$jishen['wuxing'])) {
                    $jishen['wuxing'][] = $wx;
                }
            }
            if (in_array($wx,$jishen['wuxing'])) {
                $im['xiji'] = '忌';
            }
        }
    }/*}}}*/

    

}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
