<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
/**
 * 导航设置 
 * C::m('#bazi#bazi_nav_setting')->get()
 **/
class model_bazi_nav_setting
{
	// 获取默认配置
    public function getDefault()
    {
		$setting = array (
			'navlist' => array (
				array('displayorder'=>0,'icon'=>'fa fa-home','text'=>'首页','href'=>'#/index',
                      'newtab'=>0,'enable'=>1),
                array('displayorder'=>1,'icon'=>'fa fa-question-circle','text'=>'帮助','href'=>'#/help',
                      'newtab'=>0,'enable'=>1),
			)
		);
		return $setting;
    }
    // 获取配置
	public function get()
	{
		$setting = $this->getDefault();
		global $_G;
		if (isset($_G['setting']['bazi_nav_setting'])){
			$config = unserialize($_G['setting']['bazi_nav_setting']);
			foreach ($setting as $key => &$item) {
				if (isset($config[$key])) $item = $config[$key];
			}
		}
		return $setting;
	}

	// 获取可用配置
	public function getenablelist()
	{
		$s = $this->get();
		$navlist = array();
		foreach ($s['navlist'] as $im) {
			if ($im['enable']!=1) continue;
			$navlist[] = $im;
		}
		return $navlist;
	}
	
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
