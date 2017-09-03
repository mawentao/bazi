<?php
if (!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
/**
 * 八字论断口诀表
 **/
class table_bazi_jue extends discuz_table
{
    public function __construct() {
		$this->_table = 'bazi_jue';
		$this->_pk = 'jueid';
		parent::__construct();
	}

	// 根据主键获取记录
	public function fetch_by_pk($pk)
	{
		$sql = "SELECT * FROM ".DB::table($this->_table)." WHERE ".$this->_pk."='$pk'";
		return DB::fetch_first($sql);
	}

	// 根据name获取记录
	public function fetch_by_name($juename)
	{
		$sql = "SELECT * FROM ".DB::table($this->_table)." WHERE `name`='$juename'";
		return DB::fetch_first($sql);
    }
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
