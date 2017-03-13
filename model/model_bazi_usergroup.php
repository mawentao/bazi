<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
/**
 * 用户组模块
 * C::m('#bazi#bazi_usergroup')->func()
 **/
class model_bazi_usergroup
{
	private $groupmap = array();

	private function fetch_all()
	{
		// 参考 source/admincp/admincp_usergroups.php 中的代码
		foreach(C::t('common_usergroup')->fetch_all_not(array(6, 7), true) as $group) {
			$groupid = $group['groupid'];
			$this->groupmap[$groupid] = $group;
		}
	}

	public function grouptitle($groupid)
	{
		if (empty($this->groupmap)) {
			$this->fetch_all();
		}
		return isset($this->groupmap[$groupid]) ? $this->groupmap[$groupid]['grouptitle'] : 'unknow_user_group';
	}

	// 参考 source/admincp/admincp_usergroups.php 中的代码
	public function usergroupselect()
	{
		$groupselect = array();
        foreach(C::t('common_usergroup')->fetch_all_not(array(6, 7), true) as $group) {
            $group['type'] = $group['type'] == 'special' && $group['radminid'] ? 'specialadmin' : $group['type'];
            $groupselect[$group['type']] .= "<option value=\"$group[groupid]\">$group[grouptitle]</option>\n";
        }    
        $groupselect = '<option value>空</option>'.
//			'<optgroup label="'.lang('admincp','usergroups_member').'">'.$groupselect['member'].'</optgroup>'.
            ($groupselect['special'] ? '<optgroup label="'.lang('admincp','usergroups_special').'">'.$groupselect['special'].'</optgroup>' : ''). 
//            ($groupselect['specialadmin'] ? '<optgroup label="'.lang('admincp','usergroups_specialadmin').'">'.$groupselect['specialadmin'].'</optgroup>': '').
//            '<optgroup label="'.lang('admincp','usergroups_system').'">'.$groupselect['system'].'</optgroup>'.
			'';
        //$usergroupselect = '<select name="target[]" size="10" multiple="multiple">'.$groupselect.'</select>';
        $usergroupselect = $groupselect;
		return $usergroupselect;
	}

	// 获取用户组选项列表
	public function getoptions()
	{
		$res = array(array('text'=>'全部','value'=>0));
		if (empty($groupmap)) {
			$this->fetch_all();
		}
		$map = $this->groupmap;
		foreach ($map as $rgid => $row) {
			if ($rgid==1 || $rgid==10 || ($rgid>=20 && $rgid<=30)) {
				$res[] = array (
					'value' => $rgid,
					'text' => $row['grouptitle'],
				);
			}
		}
		return $res;
	}
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
