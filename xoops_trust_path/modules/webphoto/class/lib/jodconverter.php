<?php
// $Id: jodconverter.php,v 1.2 2009/01/31 19:12:49 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-25 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_lib_jodconverter
//=========================================================

//---------------------------------------------------------
// http://www.artofsolving.com/opensource/jodconverter
//---------------------------------------------------------

class webphoto_lib_jodconverter
{
	var $_CMD_PATH_JAVA    = '';
	var $_jodconverter_jar = '';
	var $_msg_array = array();
	var $_DEBUG     = false;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_jodconverter()
{
	// dummy
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_jodconverter();
	}
	return $instance;
}

//---------------------------------------------------------
// set
//---------------------------------------------------------
function set_cmd_path_java( $val )
{
	$this->_CMD_PATH_JAVA = $val;
}

function set_jodconverter_jar( $val )
{
	$this->_jodconverter_jar = $val;
}

function set_debug( $val )
{
	$this->_DEBUG = (bool)$val ;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function convert( $src_file, $dst_file )
{
	$this->clear_msg_array();

	$cmd = $this->_CMD_PATH_JAVA .'java -jar '.$this->_jodconverter_jar.' '.$src_file.' '.$dst_file ;
	exec( "$cmd 2>&1", $ret_array, $ret_code ) ;
	if ( $this->_DEBUG ) {
		echo $cmd."<br />\n";
	}
	$this->set_msg( $cmd );
	$this->set_msg( $ret_array );
	return $ret_code;
}

//---------------------------------------------------------
// version
//---------------------------------------------------------
function version()
{
	$cmd = $this->_CMD_PATH_JAVA .'java -version' ;
	exec( "$cmd 2>&1", $ret_array, $ret_code ) ;
	if ( $this->_DEBUG ) {
		echo $cmd."<br />\n";
	}

	$ret = false ;
	if ( is_array($ret_array) && count($ret_array) ) {
		$msg = $ret_array[0] ."<br />\n";
		list ( $ret, $msg_jod ) = $this->get_version_jodconverter();
		$msg .= $msg_jod ;

	} else {
		$msg = "Error: ". $this->_CMD_PATH_JAVA ."java cannot be executed" ;
	}

	return array( $ret, $msg );
}

function get_version_jodconverter()
{
	$ret = false ;

	if ( is_file( $this->_jodconverter_jar ) ) {
		$ret  = true ;
		$msg  = " jodconverter version ";
		$msg .= $this->parse_version_jodconverter() ;

	} else {
		$msg = "Error: cannot find ". $this->_jodconverter_jar ;
	}
	return array( $ret, $msg );
}

function parse_version_jodconverter()
{
	preg_match( '/jodconverter-cli-(.*)\.jar/i', $this->_jodconverter_jar, $matches );
	if ( isset( $matches[1] ) ) {
		return  $matches[1] ;
	}
	return null;
}

//---------------------------------------------------------
// msg
//---------------------------------------------------------
function clear_msg_array()
{
	$this->_msg_array = array();
}

function get_msg_array()
{
	return $this->_msg_array;
}

function set_msg( $ret_array )
{
	if ( is_array($ret_array) ) {
		foreach( $ret_array as $line ) {
			$this->_msg_array[] = $line ;
		}
	} else {
		$this->_msg_array[] = $ret_array ;
	}
}

// --- class end ---
}

?>