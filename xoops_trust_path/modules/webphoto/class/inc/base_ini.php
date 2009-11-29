<?php
// $Id: base_ini.php,v 1.1 2009/11/29 07:37:03 ohwada Exp $

//=========================================================
// webphoto module
// 2009-11-11 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_base_ini
//=========================================================
class webphoto_inc_base_ini extends webphoto_inc_handler
{
	var $_ini_class;

	var $_DIRNAME;
	var $_TRUST_DIRNAME;
	var $_MODULE_DIR;
	var $_TRUST_DIR;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_base_ini()
{
	$this->webphoto_inc_handler();
}

function init_base_ini( $dirname , $trust_dirname )
{
	$this->_DIRNAME       = $dirname;
	$this->_MODULE_DIR    = XOOPS_ROOT_PATH  .'/modules/'. $dirname;
	$this->_TRUST_DIRNAME = $trust_dirname;
	$this->_TRUST_DIR     = XOOPS_TRUST_PATH .'/modules/'. $trust_dirname;

	$this->_ini_class 
		=& webphoto_inc_ini::getSingleton( $dirname, $trust_dirname );
	$this->_ini_class->read_main_ini();

	$this->set_debug_sql_by_ini_name(   _C_WEBPHOTO_NAME_DEBUG_SQL );
	$this->set_debug_error_by_ini_name( _C_WEBPHOTO_NAME_DEBUG_ERROR );
}

//---------------------------------------------------------
// ini class
//---------------------------------------------------------
function get_ini( $name )
{
	return $this->_ini_class->get_ini( $name );
}

function explode_ini( $name )
{
	return $this->_ini_class->explode_ini( $name );
}

//---------------------------------------------------------
// debug
//---------------------------------------------------------
function set_debug_sql_by_ini_name( $name )
{
	$val = $this->get_ini( $name );
	if ( $val ) {
		$this->set_debug_sql( $val );
	}
}

function set_debug_error_by_ini_name( $name )
{
	$val = $this->get_ini( $name );
	if ( $val ) {
		$this->set_debug_error( $val );
	}
}

// --- class end ---
}

?>