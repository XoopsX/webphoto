<?php
// $Id: edit.php,v 1.13 2008/10/13 10:24:07 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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
class webphoto_main_edit extends webphoto_photo_edit
{
	var $_notification_class;
	var $_delete_class;

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

	$this->_notification_class =& webphoto_notification_event::getInstance( $dirname , $trust_dirname );
	$this->_delete_class =& webphoto_photo_delete::getInstance( $dirname );

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
			$this->_check_token_and_redirect();
			$this->_modify();
			if ( $this->_is_video_thumb_form ) {
				break;
			}
			exit();

		case 'redo':
			$this->_check_token_and_redirect();
			$this->_redo();
			if ( $this->_is_video_thumb_form ) {
				break;
			}
			exit();

		case 'video':
			$this->_check_token_and_redirect();
			$this->_video();
			exit();

		case 'delete':
			$this->_check_token_and_redirect();
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

	$item_row = $this->_item_handler->get_row_by_id( $this->_post_photo_id );
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
	$post_op          = $this->_post_class->get_post_text('op' );
	$post_conf_delete = $this->_post_class->get_post_text('conf_delete' );

	if ( $post_conf_delete ) {
		return 'confirm';
	} elseif ( $post_op ) {
		return $post_op;
	} 
	return '';
}

function _check_token_and_redirect()
{
	$this->check_token_and_redirect( $this->_REDIRECT_THIS_URL, $this->_TIME_FAIL );
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
			redirect_header( $this->_REDIRECT_THIS_URL , $this->_TIME_FAIL, 
				$this->get_constant('ERR_FILE') ) ;
			exit();

		case _C_WEBPHOTO_ERR_NO_IMAGE;
			redirect_header( $this->_REDIRECT_THIS_URL, $this->_TIME_FAIL, 
				$this->get_constant('ERR_NOIMAGESPECIFIED') ) ;
			exit();

		case _C_WEBPHOTO_ERR_FILEREAD:
			redirect_header( $this->_REDIRECT_THIS_URL, $this->_TIME_FAIL, 
				$this->get_constant('ERR_FILEREAD') ) ;
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

	if ( $this->has_msg_array() ) {
		$msg .= $this->get_format_msg_array() ;
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

	$this->clear_msg_array();

// load
	$item_row = $this->_row_current;

	$current_cont_path  = $this->get_file_path_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_CONT ) ;

	$current_thumb_path  = $this->get_file_path_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_THUMB ) ;

	$current_middle_path  = $this->get_file_path_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_MIDDLE ) ;

	$current_flash_path  = $this->get_file_path_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_VIDEO_FLASH ) ;

	$current_docomo_path  = $this->get_file_path_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_VIDEO_DOCOMO ) ;

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
		return $this->_handler_update_photo_no_image( $item_row );
	}

// remove old photo & thumb file
	if ( $photo_tmp_name ) {
		$this->unlink_path( $current_cont_path );
		$this->unlink_path( $current_thumb_path );
		$this->unlink_path( $current_middle_path );
		$this->unlink_path( $current_flash_path );
		$this->unlink_path( $current_docomo_path );

// remove old thumb file
	} elseif ( empty($photo_tmp_name) && $thumb_tmp_name ) {
		$this->unlink_path( $current_thumb_path );
	}

	$ret12 = $this->create_photo_thumb(
		$this->_post_photo_id, $photo_tmp_name, $thumb_tmp_name );
	if ( $ret12 < 0 ) {
		return $ret12; 
	}
	$file_params = $this->get_file_params();

	return $this->_handler_update_photo_image( $item_row, $file_params );
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
	$this->clear_msg_array();

	$this->_is_video_thumb_form = false;
	$flash_param = null;

	$post_redo_thumb = $this->_post_class->get_post_text('redo_thumb' );
	$post_redo_flash = $this->_post_class->get_post_text('redo_flash' );

// load
	$item_row = $this->_row_current;

	$item_id   = $item_row['item_id'];
	$item_ext  = $item_row['item_ext'];
	$item_kind = $item_row['item_kind'];

	$cont_file = null ;
	$param     = null ;

	$cont_row = $this->get_file_row_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_CONT ) ;
	if ( is_array($cont_row) ) {
		$cont_path     = $cont_row['file_path'];
		$cont_width    = $cont_row['file_width'];
		$cont_height   = $cont_row['file_height'];
		$cont_duration = $cont_row['file_duration'];
		$cont_file     = XOOPS_ROOT_PATH . $cont_path ;

		$param                = array() ;
		$param['src_file']    = $cont_file ;
		$param['src_kind']    = $item_kind ;
		$param['video_param'] = array(
			'width'    => $cont_width ,
			'height'   => $cont_height ,
			'duration' => $cont_duration ,
		);
	}

	$flash_path  = $this->get_file_value_by_kind_name( 
		$item_row, _C_WEBPHOTO_FILE_KIND_VIDEO_FLASH, 'file_path' ) ;

	$flash_file = XOOPS_ROOT_PATH . $flash_path ;

	$flash_tmp_file = $this->_TMP_DIR .'/tmp_' . uniqid( $item_id.'_' ) ;

