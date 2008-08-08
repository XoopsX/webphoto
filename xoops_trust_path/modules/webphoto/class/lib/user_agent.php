<?php
// $Id: user_agent.php,v 1.1 2008/08/08 04:39:14 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_lib_user_agent
//=========================================================
class webphoto_lib_user_agent
{
	var $_FCT_MOBILE   = 'i';
	var $_MOBILE_ARRAY = null;

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
// public
//---------------------------------------------------------
function get_fct_mobile()
{
	$val = $this->parse_mobile();
	if ( $val ) {
		return $this->_FCT_MOBILE ;
	}
	return null;
}

function parse_mobile()
{
	if ( !is_array($this->_MOBILE_ARRAY) || !count($this->_MOBILE_ARRAY) ) {
		return null;
	}

	$agent = $this->get_user_agent();
	if ( empty($agent) ) {
		return null;
	}

	foreach ( $this->_MOBILE_ARRAY as $k => $v ) 
	{
		$pattern = '/'. preg_quote($k) .'/i';
		if ( preg_match( $pattern, $agent ) ) {
			return $v ;
		}
	}
	return null;
}

function get_user_agent()
{
	$ret = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
	return $ret;
}

function set_mobile_array( $val )
{
	$this->_MOBILE_ARRAY = $val;
}

//----- class end -----
}

?>