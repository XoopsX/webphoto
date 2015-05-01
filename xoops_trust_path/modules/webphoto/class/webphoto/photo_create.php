<?php
// $Id: photo_create.php,v 1.10 2009/01/06 09:41:35 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-01-04 K.OHWADA
// VODEO -> VIDEO
// 2008-11-29 K.OHWADA
// create_thumb_icon() -> build_icon_image()
// 2008-11-08 K.OHWADA
// insert_file_by_params()
// 2008-11-04 K.OHWADA
// Fatal error: Call to undefined method build_failed_msg()
// 2008-10-01 K.OHWADA
// video_thumb()
// get_displaytype()
// update_video_thumb_by_item_row()
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// supported exif gps
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_photo_create
//=========================================================
class webphoto_photo_create extends webphoto_base_this
{
	var $_build_class;
	var $_image_class;
	var $_mime_class;
	var $_video_class;
	var $_exif_class;

// config
	var $_cfg_makethumb    = false;
	var $_cfg_use_ffmpeg   = false;
	var $_cfg_use_pathinfo = false;
	var $_has_resize       = false;
	var $_has_rotate       = false;

// set param
	var $_flag_print_first_msg = false;
	var $_flag_force_db        = false;

// result
	var $_item_row     = null;
	var $_item_newid   = 0;
	var $_item_cat_id  = 0;
	var $_flag_resized = false;
	var $_flag_video_flash_created = false ;
	var $_flag_video_flash_failed  = false ;
	var $_flag_video_thumb_created = false ;
	var $_flag_video_thumb_failed  = false ;

	var $_cont_param  = null ;
	var $_thumb_param = null ;
	var $_video_param = null ;
	var $_msg_item    = null ;

	var $_TITLE_DEFAULT = 'no title';
	var $_EXT_PNG       = 'png';
	var $_ICON_NAME_DEFAULT = 'default.png';

