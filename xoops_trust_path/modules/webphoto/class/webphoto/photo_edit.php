<?php
// $Id: photo_edit.php,v 1.3 2008/07/05 12:54:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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
	var $_notification_class;
	var $_upload_class;
	var $_image_class;
	var $_show_class;
	var $_build_class;
	var $_delete_class;
	var $_exif_class;
	var $_mime_class;
	var $_preload_class;
	var $_video_class;
	var $_msg_class;

	var $_post_photo_id   = 0;
	var $_post_photo_catid      = 0;
	var $_post_time_photo_checkbox = 1;

	var $_cfg_makethumb = false;
	var $_cfg_use_ffmpeg = false;

	var $_has_resize = false;
	var $_has_rotate = false;

// overwrite param
	var $_photo_title         = null;
	var $_photo_datetime      = null;
	var $_photo_equipment     = null;
	var $_photo_cont_exif     = null;
	var $_photo_cont_duration = null;
	var $_preview_name        = null;
	var $_tag_name_array      = null;

	var $_time_photo_checkbox = 0;
	var $_checkbox_array      = array();

	var $_photo_tmp_name   = null;
	var $_photo_media_type = null;
	var $_thumb_tmp_name   = null;
	var $_thumb_media_type = null;

	var $_image_thumb_url  = null;
	var $_image_thumb_path = null;
	var $_image_info       = null;

	var $_photo_info       = null ;
	var $_thumb_info       = null ;
	var $_photo_thumb_info = null ;
	var $_is_video_thumb_form = false;

	var $_tag_id_array = null;
	var $_only_image_extentions = false;

	var $_PHOTO_FIELD_NAME = 'photo_file';
	var $_THUMB_FIELD_NAME = 'thumb_file';
	var $_NO_TITLE  = 'no title' ;
	var $_ORDERBY_DEFAULT = 'idA' ;

	var $_TIME_SUCCESS  = 1;
	var $_TIME_PENDING  = 3;
	var $_TIME_FAIL     = 5;

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
	$this->_video_class  =& webphoto_video::getInstance( $dirname );
	$this->_msg_class    =& webphoto_lib_msg::getInstance();

	$this->_tag_class  =& webphoto_tag::getInstance( $dirname );
	$this->_tag_class->set_is_japanese( $this->_is_japanese );

	$this->_image_class =& webphoto_image_create::getInstance( $dirname , $trust_dirname );
	$this->_has_resize  = $this->_image_class->has_resize();
	$this->_has_rotate  = $this->_image_class->has_rotate();

	$this->_preload_class =& webphoto_d3_preload::getInstance();
	$this->_preload_class->init( $dirname , $trust_dirname );

	$this->_cfg_makethumb  = $this->_config_class->get_by_name( 'makethumb' );
	$this->_cfg_use_ffmpeg = $this->_config_class->get_by_name( 'use_ffmpeg' );

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
	$this->_post_photo_id       = $this->_post_class->get_post_get_int( 'photo_id' );
	$this->_post_photo_catid    = $this->_post_class->get_post_get_int( 'photo_cat_id' );
	$this->_photo_cont_exif     = $this->_post_class->get_post_text( 'photo_cont_exif' );
	$this->_photo_cont_duration = $this->_post_class->get_post_int(  'photo_cont_duration' );
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
	$row['photo_cont_exif']      = $this->_photo_cont_exif ;
	$row['photo_cont_duration']  = $this->_photo_cont_duration ;
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
// photo cont duration
//---------------------------------------------------------
function overwrite_photo_cont_duration( $val )
{
	$val = intval($val);
	if ( $val ) {
		$this->_photo_cont_duration = $val;
	}
}

