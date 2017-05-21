<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
/**
 * 加解密模块
 * C::m('#bazi#bazi_authcode')->func()
 **/
class model_bazi_authcode
{
	// 密钥
    private $_dekey = 'MwT2017';

    // 解密id
    public function decode_id($str)
    {   
        $res = array (
            'uid' => 0,
            'board_id' => 0,
        );  
        $s = authcode($str,'DECODE',$this->_dekey);
        list($uid,$board_id,$tm) = explode('_',$s);
        $res['uid'] = intval($uid);
        $res['id']  = intval($board_id);
        $res['tm']  = $tm;
        return $res['id'];
    }

    // 加密
    public function encode_id($id)
    {   
        global $_G;
        $str = $_G['uid']."_".$id."_".time();
        return authcode($str,'ENCODE',$this->_dekey);
    }
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
