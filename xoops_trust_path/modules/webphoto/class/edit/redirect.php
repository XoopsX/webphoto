<?php
// $Id: redirect.php,v 1.2 2010/06/16 22:24:47 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-06-06 K.OHWADA
// get_time_failed()
// 2009-01-10 K.OHWADA
// webphoto_photo_redirect -> webphoto_edit_redirect
// 2008-11-08 K.OHWADA
// uploader_fetch() -> fetch_image()
// rename() -> resize_rotate() 
// _C_WEBPHOTO_UPLOAD_FIELD_PLOGO
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_redirect
//=========================================================
class webphoto_edit_redirect extends webphoto_edit_base
{
	var $_redirect_time = 0 ;
	var $_redirect_url  = null ;
	var $_redirect_msg  = null ;

	var $_TIME_SUCCESS = 1;
	var $_TIME_PENDING = 3;
	var $_TIME_FAILED  = 5;
	var $_URL_SUCCESS  = null ;
	var $_URL_PENDING  = null ;
	var $_URL_FAILED   = null ;
	var $_MSG_SUCCESS  = 'success' ;
	var $_MSG_PENDING  = 'pending' ;
	var $_MSG_FAILED   = 'failed' ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_redirect( $dirname, $trust_dirname )
{
	$this->webphoto_edit_base( $dirname, $trust_dirname );

	$this->preload_init();
	$this->preload_constant();
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_redirect( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// build failed msg
//---------------------------------------------------------
function build_failed_msg( $ret )
{
	switch ( $ret )
	{
		case _C_WEBPHOTO_ERR_DB:
			$this->set_error_in_head_with_admin_info( 'DB Error' );
			return false;

		case _C_WEBPHOTO_ERR_UPLOAD;
			$this->set_error_in_head( 'File Upload Error' );
			return false;

		case _C_WEBPHOTO_ERR_NO_SPECIFIED:
			$this->set_error( 'UPLOAD error: file name not specified' );
			return false;

		case _C_WEBPHOTO_ERR_EXT:
			$this->set_error_by_const_name('UPLOADER_ERR_NOT_ALLOWED_EXT') ;
			return false;

		case _C_WEBPHOTO_ERR_FILE_SIZE:
			$this->set_error_by_const_name('UPLOADER_ERR_LARGE_FILE_SIZE') ;
			return false;

		case _C_WEBPHOTO_ERR_NO_PERM:
			$this->set_error( _NOPERM ) ;
			return false;

		case _C_WEBPHOTO_ERR_NO_RECORD:
			$this->set_error_by_const_name('NOMATCH_PHOTO') ;
			return false;

		case _C_WEBPHOTO_ERR_EMPTY_CAT:
			$this->set_error_by_const_name('ERR_EMPTY_CAT') ;
			return false;

		case _C_WEBPHOTO_ERR_INVALID_CAT:
			$this->set_error_by_const_name('ERR_INVALID_CAT') ;
			return false;

		case _C_WEBPHOTO_ERR_EMPTY_FILE:
			$this->set_error_by_const_name('ERR_EMPTY_FILE') ;
			return false;

		case _C_WEBPHOTO_ERR_FILE:
			$this->set_error_by_const_name('ERR_FILE') ;
			return false;

		case _C_WEBPHOTO_ERR_NO_IMAGE;
			$this->set_error_by_const_name('ERR_NOIMAGESPECIFIED') ;
			return false;

		case _C_WEBPHOTO_ERR_FILEREAD:
			$this->set_error_by_const_name('ERR_FILEREAD') ;
			return false;

		case _C_WEBPHOTO_ERR_NO_TITLE:
			$this->set_error_by_const_name('ERR_TITLE') ;
			return false;

		case _C_WEBPHOTO_ERR_EMBED:
			$this->set_error_by_const_name('ERR_EMBED') ;
			return false;

		case _C_WEBPHOTO_ERR_PLAYLIST:
			$this->set_error_by_const_name('ERR_PLAYLIST') ;
			return false;

		case _C_WEBPHOTO_ERR_CREATE_PHOTO:
			$this->set_error_by_const_name('ERR_CREATE_PHOTO') ;
			return false;

		case 0:
		default:
			break;
	}

	return true ;
}

//---------------------------------------------------------
// redirect
//---------------------------------------------------------
function build_redirect( $param )
{
	$is_failed  = isset($param['is_failed'])  ? (bool)$param['is_failed']  : false ;
	$is_pending = isset($param['is_pending']) ? (bool)$param['is_pending'] : false ;

	$has_extra_msg = isset($param['has_extra_msg']) ?
		(bool)$param['has_extra_msg'] : $this->has_msg_array() ;

	$url_success = isset($param['url_success']) ? 
		$param['url_success'] : $this->_URL_SUCCESS ;

	$url_pending = isset($param['url_pending']) ? 
		$param['url_pending'] : $this->_URL_PENDING ;

	$url_failed = isset($param['url_failed']) ? 
		$param['url_failed'] : $this->_URL_FAILED ;

	$time_success = isset($param['time_success']) ? 
		intval($param['time_success']) : $this->_TIME_SUCCESS ;

	$time_pending = isset($param['time_pending']) ? 
		intval($param['time_pending']) : $this->_TIME_PENDING ;

	$time_failed = isset($param['time_failed']) ? 
		intval($param['time_failed']) : $this->_TIME_FAILED ;

	$msg_success  = isset($param['msg_success']) ? 
		$param['msg_success'] : $this->_MSG_SUCCESS ;

	$msg_pending  = isset($param['msg_pending']) ? 
		$param['msg_pending'] : $this->_MSG_PENDING ;

	$msg_failed  = isset($param['msg_failed']) ? 
		$param['msg_failed'] : $this->get_format_error() ;

	if ( empty($msg_failed) ) {
		$msg_failed = $this->_MSG_FAILED ;
	}

	$msg_extra = isset($param['msg_extra']) ? 
		$param['msg_extra'] : $this->get_format_msg_array().'<br />'.$msg_success ;

// pending
	if ( $is_failed ) {
		$url  = $url_failed ;
		$time = $time_failed ;
		$msg  = $msg_failed ;

// pending
	} elseif ( $is_pending ) {
		$url  = $url_pending ;
		$time = $time_pending ;
		$msg  = $msg_pending ;

// has msg
	} elseif ( $has_extra_msg ) {
		$url  = $url_success ;
		$time = $time_pending ;
		$msg  = $msg_extra ;

// success
	} else {
		$url  = $url_success ;
		$time = $time_success ;
		$msg  = $msg_success ;
	}

	$this->_redirect_url  = $url ;
	$this->_redirect_time = $time ;
	$this->_redirect_msg  = $msg ;

	return array( $url, $time, $msg );
}

//---------------------------------------------------------
// set & get param
//---------------------------------------------------------
function get_redirect_url()
{
	return $this->_redirect_url ;
}

function get_redirect_time()
{
	return $this->_redirect_time ;
}

function get_redirect_msg()
{
	return $this->_redirect_msg ;
}

function get_time_success()
{
	return $this->_TIME_SUCCESS;
}

function get_time_pending()
{
	return $this->_TIME_PENDING;
}

function get_time_failed()
{
	return $this->_TIME_FAILED;
}

function set_error_by_const_name( $name )
{
	$this->set_error( $this->get_constant( $name ) ) ;
}

// --- class end ---
}

?>