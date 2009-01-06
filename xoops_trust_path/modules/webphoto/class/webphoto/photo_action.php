<?php
// $Id: photo_action.php,v 1.12 2009/01/06 09:41:35 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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
// class webphoto_photo_action
//=========================================================
class webphoto_photo_action extends webphoto_photo_submit
{
	var $_show_image_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_photo_action( $dirname , $trust_dirname )
{
	$this->webphoto_photo_submit( $dirname , $trust_dirname );

	$this->_show_image_class =& webphoto_show_image::getInstance( $dirname );
}

// for admin_photo_manage admin_catmanager
function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_photo_action( $dirname , $trust_dirname );
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
function build_modify_row_by_post( $item_row, $flag_default=false )
{
	$item_id = $item_row['item_id'] ;

	$post_preview               = $this->_post_class->get_post_text('preview');
	$post_submit                = $this->_post_class->get_post_text('submit' );
	$post_time_update_checkbox  = $this->_post_class->get_post_int( 'item_time_update_checkbox' );
	$post_time_publish_checkbox = $this->_post_class->get_post_int( 'item_time_publish_checkbox' );
	$post_time_expire_checkbox  = $this->_post_class->get_post_int( 'item_time_expire_checkbox' );
	$post_time_update           = $this->get_server_time_by_post('item_time_update' );
	$post_time_publish          = $this->get_server_time_by_post('item_time_publish' );
	$post_time_expire           = $this->get_server_time_by_post('item_time_expire' );

	$publish_checkbox = _C_WEBPHOTO_NO ;
	$expire_checkbox  = _C_WEBPHOTO_NO ;
	if ( $item_row['item_time_publish'] > 0 ) {
		$publish_checkbox = _C_WEBPHOTO_YES ;
	}
	if ( $item_row['item_time_expire'] > 0 ) {
		$expire_checkbox = _C_WEBPHOTO_YES ;
	}

	if ( $flag_default ) {
		$this->set_checkbox_by_name( 'item_datetime_checkbox',     _C_WEBPHOTO_YES );	
		$this->set_checkbox_by_name( 'item_time_update_checkbox',  _C_WEBPHOTO_YES );
		$this->set_checkbox_by_name( 'item_time_publish_checkbox', $publish_checkbox );
		$this->set_checkbox_by_name( 'item_time_expire_checkbox',  $expire_checkbox );
		$this->set_tag_name_array( $this->tag_handler_tag_name_array( $item_id ) );
	}

	if ( $post_preview || $post_submit ) {

		$item_row = $this->build_row_by_post( $item_row );

// admin
		if ( $this->_FLAG_ADMIN ) {
			if ( $post_time_update_checkbox ) {
				$item_row['item_time_update'] = $post_time_update ;
			}

			$time_publish = 0 ;
			$time_expire  = 0 ;
			if ( $post_time_publish_checkbox ) {
				$time_publish = $post_time_publish ;
			}
			if ( $post_time_expire_checkbox ) {
				$time_expire = $post_time_expire ;
			}
			$item_row['item_time_publish'] = $time_publish ;
			$item_row['item_time_expire']  = $time_expire ;

// user
		} else {
			$item_row['item_time_update'] = time();
		}

	}

	return $item_row;
}

//---------------------------------------------------------
// print form video thumb
//---------------------------------------------------------
function print_form_video_thumb( $mode, $item_row )
{
	if ( $this->has_msg_array() ) {
		echo $this->get_format_msg_array() ;
		echo "<br />\n";
	}

	$form_class =& webphoto_photo_misc_form::getInstance(
		$this->_DIRNAME , $this->_TRUST_DIRNAME );
	$form_class->print_form_video_thumb( $mode, $item_row );
}

//---------------------------------------------------------
// print form video thumb
//---------------------------------------------------------
function print_form_delete_confirm( $mode, $item_row )
{
	$img_tag = $this->_show_image_class->build_img_tag_by_item_row( $item_row ) ;

	echo '<h4>'. $this->get_constant('TITLE_PHOTODEL') ."</h4>\n";
	echo '<b>'. $this->sanitize( $item_row['item_title'] ) ."<b><br />\n";

	if ( $img_tag ) {
		echo $img_tag ;
	}

	echo "<br />\n";

	$form_class =& webphoto_photo_misc_form::getInstance(
		$this->_DIRNAME , $this->_TRUST_DIRNAME );

// BUG: return to admin when delete
	$form_class->print_form_delete_confirm( $mode, $item_row['item_id'] );
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

	$item_id  = $item_row['item_id'] ;

// Check if upload file name specified
	if ( $this->is_upload_type() && !$this->check_xoops_upload_file() ) {
		return _C_WEBPHOTO_ERR_NO_SPECIFIED;
	}

	if ( $this->is_embed_type() ) {
		// dummy

	} elseif ( $this->is_admin_playlist_type() ) {
		// dummy

	} else {
		$ret11 = $this->upload_fetch_photo( true );
		if ( $ret11 < 0 ) { 
			return $ret11;	// failed
		}
	}

	$this->upload_fetch_thumb();
	$this->upload_fetch_middle();

	$photo_name  = $this->_photo_tmp_name;
	$thumb_name  = $this->_thumb_tmp_name;
	$middle_name = $this->_middle_tmp_name;

// no upload
	if ( empty($photo_name) && empty($thumb_name) && empty($middle_name) ) {
		return $this->update_photo_no_image( $item_row );
	}

	$ret12 = $this->create_photo_thumb(
		$item_row, $photo_name, $thumb_name, $middle_name, false );
	if ( $ret12 < 0 ) {
		return $ret12; 
	}

	$file_params = $this->get_file_params();

	if ( is_array($file_params) ) {
		$file_id_array = $this->update_files_from_params( $item_row, $file_params );
		$cont_id   = $this->get_array_value_by_key( $file_id_array, 'cont_id' );
	}

	$row_update = $this->build_update_row_by_post( $item_row );

	$playlist_cache = $this->get_playlist_cache_if_empty( $row_update );

	$row_update = $this->_photo_class->build_update_item_row( 
		$row_update, $file_id_array, $playlist_cache );

// update all file tables
	if ( $cont_id == 0 ) {
		$this->update_all_file_duration( $item_row );
	}

	$ret = $this->_item_handler->update( $row_update );
	if ( !$ret ) {
		$this->set_error( $this->_item_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB;
	}

	$this->tag_handler_update_tags( $item_id, $this->get_tag_name_array() );

// when approve
	if ( $this->is_apporved_status( $row_update['item_status'] ) ) {
		$this->notify_new_photo( $row_update );
	}

// save
	$this->_row_update = $row_update;

	return 0;
}

function get_array_value_by_key( $array, $key )
{
	return intval( 
		$this->_utility_class->get_array_value_by_key( $array, $key, 0 ) ) ;
}

function build_update_row_by_post( $item_row )
{
	$row_update = $this->build_modify_row_by_post( $item_row, false );

	$row_update['item_status'] = 
		$this->build_modify_status( $row_update );

	$row_update['item_search'] = $this->build_search_for_edit( 
		$row_update, $this->get_tag_name_array() );

	return $row_update;
}

function build_modify_status( $item_row )
{
	$post_valid  = $this->_post_class->get_post_int('valid');
	$post_status = $this->_post_class->get_post_int('item_status');

	$current_status = $item_row['item_status'] ;
	$time_publish   = $item_row['item_time_publish'] ;

	if ( $this->_FLAG_ADMIN ) {
		$new_status = $post_status ;
	} else {
		$new_status = $current_status ;
	}

	switch ( $current_status ) 
	{
		case _C_WEBPHOTO_STATUS_WAITING : 
			if ( $this->_FLAG_ADMIN && ( $post_valid == _C_WEBPHOTO_YES ) )  {
				$new_status = _C_WEBPHOTO_STATUS_APPROVED ;
			}
			break;

		case _C_WEBPHOTO_STATUS_APPROVED : 
			$new_status = _C_WEBPHOTO_STATUS_UPDATED ;
			break;

		case _C_WEBPHOTO_STATUS_UPDATED :
		case _C_WEBPHOTO_STATUS_OFFLINE :
		case _C_WEBPHOTO_STATUS_EXPIRED :
		default:
			break;
	}

	switch ( $new_status ) 
	{
		case _C_WEBPHOTO_STATUS_APPROVED : 
		case _C_WEBPHOTO_STATUS_UPDATED :
			if (   $this->_FLAG_ADMIN  &&
			     ( $time_publish > 0 ) &&
				 ( $time_publish > time() ) ) {
				$new_status = _C_WEBPHOTO_STATUS_OFFLINE ;
			}
			break;

		case _C_WEBPHOTO_STATUS_WAITING : 
		case _C_WEBPHOTO_STATUS_OFFLINE :
		case _C_WEBPHOTO_STATUS_EXPIRED :
		default:
			break;
	}

	return $new_status;
}

function is_apporved_status( $status )
{
	if ( $status == _C_WEBPHOTO_STATUS_APPROVED ) {
		return true;
	}
	return false;
}

function update_files_from_params( $item_row, $params )
{
	if ( !is_array($params) ) {
		return false;
	}

	$arr = array(
		'cont_id'   => $this->update_file_by_params( $item_row, $params, 'cont' ) ,
		'thumb_id'  => $this->update_file_by_params( $item_row, $params, 'thumb' ) ,
		'middle_id' => $this->update_file_by_params( $item_row, $params, 'middle' ) ,
		'flash_id'  => $this->update_file_by_params( $item_row, $params, 'flash' ) ,
		'docomo_id' => $this->update_file_by_params( $item_row, $params, 'docomo' ) ,
	);
	return $arr ;
}

function update_file_by_params( $item_row, $params, $name )
{
	$item_id = $item_row['item_id'] ;

	if ( ! isset( $params[ $name ] ) ) {
		return 0 ;
	}

	$param = $params[ $name ] ;

	if ( ! is_array($param) ) {
		return 0 ;
	}

	$file_row = $this->get_file_row_by_kind( $item_row, $param['kind'] );

// update if exists
	if ( is_array($file_row) ) {
		$file_id = $file_row['file_id'];

// remove old file
		$this->_photo_class->unlink_current_file( $file_row, $param );

		$ret = $this->_photo_class->update_file( $file_row, $param );
		if ( !$ret ) {
			$this->set_error( $this->_photo_class->get_errors() );
			return 0 ;
		}
		return $file_id;

// insert if new
	} else {
		$newid = $this->_photo_class->insert_file( $item_id, $param );
		if ( !$newid) {
			$this->set_error( $this->_photo_class->get_errors() );
		}
		return $newid ;
	}
}

//---------------------------------------------------------
// update_photo_no_image
//---------------------------------------------------------
function update_photo_no_image( $item_row )
{
	$this->update_all_file_duration( $item_row );

	$row_update = $this->build_update_row_by_post( $item_row );

	$ret = $this->_item_handler->update( $row_update );
	if ( !$ret ) {
		$this->set_error( $this->_item_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB;
	}

	$this->tag_handler_update_tags( $item_row['item_id'] , $this->get_tag_name_array() );

// when approve
	if ( $this->is_apporved_status( $row_update['item_status'] ) ) {
		$this->notify_new_photo( $row_update );
	}

// save
	$this->_row_update = $row_update;

	return 0;
}

function update_all_file_duration( $item_row )
{
	$duration = $this->_item_duration ;

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

	$delete_class =& webphoto_photo_delete::getInstance( $this->_DIRNAME );

	$ret = $delete_class->delete_photo_by_item_row( $item_row );
	if ( !$ret ) {
		$this->set_error( $delete_class->get_errors() );
		return _C_WEBPHOTO_ERR_DB;
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

		$param                = array() ;
		$param['src_file']    = $cont_file ;
		$param['src_kind']    = $item_kind ;
		$param['video_param'] = array(
			'width'    => $cont_width ,
			'height'   => $cont_height ,
			'duration' => $cont_duration ,
		);
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
	if ( $flag_thumb && $this->_cfg_makethumb && $cont_file ) {

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
			$row_update[ _C_WEBPHOTO_ITEM_FILE_VIDEO_FLASH ] = $flash_id ;
			$row_update['item_displaytype'] = _C_WEBPHOTO_DISPLAYTYPE_MEDIAPLAYER ;

			$ret = $this->_item_handler->update( $row_update );
			if ( !$ret ) {
				$this->set_error( $this->_item_handler->get_errors() );
				return _C_WEBPHOTO_ERR_DB;
			}
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

function file_delete_common( $item_row, $item_name, $url_redirect, $flag_redirect )
{
	$item_id = $item_row['item_id'] ;
	$file_id = $item_row[ $item_name ] ;

	$file_row = $this->_file_handler->get_row_by_id( $file_id );
	if ( ! is_array($file_row ) ) {
		redirect_header( $url, $this->_TIME_FAILED, 'No file record' ) ;
		exit() ;
	}

	$this->unlink_path( $file_row['file_path'] );

	$ret = $this->_file_handler->delete_by_id( $file_id );
	if ( !$ret ) {
		$msg  = "DB Error <br />\n" ;
		$msg .= $this->_file_handler->get_format_error() ;
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

// --- class end ---
}

?>