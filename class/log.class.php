<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
class bazi_log
{
    // 日志级别
    const LOG_FATAL   = 0;
    const LOG_WARNING = 2;
    const LOG_NOTICE  = 4;
    const LOG_TRACE   = 8;
    const LOG_DEBUG   = 16;

    static $LOG_NAME = array(
        self::LOG_FATAL   => 'FATAL',
        self::LOG_WARNING => 'WARNING',
        self::LOG_NOTICE  => 'NOTICE',
        self::LOG_TRACE   => 'TRACE',
        self::LOG_DEBUG   => 'DEBUG',
    );

    private $_loglevel  = self::LOG_DEBUG;

    /** 
     * 构造函数
     */
    public function __construct ($arrConfig)
    {   
        $this->_loglevel  = $arrConfig['log_level'];
    }   

    /* 记录日志接口 */
    public function fatal()  { $arg=func_get_args(); $this->write_log(self::LOG_FATAL,$arg); }
    public function warning(){ $arg=func_get_args(); $this->write_log(self::LOG_WARNING,$arg); }
    public function notice (){ $arg=func_get_args(); $this->write_log(self::LOG_NOTICE,$arg); }
    public function trace()  { $arg=func_get_args(); $this->write_log(self::LOG_TRACE,$arg); }
    public function debug()  { $arg=func_get_args(); $this->write_log(self::LOG_DEBUG,$arg); }


    //=============以下是私有函数==============

    private function write_log ($strType, $arr_data)
    {
        //1. 日志级别过滤
        $loglevel = intval($strType);
        if($loglevel>$this->_loglevel || ($loglevel%2)===1){
            return;
        }

        //2. 日志内容
        $str = sprintf("[%s]\t", self::$LOG_NAME[$loglevel]);
        if(!empty($arr_data)) {
            $str .= implode("\t",$arr_data);
        }
        $strLog = '';
        switch ($strType) {
            case self::LOG_WARNING:
            case self::LOG_FATAL:
            case self::LOG_DEBUG:
                $debug_info = debug_backtrace();
                $strLog .= "[{$debug_info[1]['file']}" . ":" . $debug_info[1]['line'] . "]\t";
                $strLog .= $str;
                break;
            case self::LOG_TRACE:
            case self::LOG_NOTICE:
                $strLog .= $str;
                break;
            default:
                break;
        }
        $strLog = str_replace("\n","",$strLog);

        //3. 写日志
        $this->write_file($logfile, $strLog);
    }

    private function write_file ($strPath, $str)
    {
        runlog('bazi', $str);
    }
}
?>
