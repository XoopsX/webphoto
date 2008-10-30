<?php
// $Id: remote_image.php,v 1.1 2008/10/30 00:25:51 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// define constant
//---------------------------------------------------------
define('_C_WEBPHOTO_REMOTE_IMAGE_ERR_WRITE', -11);

//=========================================================
// class webphoto_lib_remote_image
//=========================================================
class webphoto_lib_remote_image extends webphoto_lib_remote_file
{
	var $_dir_work = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_remote_image()
{
	$this->webphoto_lib_remote_file();

	$this->_dir_work = XOOPS_TRUST_PATH.'/tmp';
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_remote_image();
	}
	return $instance;
}

//=========================================================
// public
//=========================================================
//---------------------------------------------------------
// get_image_size
// return is same as getimagesize()
// array of width, height, type, attr
//---------------------------------------------------------
function get_image_size( $url )
{
	$this->clear_error_code();
	$this->clear_errors();

	if ( empty($url) || ($url == 'http://') || ($url == 'https://') ) {
		return false;
	}

	if ( !is_writable( $this->_dir_work ) ) {
		return false;
	}

	$data = $this->read_file( $url );
	if ( !$data ) {
		return false;
	}

	$file = tempnam( $this->_dir_work, "image" );

	if ( !$this->write_file( $file, $data ) ) {
		$this->set_error_code( _C_WEBPHOTO_REMOTE_IMAGE_ERR_WRITE );
		$this->set_error( "remote_image: cannot write : ".$file );
		return false;
	}

	$size = getimagesize($file);

	unlink($file);

	return $size;
}

//---------------------------------------------------------
// set and get property
//---------------------------------------------------------
function set_dir_work($value)
{
	$this->_dir_work = $value;
}

function get_dir_work()
{
	return $this->_dir_work;
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
function write_file( $file, $data, $mode='w', $flag_chmod=true )
{
	$fp = fopen( $file , $mode ) ;
	if ( !$fp ) {
		return false ; 
	}

	$byte = fwrite( $fp , $data ) ;
	fclose( $fp ) ;

// the user can delete this file which apache made.
	if (( $byte > 0 )&& $flag_chmod ) {
		chmod( $file, 0777 );
	}

	return $byte;
}

// --- class end ---
}

?>