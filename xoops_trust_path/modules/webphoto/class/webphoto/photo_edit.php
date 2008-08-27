<?php
// $Id: photo_edit.php,v 1.11 2008/08/27 05:11:54 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// supported exif gps
// used preload_init()
// NOT use webphoto_photo_delete
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
	var $_notification_class;
	var $_upload_class;
	var $_image_class;
	var $_show_class;
	var $_build_class;
	var $_mime_class;
	var $_photo_class;

	var $_post_photo_id    = 0;
	var $_post_item_cat_id = 0;

	var $_cfg_makethumb = false;

	var $_has_resize = false;
	var $_has_rotate = false;

// overwrite param
	var $_item_title          = null;
	var $_item_datetime       = null;
	var $_item_equipment      = null;
	var $_item_exif           = null;
	var $_item_ext            = null;
	var $_item_kind           = 0 ;
	var $_item_gmap_latitude  = 0 ;
	var $_item_gmap_longitude = 0 ;
	var $_item_gmap_zoom      = 0 ;
	var $_photo_duration      = 0 ;
	var $_preview_name        = null;
	var $_tag_name_array      = null;

	var $_checkbox_array      = array();

	var $_photo_tmp_name   = null;
	var $_photo_media_type = null;
	var $_thumb_tmp_name   = null;
	var $_thumb_media_type = null;

	var $_image_thumb_url  = null;
	var $_image_thumb_path = null;
	var $_image_info       = null;

	var $_video_param      = null ;
	var $_file_params      = null;

	var $_is_video_thumb_form = false;

	var $_tag_id_array = null;
	var $_only_image_extentions = false;

	var $_GMAP_ZOOM = _C_WEBPHOTO_GMAP_ZOOM ;
	var $_PHOTO_FIELD_NAME = 'photo_file';
	var $_THUMB_FIELD_NAME = 'thumb_file';
	var $_NO_TITLE  = 'no title' ;
	var $_ORDERBY_DEFAULT = 'idA' ;

	var $_MSG_LEVEL = 0;
	var $_MSG_FIRST = false;

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
	$this->_mime_class   =& webphoto_mime::getInstance( $dirname );
	$this->_photo_class  =& webphoto_photo_create::getInstance( $dirname , $trust_dirname );

	$this->_tag_class  =& webphoto_tag::getInstance( $dirname );
	$this->_tag_class->set_is_japanese( $this->_is_japanese );

	$this->_image_class =& webphoto_image_create::getInstance( $dirname , $trust_dirname );
	$this->_has_resize  = $this->_image_class->has_resize();
	$this->_has_rotate  = $this->_image_class->has_rotate();

	$this->_cfg_makethumb = $this->_config_class->get_by_name( 'makethumb' );
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
	$this->preload_init();
	$this->preload_constant();
}

//---------------------------------------------------------
// post param
//---------------------------------------------------------
function get_post_param()
{
	$this->_post_photo_id       = $this->_post_class->get_post_get_int( 'photo_id' );
	$this->_photo_duration      = $this->_post_class->get_post_int(     'photo_duration' );
	$this->_post_item_cat_id    = $this->_post_class->get_post_get_int( 'item_cat_id' );
	$this->_item_exif           = $this->_post_class->get_post_text(    'item_exif' );
	$this->_item_gmap_latitude  = $this->_post_class->get_post_float(   'item_gmap_latitude' );
	$this->_item_gmap_longitude = $this->_post_class->get_post_float(   'item_gmap_longitude' );
	$this->_item_gmap_zoom      = $this->_post_class->get_post_int(     'item_gmap_zoom' );
	$this->set_item_title(     $this->_post_class->get_post_text( 'item_title' ) );
	$this->set_item_equipment( $this->_post_class->get_post_text( 'item_equipment' ) );

	$this->set_item_datetime_by_post();

	$this->set_checkbox_by_post( 'item_time_update_checkbox' );

	$this->set_preview_name( $this->_post_class->get_post_text( 'preview_name' ) );
}

