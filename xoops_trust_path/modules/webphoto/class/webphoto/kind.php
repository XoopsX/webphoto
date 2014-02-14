<?php
// $Id: kind.php,v 1.7 2010/09/27 03:42:54 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-09-20 K.OHWADA
// is_wav_ext()
// 2009-10-25 K.OHWADA
// is_jpeg_ext()
// 2009-03-15 K.OHWADA
// change is_ext_in_array()
// 2009-01-25 K.OHWADA
// is_swf_ext()
// 2009-01-10 K.OHWADA
// is_general_kind() etc
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_kind
//=========================================================
class webphoto_kind
{
	var $_IMAGE_EXTS ;
	var $_SWFOBJECT_EXTS ;
	var $_MEDIAPLAYER_AUDIO_EXTS ;
	var $_MEDIAPLAYER_VIDEO_EXTS ;
	var $_VIDEO_DOCOMO_EXTS ;

	var $_FLASH_EXTS = array( 'flv' );
	var $_PDF_EXTS   = array( 'pdf' );
	var $_SWF_EXTS   = array( 'swf' );
	var $_JPEG_EXTS  = array( 'jpg','jpeg' );
	var $_MP3_EXTS   = array( 'mp3' );
	var $_WAV_EXTS   = array( 'wav' );

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_kind()
{
	$this->_IMAGE_EXTS             = explode( '|', _C_WEBPHOTO_IMAGE_EXTS );
	$this->_SWFOBJECT_EXTS         = explode( '|', _C_WEBPHOTO_SWFOBJECT_EXTS ) ;
	$this->_MEDIAPLAYER_AUDIO_EXTS = explode( '|', _C_WEBPHOTO_MEDIAPLAYER_AUDIO_EXTS ) ;
	$this->_MEDIAPLAYER_VIDEO_EXTS = explode( '|', _C_WEBPHOTO_MEDIAPLAYER_VIDEO_EXTS ) ;
	$this->_VIDEO_DOCOMO_EXTS      = explode( '|', _C_WEBPHOTO_VIDEO_DOCOMO_EXTS ) ;
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_kind();
	}
	return $instance;
}

//---------------------------------------------------------
// exts
//---------------------------------------------------------
function get_image_exts()
{
	return $this->_IMAGE_EXTS ;
}

function is_mediaplayer_ext( $ext )
{
	if ( $this->is_mediaplayer_audio_ext( $ext ) ) {
		return true;
	}
	if ( $this->is_mediaplayer_video_ext( $ext ) ) {
		return true;
	}
	return false;
}

function is_image_ext( $ext )
{
	return $this->is_ext_in_array( $ext, $this->_IMAGE_EXTS );
}

function is_jpeg_ext( $ext )
{
	return $this->is_ext_in_array( $ext, $this->_JPEG_EXTS );
}

function is_swfobject_ext( $ext )
{
	return $this->is_ext_in_array( $ext, $this->_SWFOBJECT_EXTS );
}

function is_mediaplayer_audio_ext( $ext )
{
	return $this->is_ext_in_array( $ext, $this->_MEDIAPLAYER_AUDIO_EXTS );
}

function is_mediaplayer_video_ext( $ext )
{
	return $this->is_ext_in_array( $ext, $this->_MEDIAPLAYER_VIDEO_EXTS );
}

function is_video_docomo_ext( $ext )
{
	return $this->is_ext_in_array( $ext, $this->_VIDEO_DOCOMO_EXTS );
}

function is_flash_ext( $ext )
{
	return $this->is_ext_in_array( $ext, $this->_FLASH_EXTS );
}

function is_pdf_ext( $ext )
{
	return $this->is_ext_in_array( $ext, $this->_PDF_EXTS );
}

function is_swf_ext( $ext )
{
	return $this->is_ext_in_array( $ext, $this->_SWF_EXTS );
}

function is_mp3_ext( $ext )
{
	return $this->is_ext_in_array( $ext, $this->_MP3_EXTS );
}

function is_wav_ext( $ext )
{
	return $this->is_ext_in_array( $ext, $this->_WAV_EXTS );
}

function is_ext_in_array( $ext, $arr )
{
	if ( $ext && in_array( strtolower( $ext ) , $arr ) ) {
		return true;
	}
	return false ;
}

//---------------------------------------------------------
// kind
//---------------------------------------------------------
function is_src_image_kind( $kind )
{
	if ( $this->is_image_kind( $kind ) ) {
		return true;
	}
	if ( $this->is_external_image_kind( $kind ) ) {
		return true;
	}
}

function is_video_audio_kind( $kind )
{
	if ( $this->is_video_kind( $kind ) ) {
		return true;
	}
	if ( $this->is_audio_kind( $kind ) ) {
		return true;
	}
	return false;
}

function is_external_embed_playlist_kind( $kind )
{
	if ( $this->is_external_kind( $kind ) ) {
		return true;
	}
	if ( $this->is_embed_kind( $kind ) ) {
		return true;
	}
	if ( $this->is_playlist_kind( $kind ) ) {
		return true;
	}
	return false;
}

function is_external_kind( $kind )
{
	if ( $this->is_external_general_kind( $kind ) ) {
		return true;
	}
	if ( $this->is_external_image_kind( $kind ) ) {
		return true;
	}
	return false;
}

function is_playlist_kind( $kind )
{
	if ( $this->is_playlist_feed_kind( $kind ) ) {
		return true;
	}
	if ( $this->is_playlist_dir_kind( $kind ) ) {
		return true;
	}
	return false;
}

function is_undefined_kind( $kind )
{
	return $this->_is_kind( $kind, _C_WEBPHOTO_ITEM_KIND_UNDEFINED );
}

function is_none_kind( $kind )
{
	return $this->_is_kind( $kind, _C_WEBPHOTO_ITEM_KIND_NONE );
}

function is_general_kind( $kind )
{
	return $this->_is_kind( $kind, _C_WEBPHOTO_ITEM_KIND_GENERAL );
}

function is_image_kind( $kind )
{
	return $this->_is_kind( $kind, _C_WEBPHOTO_ITEM_KIND_IMAGE );
}

function is_video_kind( $kind )
{
	return $this->_is_kind( $kind, _C_WEBPHOTO_ITEM_KIND_VIDEO );
}

function is_audio_kind( $kind )
{
	return $this->_is_kind( $kind, _C_WEBPHOTO_ITEM_KIND_AUDIO );
}

function is_embed_kind( $kind )
{
	return $this->_is_kind( $kind, _C_WEBPHOTO_ITEM_KIND_EMBED );
}

function is_external_general_kind( $kind )
{
	return $this->_is_kind( $kind, _C_WEBPHOTO_ITEM_KIND_EXTERNAL_GENERAL );
}

function is_external_image_kind( $kind )
{
	return $this->_is_kind( $kind, _C_WEBPHOTO_ITEM_KIND_EXTERNAL_IMAGE );
}

function is_playlist_feed_kind( $kind )
{
	return $this->_is_kind( $kind, _C_WEBPHOTO_ITEM_KIND_PLAYLIST_FEED );
}

function is_playlist_dir_kind( $kind )
{
	return $this->_is_kind( $kind, _C_WEBPHOTO_ITEM_KIND_PLAYLIST_DIR );
}

function _is_kind( $kind, $const )
{
	if ( $kind == $const ) {
		return true;
	}
	return false;
}

// --- class end ---
}

?>