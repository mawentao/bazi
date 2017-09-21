<?php
if (!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
/**
 * 命例分组表
 **/
class table_bazi_case_group extends discuz_table
{
    public function __construct() {
		$this->_table = 'bazi_case_group';
		$this->_pk = 'cgid';
		parent::__construct();
	}

    // 获取用户全部可用分组列表
    public function getall($uid)
    {
        $where = "uid IN (0,$uid) AND isdel=0";
        $sql = "SELECT cgid,cgname,uid FROM ".DB::table($this->_table)." WHERE $where ORDER BY cgid ASC";
        return DB::fetch_all($sql);
    }

	// 命例分组查询接口
    public function query()
    {/*{{{*/
		global $_G;
        $uid = $_G['uid'];
        $groupid = $_G['groupid'];
        $return = array(
            "totalProperty" => 0,
            "root" => array(),
        );
        $key    = bazi_validate::getNCParameter('key','key','string');
        $sort   = bazi_validate::getOPParameter('sort','sort','string',64,'cgid');
        $dir    = bazi_validate::getOPParameter('dir','dir','string',64,'ASC');
        $start  = bazi_validate::getOPParameter('start','start','integer',1024,0);
        $limit  = bazi_validate::getOPParameter('limit','limit','integer',1024,0);
        $where  = "isdel=0";
		if ($uid!=1) $where.=" AND uid IN (0,$uid)";
        if ($key != '') {
            $where .= " AND (a.name like '%$key%')";
        }
        if ($sort=='ctime' || $sort=='uid') $sort='a.'.$sort;
        $table_bazi_case_group = DB::table($this->_table);
        $table_common_member = DB::table('common_member');
        $sql = <<<EOF
SELECT SQL_CALC_FOUND_ROWS a.cgid,a.cgname,a.uid,a.ctime,
b.username
FROM $table_bazi_case_group as a LEFT JOIN $table_common_member as b ON a.uid=b.uid
WHERE $where
ORDER BY $sort $dir
EOF;
        if ($limit>0) {
            $sql .= " LIMIT $start,$limit";
        }

        $return["root"] = DB::fetch_all($sql);
        $res = DB::fetch_first("SELECT FOUND_ROWS() AS total");
        $return["totalProperty"] = intval($res['total']);
        return $return;
    }/*}}}*/
	
	// 保存分组
	public function save()
    {/*{{{*/
		global $_G;
        $uid = $_G['uid'];
        $cgid   = bazi_validate::getNCParameter('cgid','cgid','integer');
        $cgname = bazi_validate::getNCParameter('cgname','cgname','string',32);
        $sql = "SELECT cgid FROM "DB::table($this->_table)." WHERE uid IN (0,$uid) AND isdel=0";
        $item = DB::fetch_first($sql);
        if (!empty($item) && $item['cgid']!=$cgid) {
            throw new Exception("[$cgname]分组已存在");
        }
        $addtime = date('Y-m-d H:i:s');
        $sql = "INSERT INTO ".DB::table($this->_table)." (cgid,cgname,uid,ctime,isdel) values ".
               "($cgid,'$cgname',$uid,'$addtime',0) ON DUPLICATE KEY UPDATE ". 
               "cgname=values(cgname),ctime=values(ctime),isdel=values(isdel)";
        DB::query($sql);
        return 0;
    }/*}}}*/

}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