// create flash
	if ( $post_redo_flash && is_array($param) ) {
// save file
		$this->rename_file( $flash_file, $flash_tmp_file );

// BUG: undefined create_video_flash()
		$flash_param = $this->_photo_class->create_video_flash_param( $item_id, $param );
		if ( is_array($flash_param) ) {
// remove file if success
			$this->unlink_file( $flash_tmp_file );

		} else {
// recovery file if fail
			$this->rename_file( $flash_tmp_file, $flash_file );
		}
	}

// create video thumb
	if ( $post_redo_thumb && $this->_cfg_makethumb && $cont_file ) {

// BUG: undefined create_video_plural_thumbs()
		$this->_is_video_thumb_form 
			= $this->_photo_class->create_video_plural_thumbs(
				$item_id, $cont_file, $item_ext ) ;
	}

// update
	$row_update = $item_row ;

	if ( is_array($flash_param) ) {
		$flash_id = $this->_photo_class->insert_file( $item_id, $flash_param );
		if ( $flash_id > 0 ) {
			$row_update['item_file_id_4'] = $flash_id;

			$ret = $this->_item_handler->update( $row_update );
			if ( !$ret ) {
				$this->set_error( $this->_item_handler->get_errors() );
				return _C_WEBPHOTO_ERR_DB;
			}
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
	$ret = $this->exec_video_thumb() ;
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

//---------------------------------------------------------
// photo handler
//---------------------------------------------------------
function _handler_update_photo_no_image( $item_row )
{
	$this->_update_all_file_duration( $item_row );

	$row_update = $this->_build_update_row_by_post( $item_row );

	$ret = $this->_item_handler->update( $row_update );
	if ( !$ret ) {
		$this->set_error( $this->_item_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB;
	}

	$this->tag_handler_update_tags( $this->_post_photo_id , $this->get_tag_name_array() );

	$this->_xoops_notify_if_apporve( $row_update );

	return 0;
}

function _handler_update_photo_image( $item_row, $file_params )
{
	$item_id   = $item_row['item_id'] ;
	$cont_id   = 0 ;
	$thumb_id  = 0 ;
	$middle_id = 0 ;
	$flash_id  = 0 ;
	$docomo_id = 0 ;

	if ( is_array($file_params) ) {
		$cont_param   = $file_params['cont'] ;
		$thumb_param  = $file_params['thumb'] ;
		$middle_param = $file_params['middle'] ;
		$flash_param  = $file_params['flash'] ;
		$docomo_param = $file_params['docomo'] ;

		if ( is_array($cont_param) ) {
			$cont_id = $this->_photo_class->insert_file( $item_id, $cont_param );
		}
		if ( is_array($thumb_param) ) {
			$thumb_id = $this->_photo_class->insert_file( $item_id, $thumb_param );
		}
		if ( is_array($middle_param) ) {
			$middle_id = $this->_photo_class->insert_file( $item_id, $middle_param );
		}
		if ( is_array($flash_param) ) {
			$flash_id = $this->_photo_class->insert_file( $item_id, $flash_param );
		}
		if ( is_array($docomo_param) ) {
			$docomo_id = $this->_photo_class->insert_file( $item_id, $docomo_param );
		}
	}

	if ( $cont_id == 0 ) {
		$this->_update_all_file_duration( $item_row );
	}

	$row_update = $this->_build_update_row_by_post( $item_row );

	if ( $cont_id > 0 ) {
		$row_update['item_file_id_1'] = $cont_id;
	}
	if ( $thumb_id > 0 ) {
		$row_update['item_file_id_2'] = $thumb_id;
	}
	if ( $middle_id > 0 ) {
		$row_update['item_file_id_3'] = $middle_id;
	}
	if ( $flash_id > 0 ) {
		$row_update['item_file_id_4'] = $flash_id;
	}
	if ( $docomo_id > 0 ) {
		$row_update['item_file_id_5'] = $docomo_id;
	}

	$ret = $this->_item_handler->update( $row_update );
	if ( !$ret ) {
		$this->set_error( $this->_item_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB;
	}

	$this->tag_handler_update_tags( $this->_post_photo_id, $this->get_tag_name_array() );

	$this->_xoops_notify_if_apporve( $row_update );

// save
	$this->_row_update = $row_update;

	return 0;
}

function _update_all_file_duration( $item_row )
{
	$duration      = $this->get_photo_duration();
	$cont_duration = $this->get_file_cont_duration( $item_row ); 

	if ( $duration != $cont_duration ) {
		$this->_update_file_duration( $duration, $item_row, _C_WEBPHOTO_FILE_KIND_CONT );
		$this->_update_file_duration( $duration, $item_row, _C_WEBPHOTO_FILE_KIND_VIDEO_FLASH );
		$this->_update_file_duration( $duration, $item_row, _C_WEBPHOTO_FILE_KIND_VIDEO_DOCOMO );
	}
}

function _update_file_duration( $duration, $item_row, $kind )
{
	$file_row = $this->get_file_row_by_kind( $item_row, $kind );
	if ( !is_array($file_row ) ) {
		return true;
	}

	$file_row['file_duration'] = $duration ;

	$ret = $this->_file_handler->update( $file_row );
	if ( !$ret ) {
		$this->set_error( $this->_file_handler->get_errors() );
		return false;
	}
	return true;
}

function _xoops_notify_if_apporve( $item_row )
{
// when approve
	if ( $item_row['item_status'] == 1 ) {
		$this->_notification_class->notify_new_photo( 
			$this->_post_photo_id, $item_row['item_cat_id'], $item_row['item_title'] );
	}
}

function _build_update_row_by_post( $item_row )
{
	$new_status = $this->_build_new_status( $item_row['item_status'] );

	$row_update = $this->_build_preview_row_by_post( $item_row );

	$row_update['item_status'] = $new_status;
	$row_update['item_search'] = $this->build_search_for_edit( 
		$row_update, $this->get_tag_name_array() );

	return $row_update;
}

function _build_preview_row_by_post( $item_row )
{
	$post_preview              = $this->_post_class->get_post_text('preview');
	$post_submit               = $this->_post_class->get_post_text('submit' );
	$post_time_update_checkbox = $this->_post_class->get_post_int( 'item_time_update_checkbox' );
	$post_time_update          = $this->_post_class->get_post_time('item_time_update' );

	if ( $post_preview || $post_submit ) {

		$item_row = $this->build_row_by_post( $item_row );

		if ( $this->_is_module_admin ) {
			if ( $post_time_update_checkbox ) {
				$item_row['item_time_update'] = $post_time_update;
			}
		} else {
			$item_row['item_time_update'] = time();
		}

	}

	return $item_row;
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

	$item_row = $this->_item_handler->get_row_by_id( $this->_post_photo_id );
	if ( !is_array($item_row) ) {
		return _C_WEBPHOTO_ERR_NO_RECORD;
	}

// BUG: undefined method _check_uid()
	if ( ! $this->_check_perm( $item_row ) ) {
		return _C_WEBPHOTO_ERR_NO_PERM;
	}

	$ret = $this->_delete_class->delete_photo( $this->_post_photo_id );
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

}

//---------------------------------------------------------
// preview
//---------------------------------------------------------
function _get_photo_row()
{

// set checked
	$this->set_checkbox_by_name( 'item_datetime_checkbox',    _C_WEBPHOTO_YES );
	$this->set_checkbox_by_name( 'item_time_update_checkbox', _C_WEBPHOTO_YES );
	$this->set_checkbox_by_name( 'thumb_checkbox',            _C_WEBPHOTO_YES );

// load
	$item_row = $this->_row_current;

// get current tags
	$this->set_tag_name_array( $this->tag_handler_tag_name_array( $this->_post_photo_id ) );

	return $item_row;
}

function _print_preview( $item_row )
{
	$row_preview = $this->_build_preview_row_by_post( $item_row );

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

	$item_row = $this->_get_photo_row();
	$item_row = $this->_print_preview( $item_row );

	$item_id   = $item_row['item_id'];
	$item_kind = $item_row['item_kind'];

	if ( $this->_is_module_admin ) {
		$url = $this->_MODULE_URL .'/admin/index.php?fct=item_table_manage&op=form&id='. $item_id ;
		echo '<a href="'. $url .'">goto admin item table: '. $item_id ."</a><br />\n";
	}

	$is_image  = $this->is_image_kind( $item_kind ) ;
	$is_video  = $this->is_video_kind( $item_kind ) ;

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
	$form->print_form_common( $item_row, $param );
	$form->print_form_redo(   $item_row, $param );

	if ( $this->_is_module_admin ) {
		$url = $this->_MODULE_URL .'/admin/index.php' ;
		echo "<br />\n";
		echo '<a href="'. $url .'">';
		echo $this->get_constant('goto_admin');
		echo "</a><br />\n";
	}

}

function _print_form_confirm()
{
// load
	$item_row = $this->_row_current;

	$cont_url = $this->get_file_url_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_CONT ) ;

	$thumb_url = $this->get_file_url_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_THUMB ) ;

	if ( $thumb_url ) {
		$src = $thumb_url ;
	} elseif ( $cont_url && $this->is_image_kind( $item_row['item_kind'] ) ) {
		$src = $cont_url ;
	}

	echo $this->_build_bread_crumb_edit();
	echo '<h4>'. $this->get_constant('TITLE_PHOTODEL') ."</h4>\n";

	if ( $src ) {
		echo '<img src="'. $src .'" border="0" />'."\n";
		echo "<br />\n";
	}

	echo "<br />\n";

	$form =& webphoto_photo_edit_form::getInstance( $this->_DIRNAME , $this->_TRUST_DIRNAME );
	$form->print_form_delete_confirm( $item_row['item_id'] );
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