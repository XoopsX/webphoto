<?php
// $Id: xoops_header.php,v 1.5 2011/12/28 16:16:15 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-25 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// change _build_timeline_extend_js()
// 2010-11-03 K.OHWADA
// change _build_header_rss()
// 2010-01-10 K.OHWADA
// webphoto_inc_xoops_header -> webphoto_base_this
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_xoops_header
//=========================================================
class webphoto_xoops_header extends webphoto_base_this
{
	var $_header_class;

	var $_cfg_gmap_apikey;
	var $_cfg_use_popbox;
	var $_cfg_use_lightbox;
	var $_cfg_timeline_dirname;

	var $_flag_css      = false;
	var $_flag_box      = false;
	var $_flag_rss      = false;
	var $_flag_gmap     = false;
	var $_flag_popbox   = false;
	var $_flag_lightbox = false;
	var $_flag_timeline = false;

	var $_rss_mode    = null;
	var $_rss_param   = null;
	var $_rss_limit   = 100;

	var $_LIGHTBOX_URL;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_xoops_header( $dirname, $trust_dirname )
{
	$this->webphoto_base_this( $dirname, $trust_dirname );

	$this->_header_class =& webphoto_inc_xoops_header::getSingleton( $dirname );

	$this->_cfg_gmap_apikey      = $this->get_config_by_name('gmap_apikey');
	$this->_cfg_use_popbox       = $this->get_config_by_name('use_popbox');
	$this->_cfg_use_lightbox     = $this->get_config_by_name('use_lightbox');
	$this->_cfg_timeline_dirname = $this->get_config_by_name('timeline_dirname');
	
	$this->_LIGHTBOX_URL = $this->_MODULE_URL .'/images/lightbox';
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_xoops_header( $dirname, $trust_dirname );
	}
	return $instance;
}

//--------------------------------------------------------
// public
//--------------------------------------------------------
function assign_for_main()
{
	$this->assign_xoops_module_header( 
		$this->_build_xoops_header() );
}

function set_flag_css( $flag )
{
	$this->_flag_css = (bool)$flag;
}

function set_flag_box( $flag )
{
	$this->_flag_box = (bool)$flag;
}

function set_flag_popbox( $flag )
{
	$this->_flag_popbox = (bool)$flag;
}

function set_flag_lightbox( $flag )
{
	$this->_flag_lightbox = (bool)$flag;
}

function set_flag_gmap( $flag )
{
	$this->_flag_gmap = (bool)$flag;
}

function set_flag_timeline( $flag )
{
	$this->_flag_timeline = (bool)$flag;
}

function set_rss( $flag, $rss_mode, $rss_param, $rss_limit )
{
	$this->_flag_rss  = (bool)$flag;
	$this->_rss_mode  = $rss_mode;
	$this->_rss_param = $rss_param;
	$this->_rss_limit = $rss_limit;
}

