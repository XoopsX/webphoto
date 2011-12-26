<?php
// $Id: base_ini.php,v 1.2 2011/12/26 06:51:31 ohwada Exp $

//=========================================================
// webphoto module
// 2009-11-11 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// webphoto_lib_mysql_utility
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_handler_base_ini
//=========================================================
class webphoto_handler_base_ini extends webphoto_lib_tree_handler
{
	var $_utility_class;
	var $_mysql_utility_class ;
	var $_ini_class;

	var $_MODULE_DIR;
	var $_TRUST_DIRNAME;
	var $_TRUST_DIR;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_handler_base_ini( $dirname, $trust_dirname )
{
	$this->webphoto_lib_tree_handler( $dirname );

	$this->_MODULE_DIR    = XOOPS_ROOT_PATH  .'/modules/'. $dirname;
	$this->_TRUST_DIRNAME = $trust_dirname;
	$this->_TRUST_DIR     = XOOPS_TRUST_PATH .'/modules/'. $trust_dirname;

	$this->_utility_class =& webphoto_lib_utility::getInstance();
	$this->_mysql_utility_class =& webphoto_lib_mysql_utility::getInstance();

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

function explode_ini(  $name, $grue='|', $prefix=null )
{
	return $this->_ini_class->explode_ini( $name, $grue, $prefix);
}

//---------------------------------------------------------
// utility class
//---------------------------------------------------------
function perm_str_to_array( $str )
{
	return $this->_utility_class->str_to_array( $str, _C_WEBPHOTO_PERM_SEPARATOR );
}

function perm_array_to_str( $str )
{
	return $this->_utility_class->array_to_str( $str, _C_WEBPHOTO_PERM_SEPARATOR );
}

function info_str_to_array( $str )
{
	return $this->_utility_class->str_to_array( $str, _C_WEBPHOTO_INFO_SEPARATOR );
}

function info_array_to_str( $str )
{
	return $this->_utility_class->array_to_str( $str, _C_WEBPHOTO_INFO_SEPARATOR );
}

function perm_str_with_separetor( $str )
{
// &123&
	$ret = _C_WEBPHOTO_PERM_SEPARATOR . $str . _C_WEBPHOTO_PERM_SEPARATOR ;
	return $ret ;
}

function perm_str_with_like_separetor( $str )
{
// %&123&%
	$like  = '%'. $this->perm_str_with_separetor( $str ). '%' ;
	return $like;
}

//---------------------------------------------------------
// mysql
//---------------------------------------------------------
function str_to_mysql_datetime( $str )
{
	return $this->_mysql_utility_class->str_to_mysql_datetime( $str );
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