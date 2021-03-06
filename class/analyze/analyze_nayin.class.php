<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
/**
 * 纳音
 * bazi_env::c('analyze_nayin')->analyze($bazi);
 **/
class analyze_nayin
{
	public $nayin_map = array (
		'甲子'=>'海中金','甲戌'=>'山头火','甲申'=>'泉中水','甲午'=>'沙中金','甲辰'=>'复灯火','甲寅'=>'大溪水',
		'乙丑'=>'海中金','乙亥'=>'山头火','乙酉'=>'泉中水','乙未'=>'沙中金','乙巳'=>'复灯火','乙卯'=>'大溪水',
		'丙寅'=>'炉中火','丙子'=>'涧下水','丙戌'=>'屋上土','丙申'=>'山下火','丙午'=>'天河水','丙辰'=>'沙中土',
		'丁卯'=>'炉中火','丁丑'=>'涧下水','丁亥'=>'屋上土','丁酉'=>'山下火','丁未'=>'天河水','丁巳'=>'沙中土',
		'戊辰'=>'大林木','戊寅'=>'城头土','戊子'=>'霹雳火','戊戌'=>'平地木','戊申'=>'大驿土','戊午'=>'天上火',
		'己巳'=>'大林木','己卯'=>'城头土','己丑'=>'霹雳火','己亥'=>'平地木','己酉'=>'大驿土','己未'=>'天上火',
		'庚午'=>'路旁土','庚辰'=>'白蜡金','庚寅'=>'松柏木','庚子'=>'壁上土','庚戌'=>'钗钏金','庚申'=>'石榴木',
		'辛未'=>'路旁土','辛巳'=>'白蜡金','辛卯'=>'松柏木','辛丑'=>'壁上土','辛亥'=>'钗钏金','辛酉'=>'石榴木',
		'壬申'=>'剑锋金','壬午'=>'杨柳木','壬辰'=>'长流水','壬寅'=>'金箔金','壬子'=>'桑拓木','壬戌'=>'大海水',
		'癸酉'=>'剑锋金','癸未'=>'杨柳木','癸巳'=>'长流水','癸卯'=>'金箔金','癸丑'=>'桑拓木','癸亥'=>'大海水',
	);

    public function analyze(&$bazi)
    {
		$map = array();
		$arr = array('nian','yue','ri','hour');
		foreach ($arr as $z) {
			$jz = $bazi[$z.'_gan'].$bazi[$z.'_zhi'];
			$map[$z] = $this->nayin_map[$jz];
		}
		$bazi['nayin'] = &$map;
		//die(json_encode($map));
	}
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
