<?php
// $Id: item_build.php,v 1.5 2009/03/23 12:42:00 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-03-15 K.OHWADA
// _C_WEBPHOTO_ITEM_FILE_SMALL
// BUG: flash player becomes default in the user edit
// 2009-01-25 K.OHWADA
// item_content
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_item_build
//=========================================================
class webphoto_edit_item_build extends webphoto_edit_base_create
{
	var $_xoops_class;
	var $_post_class;
	var $_item_handler;
	var $_perm_class;

	var $_xoops_uid;
	var $_cfg_perm_item_read ;
	var $_has_superinsert ;
	var $_has_html ;

	var $_FLAG_ADMIN = false;
	var $_NO_TITLE   = 'no title' ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_item_build( $dirname )
{
	$this->webphoto_edit_base_create( $dirname );

	$this->_xoops_class   =& webphoto_xoops_base::getInstance();
	$this->_post_class    =& webphoto_lib_post::getInstance();
	$this->_item_handler  =& webphoto_item_handler::getInstance( $dirname );
	$this->_perm_class    =& webphoto_permission::getInstance( $dirname );

	$this->_xoops_uid          = $this->_xoops_class->get_my_user_uid() ;
	$this->_has_superinsert    = $this->_perm_class->has_superinsert();
	$this->_has_html           = $this->_perm_class->has_html();
	$this->_cfg_perm_item_read = $this->get_config_by_name( 'perm_item_read' );

}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_item_build( $dirname );
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
// post
//---------------------------------------------------------
function build_row_submit_by_post( $row, $item_datetime_checkbox )
{
	$row['item_cat_id']           = $this->get_post_int(   'item_cat_id' );
	$row['item_title']            = $this->get_post_text(  'item_title' ) ;
	$row['item_duration']         = $this->get_post_int(   'item_duration' );
	$row['item_exif']             = $this->get_post_text(  'item_exif' );
	$row['item_content']          = $this->get_post_text(  'item_content' );
	$row['item_embed_type']       = $this->get_post_text(  'item_embed_type' );
	$row['item_embed_src']        = $this->get_post_text(  'item_embed_src' );
	$row['item_embed_text']       = $this->get_post_text(  'item_embed_text' );
	$row['item_external_url']     = $this->get_post_text(  'item_external_url' );
	$row['item_external_thumb']   = $this->get_post_text(  'item_external_thumb' );
	$row['item_external_middle']  = $this->get_post_text(  'item_external_middle' );
	$row['item_gmap_latitude']    = $this->get_post_float( 'item_gmap_latitude' );
	$row['item_gmap_longitude']   = $this->get_post_float( 'item_gmap_longitude' );
	$row['item_gmap_zoom']        = $this->get_post_int(   'item_gmap_zoom' );
	$row['item_page_width']       = $this->get_post_int(   'item_page_width' );
	$row['item_page_height']      = $this->get_post_int(   'item_page_height' );
	$row['item_equipment']        = $this->get_post_text( 'item_equipment' ) ;
	$row['item_description']      = $this->get_post_text( 'item_description' );
	$row['item_editor']           = $this->get_post_text( 'item_editor' );
	$row['item_gicon_id']         = $this->get_post_int(  'item_gicon_id' );
	$row['item_place']            = $this->get_post_text( 'item_place' );
	$row['item_siteurl']          = $this->get_post_text( 'item_siteurl' );
	$row['item_artist']           = $this->get_post_text( 'item_artist' );
	$row['item_album']            = $this->get_post_text( 'item_album' );
	$row['item_label']            = $this->get_post_text( 'item_label' );

	$row['item_datetime']  = $this->get_item_datetime_by_post( $item_datetime_checkbox );
	$row['item_perm_down'] = $this->get_group_perms_str_by_post( 'item_perm_down_ids' );
	$row['item_codeinfo']  = $this->build_info_by_post( 'item_codeinfo' );

// perm
	if ( $this->_cfg_perm_item_read > 0 ) {
		$row['item_perm_read'] = $this->get_group_perms_str_by_post( 'item_perm_read_ids' );
	}

// description option
	if ( $this->_has_html ) {
		$row['item_description_html']   = $this->get_post_int( 'item_description_html' );
		$row['item_description_smiley'] = $this->get_post_int( 'item_description_smiley' );
		$row['item_description_xcode']  = $this->get_post_int( 'item_description_xcode' );
		$row['item_description_image']  = $this->get_post_int( 'item_description_image' );
		$row['item_description_br']     = $this->get_post_int( 'item_description_br' );
	}

	if ( $this->_FLAG_ADMIN ) {
// kind
		$row['item_kind']          = $this->get_post_int(   'item_kind' );
		$row['item_displaytype']   = $this->get_post_int(   'item_displaytype' );
		$row['item_onclick']       = $this->get_post_int(   'item_onclick' );

// BUG: flash player becomes default in the user edit
		$row['item_player_id']     = $this->get_post_int(   'item_player_id' );

// playlist
		$row['item_playlist_type'] = $this->get_post_int(  'item_playlist_type' );
		$row['item_playlist_feed'] = $this->get_post_text( 'item_playlist_feed' ) ;
		$row['item_playlist_dir']  = $this->get_post_text( 'item_playlist_dir' ) ;
		$row['item_playlist_time'] = $this->get_post_int(  'item_playlist_time' ) ;

	}

// text
	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_TEXT; $i++ ) 
	{
		$name = $this->_item_handler->build_name_text_by_kind( $i );
		$row[ $name ] = $this->get_post_text( $name );
	}

