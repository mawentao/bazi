<?php
if (!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
/**
 * 八字地支表
 **/
class table_bazi_dict_dizhi extends discuz_table
{
    private $map = array();

    public function __construct() {
		$this->_table = 'bazi_dict_dizhi';
		$this->_pk = 'name';
		parent::__construct();
	}

    // 获取映射表
    public function getMap() 
    {
        if (empty($this->map)) {
            $res = DB::fetch_all('SELECT * FROM '.DB::table($this->_table));
            foreach ($res as &$row) {
                $name = $row['name'];
                $row['canggan'] = explode(',',$row['canggan']);
                $this->map[$name] = $row;
            }
        }
        return $this->map;
    }

    public function get($name)
    {
        $map = $this->getMap();
        return $map[$name];
    }
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
