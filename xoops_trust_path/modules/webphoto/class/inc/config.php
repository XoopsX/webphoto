<?php
// $Id: config.php,v 1.2 2008/07/06 04:41:31 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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
function webphoto_inc_config()
{
	// dummy
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_inc_config();
	}
	return $instance;
}

function init( $dirname )
{
	$this->_DIRNAME = $dirname;
	$this->_init_cache( $dirname );
}

//---------------------------------------------------------
// cache 
//---------------------------------------------------------
function get_by_name( $name )
{
	if ( isset($this->_cached_config[ $this->_DIRNAME ][ $name ]) ) {
		return $this->_cached_config[ $this->_DIRNAME ][ $name ];
	}
	return false;
}

function _init_cache( $dirname )
{
	if ( !isset( $this->_cached_config[ $dirname ] ) ) {
		$this->_cached_config[ $dirname ] 
			= $this->_get_xoops_config( $dirname );
	}
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
	return $config_handler->getConfigsByCat( 0, $mid );
}

// --- class end ---
}

?>