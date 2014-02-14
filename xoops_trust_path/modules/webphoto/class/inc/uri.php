<?php
// $Id: uri.php,v 1.5 2011/06/05 07:23:40 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-29 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-06-04 K.OHWADA
// build_photo()
// 2010-11-03 K.OHWADA
// _C_WEBPHOTO_URI_PARAM_NAME
// 2009-03-15 K.OHWADA
// flag_amp_sanitize in build_full_uri_mode_param()
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_uri
// caller webphoto_uri webphoto_inc_tagcloud
//=========================================================
class webphoto_inc_uri
{
	var $_cfg_use_pathinfo = false ;

	var $_DIRNAME ;
	var $_MODULE_URL ;
	var $_MODULE_DIR ;

	var $_SEPARATOR  = '&amp;';
	var $_MARK_SLASH = '/' ;
	var $_MARK_COLON = ':' ;
	var $_HTML_AMP   = '&amp;' ;
	var $_HTML_SLASH = '&#047;' ;
	var $_HTML_COLON = '&#058;' ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_uri( $dirname )
{
	$this->_DIRNAME    = $dirname;
	$this->_MODULE_URL = XOOPS_URL       .'/modules/'.$dirname;
	$this->_MODULE_DIR = XOOPS_ROOT_PATH .'/modules/'.$dirname;

	$this->_init_xoops_config( $dirname );

	if ( $this->_cfg_use_pathinfo ) {
		$this->_SEPARATOR = $this->_MARK_SLASH ;
	} else {
		$this->_SEPARATOR = $this->_HTML_AMP ;
	}
}

function &getSingleton( $dirname )
{
	static $singletons;
	if ( !isset( $singletons[ $dirname ] ) ) {
		$singletons[ $dirname ] = new webphoto_inc_uri( $dirname );
	}
	return $singletons[ $dirname ];
}

//---------------------------------------------------------
// public
//---------------------------------------------------------
function get_separator()
{
	return $this->_SEPARATOR ;
}

function build_photo( $id, $flag_amp_sanitize=true )
{
	return $this->build_full_uri_mode_param( 'photo', $id, $flag_amp_sanitize );
}

function build_category( $id, $flag_amp_sanitize=true )
{
	return $this->build_full_uri_mode_param( 'category', $id, $flag_amp_sanitize );
}

function build_tag( $tag )
{
	return $this->build_full_uri_mode_param( 
		'tag', $this->rawurlencode_encode_str( $tag ) );
}

function build_search_photo_keywords( $id, $keywords )
{
	$param = array( 
		'keywords' => $keywords
	);

	$str  = $this->build_relatevie_uri_mode( 'photo' );
	$str .= $this->build_part_uri_param( $id );
	$str .= $this->build_uri_extention( $param, true, false );
	return $str;
}

function build_sitemap_category()
{
	$str  = $this->build_relatevie_uri_mode( 'category' );
	$str .= $this->build_part_uri_param_name();
	return $str;
}

//---------------------------------------------------------
// private
//---------------------------------------------------------
function build_full_uri_mode_param( $mode, $param, $flag_amp_sanitize=true )
{
	$str  = $this->_MODULE_URL .'/' ;
	$str .= $this->build_relatevie_uri_mode_param( $mode, $param, $flag_amp_sanitize );
	return $str;
}

function build_relatevie_uri_mode_param( $mode, $param, $flag_amp_sanitize=true )
{
	$str  = $this->build_relatevie_uri_mode( $mode ) ;
	$str .= $this->build_part_uri_param( $param, $flag_amp_sanitize );
	return $str;
}

function build_full_uri_mode( $mode )
{
	$str  = $this->_MODULE_URL .'/' ;
	$str .= $this->build_relatevie_uri_mode( $mode );
	return $str;
}

function build_part_uri_param( $param, $flag_amp_sanitize=true )
{
	$str = $this->build_part_uri_param_name( $flag_amp_sanitize );

	if ( $this->_cfg_use_pathinfo ) {
		$str .= $param .'/' ; 
	} else {
		$str .= $param ;
	}
	return $str;
}

function build_part_uri_param_name( $flag_amp_sanitize=true )
{
	$amp = '&' ;
	if ( $flag_amp_sanitize ) {
		$amp = '&amp;' ;
	}

	if ( $this->_cfg_use_pathinfo ) {
		$str = '/' ; 
	} else {
		$str = $amp . _C_WEBPHOTO_URI_PARAM_NAME .'=' ;
	}
	return $str;
}

function build_relatevie_uri_mode( $mode )
{
	$str  = 'index.php';
	$str .= $this->build_part_uri_mode( $mode );
	return $str;
}

function build_part_uri_mode( $mode )
{
	if ( $this->_cfg_use_pathinfo ) {
		$str = '/'. $this->sanitize($mode) ; 
	} else {
		$str = '?fct='. $this->sanitize($mode) ;
	}
	return $str;
}

function build_uri_extention( $param, $flag_amp_sanitize=true, $flag_param_sanitize=true )
{
	if ( !is_array($param) || !count($param)) {
		return null;
	}

	$amp = '&' ;
	if ( $flag_amp_sanitize ) {
		$amp = '&amp;' ;
	}

	$arr = array();
	if ( $flag_param_sanitize ) {
		foreach ( $param as $k => $v ) {
			$arr[] = $this->sanitize($k) .'='. $this->sanitize($v) ;
		}
	} else {
		foreach ( $param as $k => $v ) {
			$arr[] = $k .'='. $v ;
		}
	}

	if ( $this->_cfg_use_pathinfo ) {
		$str = implode( $arr, '/' ) .'/' ;
	} else {
		$str = $amp. implode( $arr, $amp ) ;
	}

	return $str;
}

//---------------------------------------------------------
// encode
//---------------------------------------------------------
function rawurlencode_encode_str( $str )
{
	return rawurlencode( $this->encode_str( $str ) );
}

function encode_str( $str )
{
	$str = $this->encode_slash( $str );
	return $this->encode_colon( $str );
}

function decode_str( $str )
{
	$str = $this->decode_slash( $str );
	return $this->decode_colon( $str );
}

function encode_slash( $str )
{
	return str_replace( $this->_MARK_SLASH, $this->_HTML_SLASH, $str );
}

function encode_colon( $str )
{
	return str_replace( $this->_MARK_COLON, $this->_HTML_COLON, $str );
}

function decode_slash( $str )
{
	return str_replace( $this->_HTML_SLASH, $this->_MARK_SLASH, $str );
}

function decode_colon( $str )
{
	return str_replace( $this->_HTML_COLON, $this->_MARK_COLON, $str );
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
function sanitize( $str )
{
	return htmlspecialchars( $str, ENT_QUOTES );
}

//---------------------------------------------------------
// xoops_config
//---------------------------------------------------------
function _init_xoops_config( $dirname )
{
	$config_handler =& webphoto_inc_config::getSingleton( $dirname );

	$this->_cfg_use_pathinfo = $config_handler->get_by_name( 'use_pathinfo' );
}

// --- class end ---
}

?>