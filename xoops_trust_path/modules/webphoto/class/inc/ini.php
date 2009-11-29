<?php
// $Id: ini.php,v 1.1 2009/11/29 07:37:03 ohwada Exp $

//=========================================================
// webphoto module
// 2009-11-11 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_ini
//=========================================================
class webphoto_inc_ini
{
	var $_DIRNAME;
	var $_TRUST_DIRNAME;
	var $_MODULE_DIR;
	var $_TRUST_DIR;

	var $_array_ini = null;

	var $_DEBUG_READ = false;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_ini( $dirname , $trust_dirname )
{
	$this->_DIRNAME       = $dirname;
	$this->_MODULE_DIR    = XOOPS_ROOT_PATH  .'/modules/'. $dirname;
	$this->_TRUST_DIRNAME = $trust_dirname;
	$this->_TRUST_DIR     = XOOPS_TRUST_PATH .'/modules/'. $trust_dirname;

	$constpref = strtoupper( '_P_' . $dirname. '_' ) ;
	$this->set_debug_read_by_const_name( $constpref.'DEBUG_READ' );
}

function &getSingleton( $dirname , $trust_dirname )
{
	static $singletons;
	if ( !isset( $singletons[ $dirname ] ) ) {
		$singletons[ $dirname ] = new webphoto_inc_ini( $dirname , $trust_dirname );
	}
	return $singletons[ $dirname ];
}

//---------------------------------------------------------
// public
//---------------------------------------------------------
function read_main_ini( $debug=false )
{
	$this->read_ini( 'main.ini', $debug );
}

function read_ini( $file, $debug=false )
{
	$file_trust_include = $this->_TRUST_DIR  . '/include/' . $file ;
	$file_trust_preload = $this->_TRUST_DIR  . '/preload/' . $file ;
	$file_root_include  = $this->_MODULE_DIR . '/include/' . $file ;
	$file_root_preload  = $this->_MODULE_DIR . '/preload/' . $file ;

	$arr = array();

// root: high priority
	if( file_exists( $file_root_include ) ) {
		$this->debug_msg_read_file( $file_root_include, $debug );
		$arr_ini =  parse_ini_file( $file_root_include );
		if ( is_array($arr_ini) ) {
			$arr = array_merge( $arr, $arr_ini );
		}

// trust: low priority
	} elseif( file_exists( $file_trust_include ) ) {
		$this->debug_msg_read_file( $file_trust_include, $debug );
		$arr_ini =  parse_ini_file( $file_trust_include );
		if ( is_array($arr_ini) ) {
			$arr = array_merge( $arr, $arr_ini );
		}

// read if trust
		if( file_exists( $file_trust_preload ) ) {
			$this->debug_msg_read_file( $file_trust_preload, $debug );
			$arr_ini =  parse_ini_file( $file_trust_preload );
			if ( is_array($arr_ini) ) {
				$arr = array_merge( $arr, $arr_ini );
			}
		}
	}

// read preload
	if ( file_exists( $file_root_preload ) ) {
		$this->debug_msg_read_file( $file_root_preload, $debug );
		$arr_ini =  parse_ini_file( $file_root_preload );
		if ( is_array($arr_ini) ) {
			$arr = array_merge( $arr, $arr_ini );
		}
	}

	$this->_array_ini = $arr;
	return true;
}

function get_ini( $name )
{
	if ( isset( $this->_array_ini[ $name ] ) ) {
		return  $this->_array_ini[ $name ];
	}
	return null;
}

function explode_ini( $name, $grue='|', $prefix=null )
{
	$arr = explode( $grue, $this->get_ini( $name ) );

	if ( empty($prefix) ) {
		return $arr;
	}

	$ret = array();
	foreach( $arr as $a ) {
		$ret[] = $prefix . $a;
	}
	return $ret;
}

//---------------------------------------------------------
// debug
//---------------------------------------------------------
function debug_msg_read_file( $file, $debug=true )
{
	$file_win = str_replace( '/', '\\', $file );

	if ( $this->_DEBUG_READ && $debug ) {
		echo 'read '. $file ."<br />\n";
	}
}

function set_debug_read( $val )
{
	$this->_DEBUG_READ = (bool)$val;
}

function set_debug_read_by_const_name( $name )
{
	$name = strtoupper( $name );
	if ( defined($name) ) {
		$this->set_debug_read( constant($name) );
	}
}

//----- class end -----
}

?>