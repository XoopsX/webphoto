<?php
// $Id: submit.php,v 1.5 2008/08/08 04:36:09 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-06 K.OHWADA
// used webphoto_xoops_user
// used update_video_thumb()
// not use msg_class
// 2008-08-05 K.OHWADA
// BUG: cannot preview
// 2008-07-01 K.OHWADA
// added _exec_video()
// used  build_uri_category()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_submit
//=========================================================
class webphoto_main_submit extends webphoto_photo_edit
{
	var $_notification_class;
	var $_xoops_user_class;

	var $_has_insertable  = false;
	var $_has_superinsert = false;

	var $_is_preview  = false;
	var $_created_row = null;

	var $_REDIRECT_URL;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_submit( $dirname , $trust_dirname )
{
	$this->webphoto_photo_edit( $dirname , $trust_dirname );

	$this->_xoops_user_class   =& webphoto_xoops_user::getInstance();
	$this->_notification_class =& webphoto_notification_event::getInstance(
		$dirname , $trust_dirname );

	$this->_has_insertable  = $this->_perm_class->has_insertable();
	$this->_has_superinsert = $this->_perm_class->has_superinsert();

// overwrite by submit_imagemanager
	$this->_REDIRECT_URL  = $this->_MODULE_URL .'/index.php?fct=submit';

	$this->init_preload();
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance))  {
		$instance = new webphoto_main_submit( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function check_submit()
{
	$this->_check();

// BUG: cannot preview
	switch ( $this->_get_action() ) 
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
		$this->get_constant('TITLE_ADDPHOTO'), $this->_REDIRECT_URL );

	if ( $this->_is_video_thumb_form ) {
		$this->_print_form_video_thumb();
	} else {
		if ( $this->_is_preview ) {
			$row = $this->_preview();
		} else {
			$row = $this->_get_photo_default();
		}
		$this->_print_form_submit( $row );
	}
}

// BUG: cannot preview
function _get_action()
{
	$this->_is_preview = false;

	$preview = $this->_post_class->get_post_text( 'preview' );
	$op      = $this->_post_class->get_post_text('op');
	if ( $preview ) {
		$this->_is_preview = true;
		return 'preview';
	}
	return $op;
}

//---------------------------------------------------------
// check 
//---------------------------------------------------------
function _check()
{
	$this->get_post_param();

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
	if ( ! $this->_has_insertable )   {
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
	$msg = null;
	$ret = $this->_exec_submit();

	if ( $this->_is_video_thumb_form ) {
		return;
	}

	switch ( $ret )
	{
		case _C_WEBPHOTO_ERR_EMPTY_CAT:
			$msg = $this->get_constant('ERR_EMPTY_CAT') ;
			break;

		case _C_WEBPHOTO_ERR_INVALID_CAT:
			$msg = $this->get_constant('ERR_INVALID_CAT') ;
			break;

		case _C_WEBPHOTO_ERR_NO_SPECIFIED:
			$msg = 'UPLOAD error: file name not specified';
			break;

		case _C_WEBPHOTO_ERR_DB:
			$msg = 'DB Error';
			if ( $this->_is_module_admin ) {
				$msg .= '<br />'.$this->get_format_error();
			}
			break;

		case _C_WEBPHOTO_ERR_UPLOAD;
			$msg  = 'File Upload Error';
			$msg .= '<br />'.$this->get_format_error( false );
			break;

		case _C_WEBPHOTO_ERR_FILE:
			$msg = $this->get_constant('ERR_FILE') ;
			break;

		case _C_WEBPHOTO_ERR_NO_IMAGE;
			$msg = $this->get_constant('ERR_NOIMAGESPECIFIED') ;
			break;

		case _C_WEBPHOTO_ERR_FILEREAD:
			$msg = $this->get_constant('ERR_FILEREAD') ;
			break;

		case _C_WEBPHOTO_ERR_NO_TITLE:
			$msg = $this->get_constant('ERR_TITLE') ;
			break;

		case 0:
		default:
			break;
	}

	if ( $msg ) {
		redirect_header( $this->_REDIRECT_URL, $this->_TIME_FAIL, $msg );
		exit();
	}

	$this->submit_success();
	exit();
}

function _exec_submit()
{
	$photo_tmp_name = null;
	$thumb_tmp_name = null;

	$cfg_allownoimage = $this->_config_class->get_by_name( 'allownoimage' );

	$this->clear_msg_array();

	$ret = $this->_check_submit();
	if ( $ret < 0 ) {
		return $ret;
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
	if ( empty( $photo_tmp_name ) && empty( $thumb_tmp_name ) ) {

// preview
		if ( $this->is_readable_preview() ) {
			$photo_tmp_name = $this->get_preview_name() ;

// check title
		} elseif ( ! $this->is_fill_photo_title() ) {
			return _C_WEBPHOTO_ERR_NO_TITLE;

// check allow no image mode
		} elseif( !$cfg_allownoimage ) {
			return _C_WEBPHOTO_ERR_NO_IMAGE;
		}
	}

	return $this->_add_to_handler( $photo_tmp_name, $thumb_tmp_name );
}

function _check_submit()
{

// Check if cid is valid
	if ( empty( $this->_post_photo_catid ) ) {
		return _C_WEBPHOTO_ERR_EMPTY_CAT ;
	}

	if ( ! $this->check_valid_catid( $this->_post_photo_catid ) ) {
		return _C_WEBPHOTO_ERR_INVALID_CAT ;
	}

// Check if upload file name specified
	if ( ! $this->check_xoops_upload_file_submit() ) {
		return _C_WEBPHOTO_ERR_NO_SPECIFIED;
	}

	return 0;
}

function _add_to_handler( $photo_tmp_name, $thumb_tmp_name )
{
	$ret13 = $this->_photo_handler_add();
	if ( !$ret13 ) {
		return _C_WEBPHOTO_ERR_DB;
	}

	$newid = $this->_created_row['photo_id'];

	$ret14 = $this->create_photo_thumb( $newid, $photo_tmp_name, $thumb_tmp_name );
	if ( $ret14 < 0 ) { return $ret14; }

	$info = $this->get_photo_thumb_info();
	if ( is_array($info) && count($info) ) {
		$row_update = array_merge( $this->_created_row, $info );
		$ret15 = $this->_photo_handler->update( $row_update );
		if ( !$ret15 ) {
			$this->set_error( $this->_photo_handler->get_errors() );
			return _C_WEBPHOTO_ERR_DB;
		}
	}

	$ret16 = $this->tag_handler_add_tags( $newid, $this->get_tag_name_array() );
	if ( !$ret16 ) { 
		return _C_WEBPHOTO_ERR_DB; 
	}

	$cfg_addposts = $this->_config_class->get_by_name( 'addposts' );
	$this->_xoops_user_class->increment_post_by_num_own( $cfg_addposts );

// Trigger Notification when supper insert
	if ( $this->_get_new_status() ) {
		$this->_notification_class->notify_new_photo( 
			$newid, $this->_post_photo_catid, $this->get_photo_title() );
	}

	return 0;
}

function _get_photo_tmp_name()
{
	$this->_photo_tmp_name;
}

function _get_thumb_tmp_name()
{
	$this->_thumb_tmp_name;
}

//---------------------------------------------------------
// overwrite by submit_imagemanager
//---------------------------------------------------------
function submit_success()
{
	if ( $this->_get_new_status() ) {
		$param = array(
			'orderby' => 'dated'
		);
		$url  = $this->build_uri_category( $this->_post_photo_catid, $param );
		$time = $this->_TIME_SUCCESS ;
		$msg  = '';

		if ( $this->has_msg_array() ) {
			$msg .= $this->get_format_msg_array() ;
			$msg .= "<br />\n";
			$time = $this->_TIME_PENDING ;
		}

		$msg .= $this->get_constant('SUBMIT_RECEIVED') ;

	} else {
		$url  = $this->build_uri_operate( 'latest' );
		$time = $this->_TIME_PENDING ;
		$msg  = $this->get_constant('SUBMIT_ALLPENDING') ;
	}

	redirect_header( $url, $time , $msg ) ;
	exit();
}

function check_xoops_upload_file_submit()
{
	return $this->check_xoops_upload_file( true );
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
			redirect_header( $this->_REDIRECT_URL, $this->_TIME_FAIL, $msg ) ;
			exit();

	}

	$this->submit_success();
}

//---------------------------------------------------------
// photo_handler
//---------------------------------------------------------
function _create_by_post()
{
	$row = $this->_photo_handler->create( true );

	$row_post = $this->build_row_by_post( $row );

	$row_post['photo_cat_id']    = $this->_post_photo_catid;
	$row_post['photo_uid']       = $this->_xoops_uid;

	return $row_post;
}

function _photo_handler_add()
{
	$row = $this->_create_by_post();

	$row['photo_status'] = $this->_get_new_status();
	$row['photo_search'] = $this->build_search_for_edit( $row, $this->get_tag_name_array() );

	$newid = $this->_photo_handler->insert( $row );
	if ( !$newid ) {
		$this->set_error( $this->_photo_handler->get_errors() );
		return false;
	}

	$row['photo_id'] = $newid;
	$this->_created_row = $row;

	return true;
}

function _get_new_status()
{
	return intval( $this->_has_superinsert );
}

//---------------------------------------------------------
// preview
//---------------------------------------------------------
function _preview()
{
	if ( $this->is_readable_new_photo() ) {
		$image_info = $this->_preview_new();

	} elseif ( $this->is_readable_preview() ) {
		// old preview
		$image_info = $this->_preview_old();

	} else {
		// preview without image
		$image_info = $this->_preview_no_image();
	}

	// Display Preview
	$row = $this->_create_by_post();

	$show1 = $this->show_build_preview_submit( $row, $this->get_tag_name_array() );
	$show2 = array_merge( $show1, $image_info );

	echo $this->build_preview_template( $show2 );

	if ( $row['photo_datetime'] ) {
		$this->set_checkbox_by_name( 'photo_datetime_checkbox', _C_WEBPHOTO_YES );
	} else {
		$row['photo_datetime'] = $this->get_mysql_date_today();
	}

	return $row;
}

function _preview_new()
{
	$ret = $this->upload_fetch_photo( true );
	if ( $ret < 0 ) {
		return $this->_preview_no_image();
	}

	$photo_tmp_name = $this->_photo_tmp_name;

// overwrite preview name
	$this->set_preview_name( str_replace( 
		_C_WEBPHOTO_UPLOADER_PREFIX , 
		_C_WEBPHOTO_UPLOADER_PREFIX_PREV , 
		$photo_tmp_name ) );

	return $this->_image_class->create_preview_new(
		$this->get_preview_name(), $photo_tmp_name );

}

function _preview_old()
{
	return $this->_image_class->build_preview( $this->get_preview_name() ) ;
}

function _preview_no_image()
{
	return $this->_image_class->build_no_image_preview();
}

//---------------------------------------------------------
// default
//---------------------------------------------------------
function _get_photo_default()
{
// set checked
	$this->set_checkbox_by_name( 'photo_datetime_checkbox', _C_WEBPHOTO_NO );

// new row
	$row = $this->_photo_handler->create();
	$row['photo_cat_id']   = $this->_post_photo_catid;
	$row['photo_datetime'] = $this->get_mysql_date_today();

	return $row;
}

//---------------------------------------------------------
// print form
//---------------------------------------------------------
function _print_form_submit( $row )
{
	list ( $types, $allowed_exts ) = $this->_mime_class->get_my_allowed_mimes();

	$param = array(
		'mode'            => 'submit',
		'preview_name'    => $this->get_preview_name(),
		'tag_name_array'  => $this->get_tag_name_array(),
		'checkbox_array'  => $this->get_checkbox_array(),
		'has_resize'      => $this->_has_resize,
		'has_rotate'      => $this->_has_rotate,
		'allowed_exts'    => $allowed_exts ,
	);

	$form_class =& webphoto_photo_edit_form::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );
	$form_class->print_form_common( $row, $param );
}

function _print_form_video_thumb()
{
	$this->print_form_video_thumb_common( 'submit', $this->_created_row );
}

// --- class end ---
}

?>