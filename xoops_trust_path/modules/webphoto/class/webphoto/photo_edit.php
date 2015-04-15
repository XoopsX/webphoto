<?php
// $Id: photo_edit.php,v 1.21 2008/12/18 13:23:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-12-12 K.OHWADA
// build_row_extend_by_post()
// 2008-11-29 K.OHWADA
// remove create_thumb_from_external() etc
// 2008-11-16 K.OHWADA
// item_codeinfo
// Fatal error: Call to undefined method set_item_external_thumb()
// 2008-11-08 K.OHWADA
// upload_fetch_middle()
// BUG: Undefined variable: exif
// 2008-11-04 K.OHWADA
// set_values_for_fetch_photo()
// 2008-10-01 K.OHWADA
// create_thumb_from_embed() etc
// photo_duration -> item_duration
// 2008-09-13 K.OHWADA
// get_post_cat_id()
// 2008-09-01 K.OHWADA
// photo_handler -> item_handler
// supported exif gps
// used preload_init()
// NOT use webphoto_photo_delete
// BUG: not show img alt
// 2008-08-12 K.OHWADA
// BUG: not show description in preview
// 2008-08-01 K.OHWADA
// used webphoto_photo_create
// not use msg_class
// 2008-07-01 K.OHWADA
// added create_video_thumb()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_photo_edit
//=========================================================
class webphoto_photo_edit extends webphoto_base_this
{
	var $_tag_class;
	var $_upload_class;
	var $_image_class;
	var $_build_class;
	var $_mime_class;
	var $_photo_class;
	var $_embed_class;

	var $_cfg_makethumb      = false;
	var $_cfg_allownoimage   = false ;
	var $_cfg_addposts       = 0 ;
	var $_cfg_fsize          = 0 ;
	var $_cfg_width          = 0 ;
	var $_cfg_height         = 0 ;
	var $_cfg_perm_item_read = 0 ;
	var $_has_insertable     = false;
	var $_has_superinsert    = false;
	var $_has_editable       = false;
	var $_has_deletable      = false;
	var $_has_image_resize   = false;
	var $_has_image_rotate   = false;

	var $_post_photo_id    = 0;
	var $_post_item_id     = 0;
	var $_post_item_cat_id = 0;
	var $_post_type        = null;

// overwrite param
	var $_item_title            = null;
	var $_item_datetime         = null;
	var $_item_equipment        = null;
	var $_item_duration         = 0 ;
	var $_item_exif             = null;
	var $_item_ext              = null;
	var $_item_displaytype      = 0 ;
	var $_item_onclick          = 0 ;
	var $_item_embed_type       = null;
	var $_item_embed_src        = null;
	var $_item_embed_text       = null;
	var $_item_external_url     = null;
	var $_item_external_thumb   = null;
	var $_item_external_middle  = null;
	var $_item_playlist_type    = 0;
	var $_item_playlist_feed    = null;
	var $_item_playlist_dir     = null;
	var $_item_gmap_latitude    = 0 ;
	var $_item_gmap_longitude   = 0 ;
	var $_item_gmap_zoom        = 0 ;
	var $_item_player_id        = 0 ;
	var $_item_page_width       = 0 ;
	var $_item_page_height      = 0 ;
	var $_item_kind             = _C_WEBPHOTO_ITEM_KIND_UNDEFINED ;

	var $_preview_name          = null;
	var $_tag_name_array        = null;

	var $_checkbox_array      = array();

	var $_photo_tmp_name    = null;
	var $_photo_media_type  = null;
	var $_photo_media_name  = null;
	var $_thumb_tmp_name    = null;
	var $_thumb_media_type  = null;
	var $_middle_tmp_name   = null;
	var $_middle_media_type = null;

	var $_image_thumb_url  = null;
	var $_image_thumb_path = null;
	var $_image_info       = null;

	var $_photo_param = null ;
	var $_video_param = null ;
	var $_file_params = null;

