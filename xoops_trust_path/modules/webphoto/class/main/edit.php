<?php
// $Id: edit.php,v 1.31 2011/05/11 23:35:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-05-01 K.OHWADA
// _C_WEBPHOTO_IS_PLAYLIST
// 2010-10-01 K.OHWADA
// _jpeg_delete()
// 2010-02-15 K.OHWADA
// build_footer_param()
// 2010-02-01 K.OHWADA
// _check_public()
// 2009-05-28 K.OHWADA
// BUG: not show tag
// 2009-05-05 K.OHWADA
// _build_form_edit_param() -> build_form_base_param()
// 2009-04-19 K.OHWADA
// _print_form_modify() -> _build_form_modify()
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
	var $_TIME_SUCCESS = 1;
	var $_TIME_PENDING = 3;
	var $_TIME_FAILED  = 5;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_edit( $dirname , $trust_dirname )
{
	$this->webphoto_edit_action( $dirname , $trust_dirname );

	$this->set_fct( 'edit' );
	$this->set_form_mode( 'edit' );

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

		case 'cont_delete':
			$this->_cont_delete();
			exit();

		case 'jpeg_delete':
			$this->_jpeg_delete();
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

function form_param()
{
	$this->init_form();

	switch ( $this->_form_action ) 
	{
		case 'form_video_thumb':
			$param = $this->_build_form_video_thumb() ;
			break;

		case 'form_error':
			$param = $this->_build_form_error() ;
			break;

		case 'confirm':
			$param = $this->_build_form_confirm();
			break;

		default:
			$param = $this->_build_form_modify();
			break;
	}

	$ret = array_merge( $param, $this->build_footer_param() );
	return $ret;
}

//---------------------------------------------------------
// check
//---------------------------------------------------------
function _check()
{
	$this->get_post_param();
	$item_id = $this->get_post_item_id();

	switch ( $this->_exec_check() )
	{
		case _C_WEBPHOTO_IS_PLAYLIST:
			if ( $this->_is_module_admin ) {
				$url = $this->_ADMIN_INDEX_PHP.'?fct=item_manager&op=modify_form&item_id='.$item_id ;
				redirect_header( $url , $this->_TIME_SUCCESS , _WEBPHOTO_GOTO_ADMIN ) ;
			}
			redirect_header( $this->_INDEX_PHP , $this->_TIME_FAILED , _NOPERM ) ;
			exit() ;

		case _C_WEBPHOTO_ERR_NO_PERM:
			redirect_header( $this->_INDEX_PHP , $this->_TIME_FAILED , _NOPERM ) ;
			exit() ;

		case _C_WEBPHOTO_ERR_NO_RECORD:
			redirect_header( $this->_INDEX_PHP , $this->_TIME_FAILED , $this->get_constant('NOMATCH_PHOTO') ) ;
			exit() ;

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
		return _C_WEBPHOTO_IS_PLAYLIST; 
	}

	if ( ! $this->_check_public( $item_row ) ) {
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

function _check_public( $item_row )
{
	if ( $item_row['item_status'] > 0 ) {
		return true;
	}
	return false;
}

function _get_action()
{
	$post_op            = $this->_post_class->get_post_get_text('op' );
	$post_conf_delete   = $this->_post_class->get_post_text('conf_delete' );
	$post_cont_delete   = $this->_post_class->get_post_text('file_photo_delete' );
	$post_jpeg_delete   = $this->_post_class->get_post_text('file_jpeg_delete' );
	$post_flash_delete  = $this->_post_class->get_post_text('flash_delete' );

	if ( $post_conf_delete ) {
		return 'confirm';
	} elseif ( $post_cont_delete ) {
		return 'cont_delete';
	} elseif ( $post_jpeg_delete ) {
		return 'jpeg_delete';
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
// file delete
//---------------------------------------------------------
function _cont_delete()
{
	$item_row = $this->_row_current;
	$item_id  = $item_row['item_id'] ;
	$this->_check_token_and_redirect( $item_id );

	$ret = $this->cont_delete( $item_row );

	$url = $this->_build_edit_url( $item_id );
	$this->_redirect_file_delete( $ret, $url );
}

function _jpeg_delete()
{
	$item_row = $this->_row_current;
	$item_id  = $item_row['item_id'] ;
	$this->_check_token_and_redirect( $item_id );

	$ret = $this->jpeg_thumb_delete( $item_row );

	$url = $this->_build_edit_url( $item_id );
	$this->_redirect_file_delete( $ret, $url );
}

function _flash_delete()
{
	$item_row = $this->_row_current;
	$item_id  = $item_row['item_id'] ;
	$this->_check_token_and_redirect( $item_id );

	$ret = $this->video_flash_delete( $item_row );
	$url = $this->_build_edit_url( $item_id );
	$this->_redirect_file_delete( $ret, $url );
}

function _redirect_file_delete( $ret, $url )
{
	if ( !$ret  ) {
		redirect_header( $url, $this->_TIME_FAILED, $this->_delete_error ) ;
		exit() ;
	}

	redirect_header( $url, $this->_TIME_SUCCESS, $this->get_constant('DELETED') );
	exit();
}

//---------------------------------------------------------
// print form modify
//---------------------------------------------------------
function _build_form_modify( $flag_default=true )
{
	$item_row = $this->_row_current ;

	if ( $flag_default ) {
		$this->set_param_modify_default( $item_row );

	} else {
		$item_row = $this->build_item_row_modify_post( $item_row );
	}

// BUG: not show tag
	$this->init_form();

	list( $show_form_redo, $param_form_redo ) =
		$this->build_form_redo( $item_row );

	$param = array(
		'show_preview'       => true ,
		'show_admin_manager' => $this->_is_module_admin ,
		'show_form_photo'    => true ,
		'show_form_redo'     => $show_form_redo ,
	);

	$arr = array_merge( 
		$this->_build_preview_modify( $item_row ) ,
		$this->build_form_base_param() ,
		$this->build_form_photo( $item_row ) ,
		$param, $param_form_redo
	);
	return $arr;

}

function _build_preview_modify( $item_row )
{
	$show_class =& webphoto_show_photo::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );

	$arr = array(
		'photo'              => $show_class->build_photo_show( $item_row, $this->get_tag_name_array() ) ,
		'show_photo_summary' => true 
	);
	return $arr;
}

function _build_form_video()
{
	$this->build_form_video_thumb( 'edit', $this->get_updated_row() );
}

function _build_form_error()
{
	$param = array(
		'error' => $this->get_format_error( true, false ) ,
	);
	$arr = array_merge( 
		$this->_build_form_modify( false ) ,
		$param
	);
	return $arr;
}

function _build_form_video_thumb()
{
	return $this->build_form_video_thumb( $this->get_updated_row() );
}

function _build_form_confirm()
{
	$param = array(
		'show_form_confirm' => true ,
	);

	$arr = array_merge( 
		$this->build_form_base_param() ,
		$this->build_form_delete_confirm( $this->_row_current ) ,
		$param 

	);
	return $arr;
}

// --- class end ---
}

?>