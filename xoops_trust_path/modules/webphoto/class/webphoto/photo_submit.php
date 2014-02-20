<?php
// $Id: photo_submit.php,v 1.1 2009/01/06 09:42:30 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-04 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_photo_submit
//=========================================================
class webphoto_photo_submit extends webphoto_imagemanager_submit
{
	var $_embed_class ;
	var $_editor_class ;
	var $_tag_class;

// post
	var $_post_type = null;

// item
	var $_item_editor           = null;
	var $_item_embed_type       = null;
	var $_item_embed_src        = null;
	var $_item_embed_text       = null;
	var $_item_external_url     = null;
	var $_item_external_thumb   = null;
	var $_item_external_middle  = null;
	var $_item_playlist_type    = 0;
	var $_item_playlist_feed    = null;
	var $_item_playlist_dir     = null;
	var $_item_player_id        = 0 ;
	var $_item_page_width       = 0 ;
	var $_item_page_height      = 0 ;

	var $_checkbox_array = array();
	var $_form_action    = null;

	var $_EXTERNAL_THUMB_EXT_DEFAULT = 'external';
	var $_EMBED_THUMB_EXT_DEFAULT    = 'embed';
	var $_PLAYLIST_THUMB_EXT_DEFAULT = 'playlist';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_photo_submit( $dirname , $trust_dirname )
{
	$this->webphoto_imagemanager_submit( $dirname , $trust_dirname );

	$this->_embed_class  =& webphoto_embed::getInstance( $dirname, $trust_dirname );
	$this->_editor_class =& webphoto_editor::getInstance( $dirname, $trust_dirname );

	$this->_tag_class  =& webphoto_tag::getInstance( $dirname );
	$this->_tag_class->set_is_japanese( $this->_is_japanese );

	$this->_FLAG_FETCH_ALLOW_ALL = true ;
	$this->_FLAG_FETCH_THUMB     = true ;
	$this->_FLAG_ALLOW_NONE      = $this->get_config_by_name( 'allownoimage' ) ;

}

// for admin_photo_manage admin_catmanager
function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_photo_submit( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// post param
//---------------------------------------------------------
// overwrite
function get_post_param()
{
	$this->get_post_param_basic();

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
	$this->_item_equipment        = $this->_post_class->get_post_text( 'item_equipment' ) ;
	$this->_preview_name          = $this->_post_class->get_post_text( 'preview_name' ) ;

	$this->set_item_datetime_by_post();
	$this->set_checkbox_by_post( 'item_time_update_checkbox' );
}

// overwrite
function build_row_by_post( $row, $is_submit=false, $flag_title=true )
{
	$row = $this->build_row_basic_by_post( $row, $is_submit, $flag_title );

	$row['item_embed_type']       = $this->_item_embed_type ;
	$row['item_embed_src']        = $this->_item_embed_src ;
	$row['item_embed_text']       = $this->_item_embed_text ;
	$row['item_external_url']     = $this->_item_external_url ;
	$row['item_external_thumb']   = $this->_item_external_thumb ;
	$row['item_external_middle']  = $this->_item_external_middle ;
	$row['item_player_id']        = $this->_item_player_id ;
	$row['item_page_width']       = $this->_item_page_width ;
	$row['item_page_height']      = $this->_item_page_height ;
	$row['item_gicon_id']         = $this->_post_class->get_post_int(  'item_gicon_id' );
	$row['item_place']            = $this->_post_class->get_post_text( 'item_place' );
	$row['item_siteurl']          = $this->_post_class->get_post_text( 'item_siteurl' );
	$row['item_artist']           = $this->_post_class->get_post_text( 'item_artist' );
	$row['item_album']            = $this->_post_class->get_post_text( 'item_album' );
	$row['item_label']            = $this->_post_class->get_post_text( 'item_label' );
	$row['item_perm_down']        = $this->get_group_perms_str_by_post( 'item_perm_down_ids' );
	$row['item_codeinfo']         = $this->build_info_by_post( 'item_codeinfo' );

// for future
//	$row['item_showinfo']         = $this->build_info_by_post( 'item_showinfo' );

// perm
	if ( $this->_cfg_perm_item_read > 0 ) {
		$row['item_perm_read'] = $this->get_group_perms_str_by_post( 'item_perm_read_ids' );
	}

// description
	$row['item_description'] = $this->_post_class->get_post_text( 'item_description' );
	$row['item_editor']      = $this->_post_class->get_post_text( 'item_editor' );

	if ( $this->_has_html ) {
		$row['item_description_html']   = $this->_post_class->get_post_int( 'item_description_html' );
		$row['item_description_smiley'] = $this->_post_class->get_post_int( 'item_description_smiley' );
		$row['item_description_xcode']  = $this->_post_class->get_post_int( 'item_description_xcode' );
		$row['item_description_image']  = $this->_post_class->get_post_int( 'item_description_image' );
		$row['item_description_br']     = $this->_post_class->get_post_int( 'item_description_br' );
	}

// playlist
	if ( $this->_FLAG_ADMIN ) {
		$row['item_playlist_type'] = $this->_item_playlist_type ;
		$row['item_playlist_feed'] = $this->_item_playlist_feed ;
		$row['item_playlist_dir']  = $this->_item_playlist_dir ;
		$row['item_playlist_time'] = 
			$this->_post_class->get_post_int( 'item_playlist_time' ) ;
	}

// text
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

//---------------------------------------------------------
// item
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

	$this->_item_datetime      = $datetime ;
	$this->_item_datetime_flag = $flag ;
}

function overwrite_item_external_thumb_if_empty( $val )
{
	if ( empty($this->_item_external_thumb) && $val ) {
		$this->_item_external_thumb = $val;
	}
}

//---------------------------------------------------------
// checkbox
//---------------------------------------------------------
function set_checkbox_by_post( $name )
{
	$this->set_checkbox_by_name( $name, $this->_post_class->get_post_int( $name ) );
}

function set_checkbox_by_name( $name, $value )
{
	$this->_checkbox_array[ $name ] = $value;
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
// is type
//---------------------------------------------------------
// overwrite
function is_upload_type()
{
	if ( $this->is_embed_type() ) {
		return false ;
	}
	if ( $this->is_external_type() ) {
		return false ;
	}
	if ( $this->is_admin_playlist_type() ) {
		return false ;
	}
	return true;
}

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
	$ret = empty($this->_item_external_url) ? false : true ;
	return $ret ;
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

function is_item_playlist_type_general()
{
	return $this->is_playlist_type_general(
		$this->_item_playlist_type );
}

function is_item_playlist_type_image()
{
	return $this->is_playlist_type_image(
		$this->_item_playlist_type );
}

function is_playlist_type_general( $type )
{
	switch ( $type )
	{
		case _C_WEBPHOTO_PLAYLIST_TYPE_AUDIO :
		case _C_WEBPHOTO_PLAYLIST_TYPE_VIDEO :
		case _C_WEBPHOTO_PLAYLIST_TYPE_FLASH :
			return true;
	}

	return false;
}

function is_playlist_type_image( $type )
{
	switch ( $type )
	{
		case _C_WEBPHOTO_PLAYLIST_TYPE_IMAGE :
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

function is_show_extra_form()
{
	if ( $this->_item_cat_id > 0 ) {
		return false;
	}
	return $this->is_upload_type();
}

function is_show_form_editor( $options )
{
// false if edit form
	$editor_form = $this->_post_class->get_post_int('editor_form');
	if ( $editor_form ) {
		return false;
	}

	return $this->is_show_form_editor_admin( $options );
}

function is_show_form_editor_admin( $options )
{
// true if options
	if ( is_array($options) && count($options) ) {
		return true;
	}

	return false;
}

//---------------------------------------------------------
// submit form
//---------------------------------------------------------
// overwrite
function build_submit_default_row()
{
	$row = $this->build_submit_default_row_basic();

	$this->_post_type           = $this->_post_class->get_post_get_text( 'type' );
	$this->_item_external_url   = $this->_post_class->get_post_text( 'item_external_url' );
	$this->_item_embed_type     = $this->_post_class->get_post_text( 'item_embed_type' );
	$this->_item_playlist_type  = $this->_post_class->get_post_text( 'item_playlist_type' );
	$this->_item_editor         = $this->_post_class->get_post_text( 'item_editor' ) ;

// set checked
	$this->set_checkbox_by_name( 'item_datetime_checkbox', _C_WEBPHOTO_NO );

	$options = $this->_editor_class->display_options( $this->_item_editor, $this->_has_html );

	$row['item_embed_type']     = $this->_item_embed_type;
	$row['item_playlist_type']  = $this->_item_playlist_type;
	$row['item_editor']         = $this->_item_editor;
	$row['item_datetime']       = $this->get_mysql_date_today();

	if ( $this->_has_html ) {
		$row['item_description_html'] = _C_WEBPHOTO_YES  ;
	}

	if ( is_array($options) ) {
		$row['item_description_smiley'] = $options['smiley']  ;
		$row['item_description_xcode']  = $options['xcode']  ;
		$row['item_description_image']  = $options['image']  ;
		$row['item_description_br']     = $options['br']  ;
	}

	return $row;
}

function build_submit_preview_row()
{
	$row = $this->_item_handler->create( true );
	$row = $this->build_row_by_post( $row, false, false );

	$row['item_cat_id'] = $this->_item_cat_id;
	$row['item_uid']    = $this->_xoops_uid;

	if ( $row['item_datetime'] ) {
		$this->set_checkbox_by_name( 'item_datetime_checkbox', _C_WEBPHOTO_YES );
	} else {
		$row['item_datetime'] = $this->get_mysql_date_today();
	}

	return $row;
}

function build_form_param( $mode )
{
	list ( $types, $allowed_exts ) = $this->_mime_class->get_my_allowed_mimes();

	$param = array(
		'mode'            => $mode,
		'type'            => $this->_post_type ,
		'preview_name'    => $this->_preview_name,
		'tag_name_array'  => $this->_tag_name_array,
		'checkbox_array'  => $this->_checkbox_array,
		'has_resize'      => $this->_has_image_resize,
		'has_rotate'      => $this->_has_image_rotate,
		'allowed_exts'    => $allowed_exts ,
	);

	return $param;
}

//---------------------------------------------------------
// submit
//---------------------------------------------------------
// overwrite
function submit_exec_fetch()
{
	if ( $this->is_external_type() ) {
		$this->set_ext_when_external() ;
		$this->set_title_when_external() ;

	} elseif ( $this->is_embed_type() ) {
		$this->set_title_when_embed() ;
		$this->set_thumb_when_embed() ;

	} elseif ( $this->is_admin_playlist_type() ) {
		$this->set_title_when_playlist() ;
		$this->set_player_when_playlist() ;

// fetch photo
	} else {
		$ret = $this->submit_exec_fetch_photo();
		if ( $ret < 0 ) { 
			return $ret;	// failed
		}
	}

// fetch thumb middle
	if ( $this->_FLAG_FETCH_THUMB ) {
		$this->upload_fetch_thumb();
		$this->upload_fetch_middle();
	}

	return 0; 
}

// overwrite
function submit_exec_fetch_check()
{
// BUG: not set external type
// external type
	if ( $this->is_external_type() ) {
		return 0;
	}

// embed type
	if ( $this->is_embed_type() ) {
		if ( $this->_item_embed_src || $this->_item_embed_text ) {
			return 0;
		} else {
			return _C_WEBPHOTO_ERR_EMBED;
		}
	}

// playlist type
	if ( $this->is_admin_playlist_type() ) {
		if ( $this->_item_playlist_feed ) {
			return 0;
		} elseif ( $this->_item_playlist_dir ) {
			return 0;
		} else {
			return _C_WEBPHOTO_ERR_PLAYLIST;
		}
	}

	return $this->submit_exec_fetch_check_basic();
}

// overwrite
function submit_exec_tag_save( $item_id )
{
	$ret = $this->_tag_class->add_tags( 
		$item_id, $this->_xoops_uid, $this->get_tag_name_array() );
	if ( !$ret ) { 
		return _C_WEBPHOTO_ERR_DB; 
	}
	return 0;
}

// overwrite
function submit_exec_playlist_save( $row )
{
// playlist cache
	if ( $this->is_admin_playlist_type() ) {
		$ret = $this->_playlist_class->create_cache_by_item_row( $row );
		if ( !$ret ) {
			$this->set_msg_array( $this->_playlist_class->get_errors() );
		}
	}
}

// overwrite
function submit_exec_post_count()
{
	$xoops_user_class =& webphoto_xoops_user::getInstance();
	$xoops_user_class->increment_post_by_num_own( $this->_cfg_addposts );
}

// overwrite
function submit_exec_notify( $row )
{
	if ( ! $this->get_new_status() ) {
		return;
	}

// Trigger Notification when supper insert
	$notification_class =& webphoto_notification_event::getInstance(
		$this->_DIRNAME , $this->_TRUST_DIRNAME );
	$notification_class->notify_new_photo( 
		$row['item_id'],  $row['item_cat_id'],  $row['item_title'] );
}

// overwrite
function build_update_item_row( $item_row, $file_params )
{
	$playlist_cache = $this->get_playlist_cache_if_empty( $item_row );
	$update_row = $this->build_update_item_row_basic( $item_row, $file_params, $playlist_cache );

// set by create_thumb_from_external
	if ( empty( $update_row['item_external_thumb'] ) ) {
		$update_row['item_external_thumb'] = $this->_item_external_thumb ;
	}

	return $update_row;
}

// overwrite
function get_new_kind()
{
	$kind = _C_WEBPHOTO_ITEM_KIND_GENERAL ;

// external
	if ( $this->is_external_type() ) {
		if ( $this->is_item_image_ext() ) {
			$kind = _C_WEBPHOTO_ITEM_KIND_EXTERNAL_IMAGE ;
		} else {
			$kind = _C_WEBPHOTO_ITEM_KIND_EXTERNAL_GENERAL ;
		}

// upload
	} elseif ( $this->_item_ext ) {
		$kind = $this->get_kind_by_item_ext();

// embed
	} elseif ( $this->is_embed_type() ) {
		$kind = _C_WEBPHOTO_ITEM_KIND_EMBED ;

// playlist
	} elseif ( $this->is_admin_playlist_type() ) {
		if ( $this->_item_playlist_feed ) {
			$kind = _C_WEBPHOTO_ITEM_KIND_PLAYLIST_FEED ;

		} elseif( $this->_item_playlist_dir ) {
			$kind = _C_WEBPHOTO_ITEM_KIND_PLAYLIST_DIR ;
		}
	}

	return $kind ;
}

// overwrite
function get_new_displaytype()
{
	$str = _C_WEBPHOTO_DISPLAYTYPE_GENERAL ;

	if ( $this->is_item_image_ext() ) {
		$str = _C_WEBPHOTO_DISPLAYTYPE_IMAGE ;

	} elseif ( $this->is_swfobject_ext( $this->_item_ext ) ) {
		$str = _C_WEBPHOTO_DISPLAYTYPE_SWFOBJECT ;

	} elseif ( $this->is_mediaplayer_ext( $this->_item_ext ) ) {
		$str = _C_WEBPHOTO_DISPLAYTYPE_MEDIAPLAYER ;

	} elseif ( $this->is_embed_type() ) {
		$str = _C_WEBPHOTO_DISPLAYTYPE_EMBED ;

	} elseif ( $this->is_admin_playlist_type() ) {
		if ( $this->is_item_playlist_type_general() ) {
			$str = _C_WEBPHOTO_DISPLAYTYPE_MEDIAPLAYER ;

		} elseif( $this->is_item_playlist_type_image() ) {
			$str = _C_WEBPHOTO_DISPLAYTYPE_IMAGEROTATOR ;
		}
	}

	return $str ;
}

function set_ext_when_external()
{
	$ext = $this->parse_ext( $this->_item_external_url ) ;
	if ( $ext ) {
		$this->_item_ext = $ext ;
	}
}

function external_ext_to_kind( $ext )
{
	$kind = _C_WEBPHOTO_ITEM_KIND_GENERAL ;
	if ( $ext ) {
		$kind = $this->_mime_class->ext_to_kind( $ext );
	}
	return $kind;
}

function set_title_when_external()
{
	$this->overwrite_item_title_if_empty( 
		$this->external_url_to_title() );
}

function external_url_to_title()
{
	return $this->strip_ext( 
		$this->_utility_class->parse_url_to_filename( $this->_item_external_url ) );
}

function set_title_when_embed()
{
	$this->overwrite_item_title_if_empty( 
		$this->embed_src_to_title() );
}

function set_thumb_when_embed()
{
	$this->overwrite_item_external_thumb_if_empty( 
		$this->build_embed_thumb() );
}

function embed_src_to_title()
{
	$title = null;

	if ( empty( $this->_item_embed_type ) ) {
		return $title ;	// null
	}

	if ( empty( $this->_item_embed_src ) ) {
		return $title ;	// null
	}

	$title  = $this->_item_embed_type ;
	$title .= ' : ';
	$title .= $this->_item_embed_src ;

	return $title ;
}

function build_embed_thumb()
{
	return $this->_embed_class->build_thumb(
		$this->_item_embed_type, $this->_item_embed_src );
}

function set_title_when_playlist()
{
	$this->overwrite_item_title_if_empty( 
		$this->playlist_to_title() );
}

function set_player_when_playlist()
{
	if ( $this->is_item_playlist_type_general() ) {
		$this->_item_player_id = _C_WEBPHOTO_PLAYER_ID_PLAYLIST ;
	}
}

function playlist_to_title()
{
	if ( $this->_item_playlist_dir ) {
		$title = $this->_item_playlist_dir ;

	} elseif ( $this->_item_playlist_feed ) {
		$param = parse_url( $this->_item_playlist_feed );
		if ( isset($param['host']) ) {
			$title = $param['host'] ;
		} else {
			$title = date( "YmdHis" );
		}
	}

	if ( $title ) {
		$title = 'playlist: '.$title;
	}

	return $title ;
}

function get_playlist_cache_if_empty( $item_row )
{
	$item_id             = $item_row['item_id'] ;
	$item_playlist_cache = $item_row['item_playlist_cache'] ;
	$cache = null ;

	if ( $this->is_admin_playlist_type() && 
	     empty($tem_playlist_cache) ) {

		$cache = $this->_playlist_class->build_name( $item_id ) ;
	}

	return $cache ;
}

function notify_new_photo( $item_row )
{
	$notification_class =& webphoto_notification_event::getInstance(
		$this->_DIRNAME , $this->_TRUST_DIRNAME );
	$notification_class->notify_new_photo( 
		$item_row['item_id'],  $item_row['item_cat_id'],  $item_row['item_title'] );
}

function get_created_row()
{
	return $this->_row_create ;
}

//---------------------------------------------------------
// upload
//---------------------------------------------------------
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
// create photo thumb 
//---------------------------------------------------------
// overwrite
function create_photo_thumb( $item_row, $photo_name, $thumb_name, $middle_name, $is_submit )
{
	$this->_special_ext = null ;

	$post_rotate = $this->_post_class->get_post( 'rotate' ) ;
	$rotate      = $this->conv_rotate( $post_rotate );

	list( $ret, $cont_param ) =
		$this->create_cont_param( $item_row, $photo_name, $thumb_name, $rotate );
	if ( $ret < 0 ) {
		return $ret ;
	}

	$item_id = $item_row['item_id'] ;

	$thumb_param  = null;
	$middle_param = null;

	if ( $thumb_name ) {
		$thumb_param = $this->create_thumb_param_by_tmp( $item_id, $thumb_name );

	} elseif ( $is_submit && $this->is_external_type() ) {
		$this->prepare_external_thumb();

	} elseif ( $is_submit && $this->is_embed_type() ) {
		$this->prepare_embed_thumb() ;

	} elseif ( $is_submit && $this->is_admin_playlist_type() ) {
		$this->prepare_playlist_thumb();

	} elseif ( is_array($cont_param) ) {
		$thumb_param = $this->create_thumb_param_by_param( $cont_param );
	}

	if ( $middle_name ) {
		$middle_param = $this->create_middle_param_by_tmp( $item_id, $middle_name );

	} elseif ( is_array($cont_param) ) {
		$middle_param = $this->create_middle_param_by_param( $cont_param );
	}

// unlink tmp file
	if ( $photo_name ) {
		$this->unlink_file( $this->_TMP_DIR .'/'. $photo_name );
	}
	if ( $thumb_name ) {
		$this->unlink_file( $this->_TMP_DIR .'/'. $thumb_name );
	}
	if ( $middle_name ) {
		$this->unlink_file( $this->_TMP_DIR .'/'. $middle_name );
	}

	$this->_file_params['thumb']  = $thumb_param ;
	$this->_file_params['middle'] = $middle_param ;

	return 0;
}

function prepare_external_thumb()
{
// image type
	if ( $this->is_item_image_ext() && $this->_item_external_url ) {
		$this->_item_external_thumb = $this->_item_external_url ;

	} elseif ( empty( $this->_item_ext ) ) {
		$this->_special_ext = $this->_EXTERNAL_THUMB_EXT_DEFAULT ;
	}
}

function prepare_embed_thumb()
{
	$thumb = $this->_embed_class->build_thumb( 
		$this->_item_embed_type, $this->_item_embed_src );

// plugin thumb
	if ( $thumb ) {
		$this->_item_external_thumb = $thumb ;

	} else {
		$this->_special_ext = $this->_EMBED_THUMB_EXT_DEFAULT ;
	}
}

function prepare_playlist_thumb()
{
	$this->_special_ext = $this->_PLAYLIST_THUMB_EXT_DEFAULT ;
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
// create photo
//---------------------------------------------------------
// overwrite
function create_flash_docomo_param( $photo_param, $cont_param )
{
	$item_id          = $photo_param['item_id'] ;
	$flag_video_thumb = $photo_param['flag_video_thumb'] ;

// video flash
	$flash_param = $this->_photo_class->create_video_flash_param( $item_id, $photo_param );

	if ( $this->_photo_class->get_video_flash_failed() ) {
		$this->set_msg_array( $this->get_constant('ERR_VIDEO_FLASH') ) ;
	}

// video thumb
	if ( $flag_video_thumb ) {
		$param = $photo_param ;
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

	return array( $flash_param, $docomo_param );
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

//---------------------------------------------------------
// preview
//---------------------------------------------------------
function build_preview_template( $row )
{
	$tpl = new XoopsTpl() ;
	$tpl->assign( 'xoops_dirname' , $this->_DIRNAME ) ;
	$tpl->assign( 'mydirname' ,     $this->_DIRNAME ) ;
	$tpl->assign( $this->get_photo_globals() ) ;
	$tpl->assign( 'photo' , $row ) ;

// BUG: not show description in preview
	$tpl->assign( 'show_photo_desc' , true ) ;

// BUG: not show img alt
	$tpl->assign( $this->get_lang_array() ) ;

	$template = 'db:'. $this->_DIRNAME .'_inc_photo_in_list.html';
	return $tpl->fetch( $template ) ;
}

// --- class end ---
}

?>