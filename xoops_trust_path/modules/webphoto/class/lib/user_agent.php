<?php
// $Id: user_agent.php,v 1.3 2009/01/25 10:25:27 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-01-10 K.OHWADA
// change parse_mobile_carrier()
// 2008-09-01 K.OHWADA
// added parse_browser()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_lib_user_agent
//=========================================================
class webphoto_lib_user_agent
{
	var $_os      = null;
	var $_browser = null;
	var $_mobile_carrier = null;
	var $_mobile_model   = null;

	var $_FCT_MOBILE   = 'i';
	var $_MOBILE_CARRIER_ARRAY = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_user_agent()
{
	// dummy
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_user_agent();
	}
	return $instance;
}

//---------------------------------------------------------
// parse os and browser
//
// IE : Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; .NET CLR 3.0.04506.648)
// Firefox: Mozilla/5.0 (Windows; U; Windows NT 5.1; ja; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.1
// Opera : Opera/9.27 (Windows NT 5.1; U; ja)
// Safari : Mozilla/5.0 (Windows; U; Windows NT 5.1; ja-JP) AppleWebKit/525.19 (KHTML, like Gecko) Version/3.1.2 Safari/525.21
// Chrome : Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.2.149.27 Safari/525.13
//---------------------------------------------------------
function parse_browser( $ua=null )
{
	if ( empty($ua) ) {
		$ua = $this->get_user_agent() ;
	}

	if ( empty($ua) ) {
		return null;	// undefined
	}

	$os = '';
	$browser = '';

// presume OS
	if     (preg_match("/Win/i",  $ua))  $os = 'win';
	elseif (preg_match("/Mac/i",  $ua))  $os = 'mac';
	elseif (preg_match("/Linux/i",$ua))  $os = 'linux';
	elseif (preg_match("/BSD/i",  $ua))  $os = 'bsd';
	elseif (preg_match("/IRIX/i", $ua))  $os = 'irix';
	elseif (preg_match("/Sun/i",  $ua))  $os = 'sun';
	elseif (preg_match("/HP-UX/i",$ua))  $os = 'hpux';
	elseif (preg_match("/AIX/i",  $ua))  $os = 'aix';
	elseif (preg_match("/X11/i",  $ua))  $os = 'x11';

// presume Browser
	if     (preg_match("/MSIE/i",    $ua)) $browser = 'msie';
	elseif (preg_match("/Firefox/i", $ua)) $browser = 'firefox';
	elseif (preg_match("/Opera/i",   $ua)) $browser = 'opera';
	elseif (preg_match("/Chrome/i",  $ua)) $browser = 'chrome';
	elseif (preg_match("/Safari/i",  $ua)) $browser = 'safari';
	elseif (preg_match("/Mozilla/i", $ua)) $browser = 'mozilla';

	$this->_os      = $os;
	$this->_browser = $browser;

	return $browser ;
}

function get_user_agent()
{
	$ret = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
	return $ret;
}

//---------------------------------------------------------
// parse mobile
//---------------------------------------------------------
function parse_mobile_carrier( $agent=null )
{
	if ( empty($agent) ) {
		$agent = $this->get_user_agent() ;
	}
	if ( empty($agent) ) {
		return null;	// undefined
	}

	if ( !is_array($this->_MOBILE_CARRIER_ARRAY) || !count($this->_MOBILE_CARRIER_ARRAY) ) {
		return null;
	}

	foreach ( $this->_MOBILE_CARRIER_ARRAY as $k => $v ) 
	{
		$pattern = '/'. preg_quote($k) .'/i';
		if ( preg_match( $pattern, $agent ) ) {
			$this->_mobile_carrier = $v ;
			return $v ;
		}
	}
	return null;
}

function get_fct_mobile()
{
	$val = $this->parse_mobile_carrier();
	if ( $val ) {
		return $this->_FCT_MOBILE ;
	}
	return null;
}

//---------------------------------------------------------
// set param
//---------------------------------------------------------
function set_mobile_carrier_array( $val )
{
	$this->_MOBILE_CARRIER_ARRAY = $val;
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

function get_mobile_carrier()
{
	return $this->_mobile_carrier ;
}

function get_mobile_model()
{
	return $this->_mobile_model ;
}

//----- class end -----
}

?>