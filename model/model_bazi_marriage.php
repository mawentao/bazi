<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
/**
 * 合婚模块
 * C::m('#bazi#bazi_marriage')->func()
 **/
class model_bazi_marriage
{
	// 密钥
    private $_dekey = 'MwT2017';

	// 合婚加密
	public function encode_marriage($male_case_id,$female_case_id)
	{
		global $_G;
		$str = $male_case_id."_".$female_case_id."_".$_G['uid']."_".time();
		$eyc = authcode($str,'ENCODE',$this->_dekey);
		return preg_replace("/\+/i",'-',$eyc);
	}

    // 合婚解密
    public function decode_marriage($str)
    {   
        $res = array (
            'uid' => 0,
			'male_case_id' => 0,
			'female_case_id' => 0,
			'tm' => 0,
        );  
		$str = preg_replace("/-/i",'+',$str);
        $s = authcode($str,'DECODE',$this->_dekey);
        list($res['male_case_id'],$res['female_case_id'],$res['uid'],$res['tm']) = explode('_',$s);
        return $res;
    }
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
