<?php
// $Id: ffmpeg.php,v 1.2 2008/07/05 15:45:11 ohwada Exp $

//=========================================================
// webphoto module
// 2008-07-01 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_lib_ffmpeg
//=========================================================
class webphoto_lib_ffmpeg
{
// set param
	var $_CMD_PATH = null;
	var $_TMP_PATH = null;
	var $_prefix   = 'thumb';
	var $_ext      = 'jpg';
	var $_offset   = 0;

	var $_errors = array();

	var $_EXT_FLV  = 'flv';

	var $_DEBUG = false;

	var $_CMD_INFO          = 'ffmpeg -i %s';
	var $_CMD_CREATE_THUMBS = 'ffmpeg -vframes 1 -ss %s -i %s -f image2 %s';
	var $_CMD_CREATE_FLASH  = 'ffmpeg -i %s -vcodec flv %s -f flv %s';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_ffmpeg()
{
	// dummy
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_ffmpeg();
	}
	return $instance;
}

//---------------------------------------------------------
// set 
//---------------------------------------------------------
// MUST path has no sapce
// cannot use windows type's path like the following
// C:/Program Files/program/
function set_cmd_path( $val )
{
	$this->_CMD_PATH = $val;
}

function set_tmp_path( $val )
{
	$this->_TMP_PATH = $val;
}

function set_prefix( $val )
{
	$this->_prefix = $val;
}

function set_ext( $val )
{
	$this->_ext = $val;
}

function set_offset( $val )
{
	$this->_offset = $val;
}

function set_debug( $val )
{
	$this->_DEBUG = (bool)$val ;
}

//---------------------------------------------------------
// get duration width height
//
// forcible method
// duration time in strerr, when execute the input-file only
// reference http://blog.ishiro.com/?p=182
//
// Input #0, avi, from 'hoge.avi':
//  Duration: 00:00:09.00, start: 0.000000, bitrate: 9313 kb/s
//    Stream #0.0: Video: mjpeg, yuvj422p, 640x480, 30.00 tb(r)
//    Stream #0.1: Audio: pcm_u8, 11024 Hz, mono, 88 kb/s
//---------------------------------------------------------
function get_duration_size( $file )
{
	$cmd = $this->_CMD_PATH . sprintf( $this->_CMD_INFO, $file );

	exec( "$cmd 2>&1", $outputs );
	if ( $this->_DEBUG ) {
		echo $cmd."<br />\n";
		print_r( $outputs );
		echo "<br />\n";
	}

	if ( !is_array($outputs) ) {
		return false;
	}

	$duration = 0;
	$width    = 0;
	$height   = 0;

	foreach( $outputs as $line )
	{
		if ( preg_match( "/duration.*(\d+):(\d+):(\d+)/i", $line, $match ) ) {
			$duration = intval($match[1])*3600 + intval($match[2])*60 + intval($match[3]);
		}
		if ( preg_match( "/video.* (\d+)x(\d+)/i", $line, $match ) ) {
			$width  = intval($match[1]);
			$height = intval($match[2]);
		}
	}

	$arr = array(
		'duration' => $duration ,
		'width'    => $width ,
		'height'   => $height ,
	);
	return $arr;
}

//---------------------------------------------------------
// create thumbs 
//---------------------------------------------------------
function create_thumbs( $file_in, $max=5, $start=0, $step=1 )
{
	$this->_clear_error();

	$count = 0;
	for ( $i=0; $i<$max; $i++ ) 
	{
		$sec      = $i * $step + $start ;
		$name     = $this->build_thumb_name( $i + $this->_offset ) ;
		$file_out = $this->_TMP_PATH .'/'. $name;

		$cmd = $this->_CMD_PATH . sprintf( $this->_CMD_CREATE_THUMBS, $sec, $file_in, $file_out );

		exec( "$cmd 2>&1", $outputs );
		if ( $this->_DEBUG ) {
			echo $cmd."<br />\n";
			print_r( $outputs );
			echo "<br />\n";
		}

		if ( is_file($file_out) && filesize( $file_out ) ) {
			$count ++;
		} else {
			$this->_set_error( $cmd );
			$this->_set_error( $outputs );
		}

	}
	return $count ;
}

function build_thumb_name( $num )
{
	$str = $this->_prefix . $num .'.'. $this->_ext;
	return $str;
}

//---------------------------------------------------------
// create flash 
//---------------------------------------------------------
function create_flash( $file_in, $file_out, $extra=null )
{
	$this->_clear_error();

// return input file is flash video
	if ( $this->parse_ext( $file_in ) == $this->_EXT_FLV ) {
		return false;
	}

	$cmd = $this->_CMD_PATH . sprintf( $this->_CMD_CREATE_FLASH, $file_in, $extra, $file_out );

	exec( "$cmd 2>&1", $outputs );
	if ( $this->_DEBUG ) {
		echo $cmd."<br />\n";
		print_r( $outputs );
	}

	if ( is_file($file_out) && filesize( $file_out ) ) {
		return true ;
	}

	$this->_set_error( $cmd );
	$this->_set_error( $outputs );
	return false ;
}

function parse_ext( $file )
{
	return strtolower( substr( strrchr( $file , '.' ) , 1 ) );
}

//---------------------------------------------------------
// error 
//---------------------------------------------------------
function _clear_error()
{
	$this->_errors = array();
}

function _set_error( $outputs )
{
	if ( is_array($outputs) ) {
		foreach( $outputs as $line ) {
			$this->_errors[] = $line ;
		}
	} else {
		$this->_errors[] = $outputs ;
	}
}

function get_errors()
{
	return $this->_errors;
}

// --- class end ---
}

?>