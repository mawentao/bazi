<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
/**
 * 八字提要（摘自《千里命稿》）
 * bazi_env::c('analyze_tiyao')->analyze($bazi);
 **/
class analyze_tiyao
{
    public function analyze(&$bazi)
    {
		$tk = $bazi['ri_gan'].$bazi['yue_zhi'].$bazi['hour_zhi'];
		$tiyao = C::t('#bazi#bazi_tiyao')->get_by_tk($tk);
		if (!empty($tiyao)) {
			$bazi['tiyao'] = $tiyao['tv'];
		}
	}
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
