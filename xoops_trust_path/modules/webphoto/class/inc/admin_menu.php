<?php
// $Id: admin_menu.php,v 1.9 2010/03/04 02:17:26 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-03-03 K.OHWADA
// build_menu_array()
// 2009-12-06 K.OHWADA
// webphoto_inc_ini
// 2009-03-01 K.OHWADA
// rss_manager
// 2009-01-10 K.OHWADA
// comment photo_table_manage
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
// 2008-10-01 K.OHWADA
// define_sub_menu()
// player_manager etc
// 2008-08-24 K.OHWADA
// added item_table_manage
// 2008-08-01 K.OHWADA
// added maillog_manager
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_admin_menu
// caller webphoto_lib_admin_menu admin/menu.php
//=========================================================
class webphoto_inc_admin_menu
{
	var $_ini_class ;

	var $_DIRNAME;
	var $_TRUST_DIR;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_admin_menu( $dirname, $trust_dirname )
{
	$this->_DIRNAME = $dirname;
	$this->_TRUST_DIR = XOOPS_TRUST_PATH .'/modules/'. $trust_dirname;

	$this->_ini_class 
		=& webphoto_inc_ini::getSingleton( $dirname, $trust_dirname );
	$this->_ini_class->read_main_ini();

}

function &getSingleton( $dirname, $trust_dirname )
{
	static $singletons;
	if ( !isset( $singletons[ $dirname ] ) ) {
		$singletons[ $dirname ] = new webphoto_inc_admin_menu( $dirname, $trust_dirname );
	}
	return $singletons[ $dirname ];
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function build_menu()
{
	$menu_array = $this->explode_ini('admin_menu_list');
	return $this->build_menu_array( $menu_array );
}

function build_sub_menu( )
{
	$menu_array = $this->explode_ini('admin_sub_menu_list');
	return $this->build_menu_array( $menu_array );
}


//---------------------------------------------------------
// utility
//---------------------------------------------------------
function build_menu_array( $array )
{
	$arr = array();
	foreach( $array as $fct ) 
	{
		$arr[] =  array(
			'title' => $this->build_title( $fct ) ,
			'link'  => $this->build_link(  $fct ) ,
		);
	}
	return $arr;
}

function build_title( $fct )
{
	return $this->get_constant( $fct ) ;
}

function build_link( $fct )
{
	$link  = 'admin/index.php' ;
	if ( $this->file_fct_exists( $fct ) ) {
		$link .= '?fct='. $fct ;
	}
	return $link;
}

function file_fct_exists( $fct )
{
	$file = $this->_TRUST_DIR .'/admin/'.$fct.'.php' ;
	return file_exists( $file );
}

//---------------------------------------------------------
// langauge
//---------------------------------------------------------
function get_constant( $name )
{
	$const_name = $this->get_constant_name( $name );
	if ( defined($const_name) ) {
		return constant( $const_name );
	}
	return $const_name;
}

function get_constant_name( $name )
{
	return strtoupper( '_MI_' . $this->_DIRNAME . '_ADMENU_' . $name );
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

// --- class end ---
}

?>