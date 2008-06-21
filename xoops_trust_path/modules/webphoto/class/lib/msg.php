<?php
// $Id: msg.php,v 1.1 2008/06/21 12:22:28 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_lib_error
//=========================================================
class webphoto_lib_msg
{
	var $_msgs = array();

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_msg()
{
	// dummy
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_msg();
	}
	return $instance;
}

//---------------------------------------------------------
// msg
//---------------------------------------------------------
function has_msg()
{
	if ( count($this->_msgs) ) {
		return true;
	}
	return false;
}

function clear_msgs()
{
	$this->_msgs = array();
}

function get_msgs()
{
	return $this->_msgs;
}

function get_format_msg( $flag_sanitize=true, $flag_highlight=true )
{
	$val = '';
	foreach (  $this->_msgs as $msg )
	{
		if ( $flag_sanitize ) {
			$msg = $this->sanitize($msg);
		}
		$val .= $msg . "<br />\n";
	}

	if ( $flag_highlight ) {
		$val = $this->highlight($val);
	}
	return $val;
}

function set_msg( $msg )
{
// array type
	if ( is_array($msg) ) {
		foreach ( $msg as $m ) {
			$this->_msgs[] = $m;
		}

// string type
	} else {
		$arr = explode("\n", $msg);
		foreach ( $arr as $m ) {
			$this->_msgs[] = $m;
		}
	}
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
function sanitize( $str )
{
	return htmlspecialchars( $str, ENT_QUOTES );
}

function highlight( $str )
{
	$val = '<span style="color:#ff0000;">'. $str .'</span>';
	return $val;
}

function shorten_strings( $str, $length )
{
	if ( strlen($str) > $length ) {
		$str = webphoto_substr( $str, 0, $length ).' ...';
	}
	return $str;
}

function shorten_strings_with_nl2br( $str, $length )
{
	return nl2br( $this->sanitize( $this->shorten_strings( $str, $length ) ) );
}

//----- class end -----
}

?>