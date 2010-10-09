<?php
// $Id: ffmpeg.php,v 1.12 2010/10/09 02:19:20 ohwada Exp $

//=========================================================
// webphoto module
// 2008-07-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// create_wav()
// 2010-06-06 K.OHWADA
// is_win_os()
// 2009-04-21 K.OHWADA
// chmod_file()
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
	var $_ini_safe_mode ;

// set param
	var $_CMD_PATH = null;
	var $_TMP_PATH = null;
	var $_prefix   = 'thumb';
	var $_ext      = 'jpg';
	var $_offset   = 0;
	var $_flag_chmod = false;

	var $_audio_aac_array  = array('aac','libfaad');
	var $_video_h264_array = array('h264');

	var $_msg_array = array();

	var $_CMD_FFMPEG          = 'ffmpeg';
	var $_PARAM_INFO          = ' -i %s';
	var $_PARAM_CREATE_THUMBS = ' -vframes 1 -ss %s -i %s -f image2 %s';
	var $_PARAM_CREATE_FLASH  = ' -i %s -vcodec flv %s -f flv %s';
	var $_PARAM_CREATE_MP3    = ' -i %s %s -f mp3 %s';
	var $_PARAM_CREATE_WAV    = ' -i %s %s -f wav %s';
	var $_PARAM_VERSION       = ' -version';

	var $_EXT_FLV    = 'flv';
	var $_EXT_MP3    = 'mp3';
	var $_EXT_WAV    = 'wav';
	var $_CHMOD_MODE = 0777;

	var $_DEBUG   = false;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_ffmpeg()
{
	$this->_ini_safe_mode = ini_get('safe_mode');
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
	$this->_CMD_PATH   = $val;
	$this->_CMD_FFMPEG = $this->_CMD_PATH.'ffmpeg';

	if ( $this->is_win_os() ) {
		$this->_CMD_FFMPEG = $this->conv_win_cmd( $this->_CMD_FFMPEG );
	}
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
function get_video_info( $file )
{
	$cmd = $this->_CMD_FFMPEG . sprintf( $this->_PARAM_INFO, $file );

	$this->clear_msg_array();
	$ret_array = $this->cmd_excute( $cmd );
	if ( !$ret_array ) {
		return false;
	}

	$line_duration = null;
	$line_audio    = null;
	$line_video    = null;
	$audio_codec   = null;
	$video_codec   = null;
	$duration = 0;
	$width    = 0;
	$height   = 0;

	foreach( $ret_array as $line )
	{
		if ( preg_match( "/duration.*(\d+):(\d+):(\d+)/i", $line, $match ) ) {
			$line_duration = $line;
			$duration = intval($match[1])*3600 + intval($match[2])*60 + intval($match[3]);
		}
		if ( preg_match( "/stream.*audio:(.*)/i", $line, $match ) ) {
			$line_audio = $line;
			$arr = explode( ',', $match[1] );
			if ( isset($arr[0]) ) {
				$audio_codec = trim($arr[0]);
			}
		}
		if ( preg_match( "/stream.*video:(.*)\s(\d+)x(\d+)/i", $line, $match ) ) {
			$line_video = $line;
			$width  = intval($match[2]);
			$height = intval($match[3]);
			$arr = explode( ',', $match[1] );
			if ( isset($arr[0]) ) {
				$video_codec = trim($arr[0]);
			}
		}
	}

	$arr = array(
		'line_duration' => $line_duration ,
		'line_audio'    => $line_audio ,
		'line_video'    => $line_video ,
		'audio_codec'   => $audio_codec ,
		'video_codec'   => $video_codec ,
		'is_h264_aac'   => $this->is_h264_aac( $video_codec, $audio_codec ) ,
		'duration' => $duration ,
		'width'    => $width ,
		'height'   => $height ,
	);
	return $arr;
}

function is_h264_aac( $video, $audio )
{
	if ( $this->is_video_h264( $video ) &&
	     $this->is_audio_aac( $audio ) ) {
		return true;
	}
	return false;
}

function is_video_h264( $video )
{
	if ( in_array($video, $this->_video_h264_array) ) {
		return true;
	}
	return false;
}

function is_audio_aac( $audio )
{
	if ( in_array($audio, $this->_audio_aac_array) ) {
		return true;
	}
	return false;
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

		$ret = $this->create_single_thumb( $file_in, $file_out, $sec );
		if ( $ret ) {
			$count ++;
		}
	}

	return $count ;
}

