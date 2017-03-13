<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
/**
 * 图文验证码模块
 * C::m('#bazi#bazi_seccode')->func()
 **/
class model_bazi_seccode
{
    private $_seccode_cookie_key = 'bazi_seccode';

	/**
	 * 验证码校验
	 **/
	public function check($code)
	{/*{{{*/
		$code = strtolower($code);
		$vcode = getcookie($this->_seccode_cookie_key);
		return md5($code) == $vcode;
	}/*}}}*/

	/**
     * 生成验证码
     **/
	public function mkcode($num=4, $onlynum=false)
	{/*{{{*/
		// gen
        $charset = array(
            "a","b","c","d","e","f","g","h","i","j","k","l","m",
            "n","o","p","q","r","s","t","u","v","w","x","y","z",
            "0","1","2","3","4","5","6","7","8","9"
        );  
        if ($onlynum) {
            $charset = array("0","1","2","3","4","5","6","7","8","9");
        }
        $len = count($charset);
        $res = ""; 
        shuffle($charset);
        for ($i=0; $i<$num; ++$i) {
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
        // set cookie
        $lower = strtolower($res);
        dsetcookie($this->_seccode_cookie_key, md5($lower));
        return $res;
	}/*}}}*/

	/**
     * 显示验证码图片（使用discuz原生代码）
     **/
	public function display($seccode, $width=120, $height=40)
    {/*{{{*/
        global $_G;
        require_once './source/class/class_core.php';
        require_once libfile('class/seccode'); 
        $code = new seccode();
        $code->code = $seccode;
        $code->width = $width;
        $code->height = $height;
        $code->background = ""; //$_G['setting']['seccodedata']['background'];
        $code->adulterate = ""; //$_G['setting']['seccodedata']['adulterate'];
        $code->color = ""; 
        $code->fontpath = DISCUZ_ROOT.'./static/image/seccode/font/';
        $code->datapath = DISCUZ_ROOT.'./static/image/seccode/';
        $code->includepath = DISCUZ_ROOT.'./source/class/';
        $code->display();
        exit();
    }/*}}}*/
}
// vim600: sw=4 ts=4 fdm=marker syn=php
?>
