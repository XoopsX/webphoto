<?php
// $Id: mp3.php,v 1.1 2010/09/27 03:44:45 ohwada Exp $

//=========================================================
// webphoto module
// 2010-09-20 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_ext_mp3
//=========================================================
class webphoto_ext_mp3 extends webphoto_ext_base
{
	var $_ffmpeg_class ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_ext_mp3( $dirname, $trust_dirname )
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
	return $this->match_ext_kind( $ext, _C_WEBPHOTO_MIME_KIND_AUDIO_MP3 );
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