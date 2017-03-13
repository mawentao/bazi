<?php
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
require_once dirname(__FILE__)."/class/env.class.php";
$filename = basename(__FILE__);
list($controller) = explode('.',$filename);
include template("bazi:".strtolower($controller));
