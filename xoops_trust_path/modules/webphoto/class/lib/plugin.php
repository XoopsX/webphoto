<?php
// $Id: plugin.php,v 1.2 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-04 K.OHWADA
//=========================================================

//---------------------------------------------------------
// 2009-01-10 K.OHWADA
// get_cached_class_object()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_plugin
//=========================================================
class webphoto_lib_plugin
{
	var $_utility_class;

	var $_cached_by_type = array() ;

	var $_DIRNAME;
	var $_TRUST_DIRNAME;
	var $_MODULE_URL;
	var $_MODULE_DIR;
	var $_TRUST_DIR;

	var $_TRUST_PLUGIN_DIR = null ;
	var $_ROOT_PLUGIN_DIR  = null ;
	var $_PLUGIN_PREFIX    = null ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_plugin( $dirname, $trust_dirname )
{
	$this->_DIRNAME    = $dirname ;
	$this->_MODULE_URL = XOOPS_URL       .'/modules/'. $dirname;
	$this->_MODULE_DIR = XOOPS_ROOT_PATH .'/modules/'. $dirname;
	$this->_TRUST_DIRNAME = $trust_dirname;
	$this->_TRUST_DIR     = XOOPS_TRUST_PATH .'/modules/'. $trust_dirname;

	$this->_utility_class  =& webphoto_lib_utility::getInstance();
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_plugin( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// set param
//---------------------------------------------------------
function set_dirname( $sub_dir )
{
	$dir = '/plugins/'. $sub_dir ;
	$this->_TRUST_PLUGIN_DIR = $this->_TRUST_DIR  . $dir ;
	$this->_ROOT_PLUGIN_DIR  = $this->_MODULE_DIR . $dir ;
}

function set_prefix( $val )
{
	$this->_PLUGIN_PREFIX = $val ;
}

//---------------------------------------------------------
// plugin
//---------------------------------------------------------
function build_list()
{
	$files = $this->_utility_class->get_files_in_dir( $this->_TRUST_PLUGIN_DIR, 'php', false, true );
	$arr = array() ;
	foreach ( $files as $file ) {
		$arr[] = str_replace( '.php', '', $file );
	}
	return $arr;
}

function &get_cached_class_object( $type )
{
	if ( isset( $this->_cached_by_type[ $type ] ) ) {
		return  $this->_cached_by_type[ $type ] ;
	}

	$obj =& $this->get_class_object( $type );
	if ( is_object($obj) ) {
		$this->_cached_by_type[ $type ] =& $obj;
	}
	return $obj;
}

function &get_class_object( $type )
{
	$false = false;

	if ( empty($type) ) {
		return $false;
	}

	$this->include_once_file( $type ) ;

	$class_name = $this->get_class_name( $type );
	if ( empty($class_name) ) {
		return $false;
	}

	$class = new $class_name();
	return $class ;
}

function include_once_file( $type )
{
	$file = $this->get_file_name( $type ) ;
	if ( $file ) {
		include_once $file ;
	}
}

function get_file_name( $type )
{
	$type_php   = $type .'.php' ;
	$file_trust = $this->_TRUST_PLUGIN_DIR .'/'. $type_php ;
	$file_root  = $this->_ROOT_PLUGIN_DIR  .'/'. $type_php ;

	if ( file_exists( $file_root ) ) {
		return $file_root ;
	} elseif ( file_exists( $file_trust ) ) {
		return $file_trust ;
	}
	return false;
}

function get_class_name( $type )
{
	$class = $this->_PLUGIN_PREFIX . $type ;
	if ( class_exists( $class ) ) {
		return $class;
	}
	return false;
}

// --- class end ---
}

?>