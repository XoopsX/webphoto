<?php
// $Id: editor_base.php,v 1.2 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-04 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// typo _allow_in_not_has_htmll
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_editor_base
//=========================================================
class webphoto_editor_base
{
	var $_allow_in_not_has_html = false;
	var $_show_display_options  = false;
	var $_display_smiley = 0 ;
	var $_display_xcode  = 0 ;
	var $_display_image  = 0 ;
	var $_display_br     = 0 ;

// html is dummy, set in webphoto_photo_action
	var $_display_html   = 0 ;

function webphoto_editor_base()
{
	// dummy
}

function set_allow_in_not_has_html( $val )
{
	$this->_allow_in_not_has_html = (bool)$val ;
}
function set_show_display_options( $val )
{
	$this->_show_display_options = (bool)$val ;
}
function set_display_html( $val )
{
	$this->_display_html = intval($val) ;
}
function set_display_smiley( $val )
{
	$this->_display_smiley = intval($val) ;
}
function set_display_xcode( $val )
{
	$this->_display_xcode = intval($val) ;
}
function set_display_image( $val )
{
	$this->_display_image = intval($val) ;
}
function set_display_br( $val )
{
	$this->_display_br = intval($val) ;
}

function allow_in_not_has_html()
{
// typo
	return $this->_allow_in_not_has_html ;
}

function show_display_options()
{
	return $this->_show_display_options; 
}

function display_options()
{
	$arr = array( 
		'html'   => $this->_display_html, 
		'smiley' => $this->_display_smiley, 
		'xcode'  => $this->_display_xcode, 
		'image'  => $this->_display_image, 
		'br'     => $this->_display_br 
	);
	return $arr;
}

function exists()
{
	return false;
}

function build_js()
{
	return null;
}

function build_textarea( $id, $name, $value, $rows, $cols )
{
	return null;
}

// --- class end ---
}
?>