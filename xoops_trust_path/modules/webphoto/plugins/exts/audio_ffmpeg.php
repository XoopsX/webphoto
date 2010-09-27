<?php
// $Id: audio_ffmpeg.php,v 1.1 2010/09/27 03:44:45 ohwada Exp $

//=========================================================
// webphoto module
// 2010-09-20 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_ext_audio_ffmpeg
//=========================================================
class webphoto_ext_audio_ffmpeg extends webphoto_ext_base
{
	var $_ffmpeg_class ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_ext_audio_ffmpeg( $dirname, $trust_dirname )
{
	$this->webphoto_ext_base( $dirname, $trust_dirname );

	$this->_ffmpeg_class =& webphoto_ffmpeg::getInstance( $dirname, $trust_dirname );
}

//---------------------------------------------------------
// check type
//---------------------------------------------------------
function is_ext( $ext )
{
	return $this->is_audio_ext( $ext );
}

function is_audio_ext( $ext )
{
	return $this->match_ext_kind( $ext, _C_WEBPHOTO_MIME_KIND_AUDIO_FFMPEG );
}

//---------------------------------------------------------
// create wav
//---------------------------------------------------------
function create_wav( $param )
{
	$src_file = $param['src_file'];
	$wav_file = $param['wav_file'] ;

	return $this->_ffmpeg_class->create_wav( $src_file, $wav_file );
}

//---------------------------------------------------------
// duration
//---------------------------------------------------------
function get_duration_size( $param )
{
	$src_file = $param['src_file'];
	return $this->_ffmpeg_class->get_duration_size( $src_file );
}

// --- class end ---
}

?>