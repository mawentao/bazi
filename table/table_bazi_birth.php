<?php
if (!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
/**
 * 八字生辰表
 **/
class table_bazi_birth extends discuz_table
{
    public function __construct() {
		$this->_table = 'bazi_birth';
		$this->_pk = 'bid';
		parent::__construct();
	}

	// 根据主键获取记录
	public function fetch_by_pk($pk)
	{
		$sql = "SELECT * FROM ".DB::table($this->_table)." WHERE ".$this->_pk."='$pk'";
		return DB::fetch_first($sql);
	}

	// 获取八字生辰ID(不存在会插入)
	public function get_bid($solar_calendar,$gender,$hour)
	{
		$tm = strtotime($solar_calendar);
		$hour_zhi = C::m('#bazi#bazi_theory')->get_hour_zhi($hour);
		$bid = date('Ymd',$tm).$hour_zhi.strtolower($gender);	
		$row = $this->fetch_by_pk($bid);
		// 为空则插入记录
		if (empty($row)) {
			$new_record = array (
				'bid' => $bid,
				'solar_calendar' => $solar_calendar,
				'gender' => strtolower($gender),
				'hour' => intval($hour),
				'hour_gan' => C::m('#bazi#bazi_theory')->get_hour_gan($solar_calendar,$hour),
				'hour_zhi' => $hour_zhi,
				'ctime' => date("Y-m-d H:i:s"),
			);
			$this->insert($new_record);
		}
		return $bid;
	}
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
