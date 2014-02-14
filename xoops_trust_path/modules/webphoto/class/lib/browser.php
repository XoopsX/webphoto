<?php
// $Id: browser.php,v 1.1 2011/05/10 02:59:15 ohwada Exp $

//=========================================================
// webphoto module
// 2011-05-01 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_lib_browser
//=========================================================
class webphoto_lib_browser
{
	var $_http_user_agent = null;
	var $_os              = null;
	var $_browser         = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_browser()
{
	// dummy
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_browser();
	}
	return $instance;
}

//---------------------------------------------------------
// presume os and browser by agent
//---------------------------------------------------------
function presume_agent()
{
	$agent = $_SERVER["HTTP_USER_AGENT"];

	if ( empty($agent) )
	{	return;	}	// undefined

// presume OS
	$os = 'unknown';
	if     (preg_match("/Win/i",  $agent))  $os = 'win';
	elseif (preg_match("/Mac/i",  $agent))  $os = 'mac';
	elseif (preg_match("/Linux/i",$agent))  $os = 'linux';
	elseif (preg_match("/BSD/i",  $agent))  $os = 'bsd';
	elseif (preg_match("/IRIX/i", $agent))  $os = 'irix';
	elseif (preg_match("/Sun/i",  $agent))  $os = 'sun';
	elseif (preg_match("/HP-UX/i",$agent))  $os = 'hpux';
	elseif (preg_match("/AIX/i",  $agent))  $os = 'aix';
	elseif (preg_match("/X11/i",  $agent))  $os = 'x11';

// presume Browser
	$brawser = 'unknown';
	if     (preg_match("/Opera/i",   $agent)) $browser = 'opera';
	elseif (preg_match("/MSIE/i",    $agent)) $browser = 'msie';
	elseif (preg_match("/Firefox/i", $agent)) $browser = 'firefox';
	elseif (preg_match("/Chrome/i",  $agent)) $browser = 'chrome';
	elseif (preg_match("/Safari/i",  $agent)) $browser = 'safari';

	$this->_http_user_agent = $agent;
	$this->_os              = $os;
	$this->_browser         = $browser;
}

//---------------------------------------------------------
// get param
//---------------------------------------------------------
function get_os()
{
	return $this->_os;
}

function get_browser()
{
	return $this->_browser;
}

// --- class end ---
}

?>