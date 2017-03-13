<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
/**
 * 用户登录,注册,注销模块
 * C::m('#bazi#bazi_uc')->func()
 **/
class model_bazi_uc
{
	// 退出登录
	public function logout()
	{/*{{{*/
		global $_G;
        require_once libfile('function/misc');
        require_once libfile('function/mail');
		require_once libfile('function/member');
		require_once libfile('class/member');

		$ctlObj = new logging_ctl();
		$ctlObj->setting = $_G['setting'];
		clearcookies();
		$_G['groupid'] = $_G['member']['groupid'] = 7;
		$_G['uid'] = $_G['member']['uid'] = 0;
		$_G['username'] = $_G['member']['username'] = $_G['member']['password'] = '';
	}/*}}}*/

	// 登录校验，成功返回uid，失败返回error_sring
    public function logincheck($username, $password, $questionid, $answer)
    {/*{{{*/
        global $_G;
        require_once libfile('function/misc');
        require_once libfile('function/mail');
		require_once libfile('function/member');
		require_once libfile('class/member');
        loaducenter();
        try {
            if(!($_G['member_loginperm'] = logincheck($username))) {
                throw new Exception("错误次数过多，请稍后再试");
            }
            $result = userlogin($username, $password, $questionid, $answer, 'username', $_G['clientip']); 
            $uid = $result["ucresult"]["uid"];
            if ($uid<=0) {
                throw new Exception("用户名或密码错误");
            }
            return $uid;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }/*}}}*/

    // 登录
    public function dologin($uid)
    {/*{{{*/
        global $_G;
        if (!($member = getuserbyuid($uid, 1))) {
            return false;
        }
        if (isset($member['_inarchive'])) {
            C::t('common_member_archive')->move_to_master($member['uid']);
        }
        require_once libfile('function/member');
        $cookietime = 1296000;
        setloginstatus($member, $cookietime);
		//dsetcookie('connect_login', 1, $cookietime);
		//dsetcookie('connect_is_bind', '1', 31536000);
		//dsetcookie('connect_uin', $connect_member['conopenid'], 31536000);
        return true;
    }/*}}}*/

	// 生成随机email
    public function gen_rand_email($email)
    {/*{{{*/
        $arr = explode("@",$email);
        $p = $arr[0];
        $e = $arr[1];
		$charset = array(
			"a","b","c","d","e","f","g","h","i","j","k","l","m",
			"n","o","p","q","r","s","t","u","v","w","x","y","z",
			"0","1","2","3","4","5","6","7","8","9"
		);
        $len = count($charset);
		$res = "";
		shuffle($charset);
		for ($i=0; $i<2; ++$i) {
			$rn = mt_rand(0,$len-1);
			$char = $charset[$rn];
			$charset[$rn] = $charset[$len-1];
			--$len;
			if (!is_numeric($char)) {
				$seed = mt_rand(0,1);
				if ($seed == 0) $char = strtoupper($char);
			}
			$res.= $char;
		}
        return "$p$res@$e";
    }/*}}}*/

    // 注册
    public function regist($username, $password, $email, $profile=array())
    {/*{{{*/
        global $_G;
        require_once libfile('function/misc');
        require_once libfile('function/mail');
		require_once libfile('function/member');
		require_once libfile('class/member');

		try {
            //1. check name,pass
            $userNamelen = dstrlen($username);
            if ($userNamelen<3 || $userNamelen > 15) {
                throw new Exception("username_len_invalid");
            }
            $passwdlen = dstrlen($password);
            if ($passwdlen<6 || $passwdlen > 20) {
                throw new Exception("password_len_invalid");
            }
			loaducenter();
            //1. gen uid
			$ctlObj = new register_ctl();
			$uid = uc_user_register($username, $password, $email, '', '', $_G['clientip']);
            if ($uid<=0) {
                switch ($uid) {
                    case -3: throw new Exception("used_username"); break;
                    case -4:
                    case -5: throw new Exception("invalid_email"); break;
                    case -6:
                        $email = $this->gen_rand_email($email);
                        return $this->regist($username, $password, $email, $profile);
                        break;
                    default: throw new Exception("regist_failed"); break;
                };
            }
            //2. insert db
			if($ctlObj->setting['regverify']) {
				$groupinfo['groupid'] = 8;
			} else {
				$groupinfo['groupid'] = $ctlObj->setting['newusergroupid'];
			}
			$verifyarr = array ();
			$emailstatus = 0;
			$init_arr = array('credits' => explode(',', $ctlObj->setting['initcredits']), 'profile'=>$profile, 'emailstatus' => $emailstatus);
            $password = md5($password.time());
			C::t('common_member')->insert($uid, $username, $password, $email, $_G['clientip'], $groupinfo['groupid'], $init_arr);
            //3. do login
            $this->dologin($uid);
            return $uid;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }/*}}}*/

	// 验证用户当前密码
	public function check_user_password($uid,$password)
	{/*{{{*/
		loaducenter();
		list($result) = uc_user_login($uid, $password, 1, 0);
		return $result>=0;
	}/*}}}*/

	// 更改密码 
	public function update_user_password($username,$newpassword)
	{/*{{{*/
		loaducenter();
		$res = uc_user_edit($username, '', $newpassword, '', 1); 
		return $res>0;
	}/*}}}*/

}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