function build_row_by_post( $row )
{

// overwrite if title is blank
	$this->overwrite_item_title_if_empty( $this->_NO_TITLE );

	$row['item_title']          = $this->get_item_title();
	$row['item_equipment']      = $this->get_item_equipment();
	$row['item_exif']           = $this->_item_exif ;
	$row['item_gmap_latitude']  = $this->_item_gmap_latitude ;
	$row['item_gmap_longitude'] = $this->_item_gmap_longitude ;
	$row['item_gmap_zoom']      = $this->_item_gmap_zoom ;
	$row['item_cat_id']         = $this->_post_class->get_post_int(   'item_cat_id' );
	$row['item_place']          = $this->_post_class->get_post_text(  'item_place' );
	$row['item_description']    = $this->_post_class->get_post_text(  'item_description' );
	$row['item_gicon_id']       = $this->_post_class->get_post_int(   'item_gicon_id' );

	if ( $this->is_fill_item_datetime() ) {
		$row['item_datetime'] = $this->get_item_datetime();
	}

	if ( $this->is_fill_item_ext() ) {
		$row['item_ext'] = $this->get_item_ext();
	}

	if ( $this->is_fill_item_kind() ) {
		$row['item_kind'] = $this->get_item_kind();
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
	if ( ! $this->is_fill_item_title() ) {
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
function overwrite_item_gmap( $exif )
{
	if ( ( $this->_item_gmap_latitude > 0 )&&
	     ( $this->_item_gmap_longitude > 0 ) ) {
		return;
	}

	$latitude  = $exif['latitude'] ;
	$longitude = $exif['longitude'] ;

	if ( ( $latitude > 0 )&&( $longitude > 0 ) ) {
		$this->_item_gmap_latitude  = $latitude ;
		$this->_item_gmap_longitude = $longitude ;
		$this->_item_gmap_zoom      = $this->_GMAP_ZOOM ;
	}
}

//---------------------------------------------------------
// photo duration
//---------------------------------------------------------
function set_photo_duration( $val )
{
	$this->_photo_duration = $val;
}

function get_photo_duration()
{
	return $this->_photo_duration;
}

function overwrite_photo_duration( $val )
{
	if ( $val ) {
		$this->_photo_duration = $val;
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
	$this->_item_kind = $val;
}

function get_item_kind()
{
	return $this->_item_kind;
}

function is_fill_item_kind()
{
	if ( $this->_item_kind ) {
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
		$this->overwrite_item_title_by_media_name_if_empty();

		$photo_tmp_file = $this->_TMP_DIR.'/'.$this->_photo_tmp_name ;

		$ext  = $this->parse_ext( $this->_photo_tmp_name );
		$kind = $this->_mime_class->ext_to_kind( $ext );

		$this->set_item_ext(  $ext );
		$this->set_item_kind( $kind );

// get exif if image
		if ( $this->is_image_kind( $kind ) ) {
			$exif_info = $this->_photo_class->get_exif_info( $photo_tmp_file );
			if ( is_array($exif_info) ) {
				$this->overwrite_item_datetime(  $exif_info['datetime_mysql'] );
				$this->overwrite_item_equipment( $exif_info['equipment'] );
				$this->overwrite_item_exif(      $exif_info['all_data'] );
				$this->overwrite_item_gmap(      $exif_info );
			}
		}

// get duration if video
		if ( $this->is_video_kind( $kind ) ) {
			$video_param = $this->_photo_class->get_duration_size( $photo_tmp_file );
			if ( is_array($video_param) ) {
				$this->_video_param = $video_param ;
				$this->overwrite_photo_duration( $video_param['duration'] ) ;
			}
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
	$this->_photo_class->set_msg_level( $this->_MSG_LEVEL );
	$this->_photo_class->set_flag_print_first_msg( $this->_MSG_FIRST );

	$this->_is_video_thumb_form = false;
	$this->_file_params         = null ;

	$cont_param   = null ;
	$thumb_param  = null ;
	$middle_param = null ;
	$flash_param  = null ;
	$docomo_param = null ;

// if upload main 
	if ( $photo_tmp_name ) {
// create photo
		$photo_tmp_file = $this->_TMP_DIR .'/'. $photo_tmp_name;
		$photo_kind     = $this->get_item_kind() ;

		$photo_param                = array();
		$photo_param['src_file']    = $photo_tmp_file ;
		$photo_param['src_ext']     = $this->get_item_ext() ;
		$photo_param['src_kind']    = $photo_kind ;
		$photo_param['mime']        = $this->_photo_media_type ;
		$photo_param['video_param'] = $this->_video_param ;

		$param           = $photo_param ;
		$param['rotate'] = $this->_post_class->get_post( 'rotate' ) ;
		$ret1 = $this->_photo_class->create_cont_param( $photo_id, $param );
		if ( $ret1 < 0 ) {
			$this->unlink_file( $photo_tmp_file );
			return $ret1;
		}

		$cont_param = $this->_photo_class->get_cont_param();
		if ( $this->_photo_class->get_resized() ) {
			$this->set_msg_array( $this->get_constant('SUBMIT_RESIZED') ) ;
		}

		if ( $this->is_video_kind( $photo_kind ) && is_array( $cont_param ) ) {

// video flash
			$flash_param = $this->_photo_class->create_video_flash_param( $photo_id, $param );

			if ( $this->_photo_class->get_video_flash_failed() ) {
				$this->set_msg_array( $this->get_constant('ERR_VIDEO_FLASH') ) ;
			}

// video thumb
			if ( empty($thumb_tmp_name) ) {
				$param['mode_video_thumb'] = _C_WEBPHOTO_VIDEO_THUMB_PLURAL ;
				$this->_photo_class->create_video_thumb( $photo_id, $param );

				if ( $this->_photo_class->get_video_thumb_created() ) {
					$this->_is_video_thumb_form = true;
				}
				if ( $this->_photo_class->get_video_thumb_failed() ) {
					$this->set_msg_array( $this->get_constant('ERR_VIDEO_THUMB') ) ;
				}
			}

// video docomo
			$docomo_param = $this->_photo_class->create_video_docomo_param( $photo_id, $cont_param );
		}

	}

// if upload thumb
	if ( $thumb_tmp_name ) {
		$this->_photo_class->create_thumb_from_upload( $photo_id , $thumb_tmp_name );
		$thumb_param = $this->_photo_class->get_thumb_param();
		$this->unlink_file( $this->_TMP_DIR .'/'. $thumb_tmp_name );

// if main file uploaded
	} elseif ( $photo_tmp_name && is_array( $cont_param ) ) {

		$param                = $photo_param ;
		$param['flag_thumb']  = true ;
		$param['flag_middle'] = true ;
		list( $thumb_param, $middle_param ) =
			$this->_photo_class->create_thumb_middle_param( $photo_id, $param );

	}

	if ( $photo_tmp_name ) {
		$this->unlink_file( $this->_TMP_DIR .'/'. $photo_tmp_name );
	}
	if ( $thumb_tmp_name ) {
		$this->unlink_file( $this->_TMP_DIR .'/'. $thumb_tmp_name );
	}

	$this->_file_params = array(
		'cont'   => $cont_param ,
		'thumb'  => $thumb_param ,
		'middle' => $middle_param ,
		'flash'  => $flash_param ,
		'docomo' => $docomo_param ,
	);

	return 0;
}

function get_file_params()
{
	return $this->_file_params ;
}

//---------------------------------------------------------
// video thumb
//---------------------------------------------------------
function exec_video_thumb()
{
	$this->clear_msg_array();

	$photo_id = $this->_post_class->get_post('photo_id') ;
	$name     = $this->_post_class->get_post('name') ;

	$ret = $this->_photo_class->update_video_thumb( $photo_id, $name );
	if ( $ret < 0 ) {
		return $ret;
	}

	if ( $this->_photo_class->get_video_thumb_failed() ) {
		$this->set_msg_array( $this->get_constant('ERR_VIDEO_THUMB') ) ;
	}

// set for redirect
	$this->_post_item_cat_id = $this->_photo_class->get_item_cat_id() ;

	return 0;
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

function build_preview_template( $row )
{
	$tpl = new XoopsTpl() ;
	$tpl->assign( 'xoops_dirname' , $this->_DIRNAME ) ;
	$tpl->assign( 'mydirname' ,     $this->_DIRNAME ) ;
	$tpl->assign( $this->get_photo_globals() ) ;
	$tpl->assign( 'photo' , $row ) ;

// BUG: not show description in preview
	$tpl->assign( 'show_photo_desc' , true ) ;

	$template = 'db:'. $this->_DIRNAME .'_inc_photo_in_list.html';
	return $tpl->fetch( $template ) ;
}

//---------------------------------------------------------
// update
//---------------------------------------------------------
function get_file_url_by_kind( $item_row, $kind )
{
	$file_row = $this->get_file_row_by_kind( $item_row, $kind );
	if ( is_array($file_row) ) {
		return $file_row['file_url'];
	}
	return null;
}

function get_file_path_by_kind( $item_row, $kind )
{
	$file_row = $this->get_file_row_by_kind( $item_row, $kind );
	if ( is_array($file_row) ) {
		return $file_row['file_path'];
	}
	return null;
}

function get_file_cont_duration( $item_row )
{
	$cont_row = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_CONT );
	if ( is_array($cont_row) ) {
		return $cont_row['file_duration'] ;
	}
	return null;
}

//---------------------------------------------------------
// form
//---------------------------------------------------------
function print_form_video_thumb_common( $mode, $row )
{
	if ( $this->has_msg_array() ) {
		echo $this->get_format_msg_array() ;
		echo "<br />\n";
	}

	$param = array(
		'mode' => $mode ,
	);

	$form_class =& webphoto_photo_edit_form::getInstance( $this->_DIRNAME , $this->_TRUST_DIRNAME );
	$form_class->print_form_video_thumb( $row, $param );
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