<?php
// $Id: gd.php,v 1.3 2010/10/06 02:22:46 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-10-01 K.OHWADA
// file_to_type()
// 2009-01-10 K.OHWADA
// version()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_lib_gd
//=========================================================
class webphoto_lib_gd
{
	var $_is_gd2    = false;
	var $_force_gd2 = false;

	var $_JPEG_QUALITY = 75;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_gd()
{
	$this->_is_gd2 = $this->is_gd2();
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_gd();
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function resize_rotate( $src_file, $dst_file, $max_width=0, $max_height=0, $rotate=0 )
{
	$image_size = getimagesize( $src_file ) ;
	if ( ! is_array($image_size) ) {
		return false;
	}

	$src_width  = $image_size[0] ;
	$src_height = $image_size[1] ;
	$src_type   = $image_size[2] ;

	$dst_type = $this->file_to_type( $dst_file, $src_type ) ;

	$src_img = $this->image_create( $src_file, $src_type );
	if ( !is_resource( $src_img ) ) {
		return false;
	}

	$dst_img = $src_img ;
	$rot_img = $src_img ;

	if (( $max_width > 0 )&&( $max_height > 0 )) {
		list( $new_width, $new_height ) =
			$this->image_adjust( $src_width, $src_height, $max_width, $max_height );

		$img = $this->image_resize( $src_img, $src_width, $src_height, $new_width, $new_height );
		if ( is_resource( $img ) ) {
			$dst_img = $img;
			$rot_img = $img ;
		}
	}

	if ( $rotate > 0 ) {
		$img = $this->image_rotate( $rot_img, (360 - $rotate) );
		if ( is_resource( $img ) ) {
			$dst_img = $img;
		}
	}

	if ( is_resource( $dst_img ) ) {
		$this->image_output( $dst_img, $dst_file, $dst_type );
		imagedestroy( $dst_img ) ;
		$ret = true;

	} else {
		$ret = true;
	}

	if ( is_resource( $src_img ) ) {
		imagedestroy( $src_img ) ;
	}

	if ( is_resource( $rot_img ) ) {
		imagedestroy( $rot_img ) ;
	}

	return $ret ;
}

function image_create( $file, $type )
{
	$img = null ;

	switch( $type ) {
	// GIF
	// GD 2.0.28 or later
		case 1 :
			if ( function_exists('imagecreatefromgif') ) {
				$img = imagecreatefromgif( $file ) ; 
			}
			break;

	// JPEG
	// GD 1.8 or later
		case 2 :
			if ( function_exists('imagecreatefromjpeg') ) {
				$img = imagecreatefromjpeg( $file ) ;
			}
			break ;

	// PNG
		case 3 :
			$img = imagecreatefrompng( $file ) ;
			break ;
	}

	return $img;
}

function image_output( $img, $file, $type )
{
	switch( $type ) 
	{
	// GIF
	// GD 2.0.28 or later
		case 1 :
			if ( function_exists('imagegif') ) {
				imagegif( $img, $file ) ;
			}
			break ;

	// JPEG
	// GD 1.8 or later
		case 2 :
			if ( function_exists('imagejpeg') ) {
				imagejpeg( $img, $file, $this->_JPEG_QUALITY ) ;
			}
			break ;

	// PNG
		case 3 :
			imagepng( $img, $file ) ;
			break ;
	}
}

function image_rotate( $src_img, $angle )
{
// PHP 4.3.0
	if ( $this->can_rotate() ) {
		return imagerotate( $src_img , $angle , 0 ) ;
	}
	return null;
}

function image_resize( $src_img, $src_width, $src_height, $new_width, $new_height )
{
	if ( $this->can_truecolor() ) {
		$img = imagecreatetruecolor( $new_width , $new_height ) ;

	} else {
		$img = imagecreate( $new_width , $new_height ) ;
	}

// PHP 4.0.6
	if ( function_exists( 'imagecopyresampled' ) ) {
		$ret = imagecopyresampled( $img, $src_img, 0, 0, 0, 0, $new_width, $new_height, $src_width, $src_height ) ;

	} else {
		imagecopyresized( $img, $src_img, 0, 0, 0, 0, $new_width, $new_height, $src_width, $src_height ) ;

	}

	return $img ;
}

function is_gd2()
{
// PHP 4.3.0
	if ( function_exists( 'gd_info' ) ) {
		$gd_info = gd_info() ;
		if( substr( $gd_info['GD Version'] , 0 , 10 ) == 'bundled (2' ) {
			return true;
		}
	}
	return false;
}

function image_adjust( $width, $height, $max_width, $max_height )
{
	if ( $width > $max_width ) {
		$mag    = $max_width / $width;
		$width  = $max_width;
		$height = $height * $mag;
	}

	if ( $height > $max_height ) {
		$mag    = $max_height / $height;
		$height = $max_height;
		$width  = $width * $mag;
	}

	return array( intval($width), intval($height) );
}

function file_to_type( $file, $type_default )
{
	$ext = $this->parse_ext( $file );

	switch( $ext ) 
	{
		case 'gif' :
			$type = 1 ;
			break ;

		case 'jpg' :
			$type = 2 ;
			break ;

		case 'png' :
			$type = 3 ;
			break ;

		default :
			$type = $type_default ;
			break ;
	}

	return $type;
}

function parse_ext( $file )
{
	return strtolower( substr( strrchr( $file , '.' ) , 1 ) );
}

//---------------------------------------------------------
// set & get param
//---------------------------------------------------------
function set_force_gd2( $val )
{
// force to use imagecreatetruecolor
	$this->_force_gd2 = (bool)$val;
}

function set_jpeg_quality( $val )
{
	$this->_JPEG_QUALITY = intval( $val );
}

function can_rotate()
{
// PHP 4.3.0
	if ( function_exists( 'imagerotate' ) ) {
		return true ;
	}
	return false ;
}

function can_truecolor()
{
// PHP 4.0.6 and GD 2.0.1
// fatal error in PHP 4.0.6 through 4.1.x without GD2
// http://www.php.net/manual/en/function.imagecreatetruecolor.php

	if (( $this->_is_gd2 || $this->_force_gd2 ) && function_exists( 'imagecreatetruecolor' ) ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// version
//---------------------------------------------------------
function version()
{
	if ( function_exists( 'gd_info' ) ) {
		$ret = true ;
		$gd_info = gd_info() ;
		$str = 'GD Version: '. $gd_info['GD Version'] ;

	} else {
		$ret = false;
		$str = 'not loaded';
	}
	return array( $ret, $str );
}

// --- class end ---
}

?>