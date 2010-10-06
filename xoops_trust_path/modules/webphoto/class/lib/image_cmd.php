<?php
// $Id: image_cmd.php,v 1.9 2010/10/06 02:22:46 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// same_ext()
// 2009-04-21 K.OHWADA
// chmod_file()
// 2009-01-10 K.OHWADA
// add_icon()
// 2008-11-08 K.OHWADA
// webphoto_lib_gd etc
// 2008-08-24 K.OHWADA
// exec_modify_photo()
// 2008-07-01 K.OHWADA
// changed rename to copy in modify_photo
// removed unlink in modify_photo
// removed create thumb icon
// 2008-04-02 K.OHWADA
// supported gif functions of GD
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_lib_image_cmd
//=========================================================
class webphoto_lib_image_cmd
{
	var $_gd_class;
	var $_imagemagick_class;
	var $_netpbm_class;

	var $_ini_safe_mode ;

	var $_cfg_imagingpipe  = 0;		// PIPEID_GD;
	var $_cfg_forcegd2     = false;
	var $_cfg_imagickpath  = null;
	var $_cfg_netpbmpath   = null;

	var $_normal_exts = array( 'jpg', 'jpeg', 'gif', 'png' );
	var $_flag_chmod  = true;
	var $_msgs        = array();

	var $_PATH_WATERMRAK;

	var $_PIPEID_GD      = 0 ;
	var $_PIPEID_IMAGICK = 1 ;
	var $_PIPEID_NETPBM  = 2 ;

	var $_CODE_READFAULT  = -1 ;
	var $_CODE_FAILED     = -2 ;
	var $_CODE_CREATED    = 1 ;
	var $_CODE_COPIED     = 2 ;
	var $_CODE_SKIPPED    = 3 ;
	var $_CODE_RESIZE     = 5 ;

	var $_CHMOD_MODE = 0777;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_image_cmd()
{
	$this->_gd_class          =& webphoto_lib_gd::getInstance();
	$this->_imagemagick_class =& webphoto_lib_imagemagick::getInstance();
	$this->_netpbm_class      =& webphoto_lib_netpbm::getInstance();

	$this->_ini_safe_mode = ini_get('safe_mode');
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_image_cmd();
	}
	return $instance;
}

//---------------------------------------------------------
// set param
//---------------------------------------------------------
function set_watermark( $val )
{
	$this->_PATH_WATERMRAK = $val;
}

function set_imagingpipe( $val )
{
	$this->_cfg_imagingpipe = $val;
}

function set_forcegd2( $val )
{
	$this->_gd_class->set_force_gd2( $val );
}

function set_jpeg_quality( $val )
{
	$this->_gd_class->set_jpeg_quality( $val );
}

function set_imagickpath( $val )
{
	$this->_imagemagick_class->set_cmd_path( 
		$this->add_separator_to_tail( $val ) );
}

function set_netpbmpath( $val )
{
	$this->_netpbm_class->set_cmd_path( 
		$this->add_separator_to_tail( $val ) );
}

function set_normal_exts( $val )
{
	if ( is_array($val) ) {
		$this->_normal_exts = $val;
	}
}

function set_flag_chmod( $val )
{
	$this->_flag_chmod = (bool)$val ;
}

function add_separator_to_tail( $str )
{
// Check the path to binaries of imaging packages
	if( trim( $str ) != '' && substr( $str , -1 ) != DIRECTORY_SEPARATOR ) {
		$str .= DIRECTORY_SEPARATOR ;
	}
	return $str;
}

//---------------------------------------------------------
// property
//---------------------------------------------------------
function has_resize()
{
	if ( $this->_cfg_imagingpipe == $this->_PIPEID_IMAGICK ) {
		return true;
	}
	if ( $this->_cfg_imagingpipe == $this->_PIPEID_NETPBM ) {
		return true;
	}
	if ( $this->_gd_class->can_truecolor() ) {
		return true;
	}
	return false;
}

