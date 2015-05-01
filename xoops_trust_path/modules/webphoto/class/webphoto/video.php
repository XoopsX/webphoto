<?php
// $Id: video.php,v 1.10 2009/01/06 09:41:35 ohwada Exp $

//=========================================================
// webphoto module
// 2008-07-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-01-04 K.OHWADA
// VODEO -> VIDEO
// 2008-11-29 K.OHWADA
// _C_WEBPHOTO_VODEO_THUMB_PLURAL_MAX
// 2008-11-08 K.OHWADA
// tmpdir -> workdir
// 2008-10-01 K.OHWADA
// PHOTOS_PATH -> FLASHS_PATH
// 2008-08-24 K.OHWADA
// get_flash_info -> get_flash_param
// 2008-08-01 K.OHWADA
// tmppath -> tmpdir
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_video
//=========================================================
class webphoto_video extends webphoto_lib_error
{
	var $_mime_handler ;
	var $_config_class ;
	var $_utility_class ;
	var $_ffmpeg_class ;

	var $_cfg_use_ffmpeg = false;

	var $_thumb_id   = 0;
	var $_flash_info = null ;

	var $_cached_extra_array = array();

	var $_FLASHS_PATH ;
	var $_TMP_DIR ;

	var $_PLURAL_MAX    = _C_WEBPHOTO_VIDEO_THUMB_PLURAL_MAX ;
	var $_PLURAL_SECOND = 0;
	var $_PLURAL_FIRST  = 0;
	var $_PLURAL_OFFSET = 1;

	var $_SINGLE_MAX    = 1;
	var $_SINGLE_SECOND = 1;
	var $_SINGLE_FIRST  = 0;

	var $_THUMB_PREFIX = _C_WEBPHOTO_VIDEO_THUMB_PREFIX ;	// tmp_video_
	var $_THUMB_EXT    = 'jpg';
	var $_ICON_EXT     = 'png';
	var $_FLASH_EXT    = _C_WEBPHOTO_VIDEO_FLASH_EXT ;	// flv
	var $_FLASH_MIME   = 'video/x-flv';
	var $_FLASH_MEDIUM = 'video';

