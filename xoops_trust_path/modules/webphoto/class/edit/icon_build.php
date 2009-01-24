<?php
// $Id: icon_build.php,v 1.1 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_icon_build
//=========================================================
class webphoto_edit_icon_build
{
	var $_DIRNAME ;
	var $_MODULE_URL ;
	var $_MODULE_DIR ;
	var $_ROOT_EXTS_DIR ;
	var $_ROOT_EXTS_URL ;

	var $_EXT_PNG = 'png';
	var $_ICON_NAME_DEFAULT = 'default.png';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_icon_build( $dirname )
{
	$this->_DIRNAME    = $dirname ;
	$this->_MODULE_URL = XOOPS_URL       .'/modules/'. $dirname;
	$this->_MODULE_DIR = XOOPS_ROOT_PATH .'/modules/'. $dirname;

	$this->_ROOT_EXTS_URL = $this->_MODULE_URL .'/images/exts';
	$this->_ROOT_EXTS_DIR = $this->_MODULE_DIR .'/images/exts';
}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_icon_build( $dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// icon
//---------------------------------------------------------
function build_row_icon_if_empty( $row, $ext=null )
{
	if ( $row[ _C_WEBPHOTO_ITEM_FILE_THUMB ] ) {
		return $row;
	}
	if ( $row[ 'item_external_thumb' ] ) {
		return $row;
	}
	if ( $row[ 'item_icon_name' ] ) {
		return $row;
	}
	return $this->build_row_icon( $row, $ext ) ;
}

function build_row_icon( $row, $ext=null )
{
	if ( empty($ext) ) {
		$ext = $row['item_ext'] ;
	}
	if ( empty($ext) ) {
		return $row;
	}

	list( $name, $width, $height ) = $this->build_icon_image( $ext );
	$row['item_icon_name']   = $name ;
	$row['item_icon_width']  = $width ;
	$row['item_icon_height'] = $height ;
	return $row;
}

function build_icon_image( $ext )
{
	$name  = null ;
	$width  = 0 ;
	$height = 0 ;

	if ( $ext ) {
		$name = $ext .'.'. $this->_EXT_PNG ;
		$file = $this->_ROOT_EXTS_DIR .'/'. $name ;
		if ( ! is_file( $file ) ) {
			$name = $this->_ICON_NAME_DEFAULT ;
			$file = $this->_ROOT_EXTS_DIR .'/'. $name ;
		}
		$size = getimagesize( $file );
		if ( is_array($size) ) {
			$width  = $size[0];
			$height = $size[1];
		}
	}

	return array( $name, $width, $height ) ;
}

// --- class end ---
}

?>