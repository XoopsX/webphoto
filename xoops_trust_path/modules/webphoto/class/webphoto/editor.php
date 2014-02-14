<?php
// $Id: editor.php,v 1.2 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-04 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// webphoto_lib_plugin -> webphoto_plugin_ini
// item_editor_fefault
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_editor
//=========================================================
class webphoto_editor extends webphoto_plugin_ini
{
	var $_has_html = false;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_editor( $dirname, $trust_dirname )
{
	$this->webphoto_plugin_ini( $dirname, $trust_dirname );
	$this->set_dirname( 'editors' );
	$this->set_prefix(  'webphoto_editor_' );

	$this->_perm_class 
		=& webphoto_permission::getInstance( $dirname, $trust_dirname );
	$this->_has_html = $this->_perm_class->has_html();
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_editor( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// editor
//---------------------------------------------------------
function display_options( $type )
{
	if ( empty($type ) ) {
		$type = $this->get_ini('item_editor_default') ;
	}

	$class =& $this->get_class_object( $type );
	if ( ! is_object($class) ) {
		return false;
	}
	return $class->display_options();
}

function init_form( $type, $id, $name, $value, $rows, $cols )
{
	$class =& $this->get_class_object( $type );
	if ( ! is_object($class) ) {
		return false;
	}

	$arr = array(
		'js'   => $class->build_js() ,
		'show' => $class->show_display_options() ,
		'desc' => $class->build_textarea( $id, $name, $value, $rows, $cols ) ,
	);
	return $arr;
}

function build_list_options( $flag )
{
	$list = $this->build_list();
	$arr  = array();
	foreach ( $list as $type )
	{
		if ( $this->exists( $type ) ) {
			$arr[ $type ] = $type ;
		}
	}
	if ( $flag &&
	     is_array($arr) &&
		 ( count($arr) == 1 ) &&
		 isset( $arr[ _C_WEBPHOTO_EDITOR_DEFAULT ] ) ) {
		return false;
	}
	return $arr;
}

function exists( $type )
{
	$class =& $this->get_class_object( $type );
	if ( ! is_object($class) ) {
		return false;
	}

	if ( $class->exists() &&
	   ( $this->_has_html || $class->allow_in_not_has_html() )) {
		return true;
	}
	return false ;
}

// --- class end ---
}

?>