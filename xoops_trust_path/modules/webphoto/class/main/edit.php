<?php
// $Id: edit.php,v 1.15 2008/11/01 23:53:08 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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
class webphoto_main_edit extends webphoto_photo_action
{
	var $_form_action = null;

	var $_THIS_FCT = 'edit';
	var $_THIS_URL = null;

	var $_TIME_SUCCESS = 1;
	var $_TIME_PENDING = 3;
	var $_TIME_FAILED  = 5;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_edit( $dirname , $trust_dirname )
{
	$this->webphoto_photo_action( $dirname , $trust_dirname );

	$this->_THIS_URL = $this->_MODULE_URL .'/index.php?fct='.$this->_THIS_FCT;

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

		default:
			break;
	}

	if ( $ret == _C_WEBPHOTO_RET_VIDEO_FORM ) {
		$this->_form_action = 'form_video_thumb';
	} else {
		$this->_form_action = $action;
	}

	return true;
}

function print_form()
{
	switch ( $this->_form_action ) 
	{
		case 'form_video_thumb':
			$this->print_form_video_thumb( 'edit', $this->get_updated_row() );
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

// save
	$this->_row_current = $item_row;
	return 0;
}

function _check_perm( $item_row )
{
	if ( $this->_is_module_admin ) {
		return true;
	}

// user can touch photos status > 0
	if ( ( $item_row['item_uid'] == $this->_xoops_uid ) && ( $item_row['item_status'] > 0 ) ) {
		return true;
	}
	return false;
}

function _get_action()
{
	$post_op          = $this->_post_class->get_post_get_text('op' );
	$post_conf_delete = $this->_post_class->get_post_text('conf_delete' );

	if ( $post_conf_delete ) {
		return 'confirm';
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
	$is_failed = false ;

	$this->_check_token_and_redirect();

// load
	$item_row = $this->_row_current;
	$item_id  = $item_row['item_id'] ;

	$ret = $this->modify( $item_row );
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

	redirect_header( $url , $time , $msg ) ;
	exit() ;
}

function _check_token_and_redirect()
{
	$this->check_token_and_redirect( $this->_THIS_URL, $this->_TIME_FAILED );
}

function _build_redirect_param( $is_failed, $item_id )
{
	$url = $this->_THIS_URL .'&amp;photo_id='. $item_id ;
	$param = array(
		'is_failed'   => $is_failed ,
		'url_success' => $url ,
		'url_failed'  => $url , 
		'msg_success' => $this->get_constant('DBUPDATED') ,
	);
	return $param ;
}

//---------------------------------------------------------
// redo
//---------------------------------------------------------
function _redo()
{
	$is_failed = false ;

	$this->_check_token_and_redirect();

// load
	$item_row = $this->_row_current;
	$item_id  = $item_row['item_id'] ;

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
	$this->_check_token_and_redirect();

// load
	$item_row = $this->_row_current;
	$item_id  = $item_row['item_id'] ;

	$ret = $this->_photo_class->video_thumb( $item_row );

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
	$this->_check_token_and_redirect();

// load
	$item_row = $this->_row_current;
	$item_id  = $item_row['item_id'] ;

	$ret = $this->delete( $item_row );

	$redirect_param = array(
		'is_failed'   => !$ret ,
		'url_success' => $this->_MODULE_URL .'/index.php' ,
		'url_failed'  => $this->_THIS_URL .'&amp;photo_id='. $item_id , 
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
// print form modify
//---------------------------------------------------------
function _print_form_modify()
{
	$form_class =& webphoto_photo_edit_form::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );

	$item_row  = $this->build_modify_row_by_post( $this->_row_current, true );
	$item_id   = $item_row['item_id'];
	$kind      = $item_row['item_kind'];

	echo $this->_build_bread_crumb_edit( $item_id );

	$this->_print_preview_modify( $item_row );

	if ( $this->_is_module_admin ) {
		$url = $this->_MODULE_URL .'/admin/index.php?fct=item_manager&amp;op=modify_form&amp;item_id='. $item_id ;
		echo '<a href="'. $url .'">';
		echo 'goto admin item manager: '. $item_id ;
		echo "</a><br /><br />\n";
	}

	$form_class->print_form_common( 
		$item_row, $this->build_form_param( 'edit' ) );

	if ( $this->is_video_kind( $kind ) ) {
		$form_class->print_form_redo( 'edit',  $item_row );
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

function _build_bread_crumb_edit( $item_id )
{
	$url = $this->_THIS_URL.'&amp;photo_id='.$item_id;
	return $this->build_bread_crumb( $this->get_constant('TITLE_EDIT'), $url );
}

//---------------------------------------------------------
// print form confirm
//---------------------------------------------------------
function _print_form_confirm()
{
	$item_row = $this->_row_current;
	$item_id  = $item_row['item_id'];

	echo $this->_build_bread_crumb_edit();
	$this->print_form_delete_confirm( 'edit', $item_row ) ;
}

// --- class end ---
}

?>