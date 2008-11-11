<?php
// $Id: imagemagick.php,v 1.1 2008/11/11 06:54:17 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-01 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_lib_imagemagick
//=========================================================

class webphoto_lib_imagemagick
{
	var $_cmd_path  = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_imagemagick()
{
	// dummy
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_imagemagick();
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function set_cmd_path( $val )
{
	$this->_cmd_path = $val ;
}

function resize_rotate( $src, $dst, $max_width=0, $max_height=0, $rorate=0 )
{
	$option = '' ;

	if (( $max_width > 0 )&&( $max_height > 0 )) {
		$option .= ' -geometry '. $max_width .'x'. $max_height;
	}

	if ( $rorate > 0 ) {
		$option .= ' -rotate '. $rorate ;
	}

	if ( $option ) {
		$cmd = $this->_cmd_path .'convert '. $option .' '. $src .' '.$dst;
		exec( $cmd ) ;
		return true;
	}

	return false;
}

function add_watermark( $src, $dst, $mark )
{
	$cmd = $this->_cmd_path .'composite -compose plus '. $mark .' '. $src .'' . $dst;
	exec( $cmd ) ;
}

// --- class end ---
}

?>