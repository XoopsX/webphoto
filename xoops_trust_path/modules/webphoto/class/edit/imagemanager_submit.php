<?php
// $Id: imagemanager_submit.php,v 1.9 2010/11/16 23:43:38 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-04 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-11 K.OHWADA
// changed create_image_params_by_photo()
// 2010-10-01 K.OHWADA
// create_jpeg_param_by_photo()
// 2009-05-30 K.OHWADA
// check_perm_post_by_row()
// 2009-05-05 K.OHWADA
// unlink_tmp_dir_file()
// 2009-03-15 K.OHWADA
// create_small_param_by_photo()
// 2009-01-10 K.OHWADA
// webphoto_imagemanager_submit -> webphoto_edit_imagemanager_submit
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_imagemanager_submit
//=========================================================
class webphoto_edit_imagemanager_submit extends webphoto_edit_base
{
	var $_factory_create_class;
	var $_upload_class;
	var $_redirect_class;

	var $_has_insertable     = false;
	var $_has_superinsert    = false;
	var $_has_editable       = false;
	var $_has_deletable      = false;
	var $_has_html           = false;
	var $_has_file           = false;
	var $_has_image_resize   = false;
	var $_has_image_rotate   = false;

// post
	var $_post_item_id   = 0;
	var $_item_cat_id    = 0;
	var $_preview_name   = null;
	var $_tag_name_array = null;
	var $_rotate_angle   = 0;

// upload
	var $_photo_tmp_name   = null;
	var $_photo_media_type = null;
	var $_photo_media_name = null;
	var $_jpeg_tmp_name    = null;
	var $_jpeg_media_type  = null;
	var $_file_tmp_name_array   = array();
	var $_file_media_type_array = array();

	var $_image_tmp_file      = null;
	var $_photo_param         = null ;
	var $_media_file_params   = null;
	var $_is_video_thumb_form = false;

	var $_row_fetch    = null;
	var $_row_create   = null ;

	var $_redirect_time = 0 ;
	var $_redirect_url  = null ;
	var $_redirect_msg  = null ;

	var $_REDIRECT_MSG_ERROR = 'ERROR not set message';

	var $_MSG_LEVEL   = 0;
	var $_MSG_FIRST   = false;
	var $_TIME_FAILED = 5;

