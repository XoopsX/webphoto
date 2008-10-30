<?php
// $Id: submit_file.php,v 1.5 2008/10/30 13:02:36 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-10-01 K.OHWADA
// use video_thumb()
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_submit_file
//=========================================================
class webphoto_main_submit_file extends webphoto_base_this
{
	var $_photo_class;
	var $_notification_class;
	var $_xoops_user_class;
	var $_redirect_class;

	var $_post_item_cat_id;
	var $_post_file;

	var $_cfg_file_size = 0;
	var $_has_file      = false;
	var $_has_resize    = false;

	var $_created_row = null ;
	var $_is_video_thumb_form = false;

	var $_THIS_FCT = 'submit_file';
	var $_THIS_URL = null;

	var $_TIME_SUCCESS = 1;
	var $_TIME_FAILED  = 5;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_submit_file( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_photo_class        =& webphoto_photo_create::getInstance( $dirname , $trust_dirname );
	$this->_xoops_user_class   =& webphoto_xoops_user::getInstance();
	$this->_redirect_class     =& webphoto_photo_redirect::getInstance( $dirname, $trust_dirname );
	$this->_notification_class =& webphoto_notification_event::getInstance( $dirname , $trust_dirname );

	$this->_cfg_file_size = intval( $this->get_config_by_name( 'file_size' ) );
	$this->_has_file      = $this->_perm_class->has_file();
	$this->_has_resize    = $this->_photo_class->has_resize();

	$this->_THIS_URL  = $this->_MODULE_URL .'/index.php?fct='.$this->_THIS_FCT;
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
			$this->_submit();
			break;

		case 'video':
			$this->_video();
			exit();
	}
}

function print_form()
{
	echo $this->build_bread_crumb( 
		$this->get_constant('TITLE_SUBMIT_FILE'), $this->_THIS_URL );

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
	$this->_post_item_cat_id = $this->_post_class->get_post_get_int('item_cat_id') ;
	$this->_post_file        = $this->_post_class->get_post_text( 'file' ) ;

	$ret = $this->_exec_check();
	switch ( $ret )
	{
		case _C_WEBPHOTO_ERR_NO_PERM:
			redirect_header( XOOPS_URL.'/user.php' , $this->_TIME_FAILED , 
				$this->get_constant('ERR_MUSTREGFIRST') ) ;
			exit();

		case _C_WEBPHOTO_ERR_CHECK_DIR:
			$msg = 'Directory Error';
			if ( $this->_is_module_admin ) {
				$msg .= '<br />'.$this->get_format_error();
			}
			redirect_header( $this->_INDEX_PHP, $this->_TIME_FAILED, $msg );
			exit();

		case _C_WEBPHOTO_ERR_NO_CAT_RECORD :
			redirect_header( $this->_INDEX_PHP , $this->_TIME_FAILED , 
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
	$this->check_token_and_redirect( 
		$this->_THIS_URL, $this->_TIME_FAILED );
}

//---------------------------------------------------------
// submit
//---------------------------------------------------------
function _submit()
{
	$this->_check_token_and_redirect();
	$ret1 = $this->_exec_submit();

	if ( $this->_is_video_thumb_form ) {
		return;
	}

	$ret2 = $this->build_failed_msg( $ret1 );

	$redirect_param = array(
		'url_success' => $this->_build_url_success( $this->_created_row ) ,
		'url_faild'   => $this->_THIS_URL ,
		'msg_success' => $this->get_constant('SUBMIT_RECEIVED') ,
	);

	list( $url, $time, $msg ) =
		$this->build_redirect( $redirect_param );

	redirect_header( $url, $time, $msg );
	exit();
}

function _check_submit()
{
	$ext  = $this->parse_ext( $this->_post_file ) ;
	$src_file = $this->_FILE_DIR .'/'. $this->_post_file ;

// Check if cid is valid
	if ( empty( $this->_post_item_cat_id ) ) {
		return _C_WEBPHOTO_ERR_EMPTY_CAT ;
	}

	if ( ! $this->check_valid_catid( $this->_post_item_cat_id ) ) {
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
		'src_file'         => $src_file ,
		'cat_id'           => $this->_post_item_cat_id ,
		'uid'              => $this->_xoops_uid ,
		'title'            => $title ,
		'description'      => $post_desc ,
		'status'           => _C_WEBPHOTO_STATUS_APPROVED ,
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

	$item_id = $row['item_id'];
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
		$item_id, $this->_post_item_cat_id, $title );

	return 0;
}

function _move_file( $old )
{
	$new = $this->_TMP_DIR .'/'. uniqid( 'file_' );
	rename( $old, $new );
}

function _build_url_success( $item_row )
{
	$cat_id = $item_row['item_cat_id'];

	$url_param = array(
		'orderby' => 'dated'
	);

	return $this->build_uri_category( $cat_id, $url_param );
}

//---------------------------------------------------------
// video
//---------------------------------------------------------
function _video()
{
	$this->_check_token_and_redirect();

	$item_id  = $this->_post_class->get_post_int('item_id') ;
	$item_row = $this->_item_handler->get_row_by_id( $item_id ) ;
	if ( !is_array($item_row) ) {
		redirect_header( $this->_THIS_URL, $this->_TIME_FAILED, 
			$this->get_constant('NOMATCH_PHOTO') );
	}

	$redirect_param = array(
		'url_success' => $this->_build_url_success( $item_row ) ,
		'url_faild'   => $this->_THIS_URL ,
		'msg_success' => $this->get_constant('SUBMIT_RECEIVED') ,
	);

	list( $url, $time, $msg ) =
		$this->_photo_class->video_thumb( $item_row , $redirect_param );

	redirect_header( $url, $time, $msg );
	exit();
}

//---------------------------------------------------------
// build_redirect
//---------------------------------------------------------
function build_failed_msg( $ret )
{
	$this->_redirect_class->set_error( $this->get_errors() );
	$ret = $this->_redirect_class->build_failed_msg( $ret );
	$this->clear_errors();
	$this->set_error( $this->_redirect_class->get_errors() );
	return $ret;
}

function build_redirect( $param )
{
	$this->_redirect_class->set_error( $this->get_errors() );
	return $this->_redirect_class->build_redirect( $param );
}

function get_redirect_url()
{
	return $this->_redirect_class->get_redirect_url();
}

function get_redirect_time()
{
	return $this->_redirect_class->get_redirect_time();
}

function get_redirect_msg()
{
	return $this->_redirect_class->get_redirect_msg();
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
	if ( $this->has_msg_array() ) {
		echo $this->get_format_msg_array() ;
		echo "<br />\n";
	}

	$form_class =& webphoto_photo_edit_form::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );

	$form_class->print_form_video_thumb( 
		'submit_file', $this->_created_row );
}

// --- class end ---
}

?>