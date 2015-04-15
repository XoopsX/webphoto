<?php
// $Id: module_icon.php,v 1.1 2008/06/21 12:22:22 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_d3_module_icon
//=========================================================
class webphoto_d3_module_icon
{
	var $_DIRNAME;
	var $_TRUST_DIRNAME;
	var $_MODULE_DIR;
	var $_TRUST_DIR;

	var $_CACHE_LIMIT = 3600 ; // default 3600 sec ( 1 hour )

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_d3_module_icon()
{
	// dummy
}

public static function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_d3_module_icon();
	}
	return $instance;
}

function init( $dirname , $trust_dirname )
{
	$this->_DIRNAME       = $dirname;
	$this->_TRUST_DIRNAME = $trust_dirname;

	$this->_MODULE_DIR = XOOPS_ROOT_PATH  .'/modules/'. $dirname;
	$this->_TRUST_DIR  = XOOPS_TRUST_PATH .'/modules/'. $trust_dirname;

}

//---------------------------------------------------------
// public
//---------------------------------------------------------
function output_image()
{
	$icon_trust_path = $this->_TRUST_DIR  .'/images/module_icon.png' ;
	$icon_root_path  = $this->_MODULE_DIR .'/images/module_icon.png' ;

	if ( file_exists( $icon_root_path ) ) {
		$use_custom_icon = true ;
		$icon_fullpath   = $icon_root_path ;
	} else {
		$use_custom_icon = false ;
		$icon_fullpath   = $icon_trust_path ;
	}

	$modified = intval( time() / $this->_CACHE_LIMIT ) * $this->_CACHE_LIMIT;
	$expires  = $modified + $this->_CACHE_LIMIT;

	session_cache_limiter('public');
	header("Expires: ".date( 'r', $expires ) );
	header("Cache-Control: public, max-age=".$this->_CACHE_LIMIT);
	header("Last-Modified: ".date( 'r', $modified ));
	header("Content-type: image/png");

	if ( ! $use_custom_icon && 
		function_exists( 'imagecreatefrompng' ) && 
		function_exists( 'imagecolorallocate' ) && 
		function_exists( 'imagestring' ) && 
		function_exists( 'imagepng' ) ) {

		$im = imagecreatefrompng( $icon_fullpath ) ;
		$color = imagecolorallocate( $im , 0 , 0 , 0 ) ; // black
		$px = ( 92 - 6 * strlen( $this->_DIRNAME ) ) / 2 ;
		imagestring( $im , 3 , $px , 34 , $this->_DIRNAME , $color ) ;
		imagepng( $im ) ;
		imagedestroy( $im ) ;

	} else {
		readfile( $icon_fullpath ) ;
	}
}

// --- class end ---
}

?>