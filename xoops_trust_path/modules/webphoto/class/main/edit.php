<?php
// $Id: edit.php,v 1.6 2008/08/05 13:01:28 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-05 K.OHWADA
// BUG: undefined method _check_uid()
// 2008-07-01 K.OHWADA
// added _exec_video()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_edit
//=========================================================
class webphoto_main_edit extends webphoto_photo_edit
{
	var $_form_action   = null;
	var $_has_editable  = false;
	var $_has_deletable = false;

	var $_row_current = null;
	var $_row_update  = null;

	var $_REDIRECT_THIS_URL = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_edit( $dirname , $trust_dirname )
{
	$this->webphoto_photo_edit( $dirname , $trust_dirname );

	$this->_has_editable  = $this->_perm_class->has_editable();
	$this->_has_deletable = $this->_perm_class->has_deletable();

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
	$this->_check();

	$action = $this->_get_action();
	switch ( $action ) 
	{
		case 'submit':
			$this->_check_token_exit();
			$this->_modify();
			if ( $this->_is_video_thumb_form ) {
				break;
			}
			exit();

		case 'redo':
			$this->_check_token_exit();
			$this->_redo();
			if ( $this->_is_video_thumb_form ) {
				break;
			}
			exit();

		case 'video':
			$this->_check_token_exit();
			$this->_video();
			exit();

		case 'delete':
			$this->_check_token_exit();
			$this->_delete();
			exit();

		case 'confirm':
			$this->_get_confirm_photo();
			break;

		default:
			break;
	}

	if ( $this->_is_video_thumb_form ) {
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
			$this->_print_form_video_thumb();
			break;

		case 'confirm':
			$this->_print_form_confirm();
			break;

		default:
			$this->_print_form_default();
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

	$this->_REDIRECT_THIS_URL = $this->_MODULE_URL .'/index.php?fct=edit&amp;photo_id='. $this->_post_photo_id;

	switch ( $this->_exec_check() )
	{
		case _C_WEBPHOTO_ERR_NO_PERM:
			redirect_header( $this->_INDEX_PHP , $this->_TIME_FAIL , _NOPERM ) ;
			exit ;

		case _C_WEBPHOTO_ERR_NO_RECORD:
			redirect_header( $this->_INDEX_PHP , $this->_TIME_FAIL , $this->get_constant('NOMATCH_PHOTO') ) ;
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

	$row = $this->_photo_handler->get_row_by_id( $this->_post_photo_id );
	if ( !is_array($row) ) {
		return _C_WEBPHOTO_ERR_NO_RECORD;
	}

	if ( ! $this->_check_perm( $row ) ) {
		return _C_WEBPHOTO_ERR_NO_PERM; 
	}

// save
	$this->_row_current = $row;
	return 0;
}

function _check_perm( $row )
{
	if ( $this->_is_module_admin ) {
		return true;
	}

// user can touch photos status > 0
	if ( ( $row['photo_uid'] == $this->_xoops_uid ) && ( $row['photo_status'] > 0 ) ) {
		return true;
	}
	return false;
}

function _get_action()
{
	$post_op          = $this->_post_class->get_post_text('op' );
	$post_conf_delete = $this->_post_class->get_post_text('conf_delete' );

	if ( $post_conf_delete ) {
		return 'confirm';
	} elseif ( $post_op ) {
		return $post_op;
	} 
	return '';
}

function _check_token_exit()
{
	if ( ! $this->check_token() )  {
		$msg = 'Token Error';
		if ( $this->_is_module_admin ) {
			$msg .= '<br />'.$this->get_token_errors();
		}
		redirect_header( $this->_REDIRECT_THIS_URL, $this->_TIME_FAIL , $msg );
		exit();
	}
}

//---------------------------------------------------------
// modify
//---------------------------------------------------------
function _modify()
{
	$ret = $this->_exec_modify();

	if ( $this->_is_video_thumb_form ) {
		return;
	}

	switch ( $ret )
	{
		case _C_WEBPHOTO_ERR_DB:
			$msg = 'DB Error';
			if ( $this->_is_module_admin ) {
				$msg .= '<br />'.$this->get_format_error();
			}
			redirect_header( $this->_REDIRECT_THIS_URL, $this->_TIME_FAIL, $msg ) ;
			exit();

		case _C_WEBPHOTO_ERR_UPLOAD;
			$msg  = 'File Upload Error';
			$msg .= '<br />'.$this->get_format_error( false );
			redirect_header( $this->_REDIRECT_THIS_URL , $this->_TIME_FAIL , $msg ) ;
			exit();

		case _C_WEBPHOTO_ERR_NO_SPECIFIED:
			$msg = 'UPLOAD error: file name not specified';
			redirect_header( $this->_REDIRECT_THIS_URL, $this->_TIME_FAIL, $msg );
			exit();

		case _C_WEBPHOTO_ERR_FILE:
			redirect_header( $this->_REDIRECT_THIS_URL , $this->_TIME_FAIL, $this->get_constant('ERR_FILE') ) ;
			exit();

		case _C_WEBPHOTO_ERR_NO_IMAGE;
			redirect_header( $this->_REDIRECT_THIS_URL, $this->_TIME_FAIL, $this->get_constant('ERR_NOIMAGESPECIFIED') ) ;
			exit();

		case _C_WEBPHOTO_ERR_FILEREAD:
			redirect_header( $this->_REDIRECT_THIS_URL, $this->_TIME_FAIL, $this->get_constant('ERR_FILEREAD') ) ;
			exit();

		case 0:
		default:
			break;
	}

	$this->_modify_success();
}

function _modify_success()
{
	$time = $this->_TIME_SUCCESS ;
	$msg  = '';

	if ( $this->_msg_class->has_msg() ) {
		$msg .= $this->_msg_class->get_format_msg() ;
		$msg .= "<br />\n";
		$time = $this->_TIME_PENDING ;
	}

	$msg .= $this->get_constant('DBUPDATED') ;

	redirect_header( $this->_REDIRECT_THIS_URL, $time , $msg ) ;
	exit();
}

function _exec_modify()
{
	$photo_tmp_name = null;
	$thumb_tmp_name = null;
	$image_info     = null;

	$this->_msg_class->clear_msgs();

// load
	$row = $this->_row_current;

	$current_file_path  = $row['photo_file_path'];
	$current_cont_path  = $row['photo_cont_path'];
	$current_thumb_path = $row['photo_thumb_path'];

	$current_file_ext = $row['photo_file_ext'];

// Check if upload file name specified
	if ( !$this->check_xoops_upload_file() ) {
		return _C_WEBPHOTO_ERR_NO_SPECIFIED;
	}

	$ret11 = $this->upload_fetch_photo( true );
	if ( $ret11 < 0 ) { 
		return $ret11;	// failed
	}

	$ret12 = $this->upload_fetch_thumb();
	if ( $ret12 < 0 ) { 
		return $ret12;	// failed
	}

	$photo_tmp_name = $this->_photo_tmp_name;
	$thumb_tmp_name = $this->_thumb_tmp_name;

// no upload
	if ( empty($photo_tmp_name) && empty($thumb_tmp_name) ) {
		return $this->_handler_update_photo_no_image( $row );
	}

// remove old photo & thumb file
	if ( $photo_tmp_name ) {
		$this->unlink_path( $current_file_path );
		$this->unlink_path( $current_cont_path );
		$this->unlink_path( $current_thumb_path );

// remove old thumb file
	} elseif ( empty($photo_tmp_name) && $thumb_tmp_name ) {
		$this->unlink_path( $current_thumb_path );
	}

	$ret12 = $this->create_photo_thumb(
		$this->_post_photo_id, $photo_tmp_name, $thumb_tmp_name );
	if ( $ret12 < 0 ) { return $ret12; }
	$image_info = $this->get_photo_thumb_info();

	return $this->_handler_update_photo_image( $row, $image_info );
}

function _build_new_status( $current_status )
{
	$post_valid = $this->_post_class->get_post_int('valid');

	// status change
	$new_status = null ;

// admin
	if ( $this->_is_module_admin ) {
		if ( $current_status == _C_WEBPHOTO_STATUS_WAITING ) {
			if ( $post_valid == _C_WEBPHOTO_YES )  {
				$new_status = _C_WEBPHOTO_STATUS_APPROVED ;
			}
		} else {
			if ( $post_valid == _C_WEBPHOTO_YES ) {
				$new_status = _C_WEBPHOTO_STATUS_UPDATED ;
			} else {
				$new_status = _C_WEBPHOTO_STATUS_WAITING ;
			}
		}

// user
	} else {
		$new_status = _C_WEBPHOTO_STATUS_UPDATED ;
	}

	return $new_status;
}

//---------------------------------------------------------
// redo
//---------------------------------------------------------
function _redo()
{
	$ret = $this->_exec_redo();

	if ( $this->_is_video_thumb_form ) {
		return;
	}

	switch ( $ret )
	{
		case _C_WEBPHOTO_ERR_DB:
			$msg = 'DB Error';
			if ( $this->_is_module_admin ) {
				$msg .= '<br />'.$this->get_format_error();
			}
			redirect_header( $this->_REDIRECT_THIS_URL, $this->_TIME_FAIL, $msg ) ;
			exit();

		case 0:
		default:
			break;
	}

	$this->_modify_success();
}

function _exec_redo()
{
	$this->_msg_class->clear_msgs();

	$this->_is_video_thumb_form = false;
	$file_info = null;

	$post_redo_thumb = $this->_post_class->get_post_text('redo_thumb' );
	$post_redo_flash = $this->_post_class->get_post_text('redo_flash' );

// load
	$row = $this->_row_current;

	$photo_id         = $row['photo_id'];
	$photo_file_path  = $row['photo_file_path'];
	$photo_cont_path  = $row['photo_cont_path'];
	$photo_cont_ext   = $row['photo_cont_ext'];
	$photo_thumb_path = $row['photo_thumb_path'];

	$photo_file_file  = XOOPS_ROOT_PATH . $photo_file_path ;
	$photo_cont_file  = XOOPS_ROOT_PATH . $photo_cont_path ;
	$photo_thumb_file = XOOPS_ROOT_PATH . $photo_thumb_path ;

	$tmp_file = $this->_TMP_DIR .'/tmp_' . uniqid( $photo_id.'_' ) ;

// create flash
	if ( $post_redo_flash ) {
// save file
		$this->rename_file( $photo_file_file, $tmp_file );

		$ret = $this->create_video_flash( $photo_id, $photo_cont_file );
		if ( $ret ) {
// remove file if success
			$this->unlink_file( $tmp_file );
			$file_info = $this->_video_class->get_flash_info();

		} else {
// recover file if fail
			$this->rename_file( $tmp_file, $photo_file_file );
		}
	}

// create video thumb
	if ( $post_redo_thumb && $this->_cfg_makethumb ) {
		$this->_is_video_thumb_form 
			= $this->create_video_plural_thumbs( $photo_id, $photo_cont_file, $photo_cont_ext ) ;
	}

// update
	$row_update = $row ;
	if ( is_array($file_info) && count($file_info) ) {
		$row_update = array_merge( $row_update, $file_info );

		$ret = $this->_photo_handler->update( $row_update );
		if ( !$ret ) {
			$this->set_error( $this->_photo_handler->get_errors() );
			return _C_WEBPHOTO_ERR_DB;
		}
	}

// save
	$this->_row_update = $row_update;

	return 0;
}

//---------------------------------------------------------
// video
//---------------------------------------------------------
function _video()
{
	$ret = $this->_exec_video();
	switch ( $ret )
	{
		case _C_WEBPHOTO_ERR_DB:
			$msg = 'DB Error';
			if ( $this->_is_module_admin ) {
				$msg .= '<br />'.$this->get_format_error();
			}
			redirect_header( $this->_REDIRECT_THIS_URL, $this->_TIME_FAIL, $msg ) ;
			exit();

	}

	$this->_modify_success();
}

function _exec_video()
{
	$photo_id = $this->_post_class->get_post('photo_id');
	$num      = $this->_post_class->get_post('num');

	$this->_msg_class->clear_msgs();

	$row = $this->_photo_handler->get_row_by_id( $photo_id );
	if ( !is_array($row) ) {
		return _C_WEBPHOTO_ERR_NO_RECORD;
	}

	$thumb_info = $this->create_video_thumb( $row, $num );

// update
	if ( is_array($thumb_info) && count($thumb_info) ) {
		$row_update = array_merge( $row, $thumb_info );
		$ret = $this->_photo_handler->update( $row_update );
		if ( !$ret ) {
			$this->set_error( $this->_photo_handler->get_errors() );
			return _C_WEBPHOTO_ERR_DB;
		}
	}

	return 0;
}

//---------------------------------------------------------
// photo handler
//---------------------------------------------------------
function _handler_update_photo_no_image( $row )
{
	$row_update = $this->_build_update_row_by_post( $row );

	$ret = $this->_photo_handler->update( $row_update );
	if ( !$ret ) {
		$this->set_error( $this->_photo_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB;
	}

	$ret16 = $this->tag_handler_update_tags( $this->_post_photo_id , $this->get_tag_name_array() );
	if ( !$ret16 ) {
		return _C_WEBPHOTO_ERR_DB;
	}

	$this->_xoops_notify_if_apporve( $row_update );

	return 0;
}

function _handler_update_photo_image( $row, $image_info )
{
	$row_update = $this->_build_update_row_by_post( $row );

	if ( is_array($image_info) && count($image_info) ) {
		$row_update = array_merge( $row_update, $image_info );
	}

	$ret = $this->_photo_handler->update( $row_update );
	if ( !$ret ) {
		$this->set_error( $this->_photo_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB;
	}

	$ret16 = $this->tag_handler_update_tags( $this->_post_photo_id, $this->get_tag_name_array() );
	if ( !$ret16 ) {
		return _C_WEBPHOTO_ERR_DB;
	}

	$this->_xoops_notify_if_apporve( $row_update );

// save
	$this->_row_update = $row_update;

	return 0;
}

function _xoops_notify_if_apporve( $row )
{
// when approve
	if ( $row['photo_status'] == 1 ) {
		$this->xoops_notify_for_edit( $this->_post_photo_id, $row['photo_cat_id'], $row['photo_title'] );
	}
}

function _build_update_row_by_post( $row )
{
	$new_status = $this->_build_new_status( $row['photo_status'] );

	$row_update = $this->_build_preview_row_by_post( $row );

	$row_update['photo_status'] = $new_status;
	$row_update['photo_search'] = $this->build_search_for_edit( $row_update, $this->get_tag_name_array() );

	return $row_update;
}

function _build_preview_row_by_post( $row )
{
	$post_preview              = $this->_post_class->get_post_text('preview');
	$post_submit               = $this->_post_class->get_post_text('submit' );
	$post_time_update_checkbox = $this->_post_class->get_post_int( 'photo_time_update_checkbox' );
	$post_time_update          = $this->_post_class->get_post_time('photo_time_update' );

	if ( $post_preview || $post_submit ) {

		$row = $this->build_row_by_post( $row );

		if ( $this->_is_module_admin ) {
			if ( $post_time_update_checkbox ) {
				$row['photo_time_update'] = $post_time_update;
			}
		} else {
			$row['photo_time_update'] = time();
		}

	}

	return $row;
}

//---------------------------------------------------------
// delete
//---------------------------------------------------------
function _delete()
{
	if( ! $this->_has_deletable ) {
		redirect_header( $this->_INDEX_PHP , $this->_TIME_FAIL , _NOPERM ) ;
		exit ;
	}

	$ret = $this->_exec_delete();
	switch ( $ret )
	{
		case _C_WEBPHOTO_ERR_NO_PERM:
			redirect_header( $this->_INDEX_PHP , $this->_TIME_FAIL , _NOPERM ) ;
			exit();

		case _C_WEBPHOTO_ERR_NO_RECORD:
			redirect_header( $this->_INDEX_PHP , $this->_TIME_FAIL , $this->get_constant('NOMATCH_PHOTO') ) ;
			exit() ;

		case _C_WEBPHOTO_ERR_DB:
			if ( $this->_is_module_admin ) {
				$msg  = 'DB Error';
				$msg .= '<br />'.$this->get_format_error();
				redirect_header( $this->_REDIRECT_THIS_URL, $this->_TIME_FAIL, $msg ) ;
				exit();
			}
			break;

		case 0:
		default:
			break;
	}

	redirect_header( $this->_INDEX_PHP, $this->_TIME_SUCCESS , $this->get_constant('DELETED') ) ;
	exit ;
}

function _exec_delete()
{
	if ( ! $this->_has_deletable ) {
		return _C_WEBPHOTO_ERR_NO_PERM;
	}

	$row = $this->_photo_handler->get_row_by_id( $this->_post_photo_id );
	if ( !is_array($row) ) {
		return _C_WEBPHOTO_ERR_NO_RECORD;
	}

// BUG: undefined method _check_uid()
	if ( ! $this->_check_perm( $row ) ) {
		return _C_WEBPHOTO_ERR_NO_PERM;
	}

	$ret = $this->delete_photo( $this->_post_photo_id ) ;
	if ( !$ret ) {
		return _C_WEBPHOTO_ERR_DB;
	}

	return 0;
}

//---------------------------------------------------------
// confirm_delete
//---------------------------------------------------------
function _get_confirm_photo()
{
	if( ! $this->_has_deletable ) {
		redirect_header( $this->_INDEX_PHP , $this->_TIME_FAIL , _NOPERM ) ;
		exit();
	}

//	$row = $this->_photo_handler->get_row_by_id( $this->_post_photo_id );
//	if ( !is_array($row) ) {
//		redirect_header( $this->_INDEX_PHP , $this->_TIME_FAIL , $this->get_constant('NOMATCH_PHOTO') ) ;
//		exit ;
//	}

// save
//	$this->_row = $row;

}

//---------------------------------------------------------
// preview
//---------------------------------------------------------
function _get_photo_row()
{

// set checked
	$this->set_checkbox_by_name( 'photo_datetime_checkbox',    _C_WEBPHOTO_YES );
	$this->set_checkbox_by_name( 'photo_time_update_checkbox', _C_WEBPHOTO_YES );
	$this->set_checkbox_by_name( 'thumb_checkbox',             _C_WEBPHOTO_YES );

// load
	$row = $this->_row_current;

// get current tags
	$this->set_tag_name_array( $this->tag_handler_tag_name_array( $this->_post_photo_id ) );

	return $row;
}

function _print_preview( $row )
{
	$row_preview = $this->_build_preview_row_by_post( $row );

	$show = $this->show_build_preview_edit( $row_preview, $this->get_tag_name_array() );
	echo $this->build_preview_template( $show );

	return $row_preview;
}

//---------------------------------------------------------
// print_form
//---------------------------------------------------------
function _print_form_default()
{
	echo $this->_build_bread_crumb_edit();

	$row = $this->_get_photo_row();
	$row = $this->_print_preview( $row );

	$photo_cont_ext = $row['photo_cont_ext'];

	$is_image = $this->is_normal_ext( $photo_cont_ext ) ;
	$is_video = $this->_mime_class->is_video_ext( $photo_cont_ext ) ;

	list ( $types, $allowed_exts ) = $this->_mime_class->get_my_allowed_mimes();

	$param = array(
		'mode'            => 'edit',
		'preview_name'    => $this->get_preview_name(),
		'tag_name_array'  => $this->get_tag_name_array(),
		'checkbox_array'  => $this->get_checkbox_array(),
		'has_resize'      => $this->_has_resize,
		'has_rotate'      => $this->_has_rotate,
		'allowed_exts'    => $allowed_exts ,
		'is_image'        => $is_image ,
		'is_video'        => $is_video ,
	);

	$form =& webphoto_photo_edit_form::getInstance( $this->_DIRNAME , $this->_TRUST_DIRNAME );
	$form->print_form_common( $row, $param );
	$form->print_form_redo(   $row, $param );

}

function _print_form_confirm()
{
// load
	$row = $this->_row_current;

	if ( $row['photo_thumb_url'] ) {
		$src = $row['photo_thumb_url'];
	} elseif ( $row['photo_cont_url'] && $this->is_nomrl_ext( $row['photo_cont_ext'] ) ) {
		$src = $row['photo_cont_url'];
	}

	echo $this->_build_bread_crumb_edit();
	echo '<h4>'. $this->get_constant('TITLE_PHOTODEL') ."</h4>\n";

	if ( $src ) {
		echo '<img src="'. $src .'" border="0" />'."\n";
		echo "<br />\n";
	}

	echo "<br />\n";

	$form =& webphoto_photo_edit_form::getInstance( $this->_DIRNAME , $this->_TRUST_DIRNAME );
	$form->print_form_delete_confirm( $row['photo_id'] );
}

function _print_form_video_thumb()
{
	$this->print_form_video_thumb_common( 'edit', $this->_row_update );
}

function _build_bread_crumb_edit()
{
	return $this->build_bread_crumb( $this->get_constant('TITLE_EDIT'), $this->_REDIRECT_THIS_URL );
}

// --- class end ---
}

?>