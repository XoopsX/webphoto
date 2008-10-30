<?php
// $Id: remote_file.php,v 1.1 2008/10/30 00:25:51 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// define constant
//---------------------------------------------------------
define('_C_WEBPHOTO_REMOTE_FILE_ERR_NOT_FETCH', -1);
define('_C_WEBPHOTO_REMOTE_FILE_ERR_NO_RESULT', -2);

//=========================================================
// class webphoto_lib_remote_file
// use class snoopy
//=========================================================
class webphoto_lib_remote_file extends webphoto_lib_error
{
// class instance
	var $_snoopy;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_remote_file()
{
	$this->webphoto_lib_error();

// class instance
	$this->_snoopy = new Snoopy();
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_remote_file();
	}
	return $instance;
}

//---------------------------------------------------------
// read_file
//---------------------------------------------------------
function read_file( $url )
{
	return $this->snoppy_fetch( $url );
}

//---------------------------------------------------------
// use class spoopy
//---------------------------------------------------------
function set_snoopy_proxy( $host, $port='8080', $user='', $pass='' )
{
	$this->_snoopy->proxy_host = $host;
	$this->_snoopy->proxy_port = $port;

	if ($user) {
		$this->_snoopy->proxy_user = $user;
	}
	if ($pass) {
		$this->_snoopy->proxy_pass = $pass;
	}
}

function set_snoopy_timeout_connect( $time )
{
	if ( intval($time) > 0 ) {
		$this->_snoopy->_fp_timeout = floatval($time);
	}
}

function set_snoopy_timeout_read( $time )
{
	if ( intval($time) > 0 ) {
		$this->_snoopy->read_timeout = floatval($time);
	}
}

function snoppy_fetch( $url )
{
	$this->clear_error_code();
	$this->clear_errors();

	if ( empty($url) ) {
		return false;
	}

	if ( $this->_snoopy->fetch( $url ) ) {
		$res = $this->_snoopy->results;

		if ( $res ) {
			return $res;

		} else {
			$this->set_error_code( _C_WEBPHOTO_REMOTE_FILE_ERR_NO_RESULT );
			$this->set_error( "remote_file: remote data is empty:" );
			if ($this->_snoopy->error) {
				$this->set_error( "snoopy: ". $this->_snoopy->error );
			}
			return false;
		}

	} else {
		$this->set_error_code( _C_WEBPHOTO_REMOTE_FILE_ERR_NOT_FETCH );
		$this->set_error( "remote_file: cannot fetch remote data:" );
		if ( $this->_snoopy->error ) {
			$this->set_error( "snoopy: ". $this->_snoopy->error );
		}
		return false;
	}
}

//----- class end -----
}

?>