	return $row;
}

function build_row_modify_by_post( $row, $flag_status=true )
{
	$item_id = $row['item_id'] ;

	$post_preview               = $this->get_post_text('preview');
	$post_submit                = $this->get_post_text('submit' );
	$post_time_update_checkbox  = $this->get_post_int( 'item_time_update_checkbox' );
	$post_time_publish_checkbox = $this->get_post_int( 'item_time_publish_checkbox' );
	$post_time_expire_checkbox  = $this->get_post_int( 'item_time_expire_checkbox' );
	$post_time_update           = $this->get_server_time_by_post('item_time_update' );
	$post_time_publish          = $this->get_server_time_by_post('item_time_publish' );
	$post_time_expire           = $this->get_server_time_by_post('item_time_expire' );

	if ( !$post_preview && !$post_submit ) {
		return $row;
	}

// admin
	if ( $this->_FLAG_ADMIN ) {
		if ( $post_time_update_checkbox ) {
			$row['item_time_update'] = $post_time_update ;
		}

		$time_publish = 0 ;
		$time_expire  = 0 ;
		if ( $post_time_publish_checkbox ) {
			$time_publish = $post_time_publish ;
		}
		if ( $post_time_expire_checkbox ) {
			$time_expire = $post_time_expire ;
		}
		$row['item_time_publish'] = $time_publish ;
		$row['item_time_expire']  = $time_expire ;

// user
	} else {
		$row['item_time_update'] = time();
	}

	if ( $flag_status ) {
		$row['item_status'] = $this->build_modify_status( $row );
	}

	return $row;
}

function get_item_datetime_by_post( $checkbox )
{
	if ( $checkbox == _C_WEBPHOTO_YES ) {
		return $this->_item_handler->build_datetime_by_post( 'item_datetime' );
	}
	return null ;
}

