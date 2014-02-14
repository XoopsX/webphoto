<?php
// $Id: submit.php,v 1.23 2010/11/16 23:43:38 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-11 K.OHWADA
// typo contiune -> continue
// 2010-10-01 K.OHWADA
// buiid_preview_embed()
// 2010-06-06 K.OHWADA
// remove $_TIME_SUCCESS
// 2010-02-15 K.OHWADA
// build_footer_param()
// 2010-01-10 K.OHWADA
// set_flag_css()
// 2009-05-05 K.OHWADA
// _build_form_submit_param() -> build_form_base_param()
// _submit_bulk()
// 2009-04-19 K.OHWADA
// _print_form_default() -> _build_form_default()
// 2009-03-15 K.OHWADA
// change _preview_new()
// 2009-01-10 K.OHWADA
// webphoto_photo_action -> webphoto_edit_submit
// 2009-01-04 K.OHWADA
// webphoto_photo_misc_form
// 2008-11-16 K.OHWADA
//_print_form_error()
// 2008-11-08 K.OHWADA
// remove update_init()
// 2008-11-04 K.OHWADA
// BUG: Fatal error in upload.php
// 2008-10-01 K.OHWADA
// webphoto_photo_action
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
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
class webphoto_main_submit extends webphoto_edit_submit
{
	var $_header_class;

// submit file
	var $_post_file = null ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_submit( $dirname , $trust_dirname )
{
	$this->webphoto_edit_submit( $dirname , $trust_dirname );
	$this->set_fct( 'submit' );
	$this->set_form_mode( 'submit' );

	$this->_header_class 
		=& webphoto_xoops_header::getInstance( $dirname , $trust_dirname );

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
	$ret = 0;
	$action = $this->_get_action();

	$this->_check( $action );

	switch ( $action ) 
	{
		case 'submit':
		case 'submit_bulk':
		case 'submit_file':
			$ret = $this->_submit( $action );
			break;

		case 'video':
			$this->_video();
			exit();
	}

	if ( $ret == _C_WEBPHOTO_RET_VIDEO_FORM ) {
		$this->_form_action = 'form_video_thumb';

	} elseif ( $ret == _C_WEBPHOTO_RET_ERROR ) {
		$this->_form_action = 'form_error';

	} else {
		$this->_form_action = $action;
	}
}

function form_param()
{
	$this->_header_class->set_flag_css( true );
	$this->_header_class->assign_for_main();

	$this->init_form();

	switch ( $this->_form_action )
	{
		case 'form_video_thumb':
			$param = $this->_build_form_video_thumb();
			break;

		case 'form_error':
			$param = $this->_build_form_error() ;
			break;

		case 'preview' :
			$param = $this->_build_form_preview();
			break;

		case 'submit_form':
		case 'bulk_form':
		case 'file_form':
		default:
			$param = $this->_build_form_default( $this->_form_action );
			break;
	}

	$ret = array_merge( $param, $this->build_footer_param() );
	return $ret;
}

function _get_action()
{
	$preview = $this->_post_class->get_post_text( 'preview' );
	$op      = $this->_post_class->get_post_get_text( 'op' );
	if ( $preview ) {
		return 'preview';
	}
	return $op;
}

//---------------------------------------------------------
// check 
//---------------------------------------------------------
function _check( $action )
{
	$this->get_post_param();

	switch ( $action )
	{
		case 'file_form':
		case 'submit_file':
			$ret = $this->_check_file();
			break;

		default:
			$ret = $this->submit_check();
			break;
	}

	if ( !$ret ) {
		redirect_header( 
			$this->get_redirect_url() , 
			$this->get_redirect_time() ,
			$this->get_redirect_msg()
		) ;
		exit();
	}
}

function _check_file()
{
	$ret = $this->submit_check();
	if ( !$ret ) {
		return $ret;
	}

	$ret = $this->_check_exec_file() ;
	if ( $ret < 0 ) {
		$this->submit_check_redirect( $ret );
		return false;
	}

	return true;
}

function _check_exec_file()
{
	if ( ! $this->_has_file )   {
		return _C_WEBPHOTO_ERR_NO_PERM ; 
	}

	$ret = $this->check_dir( $this->_FILE_DIR );
	if ( $ret < 0 ) {
		return $ret; 
	}

	return 0;
}

//---------------------------------------------------------
// submit
//---------------------------------------------------------
function _submit( $action )
{
	if ( ! $this->check_token() ) {
		$this->set_token_error() ;
		return _C_WEBPHOTO_RET_ERROR ;
	}

	$ret = $this->_submit_exec( $action );

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

	list( $url, $time, $msg ) = $this->build_submit_redirect(); 

	redirect_header( $url, $time, $msg );
	exit();
}

function _submit_exec( $action )
{
	$this->get_post_param();
	
	switch ($action)
	{
		case 'submit_bulk':
			$ret = $this->_submit_bulk();
			break;

		case 'submit_file':
			$ret = $this->_submit_file();
			break;

		default:
			$ret = $this->submit_exec();
			break;
	}

	if ( $this->_is_video_thumb_form ) {
		return _C_WEBPHOTO_RET_VIDEO_FORM ;
	}

	$ret2 = $this->build_failed_msg( $ret );
	if ( !$ret2 ) {
		return _C_WEBPHOTO_RET_ERROR ;
	}

	return _C_WEBPHOTO_RET_SUCCESS ;
}

function _check_token_and_redirect()
{
	$this->check_token_and_redirect( $this->_THIS_URL, $this->_TIME_FAILED );
}


//---------------------------------------------------------
// submit bulk
//---------------------------------------------------------
function _submit_bulk()
{
	$this->set_form_mode( 'bulk' );
	$this->clear_msg_array();

	$item_row_crete = $this->create_item_row_by_post();
	$post_title = $item_row_crete['item_title'] ;

	$param = array(
		'flag_video_single' => true ,
	);

	$filecount = 1 ;
	for ( $i=1; $i <= $this->_MAX_PHOTO_FILE; $i++ )
	{
		$item_row   = $item_row_crete;
		$field_name = 'file_'.$i;

		$ret = $this->_upload_class->fetch_media( 
			$field_name, $this->_FLAG_FETCH_ALLOW_ALL );
		if ( $ret < 0 ) {
			$this->set_msg_array( $this->_upload_class->get_errors() );
			contine; 
		}

// not success
		if ( $ret != 1 ) {
			continue; 
		}

		$file_tmp_name   = $this->_upload_class->get_tmp_name();
		$file_media_type = $this->_upload_class->get_uploader_media_type();
		$file_media_name = $this->_upload_class->get_uploader_media_name();

		if ( empty($file_tmp_name) ) {
			continue; 
		}

		$item_row['item_title'] = $this->build_bulk_title( $post_title, $filecount );

		$item_row = $this->_factory_create_class->build_item_row_photo( 
			$item_row, $file_tmp_name, $file_media_name );

		$param['src_file'] = $this->build_tmp_dir_file( $file_tmp_name );

		$ret = $this->_factory_create_class->create_item_from_param( $item_row, $param );
		if ( $ret < 0 ) {
			$this->set_msg_array( $this->_factory_create_class->get_errors() );

// typo contiune -> continue
			continue;
 
		}

		$filecount ++ ;
	}

	if ( $filecount == 1 ) {
		return _C_WEBPHOTO_ERR_NO_IMAGE;
	}

	$this->set_created_row( $item_row );

	$this->submit_exec_post_count();
	$this->submit_exec_notify( $item_row );

	return 0;
}

//---------------------------------------------------------
// submit file
//---------------------------------------------------------
function _submit_file()
{
	$this->set_form_mode( 'file' );
	$this->clear_msg_array();

	$this->_post_file = $this->get_post_text( 'file' ) ;
	$src_file = $this->build_file_dir_file( $this->_post_file );
	$src_node = $this->_utility_class->strip_ext( $this->_post_file );

	$item_row = $this->create_item_row_by_post();

	$ret = $this->submit_exec_check( $item_row );
	if ( $ret < 0 ) {
		return $ret ;
	}

	$ret = $this->_check_submit_file();
	if ( $ret < 0 ) {
		return $ret;
	}

	if ( empty($item_row['item_title']) ) {
		$item_row['item_title'] = addslashes( $src_node );
	}

	$item_row['item_status'] = _C_WEBPHOTO_STATUS_APPROVED ;

	$param = array(
		'src_file'          => $src_file ,
		'flag_video_plural' => true ,
	);

	$ret      = $this->_factory_create_class->create_item_from_param( $item_row, $param );
	$item_row = $this->_factory_create_class->get_item_row() ;

	if ( $ret < 0 ) {
		$this->_move_file( $src_file );
		$this->set_error( $this->_factory_create_class->get_errors() );
		return $ret;
	}

	if ( ! is_array($item_row) ) {
		$this->_move_file( $src_file );
		return _C_WEBPHOTO_ERR_CREATE_PHOTO;
	}

	$this->unlink_file( $src_file );

	$item_id = $item_row['item_id'];
	$this->set_created_row( $item_row );

	if ( $this->_factory_create_class->get_resized() ) {
		$this->set_msg_array( $this->get_constant('SUBMIT_RESIZED') ) ;
	}

	if ( $this->_factory_create_class->get_flag_flash_failed() ) {
		$this->set_msg_array( $this->get_constant('ERR_VIDEO_FLASH') ) ;
	}

	if ( $this->_factory_create_class->get_flag_video_image_failed() ) {
		$this->set_msg_array( $this->get_constant('ERR_VIDEO_THUMB') ) ;
	}

	if ( $this->_factory_create_class->get_flag_video_image_created() ) {
		$this->_is_video_thumb_form = true;
	}

	$this->submit_exec_post_count();
	$this->notify_new_photo( $item_row );

	return 0;
}

function _check_submit_file()
{
	$src_file = $this->build_file_dir_file( $this->_post_file );
	$src_ext  = $this->parse_ext( $this->_post_file ) ;

	if ( empty( $this->_post_file ) ) {
		return _C_WEBPHOTO_ERR_EMPTY_FILE ;
	}

	if ( ! is_readable( $src_file ) ) {
		return _C_WEBPHOTO_ERR_FILEREAD ;
	}

	if ( ! $this->is_my_allow_ext( $src_ext ) ) {
		return _C_WEBPHOTO_ERR_EXT ;
	}

	if ( ! $this->_check_file_size( $src_file ) ) {
		return _C_WEBPHOTO_ERR_FILE_SIZE ;
	}

	return 0;
}

function _check_file_size( $file )
{
	if ( filesize( $file ) < $this->_cfg_file_size ) {
		return true;
	}
	return false;
}

function _move_file( $old )
{
	$new = $this->build_tmp_dir_file( uniqid( 'file_' ) );
	rename( $old, $new );
}

//---------------------------------------------------------
// video
//---------------------------------------------------------
function _video()
{
	$this->_check_token_and_redirect();
	$item_row = $this->_get_item_row_or_redirect();

	$ret = $this->video_thumb( $item_row );

	list( $url, $time, $msg ) = $this->build_redirect( 
		$this->build_submit_redirect_param( !$ret, $item_row['item_cat_id'] ) );

	redirect_header( $url, $time, $msg );
	exit();
}

function _get_item_row_or_redirect()
{
	$item_id  = $this->_post_class->get_post_get_int('item_id') ;
	$item_row = $this->_item_handler->get_row_by_id( $item_id );
	if ( !is_array($item_row) ) {
		redirect_header( $this->_THIS_URL , $this->_TIME_FAILED , 
			$this->get_constant('NOMATCH_PHOTO') ) ;
		exit() ;
	}

	return $item_row ;
}

//---------------------------------------------------------
// preview
//---------------------------------------------------------
function _build_preview_info( $item_row )
{
	$image_info = null;

// new preview
	if ( $this->is_readable_new_photo() ) {
		$image_info = $this->_preview_new( $item_row );

// old preview
	} elseif ( $this->is_readable_preview() ) {
		$image_info = $this->_preview_old();

// embed preview
	} elseif ( $this->is_embed_preview( $item_row ) ) {
		list( $row, $image_info ) = $this->build_preview_embed( $item_row );
		if ( is_array($row) ) {
			$item_row = $row ;
		}
	}

// preview without image
	if ( empty($image_info) ) {
		$image_info = $this->_preview_no_image();
	}

	if ( empty( $item_row['item_title'] ) && $this->_photo_media_name ) {
		$item_row['item_title'] = $this->strip_ext( $this->_photo_media_name );
	}

	return array( $item_row, $image_info );
}

function _build_preview_submit( $item_row, $image_info )
{
	$show_class =& webphoto_show_photo::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );

