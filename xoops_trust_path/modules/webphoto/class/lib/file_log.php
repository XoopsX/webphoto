<?php
// $Id: file_log.php,v 1.1 2011/11/13 11:11:55 ohwada Exp $

//=========================================================
// webphoto module
// 2011-11-11 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_lib_file_log
//=========================================================
class webphoto_lib_file_log
{
	var $_file = '';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_file_log()
{
	$file = XOOPS_TRUST_PATH.'/log/webphoto_log.txt';
	$this->set_file( $file );
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_file_log();
	}
	return $instance;
}

function set_file( $file )
{
	$this->_file = $file;
}

//---------------------------------------------------------
// function
//---------------------------------------------------------
function backtrace()
{
	ob_start();
	debug_print_backtrace();
	$this->write( ob_get_contents() );
	ob_end_clean();
}

function printr($val)
{
	ob_start();
	print_r($val);
	$this->write( ob_get_contents() );
	ob_end_clean();
}

function time()
{
	$this->write( date("Y-m-d H:i:s") );
}

function url()
{
	$protocol = ( isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on') ) ? 'https' : 'http';
	$url = $protocol. '://'. $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] ;
	$this->write( "URL: ".$url );
}

function request_uri()
{
	$this->write( "REQUEST_URI: ".$_SERVER["REQUEST_URI"] );
}

function request_method()
{
	$this->write( "REQUEST_METHOD: ".$_SERVER["REQUEST_METHOD"] );
}

function write( $data )
{
	file_put_contents($this->_file, $data."\n", FILE_APPEND);
}

// === class end ===
}

?>