<?php
// $Id: audio.php,v 1.1 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_ext_audio
//=========================================================
class webphoto_ext_audio extends webphoto_ext_base
{
	var $_ffmpeg_class ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_ext_audio( $dirname )
{
	$this->webphoto_ext_base( $dirname );

	$this->_ffmpeg_class =& webphoto_ffmpeg::getInstance( $dirname );
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
	$mime = $this->get_cached_mime_type_by_ext( $ext );
	return $this->is_audio_mime( $mime );
}

function is_audio_mime( $mime )
{
	if ( preg_match('/^audio/', $mime ) ) {
		return true;
	}
	return false;
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