	$photo = array_merge( 
		$show_class->build_photo_show_basic( $item_row, $this->get_tag_name_array() ) ,
		$image_info 
	);

	$arr = array(
		'photo'           => $photo ,
		'show_photo_desc' => true 
	);
	return $arr;
}

function _preview_new( $item_row )
{
// BUG: Fatal error in upload.php
	$ret = $this->upload_fetch_photo( true );

	if ( $ret < 0 ) {
		return $this->_preview_no_image();
	}

	return $this->create_preview_new( $this->_photo_tmp_name );

}

function _preview_old()
{
	return $this->build_preview( $this->_preview_name ) ;
}

function _preview_no_image()
{
	return $this->build_no_image_preview();
}

//---------------------------------------------------------
// get form param
//---------------------------------------------------------
function _build_form_default( $action )
{
	$flag_embed = false;

	switch ( $action )
	{
		case 'bulk_form':
			$this->set_form_mode( 'bulk' );
			$lang_title_sub = $this->get_constant('TITLE_SUBMIT_BULK');
			break;

		case 'file_form':
			$this->set_form_mode( 'file' );
			$lang_title_sub = $this->get_constant('TITLE_SUBMIT_FILE');
			break;

		case 'submit_form':
		default:
			$flag_embed = true;
			$lang_title_sub = $this->get_constant('TITLE_SUBMIT_SINGLE');
			break;
	}

	$item_row = $this->create_item_row_default();
	$this->init_form();

	$show_form_editor = false;
	$show_form_embed  = false;
	$param_editor     = array();
	$param_embed      = array();

	$submit_show_form_editor = $this->get_ini('submit_show_form_editor');
	$submit_show_form_embed  = $this->get_ini('submit_show_form_embed');

	if ( $submit_show_form_editor ) {
		list( $show_form_editor, $param_editor ) =
			$this->build_form_editor( $item_row );
	}

	if ( $submit_show_form_embed && $flag_embed ) {
		list( $show_form_embed, $param_embed ) =
			$this->build_form_embed( $item_row );
	}

	$param = array(
		'lang_title_sub'     => $lang_title_sub ,
		'show_form_editor'   => $show_form_editor ,
		'show_form_embed'    => $show_form_embed ,
		'show_form_photo'    => true ,
		'show_uploading'     => $this->_show_uploading() ,
		'show_submit_select' => $this->_show_submit_select() ,
		'show_menu_select_file' => $this->_show_menu_select_file() ,
		'show_menu_select_bulk' => $this->_show_menu_select_bulk() ,
	);

	$arr = array_merge( 
		$this->build_form_base_param() ,
		$this->build_form_select_param() ,
		$this->build_form_photo( $item_row ) ,
		$param, $param_editor, $param_embed
	);
	return $arr;
}

