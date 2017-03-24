<?php
if (!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
/**
 * 八字提要《千里命稿》
 **/
class table_bazi_tiyao extends discuz_table
{
    public function __construct() {
		$this->_table = 'bazi_tiyao';
		$this->_pk = 'tk';
		parent::__construct();
	}

	// 根据提要ID获取内容（日干.月支.时支）
	public function get_by_tk($tk)
	{
		$sql = "SELECT tv FROM ".DB::table($this->_table)." WHERE tk='$tk'";
		return DB::fetch_first($sql);
	}
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