	var $_DEBUG = false ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_video( $dirname )
{
	$this->webphoto_lib_error();

	$this->_mime_handler  =& webphoto_mime_handler::getInstance( $dirname );
	$this->_config_class  =& webphoto_config::getInstance( $dirname );
	$this->_utility_class =& webphoto_lib_utility::getInstance();

	$uploads_path = $this->_config_class->get_uploads_path();
	$work_dir     = $this->_config_class->get_by_name( 'workdir' );

	$this->_TMP_DIR     = $work_dir.'/tmp' ;
	$this->_FLASHS_PATH = $uploads_path.'/flashs';

	$cfg_ffmpegpath        = $this->_config_class->get_dir_by_name( 'ffmpegpath' );
	$this->_cfg_use_ffmpeg = $this->_config_class->get_by_name( 'use_ffmpeg' );

	$this->_ffmpeg_class =& webphoto_lib_ffmpeg::getInstance();
	$this->_ffmpeg_class->set_tmp_path( $this->_TMP_DIR );
	$this->_ffmpeg_class->set_cmd_path( $cfg_ffmpegpath );
	$this->_ffmpeg_class->set_ext( $this->_THUMB_EXT );

	$constpref = strtoupper( '_P_' . $dirname. '_' ) ;
	$this->set_debug_by_const_name(   $constpref.'DEBUG_VIDEO' );
}

public static function &getInstance( $dirname = null, $trust_dirname = null )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_video( $dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// set param
//---------------------------------------------------------
function set_flag_chmod( $val )
{
	$this->_ffmpeg_class->set_flag_chmod( $val );
}

//---------------------------------------------------------
// duration
//---------------------------------------------------------
function get_duration_size( $file )
{
	if ( !$this->_cfg_use_ffmpeg ) {
		return null;
	}
	return $this->_ffmpeg_class->get_duration_size( $file );
}

//---------------------------------------------------------
// thumb
//---------------------------------------------------------
function create_plural_thumbs( $id, $file )
{
	if ( !$this->_cfg_use_ffmpeg ) {
		return false;
	}

	$this->_thumb_id = $id;

	$this->_ffmpeg_class->set_prefix( $this->build_ffmpeg_prefix( $id ) );
	$this->_ffmpeg_class->set_offset( $this->_PLURAL_OFFSET );

	return $this->_ffmpeg_class->create_thumbs( 
		$file, $this->_PLURAL_MAX, $this->_PLURAL_SECOND );
}

function create_single_thumb( $id, $file )
{
	$path = null;

	if ( !$this->_cfg_use_ffmpeg ) {
		return $path;
	}

	$this->_ffmpeg_class->set_prefix( $this->build_ffmpeg_prefix( $id ) );

	$count = $this->_ffmpeg_class->create_thumbs( 
		$file, $this->_SINGLE_MAX, $this->_SINGLE_SECOND );
	if ( $count ) {
		$path = $this->_TMP_DIR .'/'. $this->build_thumb_name( $id, $this->_SINGLE_FIRST, false );
	} else {
		$errors = $this->_ffmpeg_class->get_errors();
		$this->set_error( $errors );
		if ( $this->_DEBUG ) {
			print_r( $errors );
		}
	}

	return $path ;
}

function build_ffmpeg_prefix( $id )
{
// prefix_123_
	$str = $this->_THUMB_PREFIX . $id . '_';
	return $str;
}

function build_thumb_name( $id, $num )
{
// prefix_123_456.jpg
	$str = $this->build_thumb_node( $id, $num ) .'.'. $this->_THUMB_EXT ;
	return $str;
}

function build_thumb_node( $id, $num )
{
// prefix_123_456
	$str = $this->build_ffmpeg_prefix( $id ) . $num ;
	return $str;
}

function get_first_thumb_node()
{
	return $this->build_thumb_node( $this->_thumb_id, $this->_PLURAL_FIRST );
}

function get_thumb_ext()
{
	return $this->_THUMB_EXT;
}

//---------------------------------------------------------
// flash
//---------------------------------------------------------
function create_flash( $file_in, $name_out )
{
	$this->_flash_param = null;

	$ext = $this->_utility_class->parse_ext( $file_in );

	if ( !$this->_cfg_use_ffmpeg ) {
		return _C_WEBPHOTO_VIDEO_SKIPPED ;
	}

// return input file is flash video
	if ( $ext == $this->_FLASH_EXT ) {
		return _C_WEBPHOTO_VIDEO_SKIPPED ;
	}

	$path_out = $this->_FLASHS_PATH .'/'. $name_out ;
	$file_out = XOOPS_ROOT_PATH . $path_out ;
	$url_out  = XOOPS_URL . $path_out ;
	$extra    = $this->get_cached_extra_by_ext( $ext );

	$ret = $this->_ffmpeg_class->create_flash( $file_in, $file_out, $extra );
	if ( !$ret ) {
		$this->_utility_class->unlink_file( $file_out );
		$errors = $this->_ffmpeg_class->get_errors();
		$this->set_error( $errors );
		if ( $this->_DEBUG ) {
			print_r( $errors );
		}
		return _C_WEBPHOTO_VIDEO_FAILED ;
	}

	$this->_flash_param = array(
		'url'    => $url_out ,
		'path'   => $path_out ,
		'name'   => $name_out ,
		'ext'    => $this->_FLASH_EXT ,
		'mime'   => $this->_FLASH_MIME ,
		'medium' => $this->_FLASH_MEDIUM ,
		'size'   => filesize( $file_out ) ,
	);

	return _C_WEBPHOTO_VIDEO_CREATED ;
}

function get_flash_param()
{
	return $this->_flash_param;
}

function get_flash_ext()
{
	return $this->_FLASH_EXT;
}

//---------------------------------------------------------
// mime
//---------------------------------------------------------
function get_cached_extra_by_ext( $ext )
{
	if ( isset( $this->_cached_extra_array[ $ext ] ) ) {
		return  $this->_cached_extra_array[ $ext ];
	}

	$row = $this->_mime_handler->get_cached_row_by_ext( $ext );
	if ( !is_array($row) ) {
		return false;
	}

	$extra = trim( $row['mime_ffmpeg'] ) ;
	$this->_cached_extra_array[ $ext ] = $extra ;
	return $extra ;
}

//---------------------------------------------------------
// debug
//---------------------------------------------------------
function set_debug_by_const_name( $name )
{
	if ( defined($name) ) {
		$val = constant($name);
		$this->set_debug( $val );
		$this->_ffmpeg_class->set_debug( $val );
	}
}

function set_debug( $val )
{
	$this->_DEBUG = (bool)$val ;
}

// --- class end ---
}

?>