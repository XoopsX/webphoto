<?php
// $Id: mail.php,v 1.1 2011/11/12 17:18:25 ohwada Exp $

//=========================================================
// webphoto module
// 2011-11-11 K.OHWADA
//=========================================================

//=========================================================
// class webphoto_lib_mail
//=========================================================
class webphoto_lib_mail
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_mail()
{
	// dummy
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_mail();
	}
	return $instance;
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
function get_valid_addr( $str ) 
{
	list( $name, $addr ) = $this->parse_name_addr( $str );

	if ( $this->check_valid_addr( $addr ) ) {
		return $addr;
	}
	return null;
}

function check_valid_addr( $addr ) 
{
// same as class/xoopsmailer.php
	$PATTERN = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i";

	if (preg_match($PATTERN, $addr) ) {
		return true;
	}
	return false;
}

function parse_name_addr( $str ) 
{
	$name = '';

// taro <taro@example.com>
	$PATTERN = '/(.*)<(.*)>/i';

 	if ( preg_match( $PATTERN, $str, $matches ) ) {
		$name = trim( $matches[1] );
		$addr = trim( $matches[2] );
	} else {
		$addr = trim( $str );
	}
	return array( $name, $addr );
}

// --- class end ---
}

?>