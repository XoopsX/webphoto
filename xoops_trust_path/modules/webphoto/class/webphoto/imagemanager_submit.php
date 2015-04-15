<?php
// $Id: imagemanager_submit.php,v 1.1 2009/01/06 09:42:30 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-04 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_imagemanager_submit
//=========================================================
class webphoto_imagemanager_submit extends webphoto_base_this
{
	var $_upload_class;
	var $_image_class;
	var $_build_class;
	var $_mime_class;
	var $_photo_class;
	var $_redirect_class;

	var $_cfg_makethumb      = false;
	var $_cfg_addposts       = 0 ;
	var $_cfg_fsize          = 0 ;
	var $_cfg_width          = 0 ;
	var $_cfg_height         = 0 ;
	var $_cfg_perm_item_read = 0 ;

	var $_has_insertable     = false;
	var $_has_superinsert    = false;
	var $_has_editable       = false;
	var $_has_deletable      = false;
	var $_has_html           = false;
	var $_has_image_resize   = false;
	var $_has_image_rotate   = false;

// item
	var $_post_item_id          = 0;
	var $_item_cat_id           = 0;
	var $_item_title            = null;
	var $_item_datetime         = null;
	var $_item_equipment        = null;
	var $_item_duration         = 0 ;
	var $_item_exif             = null;
	var $_item_ext              = null;
	var $_item_displaytype      = 0 ;
	var $_item_onclick          = 0 ;
	var $_item_gmap_latitude    = 0 ;
	var $_item_gmap_longitude   = 0 ;
	var $_item_gmap_zoom        = 0 ;
	var $_item_kind             = _C_WEBPHOTO_ITEM_KIND_UNDEFINED ;

	var $_preview_name   = null;
	var $_tag_name_array = null;
	var $_special_ext    = null;

	var $_photo_tmp_name    = null;
	var $_photo_media_type  = null;
	var $_photo_media_name  = null;
	var $_thumb_tmp_name    = null;
	var $_thumb_media_type  = null;
	var $_middle_tmp_name   = null;
	var $_middle_media_type = null;

	var $_photo_param = null ;
	var $_video_param = null ;
	var $_file_params = null;
	var $_is_video_thumb_form = false;

	var $_row_create   = null ;
	var $_row_current  = null;
	var $_row_update   = null ;

	var $_redirect_time = 0 ;
	var $_redirect_url  = null ;
	var $_redirect_msg  = null ;

	var $_NO_TITLE           = 'no title' ;
	var $_REDIRECT_MSG_ERROR = 'ERROR not set message';

	var $_MSG_LEVEL = 0;
	var $_MSG_FIRST = false;

