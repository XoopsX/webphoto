<?php
// $Id: action.php,v 1.10 2009/12/16 13:32:34 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-12-06 K.OHWADA
// mail_approve()
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_edit_item_delete
// 2009-05-05 K.OHWADA
// edit_form_build_form_param() -> build_form_base_param()
// 2009-04-10 K.OHWADA
// BUG: not clear file id when delete file
// 2009-03-15 K.OHWADA
// small_delete()
// 2009-01-25 K.OHWADA
// add search in update_photo_no_image()
// 2009-01-13 K.OHWADA
// webphoto_photo_action -> webphoto_edit_action
// search with text content
// 2009-01-12 K.OHWADA
// Fatal error: Call to undefined method webphoto_main_edit::get_file_params()
// 2009-01-04 K.OHWADA
// webphoto_photo_edit_form -> webphoto_photo_misc_form
// BUG: return to admin when delete
// 2008-12-12 K.OHWADA
// set_flag_row_extend()
// 2008-12-07 K.OHWADA
// webphoto_show_image
// 2008-11-29 K.OHWADA
// item_time_publish
// build_show_file_image()
// 2008-11-16 K.OHWADA
// BUG: not set external type
// BUG: error twice
// 2008-11-08 K.OHWADA
// upload_fetch_middle()
// BUG: endless loop in submit check
// 2008-11-04 K.OHWADA
// BUG: undefined property _REDIRECT_TIME_FAILED
// set values in preview
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_action
//=========================================================
class webphoto_edit_action extends webphoto_edit_submit
{
	var $_delete_class;
	var $_mail_template_class;
	var $_mail_send_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_action( $dirname , $trust_dirname )
{
	$this->webphoto_edit_submit( $dirname , $trust_dirname );

	$this->_delete_class 
		=& webphoto_edit_item_delete::getInstance( $dirname , $trust_dirname );
	$this->_mail_template_class 
		=& webphoto_d3_mail_template::getInstance( $dirname , $trust_dirname );

	$this->_mail_send_class  =& webphoto_lib_mail_send::getInstance();
}

// for admin_photo_manage admin_catmanager
function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_action( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// modify check
//---------------------------------------------------------
function check_edit_perm( $item_row )
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

//---------------------------------------------------------
// modify form
//---------------------------------------------------------
function set_param_modify_default( $item_row )
{
	$publish_checkbox = _C_WEBPHOTO_NO ;
	$expire_checkbox  = _C_WEBPHOTO_NO ;
	if ( $item_row['item_time_publish'] > 0 ) {
		$publish_checkbox = _C_WEBPHOTO_YES ;
	}
	if ( $item_row['item_time_expire'] > 0 ) {
		$expire_checkbox = _C_WEBPHOTO_YES ;
	}
	$this->set_checkbox_by_name( 'item_datetime_checkbox',     _C_WEBPHOTO_YES );	
	$this->set_checkbox_by_name( 'item_time_update_checkbox',  _C_WEBPHOTO_YES );
	$this->set_checkbox_by_name( 'item_time_publish_checkbox', $publish_checkbox );
	$this->set_checkbox_by_name( 'item_time_expire_checkbox',  $expire_checkbox );
	$this->set_tag_name_array( $this->tag_handler_tag_name_array( $item_row['item_id'] ) );
}

function build_item_row_modify_post( $item_row )
{
	$checkbox = $this->get_checkbox_by_name( 'item_datetime_checkbox' );
	$item_row = $this->_factory_create_class->build_item_row_modify_post( 
		$item_row, $checkbox );
	return $item_row;
}

//---------------------------------------------------------
// build form delete confirm
//---------------------------------------------------------
function build_form_delete_confirm_with_template( $item_row )
{
	$template = 'db:'. $this->_DIRNAME .'_form_confirm.html';

	$arr = array_merge( 
		$this->_admin_item_form_class->build_form_base_param() ,
		$this->build_form_delete_confirm( $item_row ) 
	);

	$tpl = new XoopsTpl() ;
	$tpl->assign( $arr ) ;
	return $tpl->fetch( $template ) ;
}

function build_form_delete_confirm( $item_row )
{
	$src    = null ;
	$width  = 0 ;
	$height = 0 ;

	$image = $this->_show_image_class->build_image_by_item_row( $item_row, true ) ;
	if ( is_array($image) ) {
		$src    = $image['img_thumb_src'] ;
		$width  = $image['img_thumb_width'] ;
		$height = $image['img_thumb_height'] ;
	}

	$param = array(
		'thumb_src_s'  => $this->sanitize( $src ) ,
		'thumb_width'  => $width ,
		'thumb_height' => $height ,
		'item_id'      => $item_row['item_id'] ,
		'item_title_s' => $this->sanitize( $item_row['item_title'] ) ,
	);

	return $param;
}

//---------------------------------------------------------
// modify
//---------------------------------------------------------
function modify( $item_row )
{
	$this->get_post_param();
	$ret1 = $this->modify_exec( $item_row );

	if ( $this->_is_video_thumb_form ) {
		return _C_WEBPHOTO_RET_VIDEO_FORM ;
	}

	$ret2 = $this->build_failed_msg( $ret1 );
	if ( !$ret2 ) {
		return _C_WEBPHOTO_RET_ERROR ;
	}

	return _C_WEBPHOTO_RET_SUCCESS ;
}

function get_updated_row()
{
	return $this->_row_update ;
}

function modify_exec( $item_row )
{
// save
	$this->_row_update = $item_row ;

	$photo_tmp_name = null;
	$thumb_tmp_name = null;
	$image_info     = null;

	$file_id_array  = null ;
	$cont_id        = 0 ;

	$this->clear_msg_array();

	$item_row  = $this->build_item_row_modify_post( $item_row );
	$item_id   = $item_row['item_id'] ;
	$item_kind = $item_row['item_kind'] ;

	switch ( $item_kind )
	{
// embed
// playlist
		case _C_WEBPHOTO_ITEM_KIND_EMBED :
		case _C_WEBPHOTO_ITEM_KIND_PLAYLIST_FEED :
		case _C_WEBPHOTO_ITEM_KIND_PLAYLIST_DIR  :
			break;

// upload
		default:
			if ( ! $this->check_xoops_upload_file( $flag_thumb=true ) ) {
				return _C_WEBPHOTO_ERR_NO_SPECIFIED;
			}
			$ret = $this->upload_fetch_photo( true );
			if ( $ret < 0 ) { 
				return $ret;	// failed
			}
			break;
	}

	$this->upload_fetch_thumb();
	$this->upload_fetch_middle();
	$this->upload_fetch_small();

	$photo_name  = $this->_photo_tmp_name;
	$thumb_name  = $this->_thumb_tmp_name;
	$middle_name = $this->_middle_tmp_name;
	$small_name  = $this->_small_tmp_name;

// no upload
	if ( empty($photo_name) && empty($thumb_name) && empty($middle_name) && empty($small_name) ) {
		return $this->update_photo_no_image( $item_row );
	}

// ext kind exif duration
	if ( $photo_name ) {
		$item_row = $this->_factory_create_class->build_item_row_photo( 
			$item_row, $photo_name, $this->_photo_media_name );
	}

	$ret = $this->create_media_file_params( $item_row, $is_submit=false );
	if ( $ret < 0 ) {
		return $ret;
	}

// --- update files
	$file_id_array = $this->_factory_create_class->update_files_from_params( 
		$item_row, $this->_media_file_params );

// files content search
	$item_row = $this->_factory_create_class->build_item_row_modify_update( 
		$item_row, $file_id_array, $this->_tag_name_array ) ;

// --- update item
	$ret = $this->_item_handler->update( $item_row );
	if ( !$ret ) {
		$this->set_error( $this->_item_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB;
	}

	$this->update_all_file_duration_if_not_cont( $item_row, $file_id_array );
	$this->tag_handler_update_tags( $item_id, $this->get_tag_name_array() );
	$this->notify_new_photo_if_appove( $item_row );

// save
	$this->_row_update = $item_row ;

	return 0;
}

function update_all_file_duration_if_not_cont( $item_row, $file_id_array )
{
	$cont_id = $this->get_array_value_by_key( $file_id_array, 'cont_id' );
	if ( $cont_id == 0 ) {
		$this->update_all_file_duration( $item_row );
	}
}

function get_array_value_by_key( $array, $key )
{
	return intval( 
		$this->_utility_class->get_array_value_by_key( $array, $key, 0 ) ) ;
}

//---------------------------------------------------------
// update_photo_no_image
//---------------------------------------------------------
function update_photo_no_image( $item_row )
{
	$this->update_all_file_duration( $item_row );

// --- update item
// search
	$item_row = $this->_factory_create_class->build_item_row_modify_update( 
		$item_row, null, $this->_tag_name_array ) ;
	$ret = $this->_item_handler->update( $item_row );
	if ( !$ret ) {
		$this->set_error( $this->_item_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB;
	}

	$this->tag_handler_update_tags( $item_row['item_id'] , $this->_tag_name_array );
	$this->notify_new_photo_if_appove( $item_row );

// save
	$this->_row_update = $item_row ;

	return 0;
}

function update_all_file_duration( $item_row )
{
	$duration = $item_row['item_duration'] ;

	$cont_duration = 0 ; 
	$cont_row = $this->get_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_CONT );
	if ( is_array($cont_row) ) {
		$cont_duration = $cont_row['file_duration'] ;
	}

	if ( $duration != $cont_duration ) {
		$this->update_file_duration( $duration, $item_row, _C_WEBPHOTO_FILE_KIND_CONT );
		$this->update_file_duration( $duration, $item_row, _C_WEBPHOTO_FILE_KIND_VIDEO_FLASH );
		$this->update_file_duration( $duration, $item_row, _C_WEBPHOTO_FILE_KIND_VIDEO_DOCOMO );
	}
}

function update_file_duration( $duration, $item_row, $kind )
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

//---------------------------------------------------------
// delete
//---------------------------------------------------------
function delete( $item_row )
{
	$err  = null;
	$ret1 = $this->delete_exec( $item_row );

	$ret2 = $this->build_failed_msg( $ret1 );
	if ( !$ret2 ) {
		return false ;
	}

	return true ;
}

function delete_exec( $item_row )
{
	if ( ! $this->_has_deletable ) {
		return _C_WEBPHOTO_ERR_NO_PERM;
	}

	if ( ! $this->check_edit_perm( $item_row ) ) {
		return _C_WEBPHOTO_ERR_NO_PERM;
	}

	$ret = $this->_delete_class->delete_photo_by_item_row( $item_row );
	if ( !$ret ) {
		$this->set_error( $delete_class->get_errors() );
		return _C_WEBPHOTO_ERR_DB;
	}

	if ( $this->is_waiting_status( $item_row['item_status'] ) ) {
		$this->mail_refuse( $item_row );
	}

	return 0;
}

//---------------------------------------------------------
// video redo
//---------------------------------------------------------
function video_redo( $item_row )
{
	$flag_thumb = $this->_post_class->get_post_int('redo_thumb' );
	$flag_flash = $this->_post_class->get_post_int('redo_flash' );

	$ret1 = $this->video_redo_exec( $item_row, $flag_thumb, $flag_flash ) ;

	if ( $this->_is_video_thumb_form ) {
		return _C_WEBPHOTO_RET_VIDEO_FORM ;
	}

	$ret2 = $this->build_failed_msg( $ret1 );
	if ( !$ret2 ) {
		return _C_WEBPHOTO_RET_ERROR ;
	}

	return _C_WEBPHOTO_RET_SUCCESS ;
}

function video_redo_exec( $item_row, $flag_thumb, $flag_flash )
{
	$this->clear_msg_array();

	$this->_is_video_thumb_form = false;
	$flash_param = null;

	$item_id   = $item_row['item_id'];
	$item_ext  = $item_row['item_ext'];
	$item_kind = $item_row['item_kind'];

	$cont_file  = null ;
	$flash_file = null ;
	$param      = null ;

	$cont_row = $this->get_file_row_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_CONT ) ;
	if ( is_array($cont_row) ) {
		$cont_path     = $cont_row['file_path'];
		$cont_width    = $cont_row['file_width'];
		$cont_height   = $cont_row['file_height'];
		$cont_duration = $cont_row['file_duration'];
		$cont_file     = XOOPS_ROOT_PATH . $cont_path ;

		$param = $item_row ;
		$param['src_file'] = $cont_file ;
		$param['src_kind'] = $item_kind ;
		$param['src_ext']  = $item_ext  ;

	}

	$flash_row = $this->get_file_row_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_VIDEO_FLASH ) ;
	if ( is_array($flash_row) ) {
		$flash_path = $flash_row['file_path'];
		$flash_file = XOOPS_ROOT_PATH . $flash_path ;
	}

	$flash_tmp_file = $this->_TMP_DIR .'/tmp_' . uniqid( $item_id.'_' ) ;

// create flash
	if ( $flag_flash && is_array($param) ) {
// save file
		$this->rename_file( $flash_file, $flash_tmp_file );

		$flash_param = $this->create_flash_param( $param );

		if ( is_array($flash_param) ) {
// remove file if success
			$this->unlink_file( $flash_tmp_file );

		} else {
// recovery file if fail
			$this->rename_file( $flash_tmp_file, $flash_file );
		}
	}

// create video thumb
	if ( $flag_thumb && is_array($param) ) {
		$this->create_video_plural_images( $param ) ;
	}

// update
	$row_update = $item_row ;

	if ( is_array($flash_param) ) {
		$flash_id = $this->_factory_create_class->insert_file( $item_id, $flash_param );

// success
		if ( $flash_id > 0 ) {
			$row_update[ _C_WEBPHOTO_ITEM_FILE_VIDEO_FLASH ] = $flash_id ;
			$row_update['item_displaytype'] = _C_WEBPHOTO_DISPLAYTYPE_MEDIAPLAYER ;

			$ret = $this->_item_handler->update( $row_update );
			if ( !$ret ) {
				$this->set_error( $this->_item_handler->get_errors() );
				return _C_WEBPHOTO_ERR_DB;
			}

// fail
		} else {
			$this->set_error( $this->_factory_create_class->get_errors() );
		}
	}

// save
	$this->_row_update = $row_update ;

	return 0;
}

//---------------------------------------------------------
// file delete
//---------------------------------------------------------
function video_flash_delete( $item_row, $url_redirect )
{
	$this->file_delete_common(
		$item_row, _C_WEBPHOTO_ITEM_FILE_VIDEO_FLASH, $url_redirect, false );

	$item_id  = $item_row['item_id'] ;
	$item_row['item_displaytype'] = _C_WEBPHOTO_DISPLAYTYPE_GENERAL ;

	$ret = $this->_item_handler->update( $item_row );
	if ( !$ret ) {
		$msg  = "DB Error <br />\n" ;
		$msg .= $this->_item_handler->get_format_error() ;
		redirect_header( $url_redirect, $this->_TIME_FAILED, $msg );
		exit();
	}

	redirect_header( $url_redirect, $this->_TIME_SUCCESS, $this->get_constant('DELETED') );
	exit();
}

function thumb_delete( $item_row, $url_redirect )
{
	$this->file_delete_common( 
		$item_row, _C_WEBPHOTO_ITEM_FILE_THUMB, $url_redirect, true );
}

function middle_delete( $item_row, $url_redirect )
{
	$this->file_delete_common( 
		$item_row, _C_WEBPHOTO_ITEM_FILE_MIDDLE, $url_redirect, true );
}

function small_delete( $item_row, $url_redirect )
{
	$this->file_delete_common( 
		$item_row, _C_WEBPHOTO_ITEM_FILE_SMALL, $url_redirect, true );
}

function file_delete_common( $item_row, $item_name, $url_redirect, $flag_redirect )
{
	$item_id = $item_row['item_id'] ;
	$file_id = $item_row[ $item_name ] ;
	$error   = '' ;

	$file_row = $this->_file_handler->get_row_by_id( $file_id );
	if ( ! is_array($file_row ) ) {
		redirect_header( $url, $this->_TIME_FAILED, 'No file record' ) ;
		exit() ;
	}

	$this->unlink_path( $file_row['file_path'] );

	$ret = $this->_file_handler->delete_by_id( $file_id );
	if ( !$ret ) {
		$error .= $this->_file_handler->get_format_error() ;
	}

// BUG: not clear file id when delete file
	$item_row[ $item_name ] = 0 ;
	$ret = $this->_item_handler->update( $item_row );
	if ( !$ret ) {
		$error .= $this->_item_handler->get_format_error() ;
	}

	if ( $error ) {
		$msg  = "DB Error <br />\n". $error ;
		redirect_header( $url_redirect, $this->_TIME_FAILED, $msg );
		exit();
	}

	if ( $flag_redirect ) {
		redirect_header( $url_redirect, $this->_TIME_SUCCESS, $this->get_constant('DELETED') );
		exit();
	}

	return true;
}

//---------------------------------------------------------
// tag class
//---------------------------------------------------------
function tag_handler_update_tags( $item_id, $tag_name_array )
{
	return $this->_tag_class->update_tags( $item_id, $this->_xoops_uid, $tag_name_array );
}

function tag_handler_tag_name_array( $item_id )
{
	return $this->_tag_class->get_tag_name_array_by_photoid_uid( $item_id, $this->_xoops_uid );
}

//---------------------------------------------------------
// notify
//---------------------------------------------------------
function notify_new_photo_if_appove( $item_row )
{
	if ( $this->is_apporved_status( $item_row['item_status'] ) ) {
		$this->notify_new_photo( $item_row );
		$this->mail_approve( $item_row );
	}
}

function is_apporved_status( $status )
{
	if ( $status == _C_WEBPHOTO_STATUS_APPROVED ) {
		return true;
	}
	return false;
}

function is_waiting_status( $status )
{
	if ( $status == _C_WEBPHOTO_STATUS_WAITING ) {
		return true;
	}
	return false;
}

function mail_approve( $row )
{
	return $this->mail_common( 
		$row, 
		_AM_WEBPHOTO_MAIL_SUBMIT_APPROVE, 
		'submit_approve_notify.tpl' );
}

function mail_refuse( $row )
{
	return $this->mail_common( 
		$row, 
		_AM_WEBPHOTO_MAIL_SUBMIT_REFUSE, 
		'submit_refuse_notify.tpl' );
}

function mail_common( $row, $subject, $template )
{
	$email = $this->get_xoops_email_by_uid( $row['item_uid'] );
	if ( empty($email) ) {
		return true;	// no action
	}

	$param = array(
		'to_emails'  => $email ,
		'from_email' => $this->_xoops_adminmail ,
		'subject'    => $this->build_mail_subject( $subject ) ,
		'body'       => $this->build_mail_body( $row, $template ),
		'debug'      => true,
	);

	$ret = $this->_mail_send_class->send( $param );
	if ( !$ret ) {
		$this->set_error( $this->_mail_send_class->get_errors() );
		return false;
	}
	return true;
}

function build_mail_subject( $subject )
{
	$str  = $subject ;
	$str .= ' ['. $this->_xoops_sitename .'] ';
	$str .= $this->_MODULE_NAME ;
	return $str;
}

function build_mail_body( $row, $template )
{
	$tags = array(
		'PHOTO_TITLE' => $row['item_title'] ,
		'PHOTO_URL'   => $this->build_uri_photo( $row['item_id'] ),
		'PHOTO_UNAME' => $this->get_xoops_uname_by_uid( $row['item_uid'] ),
	);

	$this->_mail_template_class->init_tag_array();
	$this->_mail_template_class->assign( $tags );
	return $this->_mail_template_class->replace_tag_array_by_template( $template );
}

// --- class end ---
}

?>