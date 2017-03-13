<?php
if (!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
/**
 * 命例表
 **/
class table_bazi_case extends discuz_table
{
    public function __construct() {
		$this->_table = 'bazi_case';
		$this->_pk = 'caseid';
		parent::__construct();
	}

	// 根据命例ID获取详细信息
	public function get_by_caseid($caseid)
	{
		$table_bazi_case = DB::table('bazi_case');
		$table_bazi_birth = DB::table('bazi_birth');
		$table_bazi_calendar = DB::table('bazi_calendar');
		$sql = <<<EOF
SELECT a.caseid,a.name,a.bid,
b.gender,b.hour_gan,b.hour_zhi,
c.*
FROM $table_bazi_case as a LEFT JOIN $table_bazi_birth as b ON a.bid=b.bid
LEFT JOIN $table_bazi_calendar as c ON b.solar_calendar=c.solar_calendar
WHERE caseid='$caseid' AND isdel=0
EOF;
		return DB::fetch_first($sql);
	}


	// 保存命例
	public function save()
	{
        $caseid = bazi_validate::getNCParameter('caseid','caseid','integer');
		$name   = bazi_validate::getNCParameter('name','name','string');
        $gender = bazi_validate::getNCParameter('gender','gender','string');
		$year   = bazi_validate::getNCParameter('year','year','integer');
		$month  = bazi_validate::getNCParameter('month','month','integer');
		$date   = bazi_validate::getNCParameter('date','date','integer');
		$hour   = bazi_validate::getNCParameter('hour','hour','integer');
		$tm = strtotime("$year-$month-$date");
		$solar_calendar = date('Y-m-d',$tm);
		$bid = C::t('#bazi#bazi_birth')->get_bid($solar_calendar,$gender,$hour);
		$data = array (
			'bid' => $bid,
			'name' => $name,
			'ctime' => date("Y-m-d H:i:s"),
		);
		$res = $caseid==0 ? $this->insert($data) : $this->update($caseid,$data);
		return $res;
	}

	// 命例管理查询接口
    public function query_all()
    {/*{{{*/
		global $_G;
		$uid = $_G['uid'];
		$groupid = $_G['groupid'];

        $return = array(
            "totalProperty" => 0,
            "root" => array(),
        );
        $key    = bazi_validate::getNCParameter('key','key','string');
        $gender = bazi_validate::getNCParameter('gender','gender','string');
		$sort   = bazi_validate::getOPParameter('sort','sort','string',64,'ctime');
		$dir    = bazi_validate::getOPParameter('dir','dir','string',64,'DESC');
		$start  = bazi_validate::getOPParameter('start','start','integer',1024,0);
		$limit  = bazi_validate::getOPParameter('limit','limit','integer',1024,10);
        $where  = "isdel=0";
		if ($gender!='0') $where.=" AND b.gender='$gender'";
        if ($key != '') {
			$where .= " AND (a.name like '%$key%' OR a.bid like '%$key%')";
        }
		if ($sort=='ctime') $sort='a.ctime';
		$table_bazi_case = DB::table('bazi_case');
		$table_bazi_birth = DB::table('bazi_birth');
		$table_bazi_calendar = DB::table('bazi_calendar');
		$sql = <<<EOF
SELECT SQL_CALC_FOUND_ROWS a.caseid,a.name,a.bid,
b.gender,b.hour_gan,b.hour_zhi,
c.*
FROM $table_bazi_case as a LEFT JOIN $table_bazi_birth as b ON a.bid=b.bid
LEFT JOIN $table_bazi_calendar as c ON b.solar_calendar=c.solar_calendar
WHERE $where
ORDER BY $sort $dir
LIMIT $start,$limit
EOF;
		$return["root"] = DB::fetch_all($sql);
		$res = DB::fetch_first("SELECT FOUND_ROWS() AS total");
		$return["totalProperty"] = intval($res['total']);

        return $return;
    }/*}}}*/
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
