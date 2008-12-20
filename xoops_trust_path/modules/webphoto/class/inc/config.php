<?php
// $Id: config.php,v 1.4 2008/12/20 06:11:27 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
// 2008-11-29 K.OHWADA
// get_path_by_name()
// 2008-07-01 K.OHWADA
// webphoto_xoops_base -> xoops_gethandler()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_config
//=========================================================
//---------------------------------------------------------
// caller inc_xoops_version inc_blocks
//---------------------------------------------------------

class webphoto_inc_config
{
	var $_cached_config = array();
	var $_DIRNAME ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_config( $dirname )
{
	$this->_DIRNAME = $dirname;
	$this->_get_xoops_config( $dirname );
}

function &getSingleton( $dirname )
{
	static $singletons;
	if ( !isset( $singletons[ $dirname ] ) ) {
		$singletons[ $dirname ] = new webphoto_inc_config( $dirname );
	}
	return $singletons[ $dirname ];
}

//---------------------------------------------------------
// cache 
//---------------------------------------------------------
function get_by_name( $name )
{
	if ( isset($this->_cached_config[ $name ]) ) {
		return $this->_cached_config[ $name ];
	}
	return false;
}

function get_path_by_name( $name )
{
	$path = $this->get_by_name( $name );
	if ( $path ) {
		return $this->_add_slash_to_head( $path );
	}
	return null;
}

function _add_slash_to_head( $str )
{
// ord : the ASCII value of the first character of string
// 0x2f slash

	if( ord( $str ) != 0x2f ) {
		$str = "/". $str;
	}
	return $str;
}

//---------------------------------------------------------
// xoops class
//---------------------------------------------------------
function _get_xoops_config( $dirname )
{
	$module_handler =& xoops_gethandler('module');
	$module = $module_handler->getByDirname( $dirname );
	if ( !is_object($module) ) {
		return false;
	}
	$mid = $module->getVar( 'mid' );

	$config_handler =& xoops_gethandler('config');
	$this->_cached_config = $config_handler->getConfigsByCat( 0, $mid );
}

// --- class end ---
}

?>