//---------------------------------------------------------
// upload
//---------------------------------------------------------
function upload_fetch_photo( $flag_allow_all=false )
{
	$this->_photo_tmp_name   = null;
	$this->_photo_media_type = null;

	list ( $allowed_mimes, $my_allowed_exts ) = $this->_mime_class->get_my_allowed_mimes();

	if ( $flag_allow_all ) {
		$allowed_exts = $my_allowed_exts ;
	} else {
		$allowed_exts = $this->get_normal_exts() ;
	}

// init uploader if photo file uploaded
	$this->_upload_class->init_media_uploader( $this->_has_resize,  $allowed_mimes, $allowed_exts );

	$ret = $this->_upload_class->fetch_for_edit( $this->_PHOTO_FIELD_NAME );
	if ( $ret < 0 ) {
		$this->set_error( $this->_upload_class->get_errors() );
	}
	if ( $ret == 1 ) {
		$this->_photo_tmp_name   = $this->_upload_class->get_tmp_name();
		$this->_photo_media_type = $this->_upload_class->get_uploader_media_type();
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
	$this->_upload_class->set_image_extensions();

	$ret = $this->_upload_class->fetch_for_edit( $this->_THUMB_FIELD_NAME );
	if ( $ret < 0 ) {
		$this->set_error( $this->_upload_class->get_errors() );
	}
	if ( $ret == 1 ) {
		$this->_thumb_tmp_name   = $this->_upload_class->get_tmp_name();
		$this->_thumb_media_type = $this->_upload_class->get_uploader_media_type();
	}
}

//---------------------------------------------------------
// create photo
//---------------------------------------------------------
function create_photo_thumb( $photo_id, $photo_tmp_name, $thumb_tmp_name )
{
	$this->_photo_thumb_info    = null ;
	$this->_is_video_thumb_form = false;

	$photo_info = null;
	$thumb_info = null;

	$cfg_allownoimage = $this->_config_class->get_by_name( 'allownoimage' );

// if upload main 
	if ( $photo_tmp_name ) {

// create photo
		$ret1 = $this->create_photo( $photo_id, $photo_tmp_name );
		if ( $ret1 < 0 ) { return $ret1; }

		if ( is_array( $this->_photo_info ) ) {
// load photo info
			$photo_info = $this->_photo_info ;

// if video
			if ( empty($thumb_tmp_name) ) {
				$photo_info = $this->create_video_flash_thumb( $photo_id, $photo_info );
			}
		}
	}

// if upload thumb
	if ( $thumb_tmp_name ) {
		$this->_image_class->create_thumb_from_upload( $photo_id , $thumb_tmp_name );
		$thumb_info = $this->_image_class->get_thumb_info();
		$this->unlink_file( $this->_TMP_DIR .'/'. $thumb_tmp_name );

// if main file uploaded
	} elseif ( $photo_tmp_name && is_array( $photo_info ) ) {
		$thumb_info = $this->create_thumb_from_photo( $photo_id, $photo_info );
	}

	$this->_photo_thumb_info 
		= $this->_image_class->merge_photo_thumb_info( $photo_info, $thumb_info );

	return 0;
}

function create_photo( $photo_id, $photo_tmp_name )
{
	$this->_photo_info = null;

	$src_file = $this->_TMP_DIR .'/'. $photo_tmp_name;

	$this->_image_class->set_mode_rotate_by_post();

// create photo
	$ret = $this->_image_class->create_photo( $src_file, $photo_id );
	if ( $ret < 0 ) {
		$this->unlink_file( $src_file );
		return $ret; 
	}
	if ( $ret == _C_WEBPHOTO_IMAGE_RESIZE ) {
		$this->_msg_class->set_msg( $this->get_constant('SUBMIT_RESIZED') ) ;
	}

	$photo_info = $this->_image_class->get_photo_info();

	if ( is_array($photo_info) ) {
		$photo_info = $this->_mime_class->add_mime_to_info_if_empty( $photo_info, $this->_photo_media_type );
	}

	$this->unlink_file( $src_file );

// save photo info
	$this->_photo_info = $photo_info;
}

function create_video_flash_thumb( $photo_id, $photo_info )
{
	$photo_path = $photo_info['photo_cont_path'] ;
	$photo_name = $photo_info['photo_cont_name'] ;
	$photo_ext  = $photo_info['photo_cont_ext'] ;
	$photo_file = XOOPS_ROOT_PATH . $photo_path ;

// check video type
	if ( !$this->_mime_class->is_video_ext( $photo_ext ) || !$this->_cfg_use_ffmpeg ) {
		return $photo_info;
	}

// get duration size
	$param = $this->_video_class->get_duration_size( $photo_file );
	if ( is_array($param) ) {
		$duration = $param['duration'] ;
		$width    = $param['width'] ;
		$height   = $param['height'] ;

		if ( $duration ) {
			$this->overwrite_photo_cont_duration( $duration );
		}
		if ( $width && $height ) {
			$photo_info['photo_cont_width']  = $width ;
			$photo_info['photo_cont_height'] = $height ;
		}
	}

// create flash
	$ret = $this->create_video_flash( $photo_id, $photo_file, $photo_file_file  );
	if ( $ret ) {
		$photo_info = array_merge( $photo_info, $this->_video_class->get_flash_info() );
	}

// create video thumb
	if ( $this->_cfg_makethumb ) {
		$this->_is_video_thumb_form 
			= $this->create_video_plural_thumbs( $photo_id, $photo_file, $photo_ext ) ;
	}

	return $photo_info;
}

function create_video_flash( $photo_id, $photo_file)
{
	$flash_name = $this->_image_class->build_photo_name( 
		 $photo_id, $this->_video_class->get_flash_ext() );
	$ret = $this->_video_class->create_flash( $photo_file, $flash_name ) ;
	if ( $ret ) {
		return true;
	}
	$this->_msg_class->set_msg( $this->get_constant('ERR_VIDEO_FLASH') ) ;
	return false;
}

function create_video_plural_thumbs( $photo_id, $photo_file, $photo_ext )
{
	$count = $this->_video_class->create_plural_thumbs( $photo_id, $photo_file );
	if ( $count ) {

// create thumb icon
		$this->_image_class->copy_thumb_icon( 
			$this->_TMP_PATH, 
			$this->_video_class->get_first_thumb_node(), 
			$photo_ext );

		return true;

	}

	$this->_msg_class->set_msg( $this->get_constant('ERR_VIDEO_THUMB') ) ;
	return false;
}

function create_thumb_from_photo( $photo_id, $photo_info )
{
	$photo_path = $photo_info['photo_cont_path'] ;
	$photo_name = $photo_info['photo_cont_name'] ;
	$photo_ext  = $photo_info['photo_cont_ext'] ;

	if ( $this->is_normal_ext( $photo_ext ) ) {

// create thumb
		if ( $this->_cfg_makethumb ) {
			$this->_image_class->create_thumb_from_photo( $photo_id, $photo_path, $photo_ext );
			$thumb_info = $this->_image_class->get_thumb_info();

// substitute with photo image
		} else {
			$this->_image_class->create_thumb_substitute( $photo_path, $photo_ext );
			$thumb_info = $this->_image_class->get_thumb_info();
		}

// thumb icon
	} else {
		$this->_image_class->create_thumb_icon( $photo_id, $photo_ext );
		$thumb_info = $this->_image_class->get_thumb_info();
	}
	
	return $thumb_info;
}

function get_photo_thumb_info()
{
	return $this->_photo_thumb_info ;
}

//---------------------------------------------------------
// video thumb
//---------------------------------------------------------
function create_video_thumb( $row, $num )
{
	$thumb_info = null;

	$photo_id   = $row['photo_id'];
	$photo_path = $row['photo_cont_path'];
	$photo_name = $row['photo_cont_name'];
	$photo_ext  = $row['photo_cont_ext'];
	$thumb_path = $row['photo_thumb_path'];

	$tmp_name = $this->_video_class->build_thumb_name( $photo_id, $num, true );
	$tmp_path = $this->_TMP_PATH .'/'.  $tmp_name;
	$tmp_file = XOOPS_ROOT_PATH . $tmp_path ;

// create thumb
	if ( is_file( $tmp_file) ) {

// remove old file
		$this->unlink_path( $thumb_path );

		$this->_image_class->create_thumb_from_photo( 
			$photo_id, $tmp_path, $this->_video_class->get_thumb_ext() );
		$thumb_info = $this->_image_class->get_thumb_info();
	}

// if fail to ceate
	if ( !is_array($thumb_info) ) {
		$this->_msg_class->set_msg( $this->get_constant('ERR_VIDEO_THUMB') ) ;
		$this->_image_class->create_thumb_substitute( $photo_path, $photo_name, $photo_ext );
		$thumb_info = $this->_image_class->get_thumb_info();
	}

// remove tmp
	$max = $this->_video_class->get_thumb_plural_max();
	for ( $i=0; $i<=$max; $i++ )
	{
		$tmp_name = $this->_video_class->build_thumb_name( $photo_id, $i, true );
		$tmp_file = $this->_TMP_DIR .'/'.  $tmp_name;
		$this->unlink_file( $tmp_file );
	}

	return $thumb_info;
}

//---------------------------------------------------------
// mime type
//---------------------------------------------------------
function add_mime_if_empty( $photo_info )
{
// no image  info
	if ( !is_array($photo_info) || !count($photo_info) ) {
		return $photo_info;
	}

// if set mime
	if ( $photo_info['photo_cont_mime'] ) {
		return $photo_info;
	}

// if not set mime
	$mime = $this->_photo_media_type;
	$photo_info['photo_cont_mime'] = $mime ;
	$photo_info['photo_file_mime'] = $mime ;

// if video type
	if ( $this->_mime_class->is_video_mime( $mime ) ) {
		$medium = $this->_mime_class->get_video_medium();
		$photo_info['photo_cont_medium'] = $medium ;
		$photo_info['photo_file_medium'] = $medium ;
	}

	return $photo_info;
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
// form
//---------------------------------------------------------
function print_form_video_thumb_common( $mode, $row )
{
	if ( $this->_msg_class->has_msg() ) {
		echo $this->_msg_class->get_format_msg() ;
		echo "<br />\n";
	}

	$param = array(
		'mode' => $mode ,
	);

	$form_class =& webphoto_photo_edit_form::getInstance( $this->_DIRNAME , $this->_TRUST_DIRNAME );
	$form_class->print_form_video_thumb( $row, $param );
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