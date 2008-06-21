<?php
// $Id: config.php,v 1.1 2008/06/21 12:22:24 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_config
//=========================================================
class webphoto_config
{
	var $_utility_class ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_config( $dirname )
{
	$this->_init( $dirname );

	$this->_utility_class =& webphoto_lib_utility::getInstance();
}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_config( $dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// init
//---------------------------------------------------------
function _init( $dirname )
{
	$xoops_class =& webphoto_xoops_base::getInstance();
	$this->_config_array = $xoops_class->get_module_config_by_dirname( $dirname );
}

//---------------------------------------------------------
// get
//---------------------------------------------------------
function get_by_name( $name )
{
	if ( isset($this->_config_array[ $name ]) ) {
		return $this->_config_array[ $name ];
	}
	return null;
}

function get_array_by_name( $name )
{
	$str = $this->get_by_name( $name );
	if ( $str ) {
		$arr = explode( '|' , $str ) ;
	} else {
		$arr = array() ;
	}
	return $arr;
}

function get_dir_by_name( $name )
{
	$str = $this->get_by_name( $name );
	return $this->add_separator_to_tail( $str );
}

function get_photos_path()
{
	return $this->_get_path_by_name( 'photospath' );
}

function get_thumbs_path()
{
	return $this->_get_path_by_name( 'thumbspath' );
}

function get_gicons_path()
{
	return $this->_get_path_by_name( 'giconspath' );
}

function get_tmp_path()
{
	return $this->_get_path_by_name( 'tmppath' );
}

function has_rotate()
{
	$imagingpipe = $this->get_by_name( 'imagingpipe' );
	$forcegd2    = $this->get_by_name( 'forcegd2' );

	if ( ( $imagingpipe == _C_WEBPHOTO_PIPEID_IMAGICK ) ||
	     ( $imagingpipe == _C_WEBPHOTO_PIPEID_NETPBM )  ||
	     ( $forcegd2 && function_exists( 'imagerotate' ) ) ) {
		return true;
	}

	return false;
}

function has_resize()
{
	$imagingpipe = $this->get_by_name( 'imagingpipe' );
	$forcegd2    = $this->get_by_name( 'forcegd2' );

	if ( $imagingpipe || $forcegd2 ) { return true; }
	return false;
}

function get_middle_wh()
{
	$width  = $this->get_by_name('middle_width');
	$height = $this->get_by_name('middle_height');

	if ( $width && empty($height) ) {
		$height = $width;
	} elseif ( empty($width) && $height ) {
		$width = $height;
	}

	return array($width, $height);
}

function get_thumb_wh()
{
	$width  = $this->get_by_name('thumb_width');
	$height = $this->get_by_name('thumb_height');

	if ( $width && empty($height) ) {
		$height = $width;
	} elseif ( empty($width) && $height ) {
		$width = $height;
	}

	return array($width, $height);
}

function _get_path_by_name( $name )
{
	return $this->add_slash_to_head( $this->get_by_name( $name ) );
}

//---------------------------------------------------------
// utlity class
//---------------------------------------------------------
function add_slash_to_head( $str )
{
	return $this->_utility_class->add_slash_to_head( $str );
}

function add_separator_to_tail( $str )
{
	return $this->_utility_class->add_separator_to_tail( $str );
}

// --- class end ---
}

?>