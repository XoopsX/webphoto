<?php
// $Id: video.php,v 1.3 2009/11/29 07:34:23 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//, $trust_dirname 

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// $trust_dirname 
// 2009-10-25 K.OHWADA
// match_ext_kind()
//---------------------------------------------------------

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
function webphoto_ext_video( $dirname, $trust_dirname )
{
	$this->webphoto_ext_base( $dirname, $trust_dirname );

	$this->_ffmpeg_class =& webphoto_ffmpeg::getInstance( $dirname, $trust_dirname );
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
	$ret = $this->match_ext_kind( $ext, _C_WEBPHOTO_MIME_KIND_VIDEO );
	if ( $ret ) {
		return $ret;
	}
	return $this->match_ext_kind( $ext, _C_WEBPHOTO_MIME_KIND_VIDEO_FFMPEG );
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