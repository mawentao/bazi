<?php
if (!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
/**
 * 万年历表
 **/
class table_bazi_calendar extends discuz_table
{
    public function __construct() {
		$this->_table = 'bazi_calendar';
		$this->_pk = 'solar_calendar';
		parent::__construct();
	}

	// 根据主键获取记录
	public function fetch_by_pk($pk)
	{
		$sql = "SELECT * FROM ".DB::table($this->_table)." WHERE ".$this->_pk."='$pk'";
		return DB::fetch_first($sql);
	}

	// 获取起运年份 ($sort=1: 顺排, $sort=-1: 逆排)
	public function get_qiyun_nian($day,$sort)
	{
		$terms = "'立春','惊蛰','清明','立夏','芒种','小暑','立秋','白露','寒露','立冬','大雪','小寒'";
		$table = DB::table($this->_table);
		// 顺排
		$sql = "SELECT min(solar_calendar) as day FROM $table WHERE date(solar_calendar)>=date('$day') AND term IN ($terms)";
		// 逆排
		if ($sort==-1) {
			$sql = "SELECT max(solar_calendar) as day FROM $table WHERE date(solar_calendar)<=date('$day') AND term IN ($terms)";	
		} 
		$row = DB::fetch_first($sql);
		//print_r($row);
		$tm1 = strtotime($row['day']);
		$tm2 = strtotime($day);
		$daydiff = abs(intval(($tm2-$tm1)/86400));
		$qiyun_sui = ceil($daydiff/3);  //3天为1年
		$sheng_nian = intval(date('Y',$tm2));
		//echo "sheng_nian: $sheng_nian ";
		$qiyun_nian = $sheng_nian+$qiyun_sui;
		//die("$qiyun_sui 岁开始起运  起运年份：$qiyun_nian");
		return $qiyun_nian;
	}
	
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