	var $_is_video_thumb_form = false;
	var $_form_action         = null;

	var $_tag_id_array = null;
	var $_only_image_extentions = false;

	var $_FLAG_ADMIN = false;

	var $_PHOTO_FIELD_NAME  = _C_WEBPHOTO_UPLOAD_FIELD_PHOTO ;
	var $_THUMB_FIELD_NAME  = _C_WEBPHOTO_UPLOAD_FIELD_THUMB ;
	var $_MIDDLE_FIELD_NAME = _C_WEBPHOTO_UPLOAD_FIELD_MIDDLE ;

	var $_ORDERBY_DEFAULT = 'idA' ;
	var $_NO_TITLE  = 'no title' ;

	var $_EXTERNAL_THUMB_EXT_DEFAULT = 'external';
	var $_EMBED_THUMB_EXT_DEFAULT    = 'embed';
	var $_PLAYLIST_THUMB_EXT_DEFAULT = 'playlist';

	var $_MSG_LEVEL = 0;
	var $_MSG_FIRST = false;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_photo_edit( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_photo_class    =& webphoto_photo_create::getInstance( $dirname , $trust_dirname );
	$this->_embed_class    =& webphoto_embed::getInstance( $dirname, $trust_dirname );
	$this->_build_class    =& webphoto_photo_build::getInstance( $dirname );
	$this->_mime_class     =& webphoto_mime::getInstance( $dirname );

	$this->_tag_class  =& webphoto_tag::getInstance( $dirname );
	$this->_tag_class->set_is_japanese( $this->_is_japanese );

	$this->_image_class =& webphoto_image_create::getInstance( $dirname , $trust_dirname );
	$this->_has_image_resize  = $this->_image_class->has_resize();
	$this->_has_image_rotate  = $this->_image_class->has_rotate();

	$this->_upload_class =& webphoto_upload::getInstance( $dirname , $trust_dirname );
	$this->_upload_class->set_flag_size_limit( !$this->_has_image_resize );

	$this->_has_insertable  = $this->_perm_class->has_insertable();
	$this->_has_superinsert = $this->_perm_class->has_superinsert();
	$this->_has_editable    = $this->_perm_class->has_editable();
	$this->_has_deletable   = $this->_perm_class->has_deletable();

	$this->_cfg_makethumb      = $this->get_config_by_name( 'makethumb' );
	$this->_cfg_allownoimage   = $this->get_config_by_name( 'allownoimage' );
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
		$instance = new webphoto_photo_edit( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// preload
//---------------------------------------------------------
function init_preload()
{
	$this->preload_init();
	$this->preload_constant();
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
	$this->get_post_item_id();
	$this->get_post_cat_id();

	$this->_post_photo_id         = $this->_post_class->get_post_get_int( 'photo_id' );
	$this->_post_type             = $this->_post_class->get_post_get_text( 'type' );
	$this->_item_duration         = $this->_post_class->get_post_int(   'item_duration' );
	$this->_item_kind             = $this->_post_class->get_post_int(   'item_kind' );
	$this->_item_displaytype      = $this->_post_class->get_post_int(   'item_displaytype' );
	$this->_item_onclick          = $this->_post_class->get_post_int(   'item_onclick' );
	$this->_item_exif             = $this->_post_class->get_post_text(  'item_exif' );
	$this->_item_embed_type       = $this->_post_class->get_post_text(  'item_embed_type' );
	$this->_item_embed_src        = $this->_post_class->get_post_text(  'item_embed_src' );
	$this->_item_embed_text       = $this->_post_class->get_post_text(  'item_embed_text' );
	$this->_item_external_url     = $this->_post_class->get_post_text(  'item_external_url' );
	$this->_item_external_thumb   = $this->_post_class->get_post_text(  'item_external_thumb' );
	$this->_item_external_middle  = $this->_post_class->get_post_text(  'item_external_middle' );
	$this->_item_playlist_type    = $this->_post_class->get_post_int(   'item_playlist_type' );
	$this->_item_playlist_feed    = $this->_post_class->get_post_text(  'item_playlist_feed' );
	$this->_item_playlist_dir     = $this->_post_class->get_post_text(  'item_playlist_dir' );
	$this->_item_gmap_latitude    = $this->_post_class->get_post_float( 'item_gmap_latitude' );
	$this->_item_gmap_longitude   = $this->_post_class->get_post_float( 'item_gmap_longitude' );
	$this->_item_gmap_zoom        = $this->_post_class->get_post_int(   'item_gmap_zoom' );
	$this->_item_player_id        = $this->_post_class->get_post_int(   'item_player_id' );
	$this->_item_page_width       = $this->_post_class->get_post_int(   'item_page_width' );
	$this->_item_page_height      = $this->_post_class->get_post_int(   'item_page_height' );

	$this->set_item_title(     $this->_post_class->get_post_text( 'item_title' ) );
	$this->set_item_equipment( $this->_post_class->get_post_text( 'item_equipment' ) );

	$this->set_item_datetime_by_post();

	$this->set_checkbox_by_post( 'item_time_update_checkbox' );

	$this->set_preview_name( $this->_post_class->get_post_text( 'preview_name' ) );
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

	$this->_post_item_cat_id = intval( $str ) ;
}

function build_row_by_post( $row, $is_submit=false, $flag_title=true )
{
// overwrite if title is blank
	if ( $flag_title ) {
		$this->overwrite_item_title_if_empty( $this->_NO_TITLE );
	}

	$row['item_title']            = $this->get_item_title();
	$row['item_cat_id']           = $this->_post_item_cat_id;
	$row['item_equipment']        = $this->get_item_equipment();
	$row['item_exif']             = $this->_item_exif ;
	$row['item_embed_type']       = $this->_item_embed_type ;
	$row['item_embed_src']        = $this->_item_embed_src ;
	$row['item_embed_text']       = $this->_item_embed_text ;
	$row['item_external_url']     = $this->_item_external_url ;
	$row['item_external_thumb']   = $this->_item_external_thumb ;
	$row['item_external_middle']  = $this->_item_external_middle ;
	$row['item_gmap_latitude']    = $this->_item_gmap_latitude ;
	$row['item_gmap_longitude']   = $this->_item_gmap_longitude ;
	$row['item_gmap_zoom']        = $this->_item_gmap_zoom ;
	$row['item_player_id']        = $this->_item_player_id ;
	$row['item_page_width']       = $this->_item_page_width ;
	$row['item_page_height']      = $this->_item_page_height ;

	if ( $this->is_fill_item_datetime() ) {
		$row['item_datetime'] = $this->get_item_datetime();
	}

	if ( $this->is_fill_item_ext() ) {
		$row['item_ext'] = $this->get_item_ext();
	}

	if ( ! $this->is_item_undefined_kind() ) {
		$row['item_kind'] = $this->get_item_kind();
	}

	if ( $is_submit || $this->_FLAG_ADMIN ) {
		$row['item_displaytype'] = $this->_item_displaytype ;
		$row['item_onclick']     = $this->_item_onclick ;
	}

	if ( $this->_FLAG_ROW_EXTEND ) {
		$row = $this->build_row_extend_by_post( $row, $is_submit );
	}

	return $row;
}

function build_row_extend_by_post( $row, $is_submit=false )
{

	$row['item_gicon_id']         = $this->_post_class->get_post_int(  'item_gicon_id' );
	$row['item_place']            = $this->_post_class->get_post_text( 'item_place' );
	$row['item_description']      = $this->_post_class->get_post_text( 'item_description' );
	$row['item_siteurl']          = $this->_post_class->get_post_text( 'item_siteurl' );
	$row['item_artist']           = $this->_post_class->get_post_text( 'item_artist' );
	$row['item_album']            = $this->_post_class->get_post_text( 'item_album' );
	$row['item_label']            = $this->_post_class->get_post_text( 'item_label' );
	$row['item_perm_down']        = $this->get_group_perms_str_by_post( 'item_perm_down_ids' );
	$row['item_codeinfo']         = $this->build_info_by_post( 'item_codeinfo' );

	if ( $this->_cfg_perm_item_read > 0 ) {
		$row['item_perm_read'] = $this->get_group_perms_str_by_post( 'item_perm_read_ids' );
	}

// for future
//	$row['item_showinfo']         = $this->build_info_by_post( 'item_showinfo' );

	if ( $this->_FLAG_ADMIN ) {
		$row['item_playlist_type'] = $this->_item_playlist_type ;
		$row['item_playlist_feed'] = $this->_item_playlist_feed ;
		$row['item_playlist_dir']  = $this->_item_playlist_dir ;
		$row['item_playlist_time'] = 
			$this->_post_class->get_post_int( 'item_playlist_time' ) ;
	}

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_TEXT; $i++ ) 
	{
		$name = $this->_item_handler->build_name_text_by_kind( $i );
		$row[ $name ] = $this->_post_class->get_post_text( $name );
	}

	$post_tags = $this->_post_class->get_post_text( 'tags' );
	$this->set_tag_name_array( $this->_tag_class->str_to_tag_name_array( $post_tags ) );

	return $row;
}

function build_info_by_post( $name )
{
	return $this->_item_handler->build_info( 
		$this->_post_class->get_post( $name ) );
}

function set_checkbox_by_post( $name )
{
	$this->set_checkbox_by_name( $name, $this->_post_class->get_post_int( $name ) );
}

function set_checkbox_by_name( $name, $value )
{
	$this->_checkbox_array[ $name ] = $value;
}

function get_checkbox_array()
{
	 return $this->_checkbox_array;
}

function get_checkbox_by_name( $name )
{
	if ( isset( $this->_checkbox_array[ $name ] ) ) {
		 return $this->_checkbox_array[ $name ];
	}
	return null;
}

function set_preview_name( $val )
{
	$this->_preview_name = $val;
}

function get_preview_name()
{
	return $this->_preview_name;
}

function set_tag_name_array( $val )
{
	if ( is_array($val) ) {
		$this->_tag_name_array = $val;
	}
}

function get_tag_name_array()
{
	return $this->_tag_name_array;
}

//---------------------------------------------------------
// check
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
// is type
//---------------------------------------------------------
function is_embed_type()
{
	if ( $this->_post_type == 'embed' ) {
		return true;
	}
	if ( $this->_item_embed_type ) {
		return true;
	}
	return false;
}

function is_external_type()
{
	return $this->is_fill_item_external_url() ;
}

function is_post_playlist_type()
{
	if ( $this->_post_type == 'playlist' ) {
		return true;
	}
	if ( $this->_item_playlist_type > 0 ) {
		return true;
	}
	return false;
}

function is_admin_playlist_type()
{
	if ( $this->_FLAG_ADMIN && $this->is_post_playlist_type() ) {
		return true;
	}
	return false;
}

function is_flashvar_form()
{
	if ( $this->_form_action == 'flashvar_form' ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// photo title
//---------------------------------------------------------
function set_item_title( $val )
{
	$this->_item_title = $val;
}

function get_item_title()
{
	return $this->_item_title;
}

function overwrite_item_title_by_media_name_if_empty()
{
	$this->overwrite_item_title_if_empty(
		$this->strip_ext( $this->upload_media_name() ) );
}

function overwrite_item_title_if_empty( $val )
{
	if ( !$this->is_fill_item_title() && $val ) {
		$this->_item_title = $val;
	}
}

function is_fill_item_title()
{
	if ( $this->_item_title ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// photo equipment
//---------------------------------------------------------
function set_item_equipment( $val )
{
	$this->_item_equipment = $val;
}

function get_item_equipment()
{
	return $this->_item_equipment;
}

function overwrite_item_equipment( $val )
{
	if ( $val ) {
		$this->_item_equipment = $val;
	}
}

//---------------------------------------------------------
// photo datetime
//---------------------------------------------------------
function set_item_datetime_by_post()
{
	$flag = false;

	$this->set_checkbox_by_post( 'item_datetime_checkbox' );
	$checkbox = $this->get_checkbox_by_name( 'item_datetime_checkbox' );

	$datetime = $this->_item_handler->build_datetime_by_post( 'item_datetime' );

	if ( ( $checkbox == _C_WEBPHOTO_YES ) && $datetime ) {
		$flag = true;
	} elseif ( $checkbox == _C_WEBPHOTO_NO ) {
		$flag     = true;
		$datetime = null;
	}

	$this->set_item_datetime(      $datetime );
	$this->set_item_datetime_flag( $flag );
}

function set_item_datetime( $val )
{
	$this->_item_datetime = $val;
}

function set_item_datetime_flag( $val )
{
	$this->_item_datetime_flag = (bool)$val;
}

function get_item_datetime()
{
	return $this->_item_datetime;
}

function get_item_datetime_flag()
{
	return $this->_item_datetime_flag;
}

function overwrite_item_datetime( $datetime )
{
	if ( empty($datetime) ) { return false; }

	$this->set_item_datetime(      $datetime );
	$this->set_item_datetime_flag( true );

}

function is_fill_item_datetime()
{
	if ( $this->_item_datetime_flag ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// photo exif
//---------------------------------------------------------
function overwrite_item_exif( $val )
{
	if ( $val ) {
		$this->_item_exif = $val;
	}
}

//---------------------------------------------------------
// photo gmap
//---------------------------------------------------------
// BUG: Undefined variable: exif
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
// photo duration
//---------------------------------------------------------
function set_item_duration( $val )
{
	$this->_item_duration = intval($val);
}

function get_item_duration()
{
	return $this->_item_duration;
}

function overwrite_item_duration( $val )
{
	if ( $val ) {
		$this->_item_duration = intval($val);
	}
	
}

//---------------------------------------------------------
// photo ext 
//---------------------------------------------------------
function set_item_ext( $val )
{
	$this->_item_ext = $val;
}

function get_item_ext()
{
	return $this->_item_ext;
}

function is_fill_item_ext()
{
	if ( $this->_item_ext ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// photo kind
//---------------------------------------------------------
function set_item_kind( $val )
{
	$this->_item_kind = intval($val);
}

function get_item_kind()
{
	return $this->_item_kind;
}

function is_item_undefined_kind()
{
	return $this->is_undefined_kind( $this->_item_kind );
}

//---------------------------------------------------------
// displaytype
//---------------------------------------------------------
function set_item_displaytype( $val )
{
	$this->_item_displaytype = intval($val);
}

function get_item_displaytype()
{
	return $this->_item_displaytype;
}

//---------------------------------------------------------
// onclick
//---------------------------------------------------------
function set_item_onclick( $val )
{
	$this->_item_onclick = intval($val);
}

function get_item_onclick()
{
	return $this->_item_onclick;
}

//---------------------------------------------------------
// palyer id
//---------------------------------------------------------
function set_item_player_id( $val )
{
	$this->_item_player_id = intval($val);
}

function get_item_player_id()
{
	return $this->_item_player_id;
}

//---------------------------------------------------------
// external thumb
//---------------------------------------------------------
function is_fill_item_external_url()
{
	if ( $this->_item_external_url ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// external thumb
//---------------------------------------------------------
function overwrite_item_external_thumb_if_empty( $val )
{
	if ( !$this->is_fill_item_external_thumb() && $val ) {
		$this->_item_external_thumb = $val;
	}
}

// Fatal error: Call to undefined method set_item_external_thumb()
function set_item_external_thumb( $val )
{
	$this->_item_external_thumb = $val;
}

function get_item_external_thumb()
{
	return $this->_item_external_thumb;
}

function is_fill_item_external_thumb()
{
	if ( $this->_item_external_thumb ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// external middle
//---------------------------------------------------------
function overwrite_item_external_middle_if_empty( $val )
{
	if ( !$this->is_fill_item_external_middle() && $val ) {
		$this->_item_external_middle = $val;
	}
}

function get_item_external_middle()
{
	return $this->_item_external_middle;
}

function is_fill_item_external_middle()
{
	if ( $this->_item_external_middle ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// embed
//---------------------------------------------------------
function is_fill_item_embed_src()
{
	if ( $this->_item_embed_src ) {
		return true;
	}
	return false;
}

function is_fill_item_embed_text()
{
	if ( $this->_item_embed_text ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// playlist
//---------------------------------------------------------
function is_fill_item_playlist_feed()
{
	if ( $this->_item_playlist_feed ) {
		return true;
	}
	return false;
}

function is_fill_item_playlist_dir()
{
	if ( $this->_item_playlist_dir ) {
		return true;
	}
	return false;
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

	$item_param  = $this->_photo_class->get_item_param_extention( $src_file );
	$video_param = $this->_photo_class->get_video_param();

	$this->set_item_ext(  $item_param['item_ext'] );
	$this->set_item_kind( $item_param['item_kind'] );

	if ( isset( $item_param['item_datetime'] ) ) {
		$this->overwrite_item_datetime( $item_param['item_datetime'] );
	}

	if ( isset( $item_param['item_equipment'] ) ) {
		$this->overwrite_item_equipment( $item_param['item_equipment'] );
	}

	if ( isset( $item_param['item_exif'] ) ) {
		$this->overwrite_item_exif( $item_param['item_exif'] );
	}

	if ( isset( $item_param['item_gmap_latitude'] ) &&
		 isset( $item_param['item_gmap_longitude'] ) &&
		 isset( $item_param['item_gmap_zoom'] ) ) {

		$this->overwrite_item_gmap( 
			$item_param['item_gmap_latitude'], 
			$item_param['item_gmap_longitude'], 
			$item_param['item_gmap_zoom'] );
	}

	if ( isset( $item_param['item_duration'] ) ) {
		$this->overwrite_item_duration( $item_param['item_duration'] ) ;
	}

	if ( is_array( $video_param ) ) {
		$this->_video_param = $video_param ;
	}

}

function upload_fetch_thumb()
{
	$this->_thumb_tmp_name   = null;
	$this->_thumb_media_type = null;

// if thumb file uploaded
	$ret = $this->_upload_class->fetch_image( $this->_THUMB_FIELD_NAME );

	if ( $ret < 0 ) {
		$this->set_error( $this->_upload_class->get_errors() );
	}
	if ( $ret == 1 ) {
		$this->_thumb_tmp_name   = $this->_upload_class->get_tmp_name();
		$this->_thumb_media_type = $this->_upload_class->get_uploader_media_type();
	}
}

function upload_fetch_middle()
{
	$this->_middle_tmp_name   = null;
	$this->_middle_media_type = null;

	$ret = $this->_upload_class->fetch_image( $this->_MIDDLE_FIELD_NAME );
	if ( $ret < 0 ) {
		$this->set_error( $this->_upload_class->get_errors() );
	}
	if ( $ret == 1 ) {
		$this->_middle_tmp_name   = $this->_upload_class->get_tmp_name();
		$this->_middle_media_type = $this->_upload_class->get_uploader_media_type();
	}
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

	$item_id          = $photo_param['item_id'] ;
	$src_kind         = $photo_param['src_kind'] ;
	$flag_video_thumb = $photo_param['flag_video_thumb'] ;
	$param            = $photo_param ;

	$ret = $this->_photo_class->create_cont_param( $item_id, $param );
	if ( $ret < 0 ) {
		return $ret;
	}

	$cont_param = $this->_photo_class->get_cont_param();
	if ( $this->_photo_class->get_resized() ) {
		$this->set_msg_array( $this->get_constant('SUBMIT_RESIZED') ) ;
	}

	if ( $this->is_video_kind( $src_kind ) && is_array( $cont_param ) ) {

// video flash
		$flash_param = $this->_photo_class->create_video_flash_param( $item_id, $param );

		if ( $this->_photo_class->get_video_flash_failed() ) {
			$this->set_msg_array( $this->get_constant('ERR_VIDEO_FLASH') ) ;
		}

// video thumb
		if ( $flag_video_thumb ) {
			$param['mode_video_thumb'] = _C_WEBPHOTO_VIDEO_THUMB_PLURAL ;
			$this->_photo_class->create_video_thumb( $item_id, $param );

			if ( $this->_photo_class->get_video_thumb_created() ) {
				$this->_is_video_thumb_form = true;
			}
			if ( $this->_photo_class->get_video_thumb_failed() ) {
				$this->set_msg_array( $this->get_constant('ERR_VIDEO_THUMB') ) ;
			}
		}

// video docomo
		$docomo_param = $this->_photo_class->create_video_docomo_param( $item_id, $cont_param );
	}

	$this->_file_params = array(
		'cont'   => $cont_param ,
		'flash'  => $flash_param ,
		'docomo' => $docomo_param ,
	);

	return 0;
}

//---------------------------------------------------------
// create thumb
//---------------------------------------------------------
function create_thumb_param_by_tmp( $item_id, $thumb_name )
{
	if ( empty($thumb_name) ) {
		return null;
	}

	$thumb_file = $this->_TMP_DIR .'/'. $thumb_name;
	$this->_photo_class->create_thumb_from_image_file( $thumb_file, $item_id );
	$thumb_param = $this->_photo_class->get_thumb_param();
	$this->unlink_file( $thumb_file );

	return $thumb_param ;
}

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
function create_middle_param_by_tmp( $item_id, $middle_name )
{
	if ( empty($middle_name) ) {
		return null;
	}

	$middle_file = $this->_TMP_DIR .'/'. $middle_name;
	$this->_photo_class->create_middle_from_image_file( $middle_file, $item_id );
	$middle_param = $this->_photo_class->get_middle_param();
	$this->unlink_file( $middle_file );

	return $middle_param ;
}

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

function get_file_params()
{
	return $this->_file_params ;
}

function conv_rotate( $rotate )
{
	$rot = 0 ;
	switch( $rotate ) 
	{
		case 'rot270' :
			$rot = 270 ;
			break ;

		case 'rot180' :
			$rot = 180 ;
			break ;

		case 'rot90' :
			$rot = 90 ;
			break ;

		case 'rot0' :
		default :
			break ;
	}
	return $rot;
}

//---------------------------------------------------------
// mime type
//---------------------------------------------------------
function add_mime_if_empty( $photo_param )
{
// no image  info
	if ( !is_array($photo_param) || !count($photo_param) ) {
		return $photo_param;
	}

// if set mime
	if ( $photo_param['item_cont_mime'] ) {
		return $photo_param;
	}

// if not set mime
	$mime = $this->_photo_media_type;
	$photo_param['item_cont_mime'] = $mime ;
	$photo_param['item_file_mime'] = $mime ;

// if video type
	if ( $this->_mime_class->is_video_mime( $mime ) ) {
		$medium = $this->_mime_class->get_video_medium();
		$photo_param['item_cont_medium'] = $medium ;
		$photo_param['item_file_medium'] = $medium ;
	}

	return $photo_param;
}

//---------------------------------------------------------
// insert
//---------------------------------------------------------
function build_search_for_edit( $photo_row, $tag_name_array=null )
{
	return $this->_build_class->build_search( $photo_row, $tag_name_array );
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
	return $this->is_readable_in_tmp_dir( $this->get_preview_name() );
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