function create_single_thumb( $file_in, $file_out, $sec )
{
	$cmd = $this->_CMD_FFMPEG . sprintf( $this->_PARAM_CREATE_THUMBS, $sec, $file_in, $file_out );
	$this->cmd_excute( $cmd );
	return $this->chmod_file_out( $file_out );
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

// return input file is same format
	$ret = $this->check_file_in( $file_in, $this->_EXT_FLV );
	if ( $ret ) {
		return false;
	} 

	$cmd = $this->_CMD_FFMPEG . sprintf( 
		$this->_PARAM_CREATE_FLASH, $file_in, $extra, $file_out );

	$this->clear_msg_array();
	$this->cmd_excute( $cmd );

	return $this->chmod_file_out( $file_out );
}

//---------------------------------------------------------
// create wav
//---------------------------------------------------------
function create_wav( $file_in, $file_out, $extra=null )
{

// return input file is same format
	$ret = $this->check_file_in( $file_in, $this->_EXT_WAV );
	if ( $ret ) {
		return false;
	}

	$cmd = $this->_CMD_FFMPEG . sprintf( 
		$this->_PARAM_CREATE_WAV, $file_in, $extra, $file_out );

	$this->clear_msg_array();
	$this->cmd_excute( $cmd );

	return $this->chmod_file_out( $file_out );
}

//---------------------------------------------------------
// create mp3 
//---------------------------------------------------------
function create_mp3( $file_in, $file_out, $extra=null )
{

// return input file is same format
	$ret = $this->check_file_in( $file_in, $this->_EXT_MP3 );
	if ( $ret ) {
		return false;
	}

	$cmd = $this->_CMD_FFMPEG . sprintf( 
		$this->_PARAM_CREATE_MP3, $file_in, $extra, $file_out );

	$this->clear_msg_array();
	$this->cmd_excute( $cmd );

	return $this->chmod_file_out( $file_out );
}

//---------------------------------------------------------
// version
//---------------------------------------------------------
function version( $path )
{
	$ret = false;
	$msg = '';

	$ffmpeg = $path.'ffmpeg';
	if ( $this->is_win_os() ) {
		$ffmpeg = $this->conv_win_cmd( $ffmpeg );
	}

	$cmd = $ffmpeg.$this->_PARAM_VERSION;

	exec( "$cmd 2>&1", $ret_array ) ;
	if ( is_array($ret_array) && count($ret_array) ) {
		foreach ( $ret_array as $line ) {
			if ( preg_match('/version/i', $line ) ) {
				$msg .= $line ."<br />\n";
				$ret  = true;
			}
		}
	}

	if ( !$ret ) {
		$msg = "Error: ". $ffmpeg ." can't be executed" ;
	}

	return array( $ret, $msg );
}

//---------------------------------------------------------
// function
//---------------------------------------------------------
function cmd_excute( $cmd )
{
	$ret_array = null;
	exec( "$cmd 2>&1", $ret_array );
	if ( $this->_DEBUG ) {
		echo $cmd."<br />\n";
		print_r( $ret_array );
		echo "<br />\n";
	}

	$this->set_msg( $cmd );
	$this->set_msg( $ret_array );

	if ( !is_array($ret_array) ) {
		return false;
	}

	return $ret_array ;
}

function check_file_in( $file, $ext )
{
// file matches ext
	if ( $this->parse_ext( $file ) == $ext ) {
		return true;
	}
	return false;
}

function chmod_file_out( $file )
{
// file exists ?
	if ( !file_exists($file) || ( filesize($file) == 0 ) ) {
		return false;
	}

// chmod 
	if ( $this->_flag_chmod && !$this->_ini_safe_mode ) {
		chmod( $file, $this->_CHMOD_MODE );
	}

	return true ;
}

function parse_ext( $file )
{
	return strtolower( substr( strrchr( $file , '.' ) , 1 ) );
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
function is_win_os()
{
	if ( strpos(PHP_OS,"WIN") === 0 ) {
		return true;
	}
	return false;
}

function conv_win_cmd( $cmd )
{
	$str = '"'. $cmd .'.exe"';
	return $str;
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