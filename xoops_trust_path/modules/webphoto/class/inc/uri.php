<?php
// $Id: uri.php,v 1.2 2008/12/20 06:11:27 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-29 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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

	var $_PARAM_NAME  = 'p';
	var $_PATH_FIRST  = 0 ;
	var $_PATH_SECOND = 1 ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_uri( $dirname )
{
	$this->_DIRNAME    = $dirname;
	$this->_MODULE_URL = XOOPS_URL       .'/modules/'.$dirname;
	$this->_MODULE_DIR = XOOPS_ROOT_PATH .'/modules/'.$dirname;
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
// init
//---------------------------------------------------------
function set_use_pathinfo( $val )
{
	$this->_cfg_use_pathinfo = (bool)$val;

	if ( $this->_cfg_use_pathinfo ) {
		$this->_SEPARATOR = $this->_MARK_SLASH ;
	} else {
		$this->_SEPARATOR = $this->_HTML_AMP ;
	}
}

function get_separator()
{
	return $this->_SEPARATOR ;
}

//---------------------------------------------------------
// build_tag
//---------------------------------------------------------
function build_tag( $tag )
{
	return $this->build_full_uri_mode_param( 
		'tag', $this->rawurlencode_encode_str( $tag ) );
}

function build_full_uri_mode_param( $mode, $param )
{
	$str = $this->build_full_uri_mode( $mode ) ;
	if ( $this->_cfg_use_pathinfo ) {
		$str .= '/'. $param .'/' ; 
	} else {
		$str .= '&amp;'. $this->_PARAM_NAME .'='. $param ;
	}
	return $str;
}

function build_full_uri_mode( $mode )
{
	$str = $this->_MODULE_URL .'/index.php' ;
	if ( $this->_cfg_use_pathinfo ) {
		$str .= '/'. $this->sanitize($mode) ; 
	} else {
		$str .= '?fct='. $this->sanitize($mode) ;
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

// --- class end ---
}

?>