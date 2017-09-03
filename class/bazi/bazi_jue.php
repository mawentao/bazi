<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
/**
 * 八字论断口诀
 **/
class bazi_jue
{
	private $data=array();


    public static function getByJueId($jueid)
    {
        return C::t('#bazi#bazi_jue')->fetch_by_pk($jueid);
    }


    public static function getByJueName($juename)
    {
        return C::t('#bazi#bazi_jue')->fetch_by_name($juename);
    }
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
