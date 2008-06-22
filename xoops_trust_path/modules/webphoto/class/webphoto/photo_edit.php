<?php
// $Id: photo_edit.php,v 1.2 2008/06/22 05:26:00 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_photo_edit
// caller main_submit main_edit admin_admission admin_photo_manage
//=========================================================
class webphoto_photo_edit extends webphoto_base_this
{
	var $_tag_class;
	var $_notification_class;
	var $_upload_class;
	var $_image_class;
	var $_show_class;
	var $_build_class;
	var $_delete_class;
	var $_exif_class;
	var $_mime_class;
	var $_preload_class;

	var $_post_photo_id   = 0;
	var $_post_photo_catid      = 0;
	var $_post_time_photo_checkbox = 1;

	var $_has_resize = false;
	var $_has_rotate = false;

// overwrite param
	var $_photo_title     = null;
	var $_photo_datetime  = null;
	var $_photo_equipment = null;
	var $_photo_cont_exif = null;
	var $_preview_name    = null;
	var $_tag_name_array  = null;

	var $_time_photo_checkbox = 0;
	var $_checkbox_array      = array();

	var $_photo_tmp_name   = null;
	var $_photo_media_type = null;
	var $_thumb_tmp_name   = null;
	var $_thumb_media_type = null;

	var $_image_thumb_url  = null;
	var $_image_thumb_path = null;
	var $_image_info       = null;

	var $_tag_id_array = null;

	var $_PHOTO_FIELD_NAME = 'photo_file';
	var $_THUMB_FIELD_NAME = 'thumb_file';
	var $_NO_TITLE  = 'no title' ;
	var $_ORDERBY_DEFAULT = 'idA' ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_photo_edit( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_upload_class =& webphoto_upload::getInstance( $dirname , $trust_dirname );
	$this->_show_class   =& webphoto_show_photo::getInstance( $dirname , $trust_dirname );
	$this->_build_class  =& webphoto_photo_build::getInstance( $dirname );
	$this->_delete_class =& webphoto_photo_delete::getInstance( $dirname );
	$this->_mime_class   =& webphoto_mime::getInstance( $dirname );
	$this->_exif_class   =& webphoto_lib_exif::getInstance();

	$this->_tag_class  =& webphoto_tag::getInstance( $dirname );
	$this->_tag_class->set_is_japanese( $this->_is_japanese );

	$this->_image_class =& webphoto_image_create::getInstance( $dirname , $trust_dirname );
	$this->_has_resize  = $this->_image_class->has_resize();
	$this->_has_rotate  = $this->_image_class->has_rotate();

	$this->_preload_class =& webphoto_d3_preload::getInstance();
	$this->_preload_class->init( $dirname , $trust_dirname );

}

// for admin_photo_manage admin_catmanager
function &getInstance( $dirname , $trust_dirname )
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
	$this->_preload_constant();
}

function _preload_constant()
{
	$arr = $this->_preload_class->get_preload_const_array();

	if ( !is_array($arr) || !count($arr) ) {
		return true;	// no action
	}

	foreach( $arr as $k => $v )
	{
		$local_name = strtoupper( '_' . $k );

// array type
		if ( strpos($k, 'array_') === 0 ) {
			$temp = $this->str_to_array( $v, '|' );
			if ( is_array($temp) && count($temp) ) {
				$this->$local_name = $temp;
			}

// string type
		} else {
			$this->$local_name = $v;
		}
	}

}

//---------------------------------------------------------
// post param
//---------------------------------------------------------
function get_post_param()
{
	$this->_post_photo_id     = $this->_post_class->get_post_get_int( 'photo_id' );
	$this->_post_photo_catid  = $this->_post_class->get_post_get_int( 'photo_cat_id' );
	$this->_photo_cont_exif   = $this->_post_class->get_post_text( 'photo_cont_exif' );
	$this->set_photo_title(     $this->_post_class->get_post_text( 'photo_title' ) );
	$this->set_photo_equipment( $this->_post_class->get_post_text( 'photo_equipment' ) );

	$this->set_photo_datetime_by_post();

	$this->set_checkbox_by_post( 'photo_time_update_checkbox' );

	$this->set_preview_name( $this->_post_class->get_post_text( 'preview_name' ) );
}

