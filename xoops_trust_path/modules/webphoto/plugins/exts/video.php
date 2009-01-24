<?php
// $Id: video.php,v 1.1 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_ext_video
//=========================================================
class webphoto_ext_video extends webphoto_ext_base
{
	var $_ffmpeg_class ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_ext_video( $dirname )
{
	$this->webphoto_ext_base( $dirname );

	$this->_ffmpeg_class =& webphoto_ffmpeg::getInstance( $dirname );
}

//---------------------------------------------------------
// check type
//---------------------------------------------------------
function is_ext( $ext )
{
	return $this->is_video_ext( $ext );
}

function is_video_ext( $ext )
{
	$ext = strtolower( $ext );
	if ( $ext == $this->_ASX_EXT ) {
		return false;
	}

	$mime = $this->get_cached_mime_type_by_ext( $ext );
	return $this->is_video_mime( $mime );
}

function is_video_mime( $mime )
{
	if ( preg_match('/^video/', $mime ) ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// create image
//---------------------------------------------------------
function create_image( $param )
{
	return $this->_ffmpeg_class->create_image( $param );
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