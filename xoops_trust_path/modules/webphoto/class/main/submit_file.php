<?php
// $Id: submit_file.php,v 1.2 2008/08/09 10:49:33 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_submit_file
//=========================================================
class webphoto_main_submit_file extends webphoto_base_this
{
	var $_photo_class;
	var $_notification_class;
	var $_xoops_user_class;

	var $_post_catid;
	var $_post_file;

	var $_cfg_file_size = 0;
	var $_has_file      = false;
	var $_has_resize    = false;

	var $_created_row = null ;
	var $_is_video_thumb_form = false;

	var $_REDIRECT_URL = null;

	var $_TIME_SUCCESS  = 1;
	var $_TIME_PENDING  = 3;
	var $_TIME_FAIL     = 5;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_submit_file( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_photo_class        =& webphoto_photo_create::getInstance( $dirname , $trust_dirname );
	$this->_xoops_user_class   =& webphoto_xoops_user::getInstance();
	$this->_notification_class =& webphoto_notification_event::getInstance(
		$dirname , $trust_dirname );

	$this->_cfg_file_size = intval( $this->get_config_by_name( 'file_size' ) );
	$this->_has_file      = $this->_perm_class->has_file();
	$this->_has_resize    = $this->_photo_class->has_resize();

	$this->_REDIRECT_URL  = $this->_MODULE_URL .'/index.php?fct=submit_file';
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main_submit_file( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function check_action()
{
	$this->_check();
	$this->_is_video_thumb_form = false;

	$op = $this->_post_class->get_post('op');
	switch ( $op ) 
	{
		case 'submit':
			$this->_check_token_and_redirect();
			$this->_submit();
			if ( $this->_is_video_thumb_form ) {
				break;
			}
			exit();

		case 'video':
			$this->_check_token_and_redirect();
			$this->_video();
			exit();
	}
}

function print_form()
{
	echo $this->build_bread_crumb( 
		$this->get_constant('TITLE_SUBMIT_FILE'), $this->_REDIRECT_URL );

	if ( $this->_is_video_thumb_form ) {
		$this->_print_form_video_thumb();
	} else {
		$this->_print_form_submit();
	}
}

//---------------------------------------------------------
// check 
//---------------------------------------------------------
function _check()
{
	$this->_post_catid = $this->_post_class->get_post_get_int('photo_cat_id') ;
	$this->_post_file  = $this->_post_class->get_post_text( 'file' ) ;

	$ret = $this->_exec_check();
	switch ( $ret )
	{
		case _C_WEBPHOTO_ERR_NO_PERM:
			redirect_header( XOOPS_URL.'/user.php' , $this->_TIME_FAIL , 
				$this->get_constant('ERR_MUSTREGFIRST') ) ;
			exit();

		case _C_WEBPHOTO_ERR_CHECK_DIR:
			$msg = 'Directory Error';
			if ( $this->_is_module_admin ) {
				$msg .= '<br />'.$this->get_format_error();
			}
			redirect_header( $this->_INDEX_PHP, $this->_TIME_FAIL, $msg );
			exit();

		case _C_WEBPHOTO_ERR_NO_CAT_RECORD :
			redirect_header( $this->_INDEX_PHP , $this->_TIME_FAIL , 
				$this->get_constant('ERR_MUSTADDCATFIRST') ) ;
			exit ;

		default;
			break;
	}
}

function _exec_check()
{
	if ( ! $this->_has_file )   {
		return _C_WEBPHOTO_ERR_NO_PERM ; 
	}

	if ( ! $this->exists_cat_record() ) { 
		return _C_WEBPHOTO_ERR_NO_CAT_RECORD ; 
	}

	$ret1 = $this->check_dir( $this->_PHOTOS_DIR );
	if ( $ret1 < 0 ) {
		return $ret1; 
	}

	$ret2 = $this->check_dir( $this->_THUMBS_DIR );
	if ( $ret2 < 0 ) {
		return $ret2; 
	}

	$ret3 = $this->check_dir( $this->_TMP_DIR );
	if ( $ret3 < 0 ) {
		return $ret3; 
	}

	$ret4 = $this->check_dir( $this->_FILE_DIR );
	if ( $ret4 < 0 ) {
		return $ret4; 
	}

	return 0;
}

function _check_token_and_redirect()
{
	$this->check_token_and_redirect( $this->_REDIRECT_URL, $this->_TIME_FAIL );
}

//---------------------------------------------------------
// submit
//---------------------------------------------------------
function _submit()
{
	$ret = $this->_exec_submit();

	if ( $this->_is_video_thumb_form ) {
		return;
	}

	$msg = null;
	switch ( $ret )
	{
		case _C_WEBPHOTO_ERR_EMPTY_CAT:
			$msg = $this->get_constant('ERR_EMPTY_CAT') ;
			break;

		case _C_WEBPHOTO_ERR_INVALID_CAT:
			$msg = $this->get_constant('ERR_INVALID_CAT') ;
			break;

		case _C_WEBPHOTO_ERR_EMPTY_FILE:
			$msg = $this->get_constant('ERR_EMPTY_FILE') ;
			break;

		case _C_WEBPHOTO_ERR_FILEREAD:
			$msg = $this->get_constant('ERR_FILEREAD') ;
			break;

		case _C_WEBPHOTO_ERR_EXT:
			$msg = $this->get_constant('UPLOADER_ERR_NOT_ALLOWED_EXT') ;
			break;

		case _C_WEBPHOTO_ERR_FILE_SIZE:
			$msg = $this->get_constant('UPLOADER_ERR_LARGE_FILE_SIZE') ;
			break;

		case _C_WEBPHOTO_ERR_DB:
			$msg = 'DB Error';
			if ( $this->_is_module_admin ) {
				$msg .= '<br />'.$this->get_format_error();
			}
			break;

		case _C_WEBPHOTO_ERR_FILE:
			$msg = $this->get_constant('ERR_FILE') ;
			break;

		case _C_WEBPHOTO_ERR_NO_IMAGE;
			$msg = $this->get_constant('ERR_NOIMAGESPECIFIED') ;
			break;

		case _C_WEBPHOTO_ERR_NO_TITLE:
			$msg = $this->get_constant('ERR_TITLE') ;
			break;

		case _C_WEBPHOTO_ERR_CREATE_PHOTO:
			$msg = $this->get_constant('ERR_CREATE_PHOTO') ;
			break;

		case 0:
		default:
			break;
	}

	if ( $msg ) {
		redirect_header( $this->_REDIRECT_URL, $this->_TIME_FAIL, $msg );
		exit();
	}

	$this->_submit_success();
}

function _submit_success()
{
	$param = array(
		'orderby' => 'dated'
	);
	$url  = $this->build_uri_category( $this->_post_catid, $param );
	$time = $this->_TIME_SUCCESS ;
	$msg  = '';

	if ( $this->has_msg_array() ) {
		$msg .= $this->get_format_msg_array() ;
		$msg .= "<br />\n";
		$time = $this->_TIME_PENDING ;
	}

	$msg .= $this->get_constant('SUBMIT_RECEIVED') ;

	redirect_header( $url, $time , $msg ) ;
	exit();

}

function _check_submit()
{
	$ext  = $this->parse_ext( $this->_post_file ) ;
	$src_file = $this->_FILE_DIR .'/'. $this->_post_file ;

// Check if cid is valid
	if ( empty( $this->_post_catid ) ) {
		return _C_WEBPHOTO_ERR_EMPTY_CAT ;
	}

	if ( ! $this->check_valid_catid( $this->_post_catid ) ) {
		return _C_WEBPHOTO_ERR_INVALID_CAT ;
	}

	if ( empty( $this->_post_file ) ) {
		return _C_WEBPHOTO_ERR_EMPTY_FILE ;
	}

	if ( ! is_readable( $src_file ) ) {
		return _C_WEBPHOTO_ERR_FILEREAD ;
	}

	if ( ! $this->_photo_class->is_my_allow_ext( $ext ) ) {
		return _C_WEBPHOTO_ERR_EXT ;
	}

	if ( ! $this->check_file_size( $src_file ) ) {
		return _C_WEBPHOTO_ERR_FILE_SIZE ;
	}

	return 0;
}

function check_file_size( $file )
{
	if ( filesize( $file ) < $this->_cfg_file_size ) {
		return true;
	}
	return false;
}

function _exec_submit()
{
	$this->clear_msg_array();

	$ret = $this->_check_submit();
	if ( $ret < 0 ) {
		return $ret;
	}

	$post_title  = $this->_post_class->get_post_text( 'title' ) ;
	$post_desc   = $this->_post_class->get_post_text( 'desc' ) ;

	$ext  = $this->parse_ext( $this->_post_file ) ;
	$src_file = $this->_FILE_DIR .'/'. $this->_post_file ;

	$node = $this->_utility_class->strip_ext( $this->_post_file );

	$title = empty( $post_title ) ? addslashes( $node ) : $post_title ;

	$param = array(
		'src_file'    => $src_file ,
		'cat_id'      => $this->_post_catid ,
		'uid'         => $this->_xoops_uid ,
		'title'       => $title ,
		'description' => $post_desc ,
		'status'      => _C_WEBPHOTO_STATUS_APPROVED ,
		'mode_video_thumb' => _C_WEBPHOTO_VIDEO_THUMB_PLURAL ,
	);

	$ret = $this->_photo_class->create_from_file( $param );
	$row = $this->_photo_class->get_row() ;

	if ( $ret < 0 ) {
		$this->_move_file( $src_file );
		$this->set_error( $this->_photo_class->get_errors() );
		return $ret;
	}

	if ( ! is_array($row) ) {
		$this->_move_file( $src_file );
		return _C_WEBPHOTO_ERR_CREATE_PHOTO;
	}

	$this->unlink_file( $src_file );

	$photo_id = $row['photo_id'];
	$this->_created_row = $row ;

	if ( $this->_photo_class->get_resized() ) {
		$this->set_msg_array( $this->get_constant('SUBMIT_RESIZED') ) ;
	}

	if ( $this->_photo_class->get_video_flash_failed() ) {
		$this->set_msg_array( $this->get_constant('ERR_VIDEO_FLASH') ) ;
	}

	if ( $this->_photo_class->get_video_thumb_failed() ) {
		$this->set_msg_array( $this->get_constant('ERR_VIDEO_THUMB') ) ;
	}

	if ( $this->_photo_class->get_video_thumb_created() ) {
		$this->_is_video_thumb_form = true;
	}

	$cfg_addposts = $this->_config_class->get_by_name( 'addposts' );
	$this->_xoops_user_class->increment_post_by_num_own( $cfg_addposts );

// Trigger Notification when supper insert
	$this->_notification_class->notify_new_photo( 
		$photo_id, $this->_post_catid, $title );

	return 0;
}

function _move_file( $old )
{
	$new = $this->_TMP_DIR .'/'. uniqid( 'file_' );
	rename( $old, $new );
}

//---------------------------------------------------------
// video
//---------------------------------------------------------
function _video()
{
	$msg = null;
	$ret = $this->_exec_video();

	switch ( $ret )
	{
		case _C_WEBPHOTO_ERR_NO_RECORD:
			$msg = $this->get_constant('NOMATCH_PHOTO') ;
			break;

		case _C_WEBPHOTO_ERR_CREATE_THUMB:
			$msg = $this->get_constant('ERR_CREATE_THUMB') ;
			break;

		case _C_WEBPHOTO_ERR_DB:
			$msg = 'DB Error';
			if ( $this->_is_module_admin ) {
				$msg .= '<br />'.$this->get_format_error();
			}
			break;
	}

	if ( $msg ) {
		redirect_header( $this->_REDIRECT_URL, $this->_TIME_FAIL, $msg );
		exit();
	}

	$this->_submit_success();
}

function _exec_video()
{
	$this->clear_msg_array();

	$photo_id = $this->_post_class->get_post('photo_id') ;
	$name     = $this->_post_class->get_post('name') ;

	$ret = $this->_photo_class->update_video_thumb( $photo_id, $name );
	if ( $ret < 0 ) {
		return $ret;
	}

	if ( $this->_photo_class->get_video_thumb_failed() ) {
		$this->set_msg_array( $this->get_constant('ERR_VIDEO_THUMB') ) ;
	}

// set for redirect
	$this->_post_catid = $this->_photo_class->get_photo_cat_id() ;

	return 0;
}

//---------------------------------------------------------
// print form
//---------------------------------------------------------
function _print_form_submit()
{
	list ( $types, $allowed_exts ) = $this->_photo_class->get_my_allowed_mimes();

	$param = array(
		'has_resize'   => $this->_has_resize,
		'allowed_exts' => $allowed_exts ,
	);

	$form_class =& webphoto_photo_edit_form::getInstance( 
		$this->_DIRNAME, $this->_TRUST_DIRNAME  );

	$form_class->print_form_file( $param );
}

function _print_form_video_thumb()
{
	$this->print_form_video_thumb_common( 'file', $this->_created_row );
}

function print_form_video_thumb_common( $mode, $row )
{
	if ( $this->has_msg_array() ) {
		echo $this->get_format_msg_array() ;
		echo "<br />\n";
	}

	$param = array(
		'mode' => $mode ,
	);

	$form_class =& webphoto_photo_edit_form::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );
	$form_class->print_form_video_thumb( $row, $param );
}

// --- class end ---
}

?>