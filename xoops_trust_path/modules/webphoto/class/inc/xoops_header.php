<?php
// $Id: xoops_header.php,v 1.1 2008/06/21 12:22:25 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_xoops_header
//=========================================================
//---------------------------------------------------------
// caller inc_blocks show_main
//---------------------------------------------------------

class webphoto_inc_xoops_header
{
	var $_DIRNAME;
	var $_MODULE_URL;
	var $_LIBS_URL;
	var $_POPBOX_URL;

	var $_LANG_POPBOX_REVERT = 'Click the image to shrink it.';
	var $_LIMIT = 100;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_xoops_header()
{
	// dummy
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_inc_xoops_header();
	}
	return $instance;
}


function _init( $dirname )
{
	$this->_DIRNAME = $dirname;
	$this->_MODULE_URL = XOOPS_URL.'/modules/'.$dirname;
	$this->_LIBS_URL   = $this->_MODULE_URL .'/libs';
	$this->_POPBOX_URL = $this->_MODULE_URL .'/images/popbox';
}

//--------------------------------------------------------
// public
//--------------------------------------------------------
function assign_for_main( $param )
{
	$dirname = isset($param['dirname']) ? $param['dirname'] : null;

	$this->_init( $dirname );

	$this->_assign_xoops_module_header( 
		$this->_build_xoops_header( $param ) );
}

function assign_for_block( $dirname, $flag_popbox, $lang_popbox_revert )
{
	$this->_init( $dirname );

	$param = array(
		'flag_popbox'        => $flag_popbox ,
		'lang_popbox_revert' => $lang_popbox_revert ,
	);
	$this->_assign_xoops_module_header( 
		$this->_build_xoops_header( $param ) );
}

//--------------------------------------------------------
// private
//--------------------------------------------------------
function _build_xoops_header( $param )
{
	$flag_css    = isset($param['flag_css'])    ? (bool)$param['flag_css']    : false;
	$flag_gmap   = isset($param['flag_gmap'])   ? (bool)$param['flag_gmap']   : false;
	$flag_popbox = isset($param['flag_popbox']) ? (bool)$param['flag_popbox'] : false;
	$flag_box    = isset($param['flag_box'])    ? (bool)$param['flag_box']    : false;
	$flag_rss    = isset($param['flag_rss'])    ? (bool)$param['flag_rss']    : false;
	$gmap_apikey = isset($param['gmap_apikey']) ? $param['gmap_apikey']       : null;
	$rss_mode    = isset($param['rss_mode'])    ? $param['rss_mode']          : null;
	$rss_param   = isset($param['rss_param'])   ? $param['rss_param']         : null;
	$rss_limit   = isset($param['rss_limit'])   ? intval($param['rss_limit']) : $this->_LIMIT;
	$lang_popbox_revert = isset($param['lang_popbox_revert']) ? $param['lang_popbox_revert'] : null;

	if ( $lang_popbox_revert ) {
		$this->_LANG_POPBOX_REVERT = $lang_popbox_revert;
	}

	$str = '';
	if ( $flag_rss ) {
		$str .= $this->_build_header_once_rss( $rss_mode, $rss_param, $rss_limit );
	}
	if ( $flag_css ) {
		$str .= $this->_build_header_once( 'css' );
	}
	if ( $flag_gmap && $gmap_apikey ) {
		$str .= $this->_build_header_once_google_maps( $gmap_apikey );
		$str .= $this->_build_header_once( 'gmap_js' );
	}
	if ( $flag_popbox ) {
		$str .= $this->_build_header_once( 'popbox_js' );
	}
	if ( $flag_box ) {
		$str .= $this->_build_header_once( 'prototype_js' );
		$str .= $this->_build_header_once( 'cookiemanager_js' );
		$str .= $this->_build_header_once( 'box_js' );
	}
	return $str;
}

function _build_header_once( $name )
{
	$const_name = strtoupper( '_C_WEBPHOTO_HEADER_LOADED_'.$name );
	$func_name  = strtolower( '_build_header_'.$name );
	if ( !defined( $const_name ) ) {
		define( $const_name, 1 );
		return $this->$func_name();
	}
	return null;
}

function _build_header_once_google_maps( $gmap_apikey )
{
	$const_name = "_C_WEBPHOTO_HEADER_LOADED_GMAP_APIKEY";
	if ( !defined( $const_name ) ) {
		define( $const_name, 1 );
		return $this->_build_header_google_maps( $gmap_apikey );
	}
	return null;
}

function _build_header_once_rss( $mode, $param, $limit )
{
	$const_name = "_C_WEBPHOTO_HEADER_LOADED_RSS";
	if ( !defined( $const_name ) ) {
		define( $const_name, 1 );
		return $this->_build_header_rss( $mode, $param, $limit );
	}
	return null;
}

function _build_header_css()
{
	$str = '<link href="'. $this->_LIBS_URL .'/default.css" type="text/css" rel="stylesheet"/>'."\n";
	return $str;
}

function _build_header_google_maps( $gmap_apikey )
{
	$str = '<script src="http://maps.google.com/maps?file=api&amp;hl='. _LANGCODE .'&amp;v=2&amp;key='. $gmap_apikey .'" type="text/javascript" charset="utf-8"></script>'."\n";
	return $str;
}

function _build_header_gmap_js()
{
	$str = '<script src="'. $this->_LIBS_URL .'/gmap.js" type="text/javascript"></script>'."\n";
	return $str;
}

function _build_header_popbox_js()
{
	$str  = '<link id="lnkStyleSheet" rel="stylesheet" type="text/css" href="'. $this->_LIBS_URL .'/popbox.css" />'."\n";
	$str .= '<script src="'. $this->_LIBS_URL .'/PopBox.js" type="text/javascript"></script>'."\n";
	$str .= '<script type="text/javascript">'."\n";
	$str .= '  popBoxRevertText    = "'. $this->_LANG_POPBOX_REVERT .'" '."\n";
	$str .= '  popBoxWaitImage.src = "'. $this->_POPBOX_URL .'/spinner40.gif" '."\n";
	$str .= '  popBoxRevertImage   = "'. $this->_POPBOX_URL .'/magminus.gif" '."\n";
	$str .= '  popBoxPopImage      = "'. $this->_POPBOX_URL .'/magplus.gif" '."\n";
	$str .= '</script>'."\n";
	return $str;
}

function _build_header_prototype_js()
{
	$str = '<script src="'. $this->_LIBS_URL .'/prototype.js" type="text/javascript"></script>'."\n";
	return $str;
}

function _build_header_cookiemanager_js()
{
	$str = '<script src="'. $this->_LIBS_URL .'/cookiemanager.js" type="text/javascript"></script>'."\n";
	return $str;
}

function _build_header_box_js()
{
	$str = '<script src="'. $this->_LIBS_URL .'/box.js" type="text/javascript"></script>'."\n";
	return $str;
}

function _build_header_rss( $mode, $param, $limit )
{
	$url = $this->_MODULE_URL.'/index.php/rss/'.$mode;
	if ( $param ) {
		$url .= '/'. urlencode($param);
	}
	$url .= '/limit='. $limit .'/';
	$str = '<link rel="alternate" type="application/rss+xml" title="RSS" href="'. $url .'" />'."\n";
	return $str;
}

// some block use xoops_module_header
function _assign_xoops_module_header( $var )
{
	global $xoopsTpl;

	if ( $var ) {
		$xoopsTpl->assign(
			'xoops_module_header', 
			$var."\n".$xoopsTpl->get_template_vars('xoops_module_header')
		);
	}
}

// --- class end ---
}

?>