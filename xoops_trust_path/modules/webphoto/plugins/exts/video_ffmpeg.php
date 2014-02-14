<?php
// $Id: video_ffmpeg.php,v 1.2 2010/10/06 02:22:46 ohwada Exp $

//=========================================================
// webphoto module
// 2010-10-01 K.OHWADA
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
// create jpeg
//---------------------------------------------------------
function create_jpeg( $param )
{
	$src_file  = $param['src_file'] ;
	$jpeg_file = $param['jpeg_file'] ;

	return $this->_ffmpeg_class->create_jpeg( $src_file, $jpeg_file );
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
function get_video_info( $param )
{
	$src_file = $param['src_file'];
	return $this->_ffmpeg_class->get_video_info( $src_file );
}

// --- class end ---
}

?>