	var $_PHOTO_FIELD_NAME  = _C_WEBPHOTO_UPLOAD_FIELD_PHOTO ;
	var $_THUMB_FIELD_NAME  = _C_WEBPHOTO_UPLOAD_FIELD_THUMB ;
	var $_MIDDLE_FIELD_NAME = _C_WEBPHOTO_UPLOAD_FIELD_MIDDLE ;

// for submit_imagemanager
	var $_FLAG_FETCH_ALLOW_ALL = false ;
	var $_FLAG_FETCH_THUMB     = false ;
	var $_FLAG_ALLOW_NONE      = false ;

// for admin
	var $_FLAG_ADMIN = false;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_imagemanager_submit( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_photo_class    =& webphoto_photo_create::getInstance( $dirname , $trust_dirname );
	$this->_build_class    =& webphoto_photo_build::getInstance( $dirname );
	$this->_mime_class     =& webphoto_mime::getInstance( $dirname );
	$this->_redirect_class =& webphoto_photo_redirect::getInstance( $dirname, $trust_dirname );

	$this->_image_class =& webphoto_image_create::getInstance( $dirname , $trust_dirname );
	$this->_has_image_resize  = $this->_image_class->has_resize();
	$this->_has_image_rotate  = $this->_image_class->has_rotate();

	$this->_upload_class =& webphoto_upload::getInstance( $dirname , $trust_dirname );
	$this->_upload_class->set_flag_size_limit( !$this->_has_image_resize );

	$this->_has_insertable  = $this->_perm_class->has_insertable();
	$this->_has_superinsert = $this->_perm_class->has_superinsert();
	$this->_has_editable    = $this->_perm_class->has_editable();
	$this->_has_deletable   = $this->_perm_class->has_deletable();
	$this->_has_html        = $this->_perm_class->has_html();

	$this->_cfg_makethumb      = $this->get_config_by_name( 'makethumb' );
	$this->_cfg_addposts       = $this->get_config_by_name( 'addposts' );
	$this->_cfg_width          = $this->get_config_by_name( 'width' );
	$this->_cfg_height         = $this->get_config_by_name( 'height' );
	$this->_cfg_perm_item_read = $this->get_config_by_name( 'perm_item_read' );
}

// for admin_photo_manage admin_catmanager
public static function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_imagemanager_submit( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// set param 
//---------------------------------------------------------
function set_flag_admin( $val )
{
	$this->_FLAG_ADMIN = (bool)$val;
}

//---------------------------------------------------------
// post param 
//---------------------------------------------------------
function get_post_param()
{
	$this->get_post_param_basic();
}

function get_post_param_basic()
{
	$this->get_post_item_id();
	$this->get_post_cat_id();
	$this->_item_title = $this->_post_class->get_post_text( 'item_title' ) ;
}

function get_post_item_id()
{
	$key1 = 'item_id';
	$key2 = 'photo_id';

	$str = 0;
	if (     isset( $_POST[ $key1 ] ) ) { $str = $_POST[ $key1 ]; }
	elseif ( isset( $_GET[  $key1 ] ) ) { $str = $_GET[  $key1 ]; }

// from category
	elseif ( isset( $_GET[  $key2 ] ) ) { $str = $_GET[  $key2 ]; }

	$this->_post_item_id = intval( $str ) ;
	return $this->_post_item_id ;
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

	$this->_item_cat_id = intval( $str ) ;
}

function build_row_by_post( $row, $is_submit=false, $flag_title=true )
{
	return $this->build_row_basic_by_post( $row, $is_submit, $flag_title );
}

function build_row_basic_by_post( $row, $is_submit=false, $flag_title=true )
{
// overwrite if title is blank
	if ( $flag_title ) {
		$this->overwrite_item_title_if_empty( $this->_NO_TITLE );
	}

	$row['item_title']            = $this->_item_title;
	$row['item_cat_id']           = $this->_item_cat_id;
	$row['item_equipment']        = $this->_item_equipment;
	$row['item_exif']             = $this->_item_exif ;
	$row['item_gmap_latitude']    = $this->_item_gmap_latitude ;
	$row['item_gmap_longitude']   = $this->_item_gmap_longitude ;
	$row['item_gmap_zoom']        = $this->_item_gmap_zoom ;

	if ( $this->_item_datetime ) {
		$row['item_datetime'] = $this->_item_datetime ;
	}

	if ( $this->_item_ext ) {
		$row['item_ext'] = $this->_item_ext ;
	}

	if ( ! $this->is_item_undefined_kind() ) {
		$row['item_kind'] = $this->_item_kind ;
	}

	if ( $is_submit || $this->_FLAG_ADMIN ) {
		$row['item_displaytype'] = $this->_item_displaytype ;
		$row['item_onclick']     = $this->_item_onclick ;
	}

	return $row;
}

//---------------------------------------------------------
// item
//---------------------------------------------------------
function overwrite_item_title_if_empty( $val )
{
	if ( empty($this->_item_title) && $val ) {
		$this->_item_title = $val;
	}
}

function is_item_undefined_kind()
{
	return $this->is_undefined_kind( $this->_item_kind );
}

function overwrite_item_gmap( $latitude, $longitude, $zoom )
{
	if ( ( $this->_item_gmap_latitude  > 0 ) &&
	     ( $this->_item_gmap_longitude > 0 ) ) {
		return;
	}

	if ( ( $latitude > 0 )&&( $longitude > 0 ) ) {
		$this->_item_gmap_latitude  = $latitude ;
		$this->_item_gmap_longitude = $longitude ;
		$this->_item_gmap_zoom      = $zoom ;
	}
}

//---------------------------------------------------------
// is type
//---------------------------------------------------------
// dummy
function is_upload_type()
{
	return true;
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
// submit form
//---------------------------------------------------------
function build_submit_default_row()
{
	return $this->build_submit_default_row_basic();
}

function build_submit_default_row_basic()
{
	$this->get_post_cat_id();

	$this->_item_kind        = $this->_post_class->get_post_text( 'item_kind' );
	$this->_item_displaytype = $this->_post_class->get_post_text( 'item_displaytype' );

// new row
	$row = $this->_item_handler->create( true );
	$row['item_cat_id']         = $this->_item_cat_id;
	$row['item_kind']           = $this->_item_kind ;
	$row['item_displaytype']    = $this->_item_displaytype;

	return $row;
}

//---------------------------------------------------------
// submit
//---------------------------------------------------------
function submit()
{
	$this->get_post_param();
	$ret1 = $this->submit_exec();

	if ( $this->_is_video_thumb_form ) {
		return _C_WEBPHOTO_RET_VIDEO_FORM ;
	}

	$ret2 = $this->build_failed_msg( $ret1 );
	if ( !$ret2 ) {
		return _C_WEBPHOTO_RET_ERROR ;
	}

	return _C_WEBPHOTO_RET_SUCCESS ;
}

function submit_exec()
{
	$this->clear_msg_array();

	$ret = $this->submit_exec_check();
	if ( $ret < 0 ) {
		return $ret ;
	}

	$ret = $this->submit_exec_fetch();
	if ( $ret < 0 ) {
		return $ret ;
	}

	$this->submit_exec_set_item_param();

	if ( empty($this->_photo_tmp_name) ) {
		$ret = $this->submit_exec_fetch_check();
		if ( $ret < 0 ) {
			return $ret ;
		}
	}

	$ret = $this->submit_exec_item_save();
	if ( $ret < 0 ) {
		return $ret ;
	}

	$row     = $this->_row_create ;
	$item_id = $row['item_id'];

	$ret = $this->submit_exec_tag_save( $item_id );
	if ( $ret < 0 ) {
		return $ret ;
	}

	$this->submit_exec_playlist_save( $row );
	$this->submit_exec_post_count();
	$this->submit_exec_notify( $row );
	return 0; 
}

function submit_exec_check()
{
// Check if cid is valid
	if ( empty( $this->_item_cat_id ) ) {
		return _C_WEBPHOTO_ERR_EMPTY_CAT ;
	}

	if ( ! $this->check_valid_catid( $this->_item_cat_id ) ) {
		return _C_WEBPHOTO_ERR_INVALID_CAT ;
	}

// Check if upload file name specified
	if ( $this->is_upload_type() && ! $this->check_xoops_upload_file( $this->_FLAG_FETCH_THUMB ) ) {
		return _C_WEBPHOTO_ERR_NO_SPECIFIED;
	}

	return 0; 
}

function submit_exec_fetch()
{
	return $this->submit_exec_fetch_photo();
}

function submit_exec_fetch_photo()
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

	$this->set_values_for_fetch_photo( $this->_photo_tmp_name );

	return 0; 
}

function submit_exec_set_item_param()
{
	if ( $this->is_item_undefined_kind() ) {
		$this->_item_kind = $this->get_new_kind() ;
	}

	$this->_item_displaytype = $this->get_new_displaytype() ;
	$this->_item_onclick     = $this->get_new_onclick() ;
}

function submit_exec_fetch_check()
{
	return $this->submit_exec_fetch_check_basic();
}

function submit_exec_fetch_check_basic()
{
// check title
	if ( empty($this->_item_title) ) {
		return _C_WEBPHOTO_ERR_NO_TITLE;
	}

// check allow no image mode
	if ( $this->_FLAG_ALLOW_NONE ) {
		$this->_item_kind = _C_WEBPHOTO_ITEM_KIND_NONE ;
		return 0; 
	}

	return _C_WEBPHOTO_ERR_NO_IMAGE;
}

function submit_exec_item_save()
{
	$photo_tmp_name  = $this->_photo_tmp_name;
	$thumb_tmp_name  = $this->_thumb_tmp_name;
	$middle_tmp_name = $this->_middle_tmp_name;

// --- insert item ---
	$item_row = $this->build_insert_item_row();
	$item_id = $this->_item_handler->insert( $item_row );
	if ( !$item_id ) {
		$this->set_error( $this->_item_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB ;
	}

	$item_row['item_id'] = $item_id;

	$this->_row_create = $item_row;

	$ret14 = $this->create_photo_thumb(
		$item_row, $photo_tmp_name, $thumb_tmp_name, $middle_tmp_name, true );

	if ( $ret14 < 0 ) {
		return $ret14;
	}

	$file_params = $this->_file_params ;

// --- update item ---
	$update_row = $this->build_update_item_row( $item_row, $file_params );
	$ret15 = $this->_item_handler->update( $update_row );
	if ( !$ret15 ) {
		$this->set_error( $this->_item_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB;
	}

	$this->_row_create = $update_row ;

	return 0 ;
}

function submit_exec_tag_save( $item_id )
{
	// dummy
	return 0;
}

function submit_exec_playlist_save( $row )
{
	// dummy
}

function submit_exec_post_count()
{
	// dummy
}

function submit_exec_notify( $row )
{
	// dummy
}

function build_insert_item_row()
{
	$item_row = $this->_item_handler->create( true );
	$item_row = $this->build_row_by_post( $item_row, true );

	$item_row['item_uid']    = $this->_xoops_uid;
	$item_row['item_status'] = $this->get_new_status();
	$item_row['item_search'] = $this->build_search_for_edit( $item_row, $this->_tag_name_array );

	return $item_row;
}

function build_update_item_row( $item_row, $file_params )
{
	return $this->build_update_item_row_basic( $item_row, $file_params );
}

function build_update_item_row_basic( $item_row, $file_params, $playlist_cache=null )
{
	$item_id = $item_row['item_id'];

	$file_id_array = $this->_photo_class->insert_files_from_params(
		$item_id,  $file_params );

	$update_row = $this->_photo_class->build_update_item_row(
		$item_row, $file_id_array, $playlist_cache, $this->_special_ext );

	return $update_row;
}

function get_new_kind()
{
	return $this->get_new_kind_basic();
}

function get_new_kind_basic( $kind )
{
	$kind = _C_WEBPHOTO_ITEM_KIND_GENERAL ;
	if ( $this->_item_ext ) {
		$kind = $this->get_kind_by_item_ext();
	}
	return $kind ;
}

function get_kind_by_item_ext()
{
	return $this->_mime_class->ext_to_kind( $this->_item_ext );
}

function get_new_displaytype()
{
	$this->get_new_displaytype_basic();
}

function get_new_displaytype_basic()
{
	$str = _C_WEBPHOTO_DISPLAYTYPE_GENERAL ;
	if ( $this->is_item_image_ext() ) {
		$str = _C_WEBPHOTO_DISPLAYTYPE_IMAGE ;
	}
	return $str ;
}

function is_item_image_ext()
{
	return $this->is_image_ext( $this->_item_ext ) ;
}

function get_new_onclick()
{
	return $this->_photo_class->get_onclick( $this->_item_ext );
}

function get_new_status()
{
	return intval( $this->_has_superinsert );
}

//---------------------------------------------------------
// create photo thumb 
//---------------------------------------------------------
function create_photo_thumb( 
	$item_row, $photo_name, $thumb_name, $middle_name, $is_submit )
{
	return $this->create_photo_thumb_basic( 
		$item_row, $photo_name, $thumb_name, $middle_name, $is_submit );

}

function create_photo_thumb_basic( 
	$item_row, $photo_name, $thumb_name, $middle_name, $is_submit, $rotate=0 )
{
	$this->_special_ext = null ;

	list( $ret, $cont_param ) =
		$this->create_cont_param( $item_row, $photo_name, $thumb_name, $rotate );
	if ( $ret < 0 ) {
		return $ret ;
	}

	$item_id = $item_row['item_id'] ;

	$thumb_param  = null;
	$middle_param = null;

	if ( is_array($cont_param) ) {
		$thumb_param = $this->create_thumb_param_by_param( $cont_param );
	}

	if ( is_array($cont_param) ) {
		$middle_param = $this->create_middle_param_by_param( $cont_param );
	}

// unlink tmp file
	if ( $photo_name ) {
		$this->unlink_file( $this->_TMP_DIR .'/'. $photo_name );
	}

	$this->_file_params['thumb']  = $thumb_param ;
	$this->_file_params['middle'] = $middle_param ;

	return 0;
}

function create_cont_param( $item_row, $photo_name, $thumb_name, $rotate=0 )
{
	$photo_param = $this->build_photo_param( $item_row, $photo_name, $thumb_name, $rotate );
	$cont_param  = null;
	$ret         = 0;

	if ( is_array($photo_param) ) {
		$ret = $this->create_photo_param_by_param( $photo_param );
		if ( $ret < 0 ) {
			return array( $ret, $cont_param );
		}
		$cont_param = $photo_param ;
	}

	return array( $ret, $cont_param );
}

function build_photo_param( $item_row, $photo_name, $thumb_name, $rotate )
{
	if ( empty($photo_name) ) {
		return null; 
	}

	$photo_param                     = array();
	$photo_param['item_id']          = $item_row['item_id'] ;
	$photo_param['src_ext']          = $item_row['item_ext'] ;
	$photo_param['src_kind']         = $item_row['item_kind'] ;
	$photo_param['src_file']         = $this->_TMP_DIR .'/'. $photo_name ;
	$photo_param['mime']             = $this->_photo_media_type ;
	$photo_param['video_param']      = $this->_video_param ;
	$photo_param['flag_video_thumb'] = $thumb_name ? false : true ;
	$photo_param['rotate']           = $rotate ;

	return $photo_param;
}

//---------------------------------------------------------
// create photo
//---------------------------------------------------------
function create_photo_param_by_param( $photo_param )
{
	$this->_photo_class->set_msg_level( $this->_MSG_LEVEL );
	$this->_photo_class->set_flag_print_first_msg( $this->_MSG_FIRST );

	$this->_is_video_thumb_form = false ;
	$this->_file_params         = null ;
	$this->_photo_param         = null ;

	$cont_param   = null ;
	$flash_param  = null ;
	$docomo_param = null ;

	if ( ! is_array($photo_param) ) {
		return 0;	// no action
	}

	$item_id  = $photo_param['item_id'] ;
	$src_kind = $photo_param['src_kind'] ;

	$ret = $this->_photo_class->create_cont_param( $item_id, $photo_param );
	if ( $ret < 0 ) {
		return $ret;
	}

	$cont_param = $this->_photo_class->get_cont_param();
	if ( $this->_photo_class->get_resized() ) {
		$this->set_msg_array( $this->get_constant('SUBMIT_RESIZED') ) ;
	}

	if ( $this->is_video_kind( $src_kind ) && is_array( $cont_param ) ) {
		list( $flash_param, $docomo_param ) =
			$this->create_flash_docomo_param( $photo_param, $cont_param );
	}

	$this->_file_params = array(
		'cont'   => $cont_param ,
		'flash'  => $flash_param ,
		'docomo' => $docomo_param ,
	);

	return 0;
}

// dummy
function create_flash_docomo_param( $photo_param, $cont_param )
{
	return array( null, null );
}

//---------------------------------------------------------
// create thumb
//---------------------------------------------------------
function create_thumb_param_by_param( $param )
{
	if ( ! is_array( $param ) ) {
		return null;
	}

	$item_id = $param['item_id'] ;

	$param['flag_thumb']  = true  ;
	$param['flag_middle'] = false ;
	list( $thumb_param, $middle_param_dummy ) =
		$this->_photo_class->create_thumb_middle_param( $item_id, $param );

	return $thumb_param ;
}

//---------------------------------------------------------
// create middle
//---------------------------------------------------------
function create_middle_param_by_param( $param )
{
	if ( ! is_array( $param ) ) {
		return null;
	}

	$item_id = $param['item_id'] ;

	$param['flag_thumb']  = false ;
	$param['flag_middle'] = true ;
	list( $thumb_param_dummy, $middle_param ) =
		$this->_photo_class->create_thumb_middle_param( $item_id, $param );

	return $middle_param ;
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
// build class
//---------------------------------------------------------
function build_search_for_edit( $photo_row, $tag_name_array=null )
{
	return $this->_build_class->build_search( $photo_row, $tag_name_array );
}

//---------------------------------------------------------
// upload
//---------------------------------------------------------
function upload_fetch_photo( $flag_allow_all=false )
{
	$this->_photo_tmp_name   = null ;
	$this->_photo_media_type = null ;
	$this->_video_param      = null ;

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

	$this->overwrite_item_title_if_empty(
		$this->strip_ext( $this->_photo_media_name ) );

	return $ret;
}

function set_values_for_fetch_photo( $photo_tmp_name )
{
	$src_file = $this->_TMP_DIR.'/'.$photo_tmp_name ;

	$p           = $this->_photo_class->get_item_param_extention( $src_file );
	$video_param = $this->_photo_class->get_video_param();

	$ext       = isset($p['item_ext'])            ? $p['item_ext']            : null ;
	$kind      = isset($p['item_kind'])           ? $p['item_kind']           : null ;
	$datetime  = isset($p['item_datetime'])       ? $p['item_datetime']       : null ;
	$equipment = isset($p['item_equipment'])      ? $p['item_equipment']      : null ;
	$exif      = isset($p['item_exif'])           ? $p['item_exif']           : null ;
	$duration  = isset($p['item_duration'])       ? $p['item_duration']       : 0 ;
	$latitude  = isset($p['item_gmap_latitude'])  ? $p['item_gmap_latitude']  : 0 ;
	$longitude = isset($p['item_gmap_longitude']) ? $p['item_gmap_longitude'] : 0 ;
	$zoom      = isset($p['item_gmap_zoom'])      ? $p['item_gmap_zoom']      : 0 ;

	$this->_item_ext  = $ext ;
	$this->_item_kind = $kind ;

	if ( $datetime ) {
		$this->_item_datetime = $datetime ;
		$this->_item_datetime_flag = true ;
	}

	if ( $equipment ) {
		$this->_item_equipment = $equipment ;
	}

	if ( $exif ) {
		$this->_item_exif = $exif ;
	}

	if ( $duration > 0 ) {
		$this->_item_duration = $duration ;
	}

	$this->overwrite_item_gmap( $latitude, $longitude, $zoom );

	if ( $duration > 0 ) {
		$this->_item_duration = $duration ;
	}

	if ( is_array( $video_param ) ) {
		$this->_video_param = $video_param ;
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
	if ( $flag_thumb && !in_array( $this->_THUMB_FIELD_NAME, $post_xoops_upload_file ) ) {
		return false;
	}
	return true;
}

// --- class end ---
}

?>