	var $_VIDEO_THUMB_MAX = _C_WEBPHOTO_VIDEO_THUMB_PLURAL_MAX ;
	var $_GMAP_ZOOM       = _C_WEBPHOTO_GMAP_ZOOM ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_photo_create( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_build_class  =& webphoto_photo_build::getInstance( $dirname );
	$this->_mime_class   =& webphoto_mime::getInstance( $dirname );
	$this->_video_class  =& webphoto_video::getInstance( $dirname );
	$this->_exif_class   =& webphoto_lib_exif::getInstance();
	$this->_image_class  =& webphoto_image_create::getInstance( $dirname , $trust_dirname );

	$this->_cfg_makethumb    = $this->get_config_by_name( 'makethumb' );
	$this->_cfg_use_ffmpeg   = $this->get_config_by_name( 'use_ffmpeg' );
	$this->_cfg_use_pathinfo = $this->get_config_by_name( 'use_pathinfo' );

	$this->_has_resize  = $this->_image_class->has_resize();
	$this->_has_rotate  = $this->_image_class->has_rotate();

}

public static function &getInstance( $dirname = null, $trust_dirname = null )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_photo_create( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// video thumb
//---------------------------------------------------------
function video_thumb( $item_row )
{
	$num = $this->_post_class->get_post_text('num') ;
	$ret = $this->video_thumb_exec( $item_row, $num );
	return $this->build_failed_msg( $ret );
}

function video_thumb_exec( $item_row, $num )
{
	$this->clear_msg_array();

	$ret = $this->update_video_thumb_by_item_row( $item_row, $num ) ;
	if ( $ret < 0 ) {
		return $ret;
	}

	if ( $this->get_video_thumb_failed() ) {
		$this->set_msg_array( $this->get_constant('ERR_VIDEO_THUMB') ) ;
	}

	return 0;
}

// Fatal error: Call to undefined method build_failed_msg()
function build_failed_msg( $ret )
{
	switch ( $ret )
	{
		case _C_WEBPHOTO_ERR_DB:
			$this->set_error_in_head_with_admin_info( 'DB Error' );
			return false;
	}
	return true;
}

//---------------------------------------------------------
// create from file
//---------------------------------------------------------
function create_from_file( $param )
{
	$this->_item_newid = 0 ;
	$this->_item_row   = null ;

	$title = isset($param['title']) ? $param['title'] : $this->_TITLE_DEFAULT ;

	$ret = $this->check_item( $param );
	if ( $ret < 0 ) {
		return $ret;
	}

	$item_row  = $this->create_item_row( $param );
	$item_ext  = $item_row['item_ext'] ;
	$item_kind = $item_row['item_kind'] ;

// --- insert item ---
	$item_id = $this->_item_handler->insert( $item_row, $this->_flag_force_db );
	if ( !$item_id ) {
		$this->print_msg_level_admin( ' DB Error, ', true );
		$this->set_error( $this->_item_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB ;
	}

	$item_row['item_id'] = $item_id ; 
	$this->_item_newid   = $item_id ;
	$this->_item_row     = $item_row ;

	$param['src_ext']     = $item_ext ;
	$param['src_kind']    = $item_kind ;
	$param['video_param'] = $this->get_video_param() ;

	$this->print_first_msg( $item_id, $title );

	if ( $this->_msg_item ) {
		$this->print_msg_level_admin( $this->_msg_item );
	}

// --- insert cont ---
	$param_thumb_middle                = $param ;
	$param_thumb_middle['flag_thumb']  = true ;
	$param_thumb_middle['flag_middle'] = true ;
	$param_thumb_middle['flag_video']  = true ;

	$cont_param   = null ;
	$docomo_param = null ;

	$ret = $this->create_cont_param( $item_id, $param );
	if ( $ret == 0 ) {
		$cont_param   = $this->get_cont_param();
		$docomo_param = $this->create_video_docomo_param( $item_id, $cont_param ) ;
	}

	list( $thumb_param, $middle_param ) =
		$this->create_thumb_middle_param( $item_id, $param_thumb_middle );

	$flash_param  = $this->create_video_flash_param( $item_id, $param ) ;

	$file_params = array(
		'cont'   => $cont_param ,
		'thumb'  => $thumb_param ,
		'middle' => $middle_param ,
		'flash'  => $flash_param ,
		'docomo' => $docomo_param ,
	);

	$file_ids   = $this->insert_files_from_params( $item_id,  $file_params );
	$update_row = $this->build_update_item_row(    $item_row, $file_ids );

// --- update item ---
	$ret2 = $this->_item_handler->update( $update_row, $this->_flag_force_db );
	if ( !$ret2 ) {
		$this->print_msg_level_admin( ' DB Error, ', true );
		$this->set_error( $this->_item_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB ;
	}

	$this->_item_row = $update_row ;

	return 0 ;
}

function print_first_msg( $item_id, $title )
{
	if ( $this->_flag_print_first_msg ) {
		$this->print_msg_level_user( $this->build_msg_photo_title( $item_id, $title ) );
	}
}

function build_msg_photo_title( $item_id, $title )
{
	if ( $this->_cfg_use_pathinfo ) {
		$url = $this->_MODULE_URL .'/index.php/photo/'. $item_id .'/';
	} else {
		$url = $this->_MODULE_URL .'/index.php?fct=photo&amp;p='. $item_id ;
	}

	$msg  = ' <a href="'. $url .'" target="_blank">';
	$msg .= $item_id;
	$msg .= ' : ';
	$msg .= $this->sanitize( $title );
	$msg .= '</a> : ';
	return $msg ;
}

//---------------------------------------------------------
// create item
//---------------------------------------------------------
function check_item( $param )
{
	if ( !isset( $param['src_file'] ) ) {
		$this->print_msg_level_admin( ' Empty file, ', true );
		return _C_WEBPHOTO_ERR_EMPTY_FILE ;
	}

	if ( ! is_readable( $param['src_file'] ) ) {
		$this->print_msg_level_admin( ' Cannot read file, ', true );
		return _C_WEBPHOTO_ERR_FILEREAD;
	}

	if ( !isset( $param['cat_id'] ) ) {
		$this->print_msg_level_admin( ' Empty cat_id, ', true );
		return _C_WEBPHOTO_ERR_EMPTY_CAT ;
	}

	return 0;
}

function create_item_row( $param )
{
	$this->_flag_resized = false ;
	$this->_video_param  = null ;
	$this->_msg_item     = null ;

	$src_file    = $param['src_file'];
	$cat_id      = intval($param['cat_id']);
	$uid         = isset($param['uid'])         ? intval($param['uid'])    : $this->_xoops_uid ;
	$status      = isset($param['status'])      ? intval($param['status']) : _C_WEBPHOTO_STATUS_APPROVED ;
	$time_create = isset($param['time_create']) ? intval($param['time_create']) : time() ;
	$time_update = isset($param['time_update']) ? intval($param['time_update']) : time() ;
	$latitude    = isset($param['latitude'])    ? floatval($param['latitude'])  : 0 ;
	$longitude   = isset($param['longitude'])   ? floatval($param['longitude']) : 0 ;
	$zoom        = isset($param['zoom'])        ? intval($param['zoom'])        : 0 ;
	$title       = isset($param['title'])       ? $param['title']       : $this->_TITLE_DEFAULT ;
	$description = isset($param['description']) ? $param['description'] : null ;

// item row
	$row = $this->_item_handler->create( true );
	$row['item_time_create']    = $time_create ;
	$row['item_time_update']    = $time_update ;
	$row['item_title']          = $title ;
	$row['item_uid']            = $uid;
	$row['item_cat_id']         = $cat_id ;
	$row['item_description']    = $description ;
	$row['item_status']         = $status ;
	$row['item_gmap_latitude']  = $latitude ;
	$row['item_gmap_longitude'] = $longitude ;
	$row['item_gmap_zoom']      = $zoom ;

	$row = array_merge(
		$row, $this->get_item_param_extention( $src_file ) );

	$row['item_search'] = $this->_build_class->build_search( $row );

	return $row ;
}

function get_item_param_extention( $src_file, $src_ext=null )
{
	if ( empty($src_ext) ) {
		$src_ext = $this->parse_ext( $src_file );
	}

	$src_kind = $this->_mime_class->ext_to_kind( $src_ext );

	$param              = array();
	$param['item_ext']  = $src_ext ;
	$param['item_kind'] = $src_kind ;

// get exif if image
	if ( $this->is_image_kind( $src_kind ) ) {
		$exif_info = $this->get_exif_info( $src_file );
		if ( is_array($exif_info) ) {
			$datetime  = $exif_info['datetime_mysql'];
			$equipment = $exif_info['equipment'] ;
			$latitude  = $exif_info['latitude'] ;
			$longitude = $exif_info['longitude'] ;
			$zoom      = $exif_info['gmap_zoom'] ;
			$exif      = $exif_info['all_data'] ;

			if ( $datetime ) {
				$param['item_datetime'] = $datetime ;
			}
			if ( $equipment ) {
				$param['item_equipment'] = $equipment ;
			}
			if ( ( $latitude > 0 )&&( $longitude > 0 ) ) {
				$param['item_gmap_latitude']  = $latitude ;
				$param['item_gmap_longitude'] = $longitude ;
				$param['item_gmap_zoom']      = $zoom ;
			}
			if ( $exif ) {
				$this->_msg_item = ' get exif, ' ;
				$param['item_exif'] = $exif ;
			} else {
				$this->_msg_item = ' no exif, ' ;
			}
		}
	}

// get duration if video audio
	if ( $this->is_video_audio_kind( $src_kind ) ) {
		$video_param = $this->get_duration_size( $src_file );
		if ( is_array($video_param) ) {
			$this->_msg_item = ' get video info, ' ;
			$this->_video_param     = $video_param ;
			$param['item_duration'] = $video_param['duration'] ;

		} else {
			$this->_msg_item = ' no video info, ' ;
		}
	}

	$param['item_displaytype'] = $this->get_displaytype( $src_ext ) ;
	$param['item_onclick']     = $this->get_onclick( $src_ext ) ;

	return $param;
}

function get_displaytype( $src_ext )
{
	$displaytype = _C_WEBPHOTO_DISPLAYTYPE_GENERAL ;

	if ( $this->is_image_ext( $src_ext ) ) {
		$displaytype = _C_WEBPHOTO_DISPLAYTYPE_IMAGE ;

	} elseif ( $this->is_swfobject_ext( $src_ext ) ) {
		$displaytype = _C_WEBPHOTO_DISPLAYTYPE_SWFOBJECT ;

	} elseif ( $this->is_mediaplayer_ext( $src_ext ) ) {
		$displaytype = _C_WEBPHOTO_DISPLAYTYPE_MEDIAPLAYER ;
	}

	return $displaytype ;
}

function get_onclick( $src_ext )
{
	$onclick = _C_WEBPHOTO_ONCLICK_PAGE ;

	if ( $this->is_image_ext( $src_ext ) ) {
		$onclick = _C_WEBPHOTO_ONCLICK_POPUP ;
	}

	return $onclick ;
}

function get_exif_info( $file )
{
	$info = $this->_exif_class->read_file( $file );
	if ( !is_array($info) ) {
		return null;
	}

	$info['datetime_mysql'] = $this->exif_to_mysql_datetime( $info );

	$zoom = 0 ;
	if ( ( $info['latitude'] > 0 )&&( $info['longitude'] > 0 ) ) {
		$zoom = $this->_GMAP_ZOOM ;
	}
	$info['gmap_zoom'] = $zoom ;

	return $info;
}

function get_duration_size( $file )
{
	return $this->_video_class->get_duration_size( $file );
}

function get_video_param()
{
	return $this->_video_param ;
}

//---------------------------------------------------------
// updete item 
//---------------------------------------------------------
function build_update_item_row( $item_row, $file_id_array, $playlist_cache=null, $specail_ext=null )
{
	$row = $item_row;

	if ( is_array($file_id_array) ) {
		$cont_id   = $this->get_array_value_by_key( $file_id_array, 'cont_id' );
		$thumb_id  = $this->get_array_value_by_key( $file_id_array, 'thumb_id' );
		$middle_id = $this->get_array_value_by_key( $file_id_array, 'middle_id' );
		$flash_id  = $this->get_array_value_by_key( $file_id_array, 'flash_id' );
		$docomo_id = $this->get_array_value_by_key( $file_id_array, 'docomo_id' );

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

		if ( $flash_id > 0 ) {
			$row[ _C_WEBPHOTO_ITEM_FILE_VIDEO_FLASH ] = $flash_id;
			$row['item_displaytype'] = _C_WEBPHOTO_DISPLAYTYPE_MEDIAPLAYER ;
		}

		if ( $docomo_id > 0 ) {
			$row[ _C_WEBPHOTO_ITEM_FILE_VIDEO_DOCOMO ] = $docomo_id;
		}
	}

	if ( $playlist_cache ) {
		$row['item_playlist_cache'] = $playlist_cache ;
	}

	if ( empty( $row[ _C_WEBPHOTO_ITEM_FILE_THUMB ] ) &&
	     empty( $row[ 'item_external_thumb' ] ) &&
	     empty( $row[ 'item_icon_name' ] ) ) {

		if ( $specail_ext ) {
			$ext = $specail_ext ;
		} else {
			$ext = $item_row['item_ext'] ;
		}

		list( $name, $width, $height ) =
			$this->build_icon_image( $ext );
		$row['item_icon_name']   = $name ;
		$row['item_icon_width']  = $width ;
		$row['item_icon_height'] = $height ;
	}

	return $row ;
}

function get_array_value_by_key( $array, $key )
{
	return intval( 
		$this->_utility_class->get_array_value_by_key( $array, $key, 0 ) ) ;
}

//---------------------------------------------------------
// create cont
//---------------------------------------------------------
function create_insert_cont( $item_id, $param )
{
	$this->_cont_param = null ;

	$ret = $this->create_cont_param( $item_id, $param );
	if ( $ret < 0 ) {
		return $ret ;
	}

	$cont_param = $this->get_cont_param();
	if ( !is_array($cont_param) ) {
		return _C_WEBPHOTO_ERR_CREATE_PHOTO ;
	}

	$newid = $this->insert_file( $item_id, $cont_param );
	if ( !$newid ) {
		return _C_WEBPHOTO_ERR_DB ;
	}

	return $newid;
}

function create_cont_param( $item_id, $param )
{
	$src_file = $param['src_file'];
	$src_ext  = $param['src_ext'];
	$rotate   = isset($param['rotate']) ? intval($param['rotate']) : 0 ;

	$this->_cont_param = null ;

	$ret = $this->_image_class->create_photo( $src_file, $item_id, $rotate );

	if ( $ret == _C_WEBPHOTO_IMAGE_READFAULT ) {
		$this->print_msg_level_admin( ' Cannot read file, ', true );
		return _C_WEBPHOTO_ERR_FILEREAD;
	}
	if ( $ret == _C_WEBPHOTO_IMAGE_RESIZE ) {
		$this->_flag_resized = true;
		$this->print_msg_level_admin( ' resize photo, ' );
	}

	$cont_param = $this->_image_class->get_cont_param(); 
	if ( !is_array($cont_param) ) {
		$this->print_msg_level_admin( ' Cannot create photo, ', true );
		return _C_WEBPHOTO_ERR_CREATE_PHOTO ;
	}

	$this->_cont_param = $this->build_cont_param( $cont_param, $param );
	return 0 ;
}

function build_cont_param( $cont_param, $param )
{
	$src_ext      = $param['src_ext'];
	$mime_in      = isset($param['mime'])        ? $param['mime']         : null ;
	$video_param  = isset($param['video_param']) ? $param['video_param']  : null ;

	if ( is_array($video_param) ) {
		$cont_param['width']    = $video_param['width'] ;
		$cont_param['height']   = $video_param['height'] ;
		$cont_param['duration'] = $video_param['duration'] ;
	}

	if ( empty( $cont_param['mime'] ) ) {
		if ( $mime_in ) {
			$mime = $mime_in ;
		} else {
			$mime = $this->_mime_class->ext_to_mime( $src_ext );
		}
		$cont_param['mime']   = $mime ;
		$cont_param['medium'] = $this->_mime_class->mime_to_medium( $mime );
	}

	$cont_param['kind'] = _C_WEBPHOTO_FILE_KIND_CONT ;

	return $cont_param ;
}

function get_cont_param()
{
	return $this->_cont_param ;
}

//---------------------------------------------------------
// create thumb middle
//---------------------------------------------------------
function create_insert_thumb_middle( $item_id, $param )
{
	$thumb_id  = 0 ;
	$middle_id = 0;

	list( $thumb_param, $middle_param ) =
		$this->create_thumb_middle_param( $item_id, $param );

	if ( is_array($thumb_param) ) {
		$ret = $this->insert_file( $item_id, $thumb_param );
		if ( $ret > 0 ) {
			$thumb_id = $ret;
		}
	}

	if ( is_array($middle_param) ) {
		$ret = $this->insert_file( $item_id, $middle_param );
		if ( $ret > 0 ) {
			$middle_id = $ret;
		}
	}

	return array( $thumb_id, $middle_id );
}

function create_thumb_middle_param( $item_id, $param )
{
	$src_file = $param['src_file'];
	$src_ext  = $param['src_ext'];
	$src_kind = $param['src_kind'];

	$flag_thumb  = isset($param['flag_thumb'])  ? (bool)($param['flag_thumb'])  : false ;
	$flag_middle = isset($param['flag_middle']) ? (bool)($param['flag_middle']) : false ;
	$flag_video  = isset($param['flag_video'])  ? (bool)($param['flag_video'])  : false ;

	$thumb_param  = null;
	$middle_param = null;

	$photo_file       = $src_file ;
	$photo_ext        = $src_ext ;
	$flag_video_thumb = false;
	$video_tmp_file   = null;

// create video thumb
	if ( $flag_video && $this->is_video_kind( $src_kind ) ) {
		$param_video_thumb = $this->create_video_thumb( $item_id, $param );
		if ( is_array($param_video_thumb) ) {
			$flag_video_thumb = $param_video_thumb['flag'];
			$photo_file       = $param_video_thumb['file'];
			$photo_ext        = $param_video_thumb['ext'];
			$video_tmp_file   = $photo_file ;
		}
	}

// create thumb
	if ( $this->_cfg_makethumb && 
	   ( $this->is_image_kind( $src_kind ) || $flag_video_thumb ) ) {

		if ( $flag_thumb ) {

			$this->create_thumb_from_image_file( 
				$photo_file, $item_id, $photo_ext );

			$thumb_param = $this->get_thumb_param();
			if ( is_array($thumb_param) ) {
				$this->print_msg_level_admin( ' create thumb, ' );
			} else {
				$this->print_msg_level_admin( ' fail to create thumb, ', true ) ;
			}
		}
		if ( $flag_middle ) {

			$this->create_middle_from_image_file( 
				$photo_file, $item_id, $photo_ext );

			$middle_param = $this->get_middle_param();
			if ( is_array($middle_param) ) {
				$this->print_msg_level_admin( ' create middle, ' );
			} else {
				$this->print_msg_level_admin( ' fail to create middle, ', true ) ;
			}
		}
	}

// remove temp file
	if ( $video_tmp_file ) {
		$this->_utility_class->unlink_file( $video_tmp_file );
	}

	return array( $thumb_param, $middle_param );
}

//---------------------------------------------------------
// create video flash
//---------------------------------------------------------
function create_insert_video_flash( $item_id, $param )
{
	$flash_param = $this->create_video_flash_param( $item_id, $param ) ;
	if ( !is_array($flash_param) ) {
		return 0;
	}

	$ret = $this->insert_file( $item_id, $flash_param );
	if ( $ret < 0 ) {
		return 0;	// fail
	}
	return $ret;	// newid
}

function create_video_flash_param( $item_id, $param )
{
	$src_file = $param['src_file'];
	$src_kind = $param['src_kind'];

	$video_param = isset($param['video_param']) ? $param['video_param']  : null ;

	if ( ! $this->_cfg_use_ffmpeg ) {
		return null ;
	}
	if ( ! $this->is_video_kind( $src_kind ) ) {
		return null ;
	}

	$flash_param = $this->create_video_flash( $item_id, $src_file ) ;
	if ( !is_array($flash_param) ) {
		return null;
	}

	if ( is_array($video_param) ) {
		$flash_param['width']    = $video_param['width'] ;
		$flash_param['height']   = $video_param['height'] ;
		$flash_param['duration'] = $video_param['duration'] ;
	}

	$flash_param['kind'] = _C_WEBPHOTO_FILE_KIND_VIDEO_FLASH ;

	return $flash_param ;
}

function create_video_flash( $item_id, $src_file )
{
	$this->_flag_video_flash_created = false ;
	$this->_flag_video_flash_failed  = false ;

	$flash_param = null ;

	if ( ! $this->_cfg_use_ffmpeg ) {
		return null;
	}

	$flash_name = $this->build_photo_name( 
		$item_id, _C_WEBPHOTO_VIDEO_FLASH_EXT );

	$ret1 = $this->_video_class->create_flash( $src_file, $flash_name ) ;
	if ( $ret1 == _C_WEBPHOTO_VIDEO_CREATED ) {
		$this->_flag_video_flash_created  = true ;
		$this->print_msg_level_admin( ' create flash, ' );
		$flash_param = $this->_video_class->get_flash_param() ;

	} elseif ( $ret1 == _C_WEBPHOTO_VIDEO_FAILED ) {
		$this->_flag_video_flash_failed = true;
		$this->print_msg_level_admin( ' fail to create flash, ', true );
	}

	return $flash_param ;
}

//---------------------------------------------------------
// create video thumb
//---------------------------------------------------------
function create_video_thumb( $item_id, $param )
{
	$this->_flag_video_thumb_created = false ;
	$this->_flag_video_thumb_failed  = false ;

	$src_file = $param['src_file'];
	$src_ext  = $param['src_ext'];
	$src_kind = $param['src_kind'];

	$mode_video_thumb = isset($param['mode_video_thumb']) ?
		intval($param['mode_video_thumb']) : _C_WEBPHOTO_VIDEO_THUMB_SINGLE ;

	if ( ! $this->_cfg_makethumb ) {
		return null;
	}
	if ( ! $this->_cfg_use_ffmpeg ) {
		return null;
	}
	if ( ! $this->is_video_kind( $src_kind ) ) {
		return null;
	}

	if ( $mode_video_thumb == _C_WEBPHOTO_VIDEO_THUMB_PLURAL ) {
		$this->create_video_plural_thumbs( $item_id, $src_file, $src_ext );
		return null;

	} else {
		return $this->create_video_single_thumb( $item_id, $src_file );
	}

	return null ;	// dummy
}

function create_video_plural_thumbs( $item_id, $src_file, $src_ext )
{
	$count = $this->_video_class->create_plural_thumbs( $item_id, $src_file );
	if ( $count ) {
		$this->_flag_video_thumb_created = true;
		return true;
	}

	$this->_flag_video_thumb_failed = true;
	return false;
}

function create_video_single_thumb( $item_id, $src_file )
{
	$video_thumb_file = $this->_video_class->create_single_thumb( $item_id, $src_file ) ;
	if ( $video_thumb_file ) {
		$param = array(
			'flag' => true ,
			'file' => $video_thumb_file ,
			'ext'  => $this->_video_class->get_thumb_ext() ,
		);
		return $param;
	}
	return null;
}

//---------------------------------------------------------
// create video docomo
//---------------------------------------------------------
function create_insert_video_docomo( $item_id, $cont_param )
{
	$docomo_param = $this->create_video_docomo_param( $item_id, $cont_param ) ;
	if ( !is_array( $docomo_param ) ) {
		return 0;
	}

	$ret = $this->insert_file( $item_id, $docomo_param );
	if ( $ret < 0 ) {
		return 0;	// fail
	}
	return $ret;	// newid
}

function create_video_docomo_param( $item_id, $cont_param )
{
	if ( ! $this->is_video_docomo_ext( $cont_param['ext'] ) ) {
		return null;
	}

	$docomo_param         = $cont_param ;
	$docomo_param['path'] = '' ;	// null
	$docomo_param['kind'] = _C_WEBPHOTO_FILE_KIND_VIDEO_DOCOMO ;
	return $docomo_param ;
}

//---------------------------------------------------------
// update video thumb
//---------------------------------------------------------
function update_video_thumb_by_item_row( $item_row, $num )
{
	if ( !is_array($item_row) ) {
		return 0 ;	// no action
	}

	$item_id = $item_row['item_id'] ;
	$cat_id  = $item_row['item_cat_id'] ;
	$ext     = $item_row['item_ext'] ;

	$this->_item_row    = null;
	$this->_item_cat_id = 0 ;
	$this->_flag_video_thumb_created = false ;
	$this->_flag_video_thumb_failed  = false ;

	$thumb_id    = 0 ;
	$middle_id   = 0 ;

// created thumb
	$src_file = $this->build_video_thumb_file( $item_id, $num );
	if ( is_file($src_file) ) {
		$thumb_id = $this->create_update_video_thumb_common(
			$item_row, $src_file, _C_WEBPHOTO_FILE_KIND_THUMB );
		$middle_id = $this->create_update_video_thumb_common(
			$item_row, $src_file, _C_WEBPHOTO_FILE_KIND_MIDDLE );
	}

// remove files
	$this->unlink_video_thumb_temp_files( $item_id );

// update date
	$file_id_array = array(
		'thumb_id'  => $thumb_id ,
		'middle_id' => $middle_id ,
	);

	$row_update = $this->build_update_item_row( 
		$item_row, $file_id_array );

// --- update item ---
	$ret = $this->_item_handler->update( $row_update, $this->_flag_force_db );
	if ( !$ret ) {
		$this->set_error( $this->_item_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB ;
	}

// save row
	$this->_item_row    = $row_update ;
	$this->_item_cat_id = $cat_id ;

	return 0;
}

function create_update_video_thumb_common( $item_row, $src_file, $kind )
{
	if ( !is_file( $src_file) ) {
		return 0 ;	// no action
	}

	$item_id  = $item_row['item_id'] ;
	$item_ext = $item_row['item_ext'] ;

	$flag_update = false;

	$file_id = $this->_item_handler->build_value_fileid_by_kind( $item_row, $kind );
	if ( $file_id > 0 ) {
		$file_row = $this->_file_handler->get_row_by_id( $file_id );

		if ( is_array($file_row) ) {
			$flag_update = true ;
			$file_path   = $file_row['file_path'] ;

// remove old file
			if ( $file_path ) {
				$this->unlink_file( XOOPS_ROOT_PATH . $file_path );
			}
		}
	}

	if ( $kind == _C_WEBPHOTO_FILE_KIND_THUMB ) {
		$param = $this->create_video_thumb_for_update( $item_id, $src_file );

	} elseif ( $kind == _C_WEBPHOTO_FILE_KIND_MIDDLE ) {
		$param = $this->create_video_middle_for_update( $item_id, $src_file );
	}

	$param['duration'] = 0 ;
	$param['kind']     = $kind ;

// update
	if ( $flag_update ) {
		$this->unlink_current_file( $file_row, $param );

		$ret = $this->update_file( $file_row, $param );
		if ( !$ret ) {
			$file_id = 0;	// fail
		}

// insert
	} else {
		$ret = $this->insert_file( $item_id, $param );
		if ( $ret > 0 ) {
			$file_id = $ret;	// newid
		}
	}

	return $file_id ;
}

function create_video_thumb_for_update( $item_id, $src_file, $src_ext=null )
{
	$this->create_thumb_from_image_file( $src_file, $item_id, $src_ext );
	$param = $this->get_thumb_param();

	if ( is_array($param) ) {
		$this->_flag_video_thumb_created = true ;

	} else {
		$this->_flag_video_thumb_failed = true ;
	}

	return $param ;
}

function create_video_middle_for_update( $item_id, $src_file )
{
	$this->create_middle_from_image_file( $src_file, $item_id );
	$param = $this->get_middle_param();
	return $param ;
}

function unlink_current_file( $file_row, $param )
{
	$file_path = $file_row['file_path'];
	$path      = $param['path'];

	if ( $file_path && ( $file_path != $path ) ) {
		$this->unlink_path($file_path);
	}
}

function unlink_video_thumb_temp_files( $item_id )
{
	for ( $i = 1; $i <= $this->_VIDEO_THUMB_MAX; $i ++ )
	{
		$file = $this->build_video_thumb_file( $item_id, $i );
		$this->unlink_file( $file );
	}
}

function get_item_cat_id()
{
	return $this->_item_cat_id ;
}

function build_video_thumb_name( $item_id, $num )
{
	return $this->_video_class->build_thumb_name( $item_id, $num );
}

function build_video_thumb_file( $item_id, $num )
{
	$file = null ;
	$name = $this->_video_class->build_thumb_name( $item_id, $num );
	if ( $name ) {
		$file = $this->_TMP_DIR .'/'.  $name;
	}
	return $file ;
}

//---------------------------------------------------------
// file handler
//---------------------------------------------------------
function insert_files_from_params( $item_id, $params )
{
	if ( !is_array($params) ) {
		return false;
	}

	$arr = array(
		'cont_id'   => $this->insert_file_by_params( $item_id, $params, 'cont' ) ,
		'thumb_id'  => $this->insert_file_by_params( $item_id, $params, 'thumb' ) ,
		'middle_id' => $this->insert_file_by_params( $item_id, $params, 'middle' ) ,
		'flash_id'  => $this->insert_file_by_params( $item_id, $params, 'flash' ) ,
		'docomo_id' => $this->insert_file_by_params( $item_id, $params, 'docomo' ) ,
	);
	return $arr ;
}

function insert_file_by_params( $item_id, $params, $name )
{
	if ( isset( $params[ $name ] ) && is_array( $params[ $name ] ) ) {
		return $this->insert_file( $item_id,  $params[ $name ] );
	}
	return 0;
}

function insert_file( $item_id, $param )
{
	$row = $this->_file_handler->create();
	$row = $this->build_file_row( $row, $param );
	$row['file_item_id']   = $item_id ;

	$newid = $this->_file_handler->insert( $row, $this->_flag_force_db );
	if ( !$newid ) {
		$this->print_msg_level_admin( ' DB Error, ', true );
		$this->set_error( $this->_file_handler->get_errors() );
		return false ;
	}

	return $newid;
}

function update_file( $row, $param )
{
	$row = $this->build_file_row( $row, $param );
	$row['file_time_update'] = time() ;

// update
	$ret = $this->_file_handler->update( $row );
	if ( !$ret ) {
		$this->print_msg_level_admin( ' DB Error, ', true );
		$this->set_error( $this->_file_handler->get_errors() );
		return false ;
	}

	return true ;
}

function build_file_row( $row, $param )
{
	$width    = isset($param['width'])    ? intval($param['width'])    : 0 ;
	$height   = isset($param['height'])   ? intval($param['height'])   : 0 ;
	$duration = isset($param['duration']) ? intval($param['duration']) : 0 ;

	$row['file_url']       = $param['url'] ;
	$row['file_path']      = $param['path'] ;
	$row['file_name']      = $param['name'] ;
	$row['file_ext']       = $param['ext'] ;
	$row['file_mime']      = $param['mime'] ;
	$row['file_medium']    = $param['medium'] ;
	$row['file_size']      = $param['size'] ;
	$row['file_kind']      = $param['kind'] ;
	$row['file_width']     = $width ;
	$row['file_height']    = $height ;
	$row['file_duration']  = $duration ;

	return $row ;
}

//---------------------------------------------------------
// msg
//---------------------------------------------------------
function print_msg_level_admin( $msg, $flag_highlight=false, $flag_br=false )
{
	$str = $this->build_msg_level( _C_WEBPHOTO_MSG_LEVEL_ADMIN, $msg, $flag_highlight, $flag_br );
	if ( $str ) {
		echo $str;
	}
}

function print_msg_level_user( $msg, $flag_highlight=false, $flag_br=false )
{
	$str = $this->build_msg_level( _C_WEBPHOTO_MSG_LEVEL_USER, $msg, $flag_highlight, $flag_br );
	if ( $str ) {
		echo $str;
	}
}

//---------------------------------------------------------
// icon
//---------------------------------------------------------
function build_icon_image( $ext )
{
	$name  = null ;
	$width  = 0 ;
	$height = 0 ;

	if ( $ext ) {
		$name = $ext .'.'. $this->_EXT_PNG ;
		$file = $this->_ROOT_EXTS_DIR .'/'. $name ;
		if ( ! is_file( $file ) ) {
			$name = $this->_ICON_NAME_DEFAULT ;
			$file = $this->_ROOT_EXTS_DIR .'/'. $name ;
		}
		$size = getimagesize( $file );
		if ( is_array($size) ) {
			$width  = $size[0];
			$height = $size[1];
		}
	}

	return array( $name, $width, $height ) ;
}

//---------------------------------------------------------
// image class
//---------------------------------------------------------
function build_photo_name( $id, $ext, $extra=null )
{
	return $this->_image_class->build_photo_name( $id, $ext, $extra );
}

function create_thumb_from_image_file( $src_file, $photo_id, $src_ext=null  )
{
	return $this->_image_class->create_thumb_from_image_file( $src_file, $photo_id, $src_ext );
}

function get_thumb_param()
{
	return $this->_image_class->get_thumb_param();
}

function create_middle_from_image_file( $src_file, $photo_id, $src_ext=null )
{
	return $this->_image_class->create_middle_from_image_file( $src_file, $photo_id, $src_ext );
}

function get_middle_param()
{
	return $this->_image_class->get_middle_param();
}

function build_file_param( $path, $name, $ext=null, $kind=null )
{
	return $this->_image_class->build_file_param( $path, $name, $ext, $kind );
}

function cmd_modify_photo( $src_file , $dst_file )
{
	return $this->_image_class->cmd_modify_photo( $src_file , $dst_file );
}

//---------------------------------------------------------
// mime class
//---------------------------------------------------------
function get_my_allowed_mimes()
{
	return $this->_mime_class->get_cached_my_allowed_mimes();
}

function is_my_allow_ext( $ext )
{
	return $this->_mime_class->is_my_allow_ext( $ext );
}

//---------------------------------------------------------
// set param
//---------------------------------------------------------
function set_flag_force_db( $val )
{
	$this->_flag_force_db = (bool)$val;
}

function set_flag_print_first_msg( $val )
{
	$this->_flag_print_first_msg = (bool)$val;
}

function set_image_video_flag_chmod( $val )
{
	$this->_image_class->set_flag_chmod( $val );
	$this->_video_class->set_flag_chmod( $val );
}

//---------------------------------------------------------
// get param
//---------------------------------------------------------
function has_resize()
{
	return $this->_has_resize ;
}

function has_rotate()
{
	return $this->_has_rotate ;
}

function get_newid()
{
	return $this->_item_newid ;
}

function get_row()
{
	return $this->_item_row ;
}

function get_resized()
{
	return $this->_flag_resized ;
}

function get_video_flash_created()
{
	return $this->_flag_video_flash_created ;
}

function get_video_flash_failed()
{
	return $this->_flag_video_flash_failed ;
}

function get_video_thumb_created()
{
	return $this->_flag_video_thumb_created ;
}

function get_video_thumb_failed()
{
	return $this->_flag_video_thumb_failed;
}

// --- class end ---
}

?>