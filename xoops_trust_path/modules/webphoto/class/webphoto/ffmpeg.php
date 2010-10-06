<?php
// $Id: ffmpeg.php,v 1.6 2010/10/06 02:22:46 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// create_wav()
// 2009-11-11 K.OHWADA
// $trust_dirname
// 2009-10-25 K.OHWADA
// webphoto_cmd_base
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_ffmpeg
// wrapper for webphoto_lib_ffmpeg
//=========================================================
class webphoto_ffmpeg extends webphoto_cmd_base
{
	var $_ffmpeg_class ;

	var $_cfg_use_ffmpeg = false;

	var $_thumb_id = 0;

	var $_PLURAL_MAX    = _C_WEBPHOTO_VIDEO_THUMB_PLURAL_MAX ;
	var $_PLURAL_SECOND = 0;
	var $_PLURAL_FIRST  = 0;
	var $_PLURAL_OFFSET = 1;

	var $_SINGLE_SECOND = 1;

	var $_THUMB_PREFIX = _C_WEBPHOTO_VIDEO_THUMB_PREFIX ;	// tmp_ffmpeg_

	var $_CMD_FFMPEG = 'ffmpeg';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_ffmpeg( $dirname, $trust_dirname )
{
	$this->webphoto_cmd_base( $dirname, $trust_dirname );

	$this->_cfg_use_ffmpeg = $this->_config_class->get_by_name( 'use_ffmpeg' );
	$cfg_ffmpegpath        = $this->_config_class->get_dir_by_name( 'ffmpegpath' );

	$this->_ffmpeg_class =& webphoto_lib_ffmpeg::getInstance();
	$this->_ffmpeg_class->set_tmp_path( $this->_TMP_DIR );
	$this->_ffmpeg_class->set_cmd_path( $cfg_ffmpegpath );
	$this->_ffmpeg_class->set_ext( $this->_JPEG_EXT );
	$this->_ffmpeg_class->set_flag_chmod( true );

	$this->set_debug_by_ini_name( $this->_ffmpeg_class );
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_ffmpeg( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// duration
//---------------------------------------------------------
function get_video_info( $file )
{
	if ( !$this->_cfg_use_ffmpeg ) {
		return null;
	}
	return $this->_ffmpeg_class->get_video_info( $file );
}

//---------------------------------------------------------
// create jpeg
//---------------------------------------------------------
function create_jpeg( $src_file, $dst_file )
{
	if ( ! $this->_cfg_use_ffmpeg ) {
		return 0;
	}

	$this->_ffmpeg_class->create_single_thumb( $src_file, $dst_file, $this->_SINGLE_SECOND );
	if ( ! is_file($dst_file) ) {
		return -1;
	}

	return 1;
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

	$count = $this->_ffmpeg_class->create_thumbs( 
		$file, $this->_PLURAL_MAX, $this->_PLURAL_SECOND );

	if ( $count == 0 ) {
		$this->set_error( $this->_ffmpeg_class->get_msg_array() );
		return -1;
	}

	return 1;
}

function build_ffmpeg_prefix( $id )
{
// prefix_123_
	$str = $this->_THUMB_PREFIX . $id . '_';
	return $str;
}

// for misc_form
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

//---------------------------------------------------------
// flash
//---------------------------------------------------------
function create_flash( $src_file, $dst_file, $option=null )
{
	if ( empty($option) ) {
		$option = $this->get_cmd_option( $src_file, $this->_CMD_FFMPEG );
	}

	$ret = $this->_ffmpeg_class->create_flash( $src_file, $dst_file, $option );
	if ( !$ret ) {
		$this->set_error( $this->_ffmpeg_class->get_msg_array() );
	}
	return $ret;
}

//---------------------------------------------------------
// mp3
//---------------------------------------------------------
function create_mp3( $src_file, $dst_file, $option=null )
{
	if ( empty($option) ) {
		$option = $this->get_cmd_option( $src_file, $this->_CMD_FFMPEG );
	}

	$ret = $this->_ffmpeg_class->create_mp3( $src_file, $dst_file, $option );
	if ( !$ret ) {
		$this->set_error( $this->_ffmpeg_class->get_msg_array() );
	}
	return $ret;
}

//---------------------------------------------------------
// wav
//---------------------------------------------------------
function create_wav( $src_file, $dst_file, $option=null )
{
	if ( empty($option) ) {
		$option = $this->get_cmd_option( $src_file, $this->_CMD_FFMPEG );
	}

	$ret = $this->_ffmpeg_class->create_wav( $src_file, $dst_file, $option );
	if ( !$ret ) {
		$this->set_error( $this->_ffmpeg_class->get_msg_array() );
	}
	return $ret;
}

// --- class end ---
}

?>