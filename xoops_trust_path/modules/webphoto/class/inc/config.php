<?php
// $Id: config.php,v 1.1 2008/06/21 12:22:26 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

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
	$xoops_class =& webphoto_xoops_base::getInstance();
	return $xoops_class->get_module_config_by_dirname( $dirname ); 
}

// --- class end ---
}

?>