function has_rotate()
{
	if ( $this->_cfg_imagingpipe == $this->_PIPEID_IMAGICK ) {
		return true;
	}
	if ( $this->_cfg_imagingpipe == $this->_PIPEID_NETPBM ) {
		return true;
	}
	if ( $this->_gd_class->can_rotate() ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// return value
//   -1 : read fault
//    1 : complete created
//    2 : copied
//    3 : skipped
//    5 : resize
//---------------------------------------------------------
function resize_rotate( $src_file, $dst_file, $max_width, $max_height, $rotate=0 )
{
	if ( ! is_readable( $src_file ) ) {
		return $this->_CODE_READFAULT ;	// read fault
	}

	if ( ! $this->is_image_file( $src_file ) ) {
		return $this->_CODE_SKIPPED ;
	}

	$ret = $this->exec_resize_rotate( $src_file, $dst_file, $max_width, $max_height, $rotate );

	if ( $this->_flag_chmod ) {
		$this->chmod_file( $dst_file, $this->_CHMOD_MODE );
	}

	return $ret;
}

function exec_resize_rotate( $src_file, $dst_file, $max_width, $max_height, $rotate=0 )
{
	$width       = 0 ;
	$height      = 0 ;
	$flag_resize = $this->require_resize( $src_file, $max_width, $max_height );
	$flag_same   = $this->same_ext( $src_file , $dst_file );

	// only copy when small enough and no rotate
	if (( !$flag_resize )&&( $rotate == 0 )&& $flag_same ) {
		$this->copy_file( $src_file , $dst_file ) ;
		return $this->_CODE_COPIED;	// copied
	}

	$ret_code = $this->_CODE_CREATED;	// success

	if ( $flag_resize ) {
		$ret_code = $this->_CODE_RESIZE;	// resize
		$width    = $max_width ;
		$height   = $max_height ;
	}

	if ( $this->_cfg_imagingpipe == $this->_PIPEID_IMAGICK ) {
		$ret = $this->_imagemagick_class->resize_rotate( 
			$src_file, $dst_file, $width, $height, $rotate );

		if ( $ret && file_exists( $this->_PATH_WATERMRAK ) ) {
			$this->_imagemagick_class->add_watermark( $src_file, $dst_file, $this->_PATH_WATERMRAK );
		}

	} elseif( $this->_cfg_imagingpipe == $this->_PIPEID_NETPBM ) {
		$this->_netpbm_class->resize_rotate( 
			$src_file, $dst_file, $width, $height, $rotate );

	} else {
		$this->_gd_class->resize_rotate( 
			$src_file, $dst_file, $width, $height, $rotate );
	}

	if ( is_readable( $dst_file ) ) {
		return $ret_code ;	// complete created
	}

	// didn't exec convert, rename it.
	if ( $flag_same ) {
		$this->copy_file( $src_file , $dst_file ) ;
		return $this->_CODE_COPIED;	// copied
	}

	return $this->_CODE_FAILED ;
}

function require_resize( $src_file, $max_width, $max_height )
{
	if ( $max_width == 0 ) {
		return false;
	}
	if ( $max_height == 0 ) {
		return false;
	}

	$image_size = getimagesize( $src_file ) ;
	if ( ! is_array($image_size) ) {
		return false;
	}
	if ( $image_size[0] > $max_width ) {
		return true;
	}
	if ( $image_size[1] > $max_height ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// add icon
//---------------------------------------------------------
function add_icon( $src_file, $dst_file, $icon_file )
{
	if ( $this->_cfg_imagingpipe == $this->_PIPEID_IMAGICK ) {
		$this->_imagemagick_class->add_icon( $src_file, $dst_file, $icon_file ); 
	}
}

function convert( $src_file, $dst_file, $option=null )
{
	if ( $this->_cfg_imagingpipe == $this->_PIPEID_IMAGICK ) {
		$this->_imagemagick_class->convert( $src_file, $dst_file, $option ); 
	}
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
function is_image_file( $file )
{
	return $this->is_normal_ext( $this->parse_ext( $file ) );
}

function parse_ext( $file )
{
	return strtolower( substr( strrchr( $file , '.' ) , 1 ) );
}

function is_normal_ext( $ext )
{
	if( in_array( strtolower( $ext ) , $this->_normal_exts ) ) {
		return true;
	}
	return false;
}

function copy_file( $src, $dst )
{
	if ( $this->check_file( $src ) ) {
		return copy( $src, $dst );
	}
	return false;
}

function check_file( $file )
{
	if ( $file && file_exists($file) && is_file($file) && !is_dir($file) ) {
		return true;
	}
	$this->set_msg( 'not exist file : '.$file );
	return false;
}

function chmod_file( $file, $mode )
{
	if ( ! $this->_ini_safe_mode && is_file($file) ) {
		chmod( $file, $mode );
	}
}

function same_ext( $src_file, $dst_file )
{
	if ( $this->parse_ext( $src_file ) == $this->parse_ext( $dst_file ) ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// msg
//---------------------------------------------------------
function clear_msgs()
{
	$this->_msgs = array();
}

function get_msgs()
{
	return $this->_msgs;
}

function set_msg( $msg )
{
// array type
	if ( is_array($msg) ) {
		foreach ( $msg as $m ) {
			$this->_msgs[] = $m;
		}

// string type
	} else {
		$arr = explode("\n", $msg);
		foreach ( $arr as $m ) {
			$this->_msgs[] = $m;
		}
	}
}

// --- class end ---
}

?>