function build_modify_status( $row )
{
	$post_valid  = $this->get_post_int('valid');
	$post_status = $this->get_post_int('item_status');

	$current_status = $row['item_status'] ;
	$time_publish   = $row['item_time_publish'] ;

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

function build_info_by_post( $name )
{
	$arr = $this->get_post( $name );
	return $this->_item_handler->build_info( $arr );
}

function get_group_perms_str_by_post( $name )
{
	$arr = $this->get_post( $name );
	return $this->_utility_class->convert_group_perms_array_to_str( $arr );
}

function get_server_time_by_post( $key )
{
	$time = $this->get_post_time( $key );
	return $this->_xoops_class->user_to_server_time( $time );
}

//---------------------------------------------------------
// files 
//---------------------------------------------------------
function build_row_files( $row, $file_id_array )
{
	if ( ! is_array($file_id_array) ) {
		return $row;
	}

	$cont_id   = $this->get_array_value_by_key( $file_id_array, 'cont_id' );
	$thumb_id  = $this->get_array_value_by_key( $file_id_array, 'thumb_id' );
	$middle_id = $this->get_array_value_by_key( $file_id_array, 'middle_id' );
	$small_id  = $this->get_array_value_by_key( $file_id_array, 'small_id' );
	$flash_id  = $this->get_array_value_by_key( $file_id_array, 'flash_id' );
	$docomo_id = $this->get_array_value_by_key( $file_id_array, 'docomo_id' );
	$pdf_id    = $this->get_array_value_by_key( $file_id_array, 'pdf_id' );
	$swf_id    = $this->get_array_value_by_key( $file_id_array, 'swf_id' );

	if ( $cont_id > 0 ) {
		$row[ _C_WEBPHOTO_ITEM_FILE_CONT ] = $cont_id;
	}

	if ( $thumb_id > 0 ) {
		$row[ _C_WEBPHOTO_ITEM_FILE_THUMB ] = $thumb_id ;
		$row['item_icon_name']   = '' ;
		$row['item_icon_width']  = 0 ;
		$row['item_icon_height'] = 0 ;
	}

	if ( $middle_id > 0 ) {
		$row[ _C_WEBPHOTO_ITEM_FILE_MIDDLE ] = $middle_id;
	}

	if ( $small_id > 0 ) {
		$row[ _C_WEBPHOTO_ITEM_FILE_SMALL ] = $small_id;
	}

	if ( $flash_id > 0 ) {
		$row[ _C_WEBPHOTO_ITEM_FILE_VIDEO_FLASH ] = $flash_id;
		$row['item_displaytype'] = _C_WEBPHOTO_DISPLAYTYPE_MEDIAPLAYER ;
	}

	if ( $swf_id > 0 ) {
		$row[ _C_WEBPHOTO_ITEM_FILE_SWF ] = $swf_id;
		$row['item_displaytype'] = _C_WEBPHOTO_DISPLAYTYPE_SWFOBJECT ;
	}

	if ( $docomo_id > 0 ) {
		$row[ _C_WEBPHOTO_ITEM_FILE_VIDEO_DOCOMO ] = $docomo_id;
	}

	if ( $pdf_id > 0 ) {
		$row[ _C_WEBPHOTO_ITEM_FILE_PDF ] = $pdf_id;
	}

	return $row ;
}

//---------------------------------------------------------
// ext kind 
//---------------------------------------------------------
function build_row_ext_kind_from_file( $row, $file )
{
	$ext  = $this->parse_ext( $file );
	$kind = $this->ext_to_kind( $ext );
	$row['item_ext']  = $ext ;
	$row['item_kind'] = $kind ;
	return $row;
}

//---------------------------------------------------------
// onclick 
//---------------------------------------------------------
function build_row_onclick( $row )
{
	$row['item_onclick'] = $this->get_new_onclick( $row ) ;
	return $row;
}

function get_new_onclick( $row )
{
	$item_ext = $row['item_ext'];

	$ret = _C_WEBPHOTO_ONCLICK_PAGE ;
	if ( $this->is_image_ext( $item_ext ) ) {
		$ret = _C_WEBPHOTO_ONCLICK_POPUP ;
	}
	return $ret ;
}

//---------------------------------------------------------
// status 
//---------------------------------------------------------
function build_row_status_if_empty( $row )
{
	if( empty( $row['item_status'] ) ) {
		$row['item_status'] = $this->get_new_status();
	}
	return $row;
}

function get_new_status()
{
	return intval( $this->_has_superinsert );
}

//---------------------------------------------------------
// uid 
//---------------------------------------------------------
function build_row_uid_if_empty( $row )
{
	if( empty( $row['item_uid'] ) ) {
		$row['item_uid'] = $this->_xoops_uid;
	}
	return $row;
}

//---------------------------------------------------------
// displaytype 
//---------------------------------------------------------
function build_row_displaytype_if_empty( $row )
{
	if ( empty($row['item_displaytype']) ) {
		 $row['item_displaytype'] = $this->get_new_displaytype( $row ) ;
	}
	return $row;
}

function get_new_displaytype( $row )
{
	$item_ext = $row['item_ext'] ;

	$str = _C_WEBPHOTO_DISPLAYTYPE_GENERAL ;
	if ( $this->is_image_ext( $item_ext ) ) {
		$str = _C_WEBPHOTO_DISPLAYTYPE_IMAGE ;

	} elseif ( $this->is_swfobject_ext( $item_ext ) ) {
		$str = _C_WEBPHOTO_DISPLAYTYPE_SWFOBJECT ;

	} elseif ( $this->is_mediaplayer_ext( $item_ext ) ) {
		$str = _C_WEBPHOTO_DISPLAYTYPE_MEDIAPLAYER ;
	}
	return $str ;
}

//---------------------------------------------------------
// title 
//---------------------------------------------------------
function build_row_title_if_empty( $row )
{
	if ( empty($row['item_title']) ) {
		$row['item_title'] = $this->_NO_TITLE;
	}
	return $row;
}

//---------------------------------------------------------
// post class
//---------------------------------------------------------
function get_post_text( $key, $default=null )
{
	return $this->_post_class->get_post_text( $key, $default );
}

function get_post_int( $key, $default=0 )
{
	return $this->_post_class->get_post_int( $key, $default );
}

function get_post_float( $key, $default=0 )
{
	return $this->_post_class->get_post_float( $key, $default );
}

function get_post_time( $key, $default=0 )
{
	return $this->_post_class->get_post_time( $key, $default );
}

function get_post( $key, $default=null )
{
	return $this->_post_class->get_post( $key, $default );
}

//---------------------------------------------------------
// kind class
//---------------------------------------------------------
function is_image_ext( $ext )
{
	return $this->_kind_class->is_image_ext( $ext ) ;
}

function is_swfobject_ext( $ext )
{
	return $this->_kind_class->is_swfobject_ext( $ext ) ;
}

function is_mediaplayer_ext( $ext )
{
	return $this->_kind_class->is_mediaplayer_ext( $ext ) ;
}

//---------------------------------------------------------
// utility 
//---------------------------------------------------------
function get_array_value_by_key( $array, $key )
{
	return intval( 
		$this->_utility_class->get_array_value_by_key( $array, $key, 0 ) ) ;
}

// --- class end ---
}

?>