function build_row_by_post( $row )
{

// overwrite if title is blank
	$this->overwrite_photo_title_if_empty( $this->_NO_TITLE );

	$row['photo_title']          = $this->get_photo_title();
	$row['photo_equipment']      = $this->get_photo_equipment();
	$row['photo_cont_exif']      = $this->_photo_cont_exif;
	$row['photo_cat_id']         = $this->_post_class->get_post_int(   'photo_cat_id' );
	$row['photo_place']          = $this->_post_class->get_post_text(  'photo_place' );
	$row['photo_description']    = $this->_post_class->get_post_text(  'photo_description' );
	$row['photo_gmap_latitude']  = $this->_post_class->get_post_float( 'photo_gmap_latitude' );
	$row['photo_gmap_longitude'] = $this->_post_class->get_post_float( 'photo_gmap_longitude' );
	$row['photo_gmap_zoom']      = $this->_post_class->get_post_int(   'photo_gmap_zoom' );
	$row['photo_gicon_id']       = $this->_post_class->get_post_int(   'photo_gicon_id' );

	if ( $this->is_fill_photo_datetime() ) {
		$row['photo_datetime'] = $this->get_photo_datetime();
	}

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_PHOTO_TEXT; $i++ ) 
	{
		$name = 'photo_text'.$i;
		$row[ $name ] = $this->_post_class->get_post_text( $name );
	}

	$post_tags = $this->_post_class->get_post_text( 'tags' );
	$this->set_tag_name_array( $this->_tag_class->str_to_tag_name_array( $post_tags ) );

	return $row;
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
// photo title
//---------------------------------------------------------
function set_photo_title( $val )
{
	$this->_photo_title = $val;
}

function get_photo_title()
{
	return $this->_photo_title;
}

function overwrite_photo_title_by_media_name_if_empty()
{
	$this->overwrite_photo_title_if_empty(
		$this->strip_ext( $this->upload_media_name() ) );
}

function overwrite_photo_title_if_empty( $val )
{
	if ( ! $this->is_fill_photo_title() ) {
		$this->_photo_title = $val;
	}
}

