<?php
// $Id: imagemagick.php,v 1.3 2010/10/06 02:22:46 ohwada Exp $

//=========================================================
// webphoto module
// 2009-10-25 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// create_jpeg_from_cmyk()
// 2009-11-11 K.OHWADA
// $trust_dirname
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_imagemagick
// wrapper for webphoto_lib_imagemagick
//=========================================================
class webphoto_imagemagick extends webphoto_cmd_base
{
	var $_imagemagick_class;
	var $_cfg_imagingpipe;

	var $_PIPEID_IMAGICK = _C_WEBPHOTO_PIPEID_IMAGICK ;

	var $_CMD_CONVERT = 'convert';
	var $_CMYK_OPTION = '-colorspace RGB';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_imagemagick( $dirname, $trust_dirname )
{
	$this->webphoto_cmd_base( $dirname, $trust_dirname );

	$this->_imagemagick_class =& webphoto_lib_imagemagick::getInstance();

	$this->_cfg_imagingpipe = $this->get_config_by_name( 'imagingpipe' );

	$this->_imagemagick_class->set_cmd_path( 
		$this->get_config_dir_by_name( 'imagickpath' ) );

	$this->set_debug_by_ini_name( $this->_imagemagick_class );
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_imagemagick( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// create jpeg
//---------------------------------------------------------
function create_jpeg_from_cmyk( $src_file, $dst_file, $rotate=0, $option=null )
{
	$new_option  = $this->_CMYK_OPTION;

	if ( $rotate > 0 ) {
		$new_option .= ' -rotate '. $rotate ;
	}

	$new_option .= ' '.$option;

	return $this->create_jpeg( $src_file, $dst_file, $new_option );
}

function create_jpeg( $src_file, $dst_file, $option=null )
{
	if ( $this->_cfg_imagingpipe != $this->_PIPEID_IMAGICK ) {
		return 0 ;	// no action
	}

	if ( empty($option) ) {
		$option = $this->get_cmd_option( $src_file, $this->_CMD_CONVERT );
	}

	$this->_imagemagick_class->convert( $src_file, $dst_file, $option );
	if ( is_file($dst_file) ) {
		$this->chmod_file( $dst_file );
		return 1 ;	// suceess
	}

	$this->set_error( $this->_imagemagick_class->get_msg_array() );
	return -1 ;	// fail
}

// --- class end ---
}

?>