//--------------------------------------------------------
// private
//--------------------------------------------------------
function _build_xoops_header()
{
	$str = '';
	if ( $this->_flag_rss ) {
		$str .= $this->_build_header_once_rss( 
			$this->_rss_mode, $this->_rss_param, $this->_rss_limit );
	}
	if ( $this->_flag_css ) {
		$str .= $this->_build_header_once( 'css' );
	}
	if ( $this->_flag_gmap && $this->_cfg_gmap_apikey ) {
		$str .= $this->build_once_gmap_api();
		$str .= $this->build_once_gmap_js();
	}
	if ( $this->_flag_popbox && $this->_cfg_use_popbox ) {
		$str .= $this->build_once_popbox_js(); 
	}
	if ( $this->_flag_box ) {
		$str .= $this->_build_header_once( 'prototype_js' );
		$str .= $this->_build_header_once( 'cookiemanager_js' );
		$str .= $this->_build_header_once( 'box_js' );
	}
	if ( $this->_flag_lightbox && $this->_cfg_use_lightbox ) {
		$str .= $this->_build_header_once( 'prototype_js' );
		$str .= $this->_build_header_once( 'scriptaculous_js' );
		$str .= $this->_build_once_lightbox();
	}
	if ( $this->_flag_timeline && $this->_cfg_timeline_dirname ) {
		$str .= $this->_build_once_timeline();
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

function _build_header_once_rss( $mode, $param_encode, $limit )
{
	$const_name = $this->build_const_name( 'rss' ) ;
	if ( $this->check_once( $const_name ) ) {
		return $this->_build_header_rss( $mode, $param_encode, $limit );
	}
	return null;
}

function _build_header_css()
{
	return $this->build_link_css_libs( 'default.css' ) ;
}

function _build_header_lightbox_css()
{
	return $this->build_link_css_libs( 'lightbox/lightbox.css' ) ;
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

function _build_header_scriptaculous_js()
{
	return $this->build_script_js_libs( 
		'scriptaculous/scriptaculous.js?load=effects,builder' );
}

function _build_header_rss( $mode, $param_encode, $limit )
{
	$url = $this->_MODULE_URL.'/index.php/rss/'.$mode;
	if ( $param_encode ) {
		$url .= '/'. $param_encode ;
	}
	$url .= '/limit='. $limit .'/';

	return $this->build_link_rss( $url ) ;
}

//--------------------------------------------------------
// timeline
//--------------------------------------------------------
function _build_once_timeline()
{
	if ( $this->_check_timeline() ) {
		return $this->_build_timeline() ;
	}
	return null;
}

function _check_timeline()
{
	return $this->check_once( $this->build_const_name( 'timeline' ) );
}

function _build_timeline()
{
	$str = $this->build_envelop_js(  $this->_build_timeline_extend_js() );
	return $str;
}

function _build_timeline_extend_js()
{
	$str = <<< EOF
function webphoto_timeline_zoom( link, op, sm, sp, unit )
{
	var date  = timeline_get_center_date();
	link.href = '{$this->_MODULE_URL}/index.php?op=' + op + '&sm=' + sm + '&sp=' + sp + '&unit=' + unit + '&date=' + encodeURIComponent(date) ;
	return true;
}
EOF;
	return $str;
}

//--------------------------------------------------------
// lightbox
//--------------------------------------------------------
function _build_once_lightbox()
{
	if ( $this->_check_lightbox() ) {
		return $this->_build_lightbox() ;
	}
	return null;
}

function _check_lightbox()
{
	return $this->check_once( $this->build_const_name( 'lightbox' ) );
}

function _build_lightbox()
{
	$str  = $this->build_link_css_libs(  'lightbox/lightbox.css' );
	$str .= $this->build_script_js_libs( 'lightbox/lightbox.js' );
	$str .= $this->build_envelop_css( $this->_build_lightbox_extend_css() );
	$str .= $this->build_envelop_js(  $this->_build_lightbox_extend_js() );
	return $str;
}

function _build_lightbox_extend_css()
{
	$str = <<< EOF
#prevLink:hover, #prevLink:visited:hover {
  background: url({$this->_LIGHTBOX_URL}/prevlabel.gif) left 15% no-repeat; }
#nextLink:hover, #nextLink:visited:hover {
  background: url({$this->_LIGHTBOX_URL}/nextlabel.gif) right 15% no-repeat; }
EOF;
	return $str;
}

function _build_lightbox_extend_js()
{
	$str = <<< EOF
LightboxOptions.fileLoadingImage = "{$this->_LIGHTBOX_URL}/loading.gif";
LightboxOptions.fileBottomNavCloseImage = "{$this->_LIGHTBOX_URL}/closelabel.gif";
EOF;
	return $str;
}

//--------------------------------------------------------
// webphoto_inc_xoops_header
//--------------------------------------------------------
function assign_xoops_module_header( $val )
{
	return $this->_header_class->assign_xoops_module_header( $val );
}

function build_once_gmap_api()
{
	return $this->_header_class->build_once_gmap_api( 
		$this->_cfg_gmap_apikey );
}

function build_once_gmap_js()
{
	return $this->_header_class->build_once_gmap_js();
}

function build_once_popbox_js()
{
	return $this->_header_class->build_once_popbox_js( 
		$this->get_constant('POPBOX_REVERT') );
}

function build_link_css_libs( $file )
{
	return $this->_header_class->build_link_css_libs( $file ) ;
}

function build_script_js_libs( $file )
{
	return $this->_header_class->build_script_js_libs( $file );
}

function build_link_rss( $url )
{
	return $this->_header_class->build_link_rss( $url );
}

function build_envelop_css( $val )
{
	return $this->_header_class->build_envelop_css( $val );
}

function build_envelop_js( $val )
{
	return $this->_header_class->build_envelop_js( $val );
}

function build_const_name( $name )
{
	return $this->_header_class->build_const_name( $name ) ;
}

function check_once( $const_name )
{
	return $this->_header_class->check_once( $const_name );
}

// --- class end ---
}

?>