function is_fill_photo_title()
{
	if ( $this->_photo_title ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// photo equipment
//---------------------------------------------------------
function set_photo_equipment( $val )
{
	$this->_photo_equipment = $val;
}

function get_photo_equipment()
{
	return $this->_photo_equipment;
}

function overwrite_photo_equipment( $val )
{
	if ( $val ) {
		$this->_photo_equipment = $val;
	}
}

//---------------------------------------------------------
// photo datetime
//---------------------------------------------------------
function set_photo_datetime_by_post()
{
	$flag = false;

	$this->set_checkbox_by_post( 'photo_datetime_checkbox' );
	$checkbox = $this->get_checkbox_by_name( 'photo_datetime_checkbox' );

	$datetime = $this->_photo_handler->build_datetime_by_post( 'photo_datetime' );

	if ( ( $checkbox == _C_WEBPHOTO_YES ) && $datetime ) {
		$flag = true;
	} elseif ( $checkbox == _C_WEBPHOTO_NO ) {
		$flag     = true;
		$datetime = null;
	}

	$this->set_photo_datetime(      $datetime );
	$this->set_photo_datetime_flag( $flag );
}

function set_photo_datetime( $val )
{
	$this->_photo_datetime = $val;
}

function set_photo_datetime_flag( $val )
{
	$this->_photo_datetime_flag = (bool)$val;
}

function get_photo_datetime()
{
	return $this->_photo_datetime;
}

function get_photo_datetime_flag()
{
	return $this->_photo_datetime_flag;
}

function overwrite_photo_datetime( $datetime )
{
	if ( empty($datetime) ) { return false; }

	$this->set_photo_datetime(      $datetime );
	$this->set_photo_datetime_flag( true );

}

function is_fill_photo_datetime()
{
	if ( $this->_photo_datetime_flag ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// photo cont exif
//---------------------------------------------------------
function overwrite_photo_cont_exif( $val )
{
	if ( $val ) {
		$this->_photo_cont_exif = $val;
	}
}

//---------------------------------------------------------
// upload
//---------------------------------------------------------
function upload_fetch_photo()
{
	$this->_photo_tmp_name   = null;
	$this->_photo_media_type = null;

// init uploader if photo file uploaded
	$this->upload_init();

	$ret = $this->upload_fetch( $this->_PHOTO_FIELD_NAME );
	if ( $ret == 1 ) {
		$this->_photo_tmp_name   = $this->upload_tmp_name();
		$this->_photo_media_type = $this->upload_media_type();
		$this->overwrite_photo_title_by_media_name_if_empty();

// get exif date
		$exif_info = $this->_exif_class->read_file( $this->_TMP_DIR.'/'.$this->_photo_tmp_name );
		if ( is_array($exif_info) ) {
			$this->overwrite_photo_datetime( $this->exif_to_mysql_datetime( $exif_info ) );
			$this->overwrite_photo_equipment( $exif_info['equipment'] );
			$this->overwrite_photo_cont_exif( $exif_info['all_data'] );
		}
	}
	return $ret;
}

function upload_fetch_thumb()
{
	$this->_thumb_tmp_name   = null;
	$this->_thumb_media_type = null;

// if thumb file uploaded
	$this->upload_set_image_extensions();

	$ret = $this->upload_fetch( $this->_THUMB_FIELD_NAME );
	if ( $ret == 1 ) {
		$this->_thumb_tmp_name   = $this->upload_tmp_name();
		$this->_thumb_media_type = $this->upload_media_type();
	}
}

function exif_to_mysql_datetime( $exif )
{
	$datetime     = $exif['datetime'];
	$datetime_gnu = $exif['datetime_gnu'];

	if ( $datetime_gnu ) {
		return $datetime_gnu;
	}

	$time = $this->_utility_class->str_to_time( $datetime );
	if ( $time <= 0 ) { return false; }

	return $this->_utility_class->time_to_mysql_datetime( $time );
}

//---------------------------------------------------------
// mime type
//---------------------------------------------------------
function add_mime_if_empty( $image_info )
{
// no image  info
	if ( !is_array($image_info) || !count($image_info) ) {
		return $image_info;
	}

// if set mime
	if ( $image_info['photo_cont_mime'] ) {
		return $image_info;
	}

// if not set mime
	$mime = $this->_photo_media_type;
	$image_info['photo_cont_mime'] = $mime ;
	$image_info['photo_file_mime'] = $mime ;

// if video type
	if ( $this->_mime_class->is_video_mime( $mime ) ) {
		$medium = $this->_mime_class->get_video_medium();
		$image_info['photo_cont_medium'] = $medium ;
		$image_info['photo_file_medium'] = $medium ;
	}

	return $image_info;
}

//---------------------------------------------------------
// insert
//---------------------------------------------------------
function build_search_for_edit( $photo_row, $tag_name_array=null )
{
	return $this->_build_class->build_search( $photo_row, $tag_name_array );
}

function build_preview_template( $row )
{
	$tpl = new XoopsTpl() ;
	$tpl->assign( 'xoops_dirname' , $this->_DIRNAME ) ;
	$tpl->assign( 'mydirname' ,     $this->_DIRNAME ) ;
	$tpl->assign( $this->get_photo_globals() ) ;
	$tpl->assign( 'photo' , $row ) ;

	$template = 'db:'. $this->_DIRNAME .'_inc_photo_in_list.html';
	return $tpl->fetch( $template ) ;
}

//---------------------------------------------------------
// update
//---------------------------------------------------------

//---------------------------------------------------------
// delete
//---------------------------------------------------------
function delete_photo( $photo_id )
{
	return $this->_delete_class->delete_photo( $photo_id );
}

//---------------------------------------------------------
// cat handler
//---------------------------------------------------------
function exists_category()
{
	if ( $this->_cat_handler->get_count_all() == 0 ) {
		return false;
	}
	return true;
}

function get_cached_cat_title_by_id( $cat_id, $flag_sanitize=false )
{
	return $this->_cat_handler->get_cached_value_by_id_name( $cat_id, 'cat_title', $flag_sanitize );
}

function exists_post_cat_id()
{
	if ( $this->_post_photo_catid <= 0 ) {
		return false;
	}
	return true;
}

//---------------------------------------------------------
// tag class
//---------------------------------------------------------
function tag_handler_add_tags( $photo_id, $tag_name_array )
{
	return $this->_tag_class->add_tags( $photo_id, $this->_xoops_uid, $tag_name_array );
}

function tag_handler_update_tags( $photo_id, $tag_name_array )
{
	return $this->_tag_class->update_tags( $photo_id, $this->_xoops_uid, $tag_name_array );
}

function tag_handler_tag_name_array( $photo_id )
{
	return $this->_tag_class->get_tag_name_array_by_photoid_uid( $photo_id, $this->_xoops_uid );
}

//---------------------------------------------------------
// upload class
//---------------------------------------------------------
function upload_init()
{
	$this->_upload_class->init_media_uploader( $this->_has_resize );
}

function upload_fetch( $field_name )
{
	$ret = $this->_upload_class->fetch_for_edit( $field_name );
	if ( $ret < 0 ) {
		$this->set_error( $this->_upload_class->get_errors() );
	}
	return $ret;
}

function upload_tmp_name()
{
	return $this->_upload_class->get_tmp_name();
}

function upload_media_name()
{
	return $this->_upload_class->get_uploader_media_name();
}

function upload_media_type()
{
	return $this->_upload_class->get_uploader_media_type();
}

function upload_set_image_extensions()
{
	return $this->_upload_class->set_image_extensions();
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

function check_xoops_upload_file( $flag_thmub=true )
{
	$post_xoops_upload_file = $this->_post_class->get_post( 'xoops_upload_file' );
	if ( !is_array($post_xoops_upload_file) || !count($post_xoops_upload_file) ) {
		return false;
	}
	if ( !in_array( $this->_PHOTO_FIELD_NAME, $post_xoops_upload_file ) ) {
		return false;
	}
	if ( $flag_thmub && !in_array( $this->_THUMB_FIELD_NAME, $post_xoops_upload_file ) ) {
		return false;
	}
	return true;
}

//---------------------------------------------------------
// show class
//---------------------------------------------------------
function show_build_preview_submit( $row, $tag_name_array )
{
	return $this->_show_class->build_photo_show_basic( $row, $tag_name_array );
}

function show_build_preview_edit( $row, $tag_name_array )
{
	return $this->_show_class->build_photo_show( $row, $tag_name_array );
}

// --- class end ---
}

?>