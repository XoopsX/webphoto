<?php
// $Id: video_ffmpeg.php,v 1.1 2010/09/27 03:44:45 ohwada Exp $

//=========================================================
// webphoto module
// 2010-09-20 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_ext_video_ffmpeg
//=========================================================
class webphoto_ext_video_ffmpeg extends webphoto_ext_base
{
	var $_ffmpeg_class ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_ext_video_ffmpeg( $dirname, $trust_dirname )
{
	$this->webphoto_ext_base( $dirname, $trust_dirname );

	$this->_ffmpeg_class =& webphoto_ffmpeg::getInstance( $dirname, $trust_dirname );
}

//---------------------------------------------------------
// check type
//---------------------------------------------------------
function is_ext( $ext )
{
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
// create video_images
//---------------------------------------------------------
function create_video_images( $param )
{
	$item_id  = $param['item_id'] ;
	$src_file = $param['src_file'];

	return $this->_ffmpeg_class->create_plural_images( $item_id, $src_file );
}

//---------------------------------------------------------
// create flv
//---------------------------------------------------------
function create_flv( $param )
{
	$src_file = $param['src_file'];
	$flv_file = $param['flv_file'] ;

	$ret = $this->_ffmpeg_class->create_flash( $src_file, $flv_file );
	if ( $ret == -1 ) { 
		$this->set_error( $this->_ffmpeg_class->get_errors() );
	}

	return $ret;
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