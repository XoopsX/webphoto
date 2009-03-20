<?php
// $Id: edit.php,v 1.22 2009/03/20 04:18:09 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-03-15 K.OHWADA
// small_delete()
// 2009-01-10 K.OHWADA
// webphoto_photo_action -> webphoto_edit_action
// 2009-01-04 K.OHWADA
// webphoto_photo_misc_form
// 2008-11-16 K.OHWADA
// _print_form_error()
// get_cached_file_row_by_kind()
// 2008-11-08 K.OHWADA
// _thumb_delete()
// 2008-10-10 K.OHWADA
// webphoto_photo_action
// 2008-10-01 K.OHWADA
// Fatal error: Call to undefined method xoops_notify_for_edit()
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// used webphoto_photo_delete
// 2008-08-15 K.OHWADA
// BUG: undefined create_video_flash()
// 2008-08-06 K.OHWADA
// used update_video_thumb()
// not use msg_class
// 2008-08-05 K.OHWADA
// BUG: undefined method _check_uid()
// 2008-07-01 K.OHWADA
// added _exec_video()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_edit
//=========================================================
class webphoto_main_edit extends webphoto_edit_action
{
	var $_THIS_FCT  = 'edit' ;
	var $_THIS_URL  = null ;

	var $_TIME_SUCCESS = 1;
	var $_TIME_PENDING = 3;
	var $_TIME_FAILED  = 5;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_edit( $dirname , $trust_dirname )
{
	$this->webphoto_edit_action( $dirname , $trust_dirname );

	$this->_THIS_URL  = $this->_MODULE_URL .'/index.php?fct='.$this->_THIS_FCT;

	$this->init_preload();
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance))  {
		$instance = new webphoto_main_edit( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function check_action()
{
	$ret = 0;
	$this->_check();

	$action = $this->_get_action();
	switch ( $action ) 
	{
		case 'modify':
			$ret = $this->_modify();
			break;

		case 'redo':
			$ret = $this->_redo();
			break;

		case 'video':
			$this->_video();
			exit();

		case 'delete':
			$this->_delete();
			exit();

		case 'confirm':
			$this->_check_delete_perm_or_redirect();
			break;

		case 'thumb_delete':
			$this->_thumb_delete();
			exit();

		case 'middle_delete':
			$this->_middle_delete();
			exit();

		case 'small_delete':
			$this->_small_delete();
			exit();

		case 'flash_delete':
			$this->_flash_delete();
			exit();

		default:
			break;
	}

	if ( $ret == _C_WEBPHOTO_RET_VIDEO_FORM ) {
		$this->_form_action = 'form_video_thumb';

	} elseif ( $ret == _C_WEBPHOTO_RET_ERROR ) {
		$this->_form_action = 'form_error';

	} else {
		$this->_form_action = $action;
	}

	return true;
}

function print_form()
{
	echo $this->_build_bread_crumb_edit();

	switch ( $this->_form_action ) 
	{
		case 'form_video_thumb':
			$this->_print_form_video() ;
			break;

		case 'form_error':
			$this->_print_form_error() ;
			break;

		case 'confirm':
			$this->_print_form_confirm();
			break;

		default:
			$this->_print_form_modify();
			break;
	}
	return true;
}

//---------------------------------------------------------
// check
//---------------------------------------------------------
function _check()
{
	$this->get_post_param();

	switch ( $this->_exec_check() )
	{
		case _C_WEBPHOTO_ERR_NO_PERM:
			redirect_header( $this->_INDEX_PHP , $this->_TIME_FAILED , _NOPERM ) ;
			exit ;

		case _C_WEBPHOTO_ERR_NO_RECORD:
			redirect_header( $this->_INDEX_PHP , $this->_TIME_FAILED , $this->get_constant('NOMATCH_PHOTO') ) ;
			exit ;

		case 0:
		default:
			break;
	}

	return true;
}

function _exec_check()
{
	if ( ! $this->_has_editable ) {
		return _C_WEBPHOTO_ERR_NO_PERM;
	}

	$item_id  = $this->get_post_item_id();
	$item_row = $this->_item_handler->get_row_by_id( $item_id );
	if ( !is_array($item_row) ) {
		return _C_WEBPHOTO_ERR_NO_RECORD;
	}

	if ( ! $this->_check_perm( $item_row ) ) {
		return _C_WEBPHOTO_ERR_NO_PERM; 
	}

	if ( $this->_check_playlist( $item_row ) ) {
		return _C_WEBPHOTO_ERR_NO_PERM; 
	}

// save
	$this->_row_current = $item_row;
	return 0;
}

function _check_perm( $item_row )
{
	if ( $this->_is_module_admin ) {
		return true;
	}

	$uid    = $item_row['item_uid'];
	$status = $item_row['item_status'];

// user can touch photos status > 0
	if ( ( $uid == $this->_xoops_uid ) && ( $status > 0 ) ) {
		return true;
	}
	return false;
}

function _check_playlist( $item_row )
{
	$kind = $item_row['item_kind'];
	if ( $this->is_playlist_kind( $kind ) ) {
		return true;
	}
	return false;
}

function _get_action()
{
	$post_op            = $this->_post_class->get_post_get_text('op' );
	$post_conf_delete   = $this->_post_class->get_post_text('conf_delete' );
	$post_thumb_delete  = $this->_post_class->get_post_text('file_thumb_delete' );
	$post_middle_delete = $this->_post_class->get_post_text('file_middle_delete' );
	$post_small_delete  = $this->_post_class->get_post_text('file_small_delete' );
	$post_flash_delete  = $this->_post_class->get_post_text('flash_delete' );

	if ( $post_conf_delete ) {
		return 'confirm';
	} elseif ( $post_thumb_delete ) {
		return 'thumb_delete';
	} elseif ( $post_middle_delete ) {
		return 'middle_delete';
	} elseif ( $post_small_delete ) {
		return 'small_delete';
	} elseif ( $post_flash_delete ) {
		return 'flash_delete';
	} elseif ( $post_op ) {
		return $post_op;
	} 
	return '';
}

//---------------------------------------------------------
// modify
//---------------------------------------------------------
function _modify()
{
// load
	$item_row = $this->_row_current;
	$item_id  = $item_row['item_id'] ;

	if ( ! $this->check_token() ) {
		$this->set_token_error() ;
		return _C_WEBPHOTO_RET_ERROR ;
	}

	$ret = $this->modify( $item_row );
	switch ( $ret )
	{

// video form, error
		case _C_WEBPHOTO_RET_VIDEO_FORM :
		case _C_WEBPHOTO_RET_ERROR :
			return $ret;

// success
		case _C_WEBPHOTO_RET_SUCCESS :
			break;
	}

	list( $url, $time, $msg ) = $this->build_redirect( 
		$this->_build_redirect_param( false, $item_id ) );

	redirect_header( $url , $time , $msg ) ;
	exit() ;
}

function _check_token_and_redirect( $item_id )
{
	$this->check_token_and_redirect( 
		$this->_build_edit_url( $item_id ), $this->_TIME_FAILED );
}

function _build_redirect_param( $is_failed, $item_id )
{
	$url = $this->_build_edit_url( $item_id ) ;
	$param = array(
		'is_failed'   => $is_failed ,
		'url_success' => $url ,
		'url_failed'  => $url , 
		'msg_success' => $this->get_constant('DBUPDATED') ,
	);
	return $param ;
}

function _build_edit_url( $item_id )
{
	$str = $this->_THIS_URL .'&amp;photo_id='. $item_id ;
	return $str ;
}

//---------------------------------------------------------
// redo
//---------------------------------------------------------
function _redo()
{
	$is_failed = false ;

// load
	$item_row = $this->_row_current;
	$item_id  = $item_row['item_id'] ;

	$this->_check_token_and_redirect( $item_id );

	$ret = $this->video_redo( $item_row );
	switch ( $ret )
	{

// video form
		case _C_WEBPHOTO_RET_VIDEO_FORM :
			return $ret;

// success
		case _C_WEBPHOTO_RET_SUCCESS :
			break;

// error
		case _C_WEBPHOTO_RET_ERROR :
			$is_failed = true;
			break;
	}

	list( $url, $time, $msg ) = $this->build_redirect( 
		$this->_build_redirect_param( $is_failed, $item_id ) );

	redirect_header( $url, $time, $msg ) ;
	exit();
}

//---------------------------------------------------------
// video
//---------------------------------------------------------
function _video()
{
// load
	$item_row = $this->_row_current;
	$item_id  = $item_row['item_id'] ;

	$this->_check_token_and_redirect( $item_id );

	$ret = $this->video_thumb( $item_row );

	list( $url, $time, $msg ) = $this->build_redirect( 
		$this->_build_redirect_param( !$ret, $item_id ) );

	redirect_header( $url, $time, $msg );
	exit();
}

//---------------------------------------------------------
// delete
//---------------------------------------------------------
function _delete()
{
// load
	$item_row = $this->_row_current;
	$item_id  = $item_row['item_id'] ;

	$this->_check_token_and_redirect( $item_id );

	$ret = $this->delete( $item_row );

	$redirect_param = array(
		'is_failed'   => !$ret ,
		'url_success' => $this->_INDEX_PHP ,
		'url_failed'  => $this->_build_edit_url( $item_id ) ,
		'msg_success' => $this->get_constant('DELETED') ,
	);

	list( $url, $time, $msg ) = 
		$this->build_redirect( $redirect_param ) ;

	redirect_header( $url, $time, $msg );
	exit();
}

//---------------------------------------------------------
// confirm_delete
//---------------------------------------------------------
function _check_delete_perm_or_redirect()
{
	if( ! $this->_has_deletable ) {
		redirect_header( $this->_INDEX_PHP , $this->_TIME_FAILED , _NOPERM ) ;
		exit();
	}
}

//---------------------------------------------------------
// thumb delete
//---------------------------------------------------------
function _thumb_delete()
{
	list($item_row, $url_redirect) = $this->_delete_common();
	$this->thumb_delete( $item_row, $url_redirect );
}

function _middle_delete()
{
	list($item_row, $url_redirect) = $this->_delete_common();
	$this->middle_delete( $item_row, $url_redirect );
}

function _small_delete()
{
	list($item_row, $url_redirect) = $this->_delete_common();
	$this->small_delete( $item_row, $url_redirect );
}

function _flash_delete()
{
	list($item_row, $url_redirect) = $this->_delete_common();
	$this->video_flash_delete( $item_row, $url_redirect );
}

function _delete_common()
{
	$item_row = $this->_row_current;
	$item_id  = $item_row['item_id'] ;
	$this->_check_token_and_redirect( $item_id );
	$url_redirect = $this->_build_edit_url( $item_id );
	return array( $item_row, $url_redirect );
}

//---------------------------------------------------------
// print form modify
//---------------------------------------------------------
function _print_form_video()
{
	$this->print_form_video_thumb( 'edit', $this->get_updated_row() );
}

function _print_form_error()
{
	echo $this->error_in_box( $this->get_format_error() );
	$this->_print_form_modify( $flag_default=false );
}

function _print_form_modify( $flag_default=true )
{
	$edit_form_class =& webphoto_edit_photo_form::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );
	$misc_form_class =& webphoto_edit_misc_form::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );

	$item_row = $this->_row_current ;

	if ( $flag_default ) {
		$this->set_param_modify_default( $item_row );

	} else {
		$item_row = $this->build_item_row_modify_post( $item_row );
	}

	$flash_row = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_VIDEO_FLASH ) ;

	$item_id   = $item_row['item_id'] ;
	$kind      = $item_row['item_kind'] ;

	$this->_print_preview_modify( $item_row );

	if ( $this->_is_module_admin ) {
		$url = $this->_MODULE_URL .'/admin/index.php?fct=item_manager&amp;op=modify_form&amp;item_id='. $item_id ;
		echo '<a href="'. $url .'">';
		echo 'goto admin item manager: '. $item_id ;
		echo "</a><br /><br />\n";
	}

	$edit_form_class->print_form_common( 
		$item_row, $this->build_form_param( 'edit' ) );

	if ( $this->is_video_kind( $kind ) ) {
		$misc_form_class->print_form_redo( 'edit', $item_row, $flash_row );
	}

	if ( $this->_is_module_admin ) {
		$url = $this->_MODULE_URL .'/admin/index.php' ;
		echo "<br />\n";
		echo '<a href="'. $url .'">';
		echo $this->get_constant('goto_admin');
		echo "</a><br />\n";
	}

}

function _print_preview_modify( $item_row )
{
	$show_class =& webphoto_show_photo::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );

	echo $this->build_preview_template( 
		$show_class->build_photo_show( $item_row, $this->get_tag_name_array() ) );
}

function _build_bread_crumb_edit()
{
	$item_id = $this->_row_current['item_id'] ;

	return $this->build_bread_crumb( 
		$this->get_constant('TITLE_EDIT'), 
		$this->_build_edit_url( $item_id ) );
}

//---------------------------------------------------------
// print form confirm
//---------------------------------------------------------
function _print_form_confirm()
{
	$this->print_form_delete_confirm( 'edit', $this->_row_current ) ;
}

// --- class end ---
}

?>