<?php
/**
*	来自：信呼开发团队
*	作者：磐石(rainrock)
*	网址：http://www.rockoa.com/
*	系统默认配置文件，请不要去修改
*	要修改配置文件在：webmain/webmainConfig.php
*	创建时间：2018-01-07
*/
@session_start();
if(function_exists('date_default_timezone_set'))date_default_timezone_set('Asia/Shanghai'); //设置默认时区
header('Content-Type:text/html;charset=utf-8');
define('ROOT_PATH',str_replace('\\','/',dirname(dirname(__FILE__))));	//系统跟目录路径

include_once(''.ROOT_PATH.'/include/rockFun.php');
include_once(''.ROOT_PATH.'/include/Chajian.php');
include_once(''.ROOT_PATH.'/include/class/rockClass.php');
$rock 		= new rockClass();

$db			= null;		
$smarty		= false;
define('HOST', $rock->host);
define('REWRITE', 'true');
if(!defined('PROJECT'))define('PROJECT', $rock->get('p', 'webmain'));
if(!defined('ENTRANCE'))define('ENTRANCE', 'index');

$config		= array(
	'title'		=> '信呼',
	'url'		=> '',
	'urly'		=> 'http://www.rockoa.com/',
	'baseurl'	=> '/base/',			//当部署独立站点时，这个填写站点地址
	'db_host'	=> '127.0.0.1',
	'db_user'	=> 'root',
	'db_pass'	=> '666666',
	'db_base'	=> 'rockzwoanew',
	'perfix'	=> 'zwoa_',
	'qom'		=> 'rockbase_',
	'install'	=> false,
	'version'	=> require('version.php'),
	'path'		=> 'index',
	'updir'		=> 'upload',
	'dbencrypt'	=> false,
	'sqllog'	=> false,
	'memory_limit'	=> '',			//运行内存大小
	'timeout'		=> -1,			//抄送时间(秒),-1默认的
	'db_drive'		=> 'mysqli',	//数据库操作驱动：mysqli,pdo
	'db_engine'		=> 'MyISAM',	//数据库默认引擎
	'debug'			=> true,		//默认debug模式
	'apikey'		=> '',
	'alloworigin'	=> '', 			//跨域的，如http://www.rockoa.com
	'platurl'		=> '', 			//平台地址，如http://cloud.rockoa.com
	'officeyl'		=> '1',			//文档Excel.Doc预览类型,0自己部署插件，1使用官网支持任何平台
);

$_confpath		= $rock->strformat('?0/?1/?1Config.php', ROOT_PATH, PROJECT);
if(file_exists($_confpath)){
	$_tempconf	= require($_confpath);
	foreach($_tempconf as $_tkey=>$_tvs)$config[$_tkey] = $_tvs;
	if(isempt($config['url']))$config['url'] = $rock->url();
	if(!isempt($config['memory_limit']) && function_exists('ini_set'))
		ini_set('memory_limit', $config['memory_limit']);
	if($config['timeout']>-1 && function_exists('set_time_limit'))set_time_limit($config['timeout']);
}

define('DEBUG', $config['debug']);
error_reporting(DEBUG ? E_ALL : 0);

define('TITLE', $config['title']);
define('URL', $config['url']);
define('PLATURL', $config['platurl']);
define('URLY', $config['urly']);
define('PATH', $config['path']);

define('DB_DRIVE', $config['db_drive']);
define('DB_HOST', $config['db_host']);
define('DB_USER', $config['db_user']);
define('DB_PASS', $config['db_pass']);
define('DB_BASE', $config['db_base']);

define('UPDIR', $config['updir']);
define('PREFIX', $config['perfix']);
define('QOM', $config['qom']);
define('VERSION', $config['version']);
define('SYSURL', ''.URL.PATH.'.php');

$rock->initRock();