	var $_PHOTO_FIELD_NAME  = _C_WEBPHOTO_UPLOAD_FIELD_PHOTO ;
	var $_JPEG_FIELD_NAME   = _C_WEBPHOTO_UPLOAD_FIELD_JPEG ;

// for submit_imagemanager
	var $_FLAG_FETCH_ALLOW_ALL = false ;

// for admin
	var $_FLAG_ADMIN = false;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_imagemanager_submit( $dirname , $trust_dirname )
{
	$this->webphoto_edit_base( $dirname , $trust_dirname );

	$this->_factory_create_class =& webphoto_edit_factory_create::getInstance( 
		$dirname , $trust_dirname );
	$this->_redirect_class =& webphoto_edit_redirect::getInstance( 
		$dirname, $trust_dirname );

	$this->_has_image_resize  = $this->_factory_create_class->has_image_resize();
	$this->_has_image_rotate  = $this->_factory_create_class->has_image_rotate();

	$this->_upload_class =& webphoto_upload::getInstance( 
		$dirname , $trust_dirname );

	$this->_has_insertable  = $this->_perm_class->has_insertable();
	$this->_has_superinsert = $this->_perm_class->has_superinsert();
	$this->_has_editable    = $this->_perm_class->has_editable();
	$this->_has_deletable   = $this->_perm_class->has_deletable();
	$this->_has_html        = $this->_perm_class->has_html();
	$this->_has_file        = $this->_perm_class->has_file();

	$this->_TIME_FAILED = $this->_redirect_class->get_time_failed();
}

// for admin_photo_manage admin_catmanager
function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_imagemanager_submit( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// set param 
//---------------------------------------------------------
function set_flag_admin( $val )
{
	$this->_FLAG_ADMIN = (bool)$val;
	$this->_factory_create_class->set_flag_admin( $val );
}

//---------------------------------------------------------
// post param
//---------------------------------------------------------
function get_post_item_id()
{
	$key1 = 'item_id';
	$key2 = 'photo_id';

	$str = 0;
	if (     isset( $_POST[ $key1 ] ) ) { $str = $_POST[ $key1 ]; }
	elseif ( isset( $_GET[  $key1 ] ) ) { $str = $_GET[  $key1 ]; }

// from category
	elseif ( isset( $_GET[  $key2 ] ) ) { $str = $_GET[  $key2 ]; }

	return intval( $str ) ;
}

function get_post_cat_id()
{
	$key1 = 'item_cat_id';
	$key2 = 'cat_id';

	$str = 0;
	if (     isset( $_POST[ $key1 ] ) ) { $str = $_POST[ $key1 ]; }
	elseif ( isset( $_GET[  $key1 ] ) ) { $str = $_GET[  $key1 ]; }

// from category
	elseif ( isset( $_GET[  $key2 ] ) ) { $str = $_GET[  $key2 ]; }

	return intval( $str ) ;
}

//---------------------------------------------------------
// submit check 
//---------------------------------------------------------
function submit_check()
{
	$ret = $this->submit_check_exec() ;
	if ( $ret < 0 ) {
		$this->submit_check_redirect( $ret );
		return false;
	}

	return true;
}

function submit_check_redirect( $ret )
{
	$url = null ;
	$msg = null ;

	switch ( $ret )
	{
		case _C_WEBPHOTO_ERR_NO_PERM:
			$url = XOOPS_URL .'/user.php';
			$msg = $this->get_constant('ERR_MUSTREGFIRST') ;
			break;

		case _C_WEBPHOTO_ERR_CHECK_DIR:
			$url = $this->_MODULE_URL ;
			$msg = 'Directory Error';
			if ( $this->_is_module_admin ) {
				$msg .= '<br />'.$this->get_format_error();
			}
			break;

		case _C_WEBPHOTO_ERR_NO_CAT_RECORD :
			$url = $this->_MODULE_URL ;
			$msg = $this->get_constant('ERR_MUSTADDCATFIRST') ;
			break;

		default;
			break;
	}

	$this->_redirect_url  = $url ;
	$this->_redirect_msg  = $msg ;

// BUG: undefined property _REDIRECT_TIME_FAILED
	$this->_redirect_time = $this->_TIME_FAILED ;
}

function submit_check_exec()
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

//---------------------------------------------------------
// submit
//---------------------------------------------------------
function submit_exec_check( $item_row )
{
	$cat_id = $item_row['item_cat_id'] ;

// Check if cid is valid
	if ( empty( $cat_id ) ) {
		return _C_WEBPHOTO_ERR_EMPTY_CAT ;
	}

	$cat_row = $this->_cat_handler->get_cached_row_by_id( $cat_id );
	if ( !is_array($cat_row) ) {
		return _C_WEBPHOTO_ERR_INVALID_CAT ;
	}

	if ( ! $this->_cat_handler->check_perm_read_by_row( $cat_row ) ) {
		return _C_WEBPHOTO_ERR_INVALID_CAT ;
	}

	if ( ! $this->_cat_handler->check_perm_post_by_row( $cat_row ) ) {
		return _C_WEBPHOTO_ERR_INVALID_CAT ;
	}

	return 0; 
}

function submit_exec_fetch_photo( $row )
{
	$ret = $this->upload_fetch_photo( $this->_FLAG_FETCH_ALLOW_ALL );
	if ( $ret < 0 ) { 
		return $ret;	// failed
	}

// preview
	if ( empty($this->_photo_tmp_name) && 
	     $this->is_readable_preview() ) {
		$this->_photo_tmp_name = $this->_preview_name ;
	}

	if ( $this->_photo_tmp_name ) {
// ext kind exif duration
		$row = $this->_factory_create_class->build_item_row_photo( 
			$row, $this->_photo_tmp_name, $this->_photo_media_name );
	}

	$this->_row_fetch = $row ;
	return 0; 
}

function build_item_row_submit_insert( $row )
{
// status onclick search
	return $this->_factory_create_class->build_item_row_submit_insert( 
		$row, $this->_tag_name_array );
}

function build_item_row_submit_update( $row )
{
// files content icon search
	return $this->_factory_create_class->build_item_row_submit_update( 
		$row, $this->_file_id_array, $this->_tag_name_array );
}

function set_created_row( $val )
{
	$this->_row_create = $val;
}

function get_created_row()
{
	return $this->_row_create ;
}

function check_cat_perm_post( $id )
{
	$row = $this->_cat_handler->get_cached_row_by_id( $id );
	if ( is_array($row) ) {
		return $this->_cat_handler->check_perm_post_by_row( $row );
	}
	return false;
}

//---------------------------------------------------------
// media files 
//---------------------------------------------------------
function insert_media_files_from_params( $item_row )
{
	return $this->_factory_create_class->insert_files_from_params( 
		$item_row['item_id'], $this->_media_file_params );
}

function unlink_uploaded_files()
{
	$this->unlink_tmp_dir_file( $this->_photo_tmp_name );
	$this->unlink_tmp_dir_file( $this->_jpeg_tmp_name );

	$this->unlink_file( $this->_image_tmp_file );

	if ( $this->_photo_tmp_name ) {
		$rot_name = str_replace( 
			_C_WEBPHOTO_UPLOADER_PREFIX_PREV , 
			_C_WEBPHOTO_UPLOADER_PREFIX_ROT , 
			$this->_photo_tmp_name 
		) ;
		$this->unlink_tmp_dir_file( $rot_name );
	}

	$file_arr = $this->_file_tmp_name_array;

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_FILE_ID; $i++ ) 
	{
		$name  = 'file_'.$i;
		$file_name  = isset( $file_arr[ $name ] )  ? $file_arr[ $name ]  : null;
		$this->unlink_tmp_dir_file( $file_name );
	}

}

//---------------------------------------------------------
// create cont
//---------------------------------------------------------
function init_photo_create()
{
	$this->_factory_create_class->set_msg_level( $this->_MSG_LEVEL );
	$this->_factory_create_class->set_flag_print_first_msg( $this->_MSG_FIRST );
}

function build_photo_param( $item_row )
{
	$param = $item_row ;

	if ( $this->_photo_tmp_name ) {
		$param['src_ext']  = $item_row['item_ext'] ;
		$param['src_kind'] = $item_row['item_kind'] ;
		$param['src_file'] = $this->_TMP_DIR .'/'. $this->_photo_tmp_name ;
		$param['src_mime'] = $this->_photo_media_type ;
		$param['rotate_angle'] = $this->_rotate_angle ;
	}

	return $param ;
}

function create_cont_param( $photo_param )
{
	if ( ! is_array($photo_param) ) {
		return array( 0, null );	// no action
	}

	$ret = $this->_factory_create_class->create_cont_param( $photo_param );
	if ( $ret < 0 ) {
		return array( $ret, null );
	}

	$cont_param = $this->_factory_create_class->get_cont_param();

	return array( 0, $cont_param );
}

//---------------------------------------------------------
// create jpeg
//---------------------------------------------------------
function create_jpeg_param_by_photo( $photo_param, $file_params )
{
	return $this->_factory_create_class->create_jpeg_param( $photo_param, $file_params );
}

function create_jpeg_param_by_tmp( $item_row, $tmp_name )
{
	return $this->_factory_create_class->create_jpeg_param_by_tmp( $item_row, $tmp_name );
}

//---------------------------------------------------------
// create images
//---------------------------------------------------------
function create_image_params_by_photo( $photo_param, $file_params=null )
{
	return $this->_factory_create_class->create_image_params( 
		$photo_param, $file_params );
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
// BUG: error twice
	$this->_redirect_class->clear_errors();

	$this->_redirect_class->set_msg_array( $this->get_msg_array() );
	$this->_redirect_class->set_error( $this->get_errors() );
	$ret = $this->_redirect_class->build_redirect( $param );

// BUG: endless loop in submit check
	$this->_redirect_url  = $this->_redirect_class->get_redirect_url();
	$this->_redirect_time = $this->_redirect_class->get_redirect_time();
	$this->_redirect_msg  = $this->_redirect_class->get_redirect_msg();

	return $ret ;
}

function get_redirect_url()
{
	if ( $this->_redirect_url ) {
		return $this->_redirect_url ;
	}
	return $this->_MODULE_URL ;
}

function get_redirect_time()
{
	if ( $this->_redirect_time > 0 ) {
		return $this->_redirect_time ;
	}
	return $this->_TIME_FAILED ;
}

function get_redirect_msg()
{
	if ( $this->_redirect_msg ) {
		return $this->_redirect_msg ;
	}
	return $this->_REDIRECT_MSG_ERROR ;
}

//---------------------------------------------------------
// upload
//---------------------------------------------------------
function upload_fetch_photo( $flag_allow_all=false )
{
	$this->_photo_tmp_name   = null ;
	$this->_photo_media_type = null ;

	$ret = $this->_upload_class->fetch_media( 
		$this->_PHOTO_FIELD_NAME, $flag_allow_all );

	if ( $ret < 0 ) {
		$this->set_error( $this->_upload_class->get_errors() );
	}

// not success
	if ( $ret != 1 ) {
		return $ret ;
	}

	$this->_photo_tmp_name   = $this->_upload_class->get_tmp_name();
	$this->_photo_media_type = $this->_upload_class->get_uploader_media_type();
	$this->_photo_media_name = $this->_upload_class->get_uploader_media_name();

	return $ret;
}

function upload_fetch_jpeg()
{
	$this->_jpeg_tmp_name   = null;
	$this->_jpeg_media_type = null;

// if jpeg file uploaded
	$ret = $this->_upload_class->fetch_image( $this->_JPEG_FIELD_NAME );

	if ( $ret < 0 ) {
		$this->set_error( $this->_upload_class->get_errors() );
	}
	if ( $ret == 1 ) {
		$this->_jpeg_tmp_name   = $this->_upload_class->get_tmp_name();
		$this->_jpeg_media_type = $this->_upload_class->get_uploader_media_type();
	}
}

function upload_fetch_files()
{
	$this->_file_tmp_name_array   = array();
	$this->_file_media_type_array = array();

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_FILE_ID; $i++ ) 
	{
		$name  = 'file_'.$i;

// if file file uploaded
		$ret = $this->_upload_class->fetch_media( $name, true );

		if ( $ret < 0 ) {
			$this->set_error( $this->_upload_class->get_errors() );
		}
		if ( $ret == 1 ) {
			$this->_file_tmp_name_array[ $name ]   = $this->_upload_class->get_tmp_name();
			$this->_file_media_type_array[ $name ] = $this->_upload_class->get_uploader_media_type();
		}
	}
}

//---------------------------------------------------------
// upload class
//---------------------------------------------------------
function upload_media_name()
{
	return $this->_upload_class->get_uploader_media_name();
}

function is_readable_files_tmp_name( $filed )
{
	return $this->_upload_class->is_readable_files_tmp_name( $filed );
}

function is_readable_in_tmp_dir( $name )
{
	return $this->_upload_class->is_readable_in_tmp_dir( $name );
}

function is_readable_new_photo()
{
	return $this->is_readable_files_tmp_name( $this->_PHOTO_FIELD_NAME );
}

function is_readable_preview()
{
	return $this->is_readable_in_tmp_dir( $this->_preview_name );
}

function check_xoops_upload_file( $flag_thumb=true )
{
	$post_xoops_upload_file = $this->_post_class->get_post( 'xoops_upload_file' );
	if ( !is_array($post_xoops_upload_file) || !count($post_xoops_upload_file) ) {
		return false;
	}
	if ( !in_array( $this->_PHOTO_FIELD_NAME, $post_xoops_upload_file ) ) {
		return false;
	}
	if ( $flag_thumb && !in_array( $this->_JPEG_FIELD_NAME, $post_xoops_upload_file ) ) {
		return false;
	}

	return true;
}

// --- class end ---
}

?>