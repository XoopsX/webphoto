<?php
// $Id: imagemagick.php,v 1.3 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-01-10 K.OHWADA
// version()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_lib_imagemagick
//=========================================================

class webphoto_lib_imagemagick
{
	var $_cmd_path = null;
	var $_DEBUG    = false ;

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

function set_debug( $val )
{
	$this->_DEBUG = (bool)$val ;
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
		$this->convert( $src, $dst, $option );
		return true;
	}

	return false;
}

function add_watermark( $src, $dst, $mark )
{
	$option = '-compose plus ';
	$this->composite( $src, $dst, $mark, $option );
}

function add_icon( $src, $dst, $icon )
{
	$option = ' -gravity southeast ';
	$this->composite( $src, $dst, $icon, $option );
}

function convert( $src, $dst, $option='' )
{
	$cmd = $this->_cmd_path .'convert '. $option .' '. $src .' '.$dst ;
	exec( $cmd ) ;
	if ( $this->_DEBUG ) {
		echo $cmd."<br />\n";
	}
}

function composite( $src, $dst, $change, $option='' )
{
	$cmd = $this->_cmd_path .'composite '. $option .' '. $change .' '. $src .' '. $dst ;
	exec( $cmd ) ;
	if ( $this->_DEBUG ) {
		echo $cmd."<br />\n";
	}
}

//---------------------------------------------------------
// version
//---------------------------------------------------------
function version( $path )
{
	$cmd = "{$path}convert --help";
	exec( $cmd , $ret_array ) ;
	if( count( $ret_array ) > 0 ) {
		$ret = true ;
		$str = $ret_array[0]. "<br />\n";

	} else {
		$ret = false ;
		$str = "Error: {$path}convert can't be executed" ;
	}
	return array( $ret, $str );
}

// --- class end ---
}

?>