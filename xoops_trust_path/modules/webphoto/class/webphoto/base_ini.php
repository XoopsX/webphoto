<?php
// $Id: base_ini.php,v 1.2 2010/01/25 10:03:07 ohwada Exp $

//=========================================================
// webphoto module
// 2009-11-11 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-01-10 K.OHWADA
// isset_ini()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_base_ini
//=========================================================
class webphoto_base_ini extends webphoto_lib_base
{
	var $_ini_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_base_ini( $dirname, $trust_dirname )
{
	$this->webphoto_lib_base( $dirname, $trust_dirname );

	$this->_ini_class 
		=& webphoto_inc_ini::getSingleton( $dirname, $trust_dirname );
	$this->_ini_class->read_main_ini();
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

function isset_ini( $name )
{
	return $this->_ini_class->isset_ini( $name );
}

// --- class end ---
}

?>