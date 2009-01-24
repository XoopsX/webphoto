<?php
// $Id: ffmpeg.php,v 1.6 2009/01/24 15:33:44 ohwada Exp $

//=========================================================
// webphoto module
// 2008-07-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-01-10 K.OHWADA
// version()
// 2008-08-24 K.OHWADA
// flag_chmod
//---------------------------------------------------------

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
	var $_flag_chmod = false;

	var $_msg_array = array();

	var $_CMD_INFO          = 'ffmpeg -i %s';
	var $_CMD_CREATE_THUMBS = 'ffmpeg -vframes 1 -ss %s -i %s -f image2 %s';
	var $_CMD_CREATE_FLASH  = 'ffmpeg -i %s -vcodec flv %s -f flv %s';

	var $_EXT_FLV = 'flv';

	var $_DEBUG   = false;

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

function set_flag_chmod( $val )
{
	$this->_flag_chmod = (bool)$val ;
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
	$this->clear_msg_array();

	$cmd = $this->_CMD_PATH . sprintf( $this->_CMD_INFO, $file );

	$outputs = null;
	exec( "$cmd 2>&1", $ret_array );
	if ( $this->_DEBUG ) {
		echo $cmd."<br />\n";
		print_r( $outputs );
		echo "<br />\n";
	}

	$this->set_msg( $cmd );
	$this->set_msg( $ret_array );

	if ( !is_array($ret_array) ) {
		return false;
	}

	$duration = 0;
	$width    = 0;
	$height   = 0;

	foreach( $ret_array as $line )
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
	$this->clear_msg_array();

	$count = 0;
	for ( $i=0; $i<$max; $i++ ) 
	{
		$sec      = $i * $step + $start ;
		$name     = $this->build_thumb_name( $i + $this->_offset ) ;
		$file_out = $this->_TMP_PATH .'/'. $name;

		$cmd = $this->_CMD_PATH . sprintf( $this->_CMD_CREATE_THUMBS, $sec, $file_in, $file_out );

		$ret_array = null;
		exec( "$cmd 2>&1", $ret_array );
		if ( $this->_DEBUG ) {
			echo $cmd."<br />\n";
			print_r( $ret_array );
			echo "<br />\n";
		}

		$this->set_msg( $cmd );
		$this->set_msg( $ret_array );

		if ( is_file($file_out) && filesize( $file_out ) ) {
			if ( $this->_flag_chmod ) {
				chmod( $file_out, 0777 );
			}
			$count ++;
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
	$this->clear_msg_array();

// return input file is flash video
	if ( $this->parse_ext( $file_in ) == $this->_EXT_FLV ) {
		return false;
	}

	$cmd = $this->_CMD_PATH . sprintf( 
		$this->_CMD_CREATE_FLASH, $file_in, $extra, $file_out );

	$ret_array = null;
	exec( "$cmd 2>&1", $ret_array );
	if ( $this->_DEBUG ) {
		echo $cmd."<br />\n";
		print_r( $ret_array );
	}

	$this->set_msg( $cmd );
	$this->set_msg( $ret_array );

	if ( is_file($file_out) && filesize( $file_out ) ) {
		if ( $this->_flag_chmod ) {
			chmod( $file_out, 0777 );
		}
		return true ;
	}

	return false ;
}

function parse_ext( $file )
{
	return strtolower( substr( strrchr( $file , '.' ) , 1 ) );
}

//---------------------------------------------------------
// version
//---------------------------------------------------------
function version( $path )
{
	$ret = false;
	$str = '';

	$cmd = "{$path}ffmpeg -version 2>&1";
	exec( $cmd , $ret_array ) ;
	if ( is_array($ret_array) && count($ret_array) ) {
		foreach ( $ret_array as $line ) {
			if ( preg_match('/version/i', $line ) ) {
				$str .= $line ."<br />\n";
				$ret  = true;
			}
		}
	}

	if ( !$ret ) {
		$str = "Error: {$path}ffmpeg can't be executed" ;
	}

	return array( $ret, $str );
}

//---------------------------------------------------------
// msg
//---------------------------------------------------------
function clear_msg_array()
{
	$this->_msg_array = array();
}

function get_msg_array()
{
	return $this->_msg_array;
}

function set_msg( $ret_array )
{
	if ( is_array($ret_array) ) {
		foreach( $ret_array as $line ) {
			$this->_msg_array[] = $line ;
		}
	} else {
		$this->_msg_array[] = $ret_array ;
	}
}

// --- class end ---
}

?>