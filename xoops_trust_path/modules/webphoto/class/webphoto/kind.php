<?php
// $Id: kind.php,v 1.2 2008/11/01 23:53:08 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

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

function is_image_ext( $ext )
{
	if ( in_array( strtolower( $ext ) , $this->_IMAGE_EXTS ) ) {
		return true;
	}
	return false;
}

function is_swfobject_ext( $ext )
{
	if ( in_array( strtolower( $ext ) , $this->_SWFOBJECT_EXTS ) ) {
		return true;
	}
	return false;
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

function is_mediaplayer_audio_ext( $ext )
{
	if ( in_array( strtolower( $ext ) , $this->_MEDIAPLAYER_AUDIO_EXTS ) ) {
		return true;
	}
	return false;
}

function is_mediaplayer_video_ext( $ext )
{
	if ( in_array( strtolower( $ext ) , $this->_MEDIAPLAYER_VIDEO_EXTS ) ) {
		return true;
	}
	return false;
}

function is_video_docomo_ext( $ext )
{
	if ( in_array( strtolower( $ext ) , $this->_VIDEO_DOCOMO_EXTS ) ) {
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

function is_undefined_kind( $kind )
{
	if ( $kind == _C_WEBPHOTO_ITEM_KIND_UNDEFINED ) {
		return true;
	}
	return false;
}

function is_image_kind( $kind )
{
	if ( $kind == _C_WEBPHOTO_ITEM_KIND_IMAGE ) {
		return true;
	}
	return false;
}

function is_video_kind( $kind )
{
	if ( $kind == _C_WEBPHOTO_ITEM_KIND_VIDEO ) {
		return true;
	}
	return false;
}

function is_audio_kind( $kind )
{
	if ( $kind == _C_WEBPHOTO_ITEM_KIND_AUDIO ) {
		return true;
	}
	return false;
}

function is_external_image_kind( $kind )
{
	if ( $kind == _C_WEBPHOTO_ITEM_KIND_EXTERNAL_IMAGE ) {
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

function is_playlist_feed_kind( $kind )
{
	if ( $kind == _C_WEBPHOTO_ITEM_KIND_PLAYLIST_FEED ) {
		return true;
	}
	return false;
}

function is_playlist_dir_kind( $kind )
{
	if ( $kind == _C_WEBPHOTO_ITEM_KIND_PLAYLIST_DIR ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// type
//---------------------------------------------------------
function is_external_type_general( $type )
{
	if ( $type == _C_WEBPHOTO_EXTERNAL_TYPE_GENERAL ) {
		return true;
	}
	return false;
}

// --- class end ---
}

?>