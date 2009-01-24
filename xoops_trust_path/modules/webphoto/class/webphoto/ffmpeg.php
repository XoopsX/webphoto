<?php
// $Id: ffmpeg.php,v 1.2 2009/01/24 15:33:44 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_ffmpeg
// wrapper for webphoto_lib_ffmpeg
//=========================================================
class webphoto_ffmpeg extends webphoto_lib_error
{
	var $_config_class ;
	var $_ffmpeg_class ;

	var $_cfg_use_ffmpeg = false;

	var $_thumb_id = 0;

	var $_TMP_DIR ;

	var $_PLURAL_MAX    = _C_WEBPHOTO_VIDEO_THUMB_PLURAL_MAX ;
	var $_PLURAL_SECOND = 0;
	var $_PLURAL_FIRST  = 0;
	var $_PLURAL_OFFSET = 1;

	var $_SINGLE_MAX    = 1;
	var $_SINGLE_SECOND = 1;
	var $_SINGLE_FIRST  = 0;

	var $_THUMB_PREFIX = _C_WEBPHOTO_VIDEO_THUMB_PREFIX ;	// tmp_ffmpeg_
	var $_JPEG_EXT     = 'jpg';

	var $_DEBUG = false ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_ffmpeg( $dirname )
{
	$this->webphoto_lib_error();

	$this->_config_class  =& webphoto_config::getInstance( $dirname );

	$this->_TMP_DIR        = $this->_config_class->get_work_dir( 'tmp' );
	$this->_cfg_use_ffmpeg = $this->_config_class->get_by_name( 'use_ffmpeg' );
	$cfg_ffmpegpath        = $this->_config_class->get_dir_by_name( 'ffmpegpath' );

	$this->_ffmpeg_class =& webphoto_lib_ffmpeg::getInstance();
	$this->_ffmpeg_class->set_tmp_path( $this->_TMP_DIR );
	$this->_ffmpeg_class->set_cmd_path( $cfg_ffmpegpath );
	$this->_ffmpeg_class->set_ext( $this->_JPEG_EXT );
	$this->_ffmpeg_class->set_flag_chmod( true );

	$constpref = strtoupper( '_P_' . $dirname. '_' ) ;
	$this->set_debug_by_const_name(   $constpref.'DEBUG_FFMPEG' );
}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_ffmpeg( $dirname );
	}
	return $instance;
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
// create image
//---------------------------------------------------------
function create_image( $param )
{
	$item_id  = $param['item_id'];
	$src_file = $param['src_file'];
	$src_ext  = $param['src_ext'];

	if ( ! $this->_cfg_use_ffmpeg ) {
		return null;
	}

	if ( isset( $this->_cached[ $item_id ] ) ) {
		$created_file = $this->_cached[ $item_id ];
	} else {
		$created_file = $this->create_jpeg( $item_id, $src_file );
	}

	if ( ! is_file($created_file) ) {
		return null;
	}

	$this->_cached[ $item_id ] = $created_file ;

	$arr = array(
		'flag'      => true ,
		'item_id'   => $item_id ,
		'src_file'  => $created_file ,
		'src_ext'   => $this->_JPEG_EXT ,
		'icon_name' => $src_ext ,
	);
	return $arr;
}

function create_jpeg( $id, $file )
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
		$this->set_error( $this->_ffmpeg_class->get_msg_array() );
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
	$str = $this->build_thumb_node( $id, $num ) .'.'. $this->_JPEG_EXT ;
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
	return $this->_JPEG_EXT;
}

//---------------------------------------------------------
// plural images
//---------------------------------------------------------
function create_plural_images( $id, $file )
{
	if ( !$this->_cfg_use_ffmpeg ) {
		return false;
	}

	$this->_ffmpeg_class->set_prefix( $this->build_ffmpeg_prefix( $id ) );
	$this->_ffmpeg_class->set_offset( $this->_PLURAL_OFFSET );

	return $this->_ffmpeg_class->create_thumbs( 
		$file, $this->_PLURAL_MAX, $this->_PLURAL_SECOND );
}

//---------------------------------------------------------
// flash
//---------------------------------------------------------
function create_flash( $file_in, $file_out, $extra )
{
	$ret = $this->_ffmpeg_class->create_flash( $file_in, $file_out, $extra );
	if ( !$ret ) {
		$this->set_error( $this->_ffmpeg_class->get_msg_array() );
	}
	return $ret;
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