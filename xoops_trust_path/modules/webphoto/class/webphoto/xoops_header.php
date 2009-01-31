<?php
// $Id: xoops_header.php,v 1.1 2009/01/31 19:14:12 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-25 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_xoops_header
//=========================================================
class webphoto_xoops_header extends webphoto_inc_xoops_header
{
	var $_LIMIT = 100;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_xoops_header( $dirname )
{
	$this->webphoto_inc_xoops_header( $dirname );
}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_xoops_header( $dirname );
	}
	return $instance;
}

//--------------------------------------------------------
// public
//--------------------------------------------------------
function assign_for_main( $param )
{
	$this->assign_xoops_module_header( 
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

	$str = '';
	if ( $flag_rss ) {
		$str .= $this->_build_header_once_rss( $rss_mode, $rss_param, $rss_limit );
	}
	if ( $flag_css ) {
		$str .= $this->_build_header_once( 'css' );
	}
	if ( $flag_gmap && $gmap_apikey ) {
		$str .= $this->build_once_gmap_api( $gmap_apikey );
		$str .= $this->build_once_gmap_js();
	}
	if ( $flag_popbox ) {
		$str .= $this->build_once_popbox_js( $lang_popbox_revert );
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
	$const_name = $this->build_const_name( $name ) ;
	$func_name  = strtolower( '_build_header_'.$name );
	if ( $this->check_once( $const_name ) ) {
		return $this->$func_name();
	}
	return null;
}

function _build_header_once_rss( $mode, $param, $limit )
{
	$const_name = $this->build_const_name( 'rss' ) ;
	if ( $this->check_once( $const_name ) ) {
		return $this->_build_header_rss( $mode, $param, $limit );
	}
	return null;
}

function _build_header_css()
{
	return $this->build_link_css_libs( 'default.css' ) ;
}

function _build_header_prototype_js()
{
	return $this->build_script_js_libs( 'prototype.js' );
}

function _build_header_cookiemanager_js()
{
	return $this->build_script_js_libs( 'cookiemanager.js' );
}

function _build_header_box_js()
{
	return $this->build_script_js_libs( 'box.js' );
}

function _build_header_rss( $mode, $param, $limit )
{
	$url = $this->_MODULE_URL.'/index.php/rss/'.$mode;
	if ( $param ) {
		$url .= '/'. urlencode($param);
	}
	$url .= '/limit='. $limit .'/';

	return $this->build_link_rss( $url ) ;
}

// --- class end ---
}

?>