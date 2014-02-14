<?php
// $Id: item_build.php,v 1.17 2011/05/10 02:56:39 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-05-01 K.OHWADA
// build_modify_status_admin()
// 2010-09-20 K.OHWADA
// item_displayfile
// 2010-01-10 K.OHWADA
// item_description_scroll
// 2009-12-06 K.OHWADA
// item_perm_level
// 2009-11-11 K.OHWADA
// $trust_dirname
// item_detail_onclick
// 2009-10-25 K.OHWADA
// _C_WEBPHOTO_FILE_LIST
// BUG: player id is not correctly selected 
// 2009-05-05 K.OHWADA
// item_uid
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
	var $_cat_handler;
	var $_perm_class;
	var $_use_item_class;

	var $_xoops_uid;
	var $_cfg_perm_item_read ;
	var $_has_superinsert ;
	var $_has_html ;

	var $_flag_admin = false;

	var $_FILE_LIST;
	var $_NO_TITLE   = 'no title' ;
	var $_PLAYER_ID_FLASH_DEFAULT = 1;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_item_build( $dirname , $trust_dirname )
{
	$this->webphoto_edit_base_create( $dirname , $trust_dirname );

	$this->_xoops_class   =& webphoto_xoops_base::getInstance();
	$this->_post_class    =& webphoto_lib_post::getInstance();

	$this->_item_handler  
		=& webphoto_item_handler::getInstance( $dirname , $trust_dirname );
	$this->_cat_handler  
		=& webphoto_cat_handler::getInstance( $dirname , $trust_dirname );
	$this->_perm_class    
		=& webphoto_permission::getInstance( $dirname , $trust_dirname );
	$this->_use_item_class      
		=& webphoto_edit_use_item::getInstance( $dirname, $trust_dirname );

	$this->_xoops_uid          = $this->_xoops_class->get_my_user_uid() ;
	$this->_has_superinsert    = $this->_perm_class->has_superinsert();
	$this->_has_html           = $this->_perm_class->has_html();
	$this->_cfg_perm_item_read = $this->get_config_by_name( 'perm_item_read' );

	$this->_FILE_LIST = explode( '|', _C_WEBPHOTO_FILE_LIST );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_item_build( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// set param 
//---------------------------------------------------------
function set_flag_admin( $val )
{
	$this->_flag_admin = (bool)$val;
	$this->_use_item_class->set_flag_admin( $val );
}

//---------------------------------------------------------
// post
//---------------------------------------------------------
function build_row_submit_by_post( $row, $item_datetime_checkbox )
{
	$row = $this->build_row_common_by_post( $row, $item_datetime_checkbox );
	$row = $this->build_row_perm_by_post( $row, true );
	return $row;
}

function build_row_modify_by_post( $row, $item_datetime_checkbox, $flag_status=true )
{
	$row = $this->build_row_common_by_post( $row, $item_datetime_checkbox );
	$row = $this->build_row_edit_by_post( $row, $flag_status );
	$row = $this->build_row_perm_by_post( $row, false );
	return $row;
}

function build_row_common_by_post( $row, $item_datetime_checkbox )
{
// basic
	$row['item_cat_id']             = $this->get_post_int(  'item_cat_id' );
	$row['item_title']              = $this->get_post_text( 'item_title' ) ;
	$row['item_editor']             = $this->get_post_text( 'item_editor' );
	$row['item_description']        = $this->get_post_text( 'item_description' );
	$row['item_description_smiley'] = $this->get_post_int(  'item_description_smiley' );
	$row['item_description_xcode']  = $this->get_post_int(  'item_description_xcode' );
	$row['item_description_image']  = $this->get_post_int(  'item_description_image' );
	$row['item_description_br']     = $this->get_post_int(  'item_description_br' );
	$row['item_embed_type']         = $this->get_post_text( 'item_embed_type' );
	$row['item_embed_src']          = $this->get_post_text( 'item_embed_src' );
	$row['item_embed_text']         = $this->get_post_text( 'item_embed_text' );

	if ( $this->use_item('description_scroll') ) {
		$row['item_description_scroll'] = $this->get_post_int('item_description_scroll');
	}

	if ( $this->use_item('codeinfo') ) {
		$row['item_codeinfo'] = $this->build_info_by_post('item_codeinfo');
	}

	if ( $this->use_item('external_url') ) {
		$row['item_external_url'] = $this->get_post_text('item_external_url');
	}

	if ( $this->use_item('external_thumb') ) {
		$row['item_external_thumb'] = $this->get_post_text('item_external_thumb');
	}

	if ( $this->use_item('external_middle') ) {
		$row['item_external_middle'] = $this->get_post_text('item_external_middle');
	}

	if ( $this->use_item('datetime') ) {
		$row['item_datetime'] = $this->get_item_datetime_by_post( $item_datetime_checkbox );
	}

	if ( $this->use_item('place') ) {
		$row['item_place'] = $this->get_post_text('item_place');
	}

	if ( $this->use_item('equipment') ) {
		$row['item_equipment'] = $this->get_post_text('item_equipment') ;
	}

	if ( $this->use_item('duration') ) {
		$row['item_duration'] = $this->get_post_int('item_duration');
	}

	if ( $this->use_item('siteurl') ) {
		$row['item_siteurl'] = $this->get_post_text('item_siteurl');
	}

	if ( $this->use_item('artist') ) {
		$row['item_artist'] = $this->get_post_text('item_artist');
	}

	if ( $this->use_item('album') ) {
		$row['item_album'] = $this->get_post_text('item_album');
	}

	if ( $this->use_item('label') ) {
		$row['item_label'] = $this->get_post_text('item_label');
	}

	if ( $this->use_item('exif') ) {
		$row['item_exif'] = $this->get_post_text('item_exif');
	}

	if ( $this->use_item('content') ) {
		$row['item_content'] = $this->get_post_text('item_content');
	}

	if ( $this->use_item('page_width') ) {
		$row['item_page_width'] = $this->get_post_int('item_page_width');
	}

	if ( $this->use_item('page_height') ) {
		$row['item_page_height'] = $this->get_post_int('item_page_height');
	}

// description html
	if ( $this->_has_html ) {
		$row['item_description_html']   = $this->get_post_int( 'item_description_html' );
	}

// gmap
	if ( $this->use_gmap() ) {
		$row['item_gmap_latitude']  = $this->get_post_float( 'item_gmap_latitude' );
		$row['item_gmap_longitude'] = $this->get_post_float( 'item_gmap_longitude' );
		$row['item_gmap_zoom']      = $this->get_post_int(   'item_gmap_zoom' );
		$row['item_gicon_id']       = $this->get_post_int(   'item_gicon_id' );
	}

	if ( $this->_flag_admin ) {
		$row['item_uid']           = $this->get_post_int(   'item_uid' );

// kind
		$row['item_kind']          = $this->get_post_int(   'item_kind' );
		$row['item_displaytype']   = $this->get_post_int(   'item_displaytype' );
		$row['item_displayfile']   = $this->get_post_int(   'item_displayfile' );
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

function build_row_edit_by_post( $row, $flag_status=true )
{
	$item_id = $row['item_id'] ;

	$post_preview               = $this->get_post_text('preview');
	$post_submit                = $this->get_post_text('submit' );
	$post_detail_onclick        = $this->get_post_int( 'item_detail_onclick' );
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
	if ( $this->_flag_admin ) {
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
		$row['item_time_publish']   = $time_publish ;
		$row['item_time_expire']    = $time_expire ;
		$row['item_detail_onclick'] = $post_detail_onclick ;

// user
	} else {
		$row['item_time_update'] = time();
	}

	if ( $flag_status ) {
		$row['item_status'] = $this->build_modify_status( $row );
	}

	return $row;
}

function build_row_perm_by_post( $row, $flag_submit )
{
	$item_perm       = $this->build_item_perm_by_post_level();
	$item_perm_level = $this->get_post_int( 'item_perm_level' );
	$item_perm_read  = $this->get_group_perms_str_by_post( 'item_perm_read_ids' );
	$item_perm_down  = $this->get_group_perms_str_by_post( 'item_perm_down_ids' );

// admin
	if ( $this->use_item_perm_level_admin() ) {
		$row['item_perm_level'] = $item_perm_level ;
		$row['item_perm_read']  = $item_perm;

		if ( $this->use_item('perm_down') ) {
			$row['item_perm_down'] = $item_perm_down ;
		}

// user
	} elseif ( $flag_submit && $this->use_item_perm_level() ) {
		$row['item_perm_level'] = $item_perm_level;
		$row['item_perm_read']  = $item_perm;
		$row['item_perm_down']  = $item_perm;

	} elseif ( !$flag_submit && $this->editable_item_perm_level() ) {
		$row['item_perm_level'] = $item_perm_level;
		$row['item_perm_read']  = $item_perm;
		$row['item_perm_down']  = $item_perm;

// not use item_level
	} else {
		if ( $this->use_item_perm_read() ) {
			$row['item_perm_read'] = $item_perm_read ;
		}
		if ( $this->use_item('perm_down') ) {
			$row['item_perm_down'] = $item_perm_down ;
		}
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
	if ( $this->_flag_admin ) {
		return $this->build_modify_status_admin( $row );
	}
	return $this->build_modify_status_user( $row );
}

function build_modify_status_user( $row )
{
	$current_status = $row['item_status'] ;
	$new_status     = $current_status ;

	switch ( $current_status ) 
	{
		case _C_WEBPHOTO_STATUS_APPROVED : 
			$new_status = _C_WEBPHOTO_STATUS_UPDATED ;
			break;

		case _C_WEBPHOTO_STATUS_WAITING : 
		case _C_WEBPHOTO_STATUS_UPDATED :
		case _C_WEBPHOTO_STATUS_OFFLINE :
		case _C_WEBPHOTO_STATUS_EXPIRED :
		default:
			break;
	}

	return $new_status;
}

function build_modify_status_admin( $row )
{
	$post_valid  = $this->get_post_int('valid');
	$post_status = $this->get_post_int('item_status');

	$current_status = $row['item_status'] ;
	$time_publish   = $row['item_time_publish'] ;

	$new_status = $post_status ;

	switch ( $current_status ) 
	{
		case _C_WEBPHOTO_STATUS_WAITING : 
			if ( $post_valid == _C_WEBPHOTO_YES ) {
				$new_status = _C_WEBPHOTO_STATUS_APPROVED ;
			}
			break;

		case _C_WEBPHOTO_STATUS_APPROVED : 
			if ( $post_status == _C_WEBPHOTO_STATUS_APPROVED ) {
				$new_status = _C_WEBPHOTO_STATUS_UPDATED ;
			}
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
			if ( ( $time_publish > 0 ) &&
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

function use_item_perm_level_admin()
{
	if ( $this->_flag_admin && $this->use_item_perm_level() ) {
		return true;
	}
	return false;
}

function build_item_perm_by_post_level()
{
	$level  = $this->get_post_int( 'item_perm_level' );
	$cat_id = $this->get_post_int( 'item_cat_id' );
	return $this->build_item_perm_by_level_catid( $level, $cat_id );
}

function build_item_perm_by_level_catid( $level, $cat_id )
{
	switch ( $level ) 
	{
		case _C_WEBPHOTO_PERM_LEVEL_GROUP:
			$val = $this->build_item_perm_group_by_catid( $cat_id );
			break;

		case _C_WEBPHOTO_PERM_LEVEL_PUBLIC:
		default:
			$val = _C_WEBPHOTO_PERM_ALLOW_ALL ;
			break;
	}
	return $val;
}

function build_item_perm_group_by_catid( $cat_id )
{
	$arr = array( XOOPS_GROUP_ADMIN );

	$cat_row = $this->_cat_handler->get_cached_row_by_id( $cat_id );
	if ( is_array($cat_row) ) {
		$cat_group_id = $cat_row['cat_group_id'] ;
		if ( $cat_group_id > 0 ) {
			$arr[] = $cat_group_id;
		}
	}

	$val = $this->_utility_class->array_to_perm( $arr, _C_WEBPHOTO_PERM_SEPARATOR );
	return $val;
}

//---------------------------------------------------------
// use item class 
//---------------------------------------------------------
function use_item( $key )
{
	return $this->_use_item_class->use_item_or_admin( $key );
}

function use_item_perm_read()
{
	return $this->_use_item_class->use_item_perm_read();
}

function use_item_perm_level()
{
	return $this->_use_item_class->use_item_perm_level();
}

function editable_item_perm_level()
{
	return $this->_use_item_class->editable_item_perm_level();
}

function use_gmap()
{
	return $this->_use_item_class->use_gmap();
}

//---------------------------------------------------------
// files 
//---------------------------------------------------------
function build_row_files( $row, $file_id_array )
{
	if ( ! is_array($file_id_array) ) {
		return $row;
	}

	foreach( $this->_FILE_LIST as $file ) 
	{
		$file_id_name = $file.'_id';
		$file_id      = $this->get_array_value_by_key( $file_id_array, $file_id_name );
		if ( $file_id > 0 ) {
			$row = $this->build_row_files_individual( $row, $file, $file_id );
		}
	}

	return $row ;
}

function build_row_files_individual( $row, $file, $file_id )
{
	$const_name = strtoupper( '_C_WEBPHOTO_ITEM_FILE_'.$file );
	$const      = constant($const_name);
	$row[ $const ] = $file_id;

	switch ($file)
	{
		case 'thumb':
			$row['item_icon_name']   = '' ;
			$row['item_icon_width']  = 0 ;
			$row['item_icon_height'] = 0 ;
			break;

		case 'flash':
// BUG: player id is not correctly selected 
			$row['item_player_id']   = $this->_PLAYER_ID_FLASH_DEFAULT ;
			$row['item_displaytype'] = _C_WEBPHOTO_DISPLAYTYPE_MEDIAPLAYER ;
			if ( empty($row['item_displayfile']) ) {
				$row['item_displayfile'] = _C_WEBPHOTO_FILE_KIND_FLASH ;
			}
			break;

		case 'mp3':
			$row['item_displaytype'] = _C_WEBPHOTO_DISPLAYTYPE_MEDIAPLAYER ;
			$row['item_displayfile'] = _C_WEBPHOTO_FILE_KIND_MP3 ;
			break;

		case 'swf':
			$row['item_displaytype'] = _C_WEBPHOTO_DISPLAYTYPE_SWFOBJECT ;
			$row['item_displayfile'] = _C_WEBPHOTO_FILE_KIND_SWF ;
			break;

		case 'pdf':
			if ( $this->get_ini( 'item_detail_onclick_pdf' ) ) {
				$row['item_detail_onclick'] = _C_WEBPHOTO_FILE_KIND_PDF ;
			}
			break;
	}

	return $row;
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
// detail_onclick
//---------------------------------------------------------
function build_row_detail_onclick_if_empty( $row )
{
	if ( empty($row['item_detail_onclick']) ) {
		 $row['item_detail_onclick'] = $this->get_new_detail_onclick( $row ) ;
	}
	return $row;
}

function get_new_detail_onclick( $row )
{
	$item_ext = $row['item_ext'] ;

	$str = _C_WEBPHOTO_DETAIL_ONCLICK_DEFAULT ;
	if ( $this->is_image_ext( $item_ext ) ) {
		if ( $this->get_ini('item_detail_onclick_lightbox') ) {
			$str = _C_WEBPHOTO_DETAIL_ONCLICK_LIGHTBOX ;
		} else {
			$str = _C_WEBPHOTO_DETAIL_ONCLICK_IMAGE ;
		}
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