function _build_form_error()
{
	$param = array(
		'error'          => $this->get_format_error( true, false ) ,
		'show_uploading' => $this->_show_uploading() ,
	);
	$arr = array_merge( 
		$this->_build_form_preview(),
		$param
	);
	return $arr;
}

function _build_form_preview()
{
	$item_row = $this->create_item_row_preview() ;

// set preview name
	list( $item_row, $image_info ) =
		$this->_build_preview_info( $item_row );

// set preview name to form
	$this->init_form();

	$param = array(
		'show_preview'    => true ,
		'show_form_photo' => true ,
		'lang_title_sub'  => $this->get_constant('TITLE_SUBMIT_SINGLE') ,
		'show_uploading'  => $this->_show_uploading() ,
	);

	$arr = array_merge( 
		$this->build_form_base_param() ,
		$this->_build_preview_submit( $item_row, $image_info ) ,
		$this->build_form_photo( $item_row ) ,
		$param 
	);
	return $arr;
}

function _build_form_video_thumb()
{
	return $this->build_form_video_thumb( $this->get_created_row() );
}

//---------------------------------------------------------
// menu
//---------------------------------------------------------
function _show_submit_select()
{
	return $this->get_ini('submit_show_submit_select') ;
}

function _show_menu_select_file()
{
	$show_menu = $this->get_ini('submit_show_menu_select_file') ;
	$show = ( $show_menu && $this->_cfg_file_dir && $this->_has_file );
	return $show;
}

function _show_menu_select_bulk()
{
	$show_menu = $this->get_ini('submit_show_menu_select_bulk') ;
	$show = ( $show_menu && $this->_has_superinsert );
	return $show;
}

function _show_uploading()
{
	return $this->get_ini('submit_show_uploading') ;
}

// --- class end ---
}

?>