<?php
// $Id: wav.php,v 1.3 2010/10/06 02:22:46 ohwada Exp $

//=========================================================
// webphoto module
// 2009-10-25 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// get_video_info()
// 2009-11-11 K.OHWADA
// $trust_dirname 
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_ext_wav
//=========================================================
class webphoto_ext_wav extends webphoto_ext_base
{
	var $_lame_class;
	var $_ffmpeg_class ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_ext_wav( $dirname, $trust_dirname )
{
	$this->webphoto_ext_base( $dirname, $trust_dirname );

	$this->_lame_class   =& webphoto_lame::getInstance(   $dirname, $trust_dirname  );
	$this->_ffmpeg_class =& webphoto_ffmpeg::getInstance( $dirname, $trust_dirname );
}

//---------------------------------------------------------
// check ext
//---------------------------------------------------------
function is_ext( $ext )
{
	return $this->is_audio_wav_ext( $ext );
}

function is_audio_wav_ext( $ext )
{
	return $this->match_ext_kind( $ext, _C_WEBPHOTO_MIME_KIND_AUDIO_WAV );
}

//---------------------------------------------------------
// create mp3
//---------------------------------------------------------
function create_mp3( $param )
{
	$src_file = $param['src_file'];
	$mp3_file = $param['mp3_file'] ;

	return $this->_lame_class->create_mp3( $src_file, $mp3_file );
}

//---------------------------------------------------------
// duration
//---------------------------------------------------------
function get_video_info( $param )
{
	$src_file = $param['src_file'];
	return $this->_ffmpeg_class->get_video_info( $src_file );
}

// --- class end ---
}

?>