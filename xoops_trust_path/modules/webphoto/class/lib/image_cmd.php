<?php
// $Id: image_cmd.php,v 1.2 2008/06/21 17:20:29 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_lib_image_cmd
// base on myalbum's functions
//=========================================================

//---------------------------------------------------------
// change log
// support gif functions of GD
//---------------------------------------------------------

class webphoto_lib_image_cmd
{
	var $_ICON_DIR;
	var $_PATH_DEFAULT_ICON;
	var $_PATH_WATERMRAK;

	var $_cfg_imagingpipe  = 0;		// PIPEID_GD;
	var $_cfg_forcegd2     = false;
	var $_cfg_imagickpath  = null;
	var $_cfg_netpbmpath   = null;
	var $_cfg_width        = 1024;
	var $_cfg_height       = 1024;
	var $_cfg_makethumb    = true;
	var $_cfg_thumbs_path  = null;
	var $_cfg_thumbs_url   = null;
	var $_cfg_thumb_width  = 140;
	var $_cfg_thumb_height = 140;
	var $_cfg_thumbrule    = 'w';
	var $_normal_exts      = array( 'jpg', 'jpeg', 'gif', 'png' );
	var $_mode_rotate      = null;

	var $_thumb_path = null;
	var $_thumb_name = null;
	var $_thumb_ext  = null;

	var $_msgs = array();

	var $_PIPEID_GD      = 0 ;
	var $_PIPEID_IMAGICK = 1 ;
	var $_PIPEID_NETPBM  = 2 ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_image_cmd()
{
	// dummy
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
function set_icon_dir( $val )
{
	$this->_ICON_DIR = $val;
	$this->_PATH_DEFAULT_ICON = $this->_ICON_DIR .'/default.png';
}

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
	$this->_cfg_forcegd2 = (bool)$val;
}

function set_imagickpath( $val )
{
	$this->_cfg_imagickpath = $this->_add_separator_to_tail( $val );
}

function set_netpbmpath( $val )
{
	$this->_cfg_netpbmpath = $this->_add_separator_to_tail( $val );
}

function set_width( $val )
{
	$this->_cfg_width = intval($val);
}

function set_height( $val )
{
	$this->_cfg_height = intval($val);
}

function set_makethumb( $val )
{
	$this->_cfg_makethumb = (bool)$val;
}

function set_thumbs_path( $val )
{
	$this->_cfg_thumbs_path = $val;
}

function set_thumb_width( $val )
{
	$this->_cfg_thumb_width = intval($val);
}

function set_thumb_height( $val )
{
	$this->_cfg_thumb_height = intval($val);
}

function set_thumbrule( $val )
{
	$this->_cfg_thumbrule = $val;
}

function set_normal_exts( $val )
{
	if ( is_array($val) ) {
		$this->_normal_exts = $val;
	}
}

function set_mode_rotate( $val )
{
	$this->_mode_rotate = $val;
}

function _add_separator_to_tail( $str )
{
// Check the path to binaries of imaging packages
	if( trim( $str ) != '' && substr( $str , -1 ) != DIRECTORY_SEPARATOR ) {
		$str .= DIRECTORY_SEPARATOR ;
	}
	return $str;
}

//---------------------------------------------------------
// modify
// return value
//   0 : read fault
//   1 : complete created
//   2 : copied
//   5 : resize
//---------------------------------------------------------
function modify_photo( $src_file , $dst_file )
{
	if( ! is_readable( $src_file ) ) {
		return 0 ;	// read fault
	}

	$ext = $this->parse_ext( $dst_file );

	if( !$this->is_normal_ext($ext) ) {
		$this->rename_file( $src_file , $dst_file ) ;
		return 2;	// copied
	}

	list( $width , $height , $type ) = getimagesize( $src_file ) ;

	// only copy when small enough and no rotate
	if (( $width  <= $this->_cfg_width ) && ( $height <= $this->_cfg_height ) &&
		( !$this->has_rotate() || !$this->require_rotate() ) ) {
		$this->rename_file( $src_file , $dst_file ) ;
		return 2;	// copied
	}

	if ( $this->_cfg_imagingpipe == $this->_PIPEID_IMAGICK ) {
		return $this->modify_photo_by_imagick( $src_file , $dst_file ) ;
	} elseif( $this->_cfg_imagingpipe == $this->_PIPEID_NETPBM ) {
		return  $this->modify_photo_by_netpbm( $src_file , $dst_file ) ;
	} elseif ( $this->_cfg_forcegd2 ) {
		return $this->modify_photo_by_gd( $src_file , $dst_file ) ;
	}

	$this->rename_file( $src_file , $dst_file ) ;
	return 2;	// copied
}

function has_rotate()
{
	if ( ( $this->_cfg_imagingpipe == $this->_PIPEID_IMAGICK ) ||
	     ( $this->_cfg_imagingpipe == $this->_PIPEID_NETPBM )  ||
	     ( $this->_cfg_forcegd2 && function_exists( 'imagerotate' ) ) ) {
		return true;
	}
	return false;
}

function require_rotate()
{
	switch( $this->_mode_rotate ) 
	{
		case 'rot270' :
		case 'rot180' :
		case 'rot90' :
			return true;

		case 'rot0' :
		default :
			break ;
	}
	return false;
}

// Modifying Original Photo by GD
function modify_photo_by_gd( $src_file , $dst_file )
{
	$ret_code = 1;	// success

	if( ! is_readable( $src_file ) ) {
		return 0 ;	// read fault
	}

	list ( $width , $height , $type ) = getimagesize( $src_file ) ;
 
	switch( $type ) {
	// GIF
		case 1 :
	// this function exists in GD 2.0.28 or later
			if ( function_exists('imagecreatefromgif') ) {
				$src_img = imagecreatefromgif( $src_file ) ; 
			} else {
				$this->copy_file( $src_file , $dst_file ) ;
				return 2 ;	// copied
			}
			break;

	// JPEG
		case 2 :
			$src_img = imagecreatefromjpeg( $src_file ) ;
			break ;

	// PNG
		case 3 :
			$src_img = imagecreatefrompng( $src_file ) ;
			break ;

	// other
		default :
			$this->rename_file( $src_file, $dst_file ) ;
			return 2 ;	// copied
	}

	if ( $width > $this->_cfg_width || $height > $this->_cfg_height ) {
		$ret_code = 5;	// resize

		if( $width / $this->_cfg_width > $height / $this->_cfg_height ) {
			$new_w = $this->_cfg_width ;
			$scale = $width / $new_w ; 
			$new_h = intval( round( $height / $scale ) ) ;
		} else {
			$new_h = $this->_cfg_height ;
			$scale = $height / $new_h ; 
			$new_w = intval( round( $width / $scale ) ) ;
		}
		$dst_img = imagecreatetruecolor( $new_w , $new_h ) ;
		imagecopyresampled( $dst_img , $src_img , 0 , 0 , 0 , 0 , $new_w , $new_h , $width , $height ) ;
	}

	if ( function_exists( 'imagerotate' ) ) {
		switch( $this->_mode_rotate ) 
		{
			case 'rot270' :
				if( ! isset( $dst_img ) || ! is_resource( $dst_img ) ) $dst_img = $src_img ;
				// patch for 4.3.1 bug
				$dst_img = imagerotate( $dst_img , 270 , 0 ) ;
				$dst_img = imagerotate( $dst_img , 180 , 0 ) ;
				break ;

			case 'rot180' :
				if( ! isset( $dst_img ) || ! is_resource( $dst_img ) ) $dst_img = $src_img ;
				$dst_img = imagerotate( $dst_img , 180 , 0 ) ;
				break ;

			case 'rot90' :
				if( ! isset( $dst_img ) || ! is_resource( $dst_img ) ) $dst_img = $src_img ;
				$dst_img = imagerotate( $dst_img , 270 , 0 ) ;
				break ;

			case 'rot0' :
			default :
				break ;
		}
	}

	if ( isset( $dst_img ) && is_resource( $dst_img ) ) {
		switch( $type ) 
		{
		// GIF
			case 1 :
		// this function exists in GD 2.0.28 or later
				if ( function_exists('imagegif') ) {
					imagegif( $dst_img, $dst_file ) ;
					imagedestroy( $dst_img ) ;
				} else {
					$this->copy_file( $src_file , $dst_file ) ;
					return 2 ;	// copied
				}
				break ;

		// JPEG
			case 2 :
				imagejpeg( $dst_img , $dst_file ) ;
				imagedestroy( $dst_img ) ;
				break ;

		// PNG
			case 3 :
				imagepng( $dst_img , $dst_file ) ;
				imagedestroy( $dst_img ) ;
				break ;
		}
	}

	imagedestroy( $src_img ) ;
	if( ! is_readable( $dst_file ) ) {
		// didn't exec convert, rename it.
		$this->rename_file( $src_file , $dst_file ) ;
		return 2 ;	// copied

	} else {
		$this->unlink_file( $src_file ) ;
		return $ret_code ;	// complete created
	}
}

// Modifying Original Photo by ImageMagick
function modify_photo_by_imagick( $src_file , $dst_file )
{
	$ret_code = 1;	// success

	if( ! is_readable( $src_file ) ) {
		return 0 ;	// read fault
	}

	// Make options for imagick
	$option = "" ;

	list ( $width , $height , $type ) = getimagesize( $src_file ) ;
	if ( $width > $this->_cfg_width || $height > $this->_cfg_height ) {
		$ret_code = 5;	// resize
		$option .= ' -geometry '. $this->_cfg_width .'x'. $this->_cfg_height;
	}

	switch( $this->_mode_rotate ) 
	{
		case 'rot270' :
			$option .= " -rotate 270" ;
			break ;

		case 'rot180' :
			$option .= " -rotate 180" ;
			break ;

		case 'rot90' :
			$option .= " -rotate 90" ;
			break ;

		default :
		case 'rot0' :
			break ;
	}

	// Do Modify and check success
	if ( $option != "" ) {
		$cmd = $this->_cfg_imagickpath .'convert '. $option .' '. $src_file .' '.$dst_file;
		exec( $cmd ) ;
	}

	if( ! is_readable( $dst_file ) ) {
		// didn't exec convert, rename it.
		$this->rename_file( $src_file , $dst_file ) ;
		$ret = 2 ;	// copied

	} else {
		$this->unlink_file( $src_file ) ;
		$ret = $ret_code ;	// complete created
	}

	// plus water mark
	if ( file_exists( $this->_PATH_WATERMRAK ) ) {
		$cmd = $this->_cfg_imagickpath .'composite -compose plus '. $this->_PATH_WATERMRAK .' '. $dst_file .'' . $dst_file;
		exec( $cmd ) ;
	}

	return $ret ;
}

// Modifying Original Photo by NetPBM
function modify_photo_by_netpbm( $src_file , $dst_file )
{
	$ret_code = 1;	// success

	if( ! is_readable( $src_file ) ) {
		return 0 ;	// read fault
	}

	list( $width , $height , $type ) = getimagesize( $src_file ) ;

	$pipe1  = '' ;
	$pipe11 = '' ;
	$pipe12 = '' ;

	switch( $type ) {
		case 1 :
			// GIF
			$pipe0  = $this->_cfg_netpbmpath .'giftopnm';
			$pipe2  = $this->_cfg_netpbmpath .'ppmquant 256 | ';
			$pipe2 .= $this->_cfg_netpbmpath .'ppmtogif';
			break ;

		case 2 :
			// JPEG
			$pipe0 = $this->_cfg_netpbmpath. 'jpegtopnm';
			$pipe2 = $this->_cfg_netpbmpath. 'pnmtojpeg';
			break ;

		case 3 :
			// PNG
			$pipe0 = $this->_cfg_netpbmpath .'pngtopnm';
			$pipe2 = $this->_cfg_netpbmpath .'pnmtopng';
			break ;

		default :
			$this->rename_file( $src_file, $dst_file ) ;
			return 2 ;	// copied
	}

	if ( $width > $this->_cfg_width || $height > $this->_cfg_height ) {
		$ret_code = 5;	// resize

		if( $width / $this->_cfg_width > $height / $this->_cfg_height ) {
			$new_w = $this->_cfg_width ;
			$scale = $width / $new_w ; 
			$new_h = intval( round( $height / $scale ) ) ;
		} else {
			$new_h = $this->_cfg_height ;
			$scale = $height / $new_h ; 
			$new_w = intval( round( $width / $scale ) ) ;
		}

		$pipe11 = $this->_cfg_netpbmpath .'pnmscale -xysize '. $new_w .' '. $new_h;
	}

	$cmd_pnmflip = $this->_cfg_netpbmpath .'pnmflip';

	switch ( $this->_mode_rotate ) 
	{
		case 'rot270' :
			$pipe12 = $cmd_pnmflip .' -r90 ';
			break ;

		case 'rot180' :
			$pipe12 = $cmd_pnmflip .' -r180 ';
			break ;

		case 'rot90' :
			$pipe12 = $cmd_pnmflip .' -r270 ';
			break ;

		case 'rot0' :
		default :
			break ;
	}

	// Do Modify and check success
	if ( $pipe11 && pipe12 ) {
		$pipe1 = $pipe11 .' | '. $pipe12;
	} elseif ( $pipe11 ) {	
		$pipe1 = $pipe11;
	} elseif ( $pipe12 ) {	
		$pipe1 = $pipe12;
	}

	if ( $pipe1 ) {	
		$cmd = $pipe0 .' < '. $src_file .' | '. $pipe1 .' | '. $pipe2 .' > '. $dst_file;
		exec( $cmd ) ;
	}

	if ( ! is_readable( $dst_file ) ) {
		// didn't exec convert, rename it.
		$this->rename_file( $src_file , $dst_file ) ;
		return 2 ;	// copied

	} else {
		$this->unlink_file( $src_file ) ;
		return $ret_code ;	// complete created
	}
}

//---------------------------------------------------------
// create_thumb Wrapper
// return value
//   0 : read fault
//   1 : complete created
//   2 : copied
//   3 : skipped
//   4 : icon (not normal exts)
//---------------------------------------------------------
function create_thumb( $src_file , $node , $ext )
{
	$name_ext = $node .'.'. $ext;
	$name_png = $node .'.png';
	$ext_png  = $ext  .'.png';

	$thumb_path_ext = $this->_cfg_thumbs_path .'/'. $name_ext;
	$thumb_path_png = $this->_cfg_thumbs_path .'/'. $name_png;

	$thumb_file_ext = XOOPS_ROOT_PATH . $thumb_path_ext ;
	$thumb_file_png = XOOPS_ROOT_PATH . $thumb_path_png ;

	$icon_file_png  = $this->_ICON_DIR        .'/'. $ext_png;

	if ( ! $this->is_normal_ext( $ext ) ) {
		$this->_thumb_path = $thumb_path_png;
		$this->_thumb_name = $name_png;
		$this->_thumb_ext  = 'png';
		$this->copy_thumb_from_icons( $icon_file_png , $thumb_file_png ) ;
		return 4 ;	// icon (not normal exts)
	}

	if ( ! $this->_cfg_makethumb ) {
		return 3 ;	// skipped
	}

	if( ! is_readable( $src_file ) ) {
		return 0 ;	// read fault
	}

	$this->_thumb_path = $thumb_path_ext;
	$this->_thumb_name = $name_ext;
	$this->_thumb_ext  = $ext;

	if( $this->_cfg_imagingpipe == $this->_PIPEID_IMAGICK ) {
		return $this->create_thumb_by_imagick( $src_file , $thumb_file_ext ) ;
	} else if( $this->_cfg_imagingpipe == $this->_PIPEID_NETPBM ) {
		return $this->create_thumb_by_netpbm( $src_file , $thumb_file_ext ) ;
	}
	return $this->create_thumb_by_gd( $src_file , $thumb_file_ext ) ;
}

function get_thumb_path()
{
	return $this->_thumb_path;
}

function get_thumb_name()
{
	return $this->_thumb_name;
}

function get_thumb_ext()
{
	return $this->_thumb_ext;
}

// Copy Thumbnail from directory of icons
function copy_thumb_from_icons( $src_file , $dst_file )
{
	$this->unlink_file( $dst_file ) ;
	$copy_success = $this->copy_file( $src_file, $dst_file ) ;
	if ( empty( $copy_success ) ) {
		$this->copy_file( $this->_PATH_DEFAULT_ICON, $dst_file ) ;
	}
	return 4 ;	// icon (not normal exts)
}

// Creating Thumbnail by GD
function create_thumb_by_gd( $src_file , $dst_file )
{
	if( ! is_readable( $src_file ) ) {
		return 0 ;	// read fault
	}

	$bundled_2 = false ;
	if( ! $this->_cfg_forcegd2 && function_exists( 'gd_info' ) ) {
		$gd_info = gd_info() ;
		if( substr( $gd_info['GD Version'] , 0 , 10 ) == 'bundled (2' ) $bundled_2 = true ;
	}

	$this->unlink_file( $dst_file ) ;

	list( $width , $height , $type ) = getimagesize( $src_file ) ;
	switch( $type ) 
	{
	// GIF
		case 1 :
	// this function exists in GD 2.0.28 or later
			if ( function_exists('imagecreatefromgif') ) {
				$src_img = imagecreatefromgif( $src_file ) ; 
			} else {
				$this->copy_file( $src_file , $dst_file ) ;
				return 2 ;	// copied
			}
			break;

	// JPEG
		case 2 :
			$src_img = imagecreatefromjpeg( $src_file ) ;
			break ;

	// PNG
		case 3 :
			$src_img = imagecreatefrompng( $src_file ) ;
			break ;



	// skip
		default :
			$this->copy_file( $src_file , $dst_file ) ;
			return 2 ;	// copied
	}

	list( $new_w , $new_h ) = $this->get_thumbnail_wh( $width , $height ) ;

	if( $width <= $new_w && $height <= $new_h ) {
		// only copy when small enough
		$this->copy_file( $src_file , $dst_file ) ;
		return 2 ;	// copied
	}

	if( $bundled_2 ) {
		$dst_img = imagecreate( $new_w , $new_h ) ;
		imagecopyresampled( $dst_img , $src_img , 0 , 0 , 0 , 0 , $new_w , $new_h , $width , $height ) ;
	} else {
		$dst_img = @imagecreatetruecolor( $new_w , $new_h ) ;
		if( ! $dst_img ) {
			$dst_img = imagecreate( $new_w , $new_h ) ;
			imagecopyresized( $dst_img , $src_img , 0 , 0 , 0 , 0 , $new_w , $new_h , $width , $height ) ;
		} else {
			imagecopyresampled( $dst_img , $src_img , 0 , 0 , 0 , 0 , $new_w , $new_h , $width , $height ) ;
		}
	}

	switch( $type ) 
	{
		case 1 :
		// GIF
		// this function exists in GD 2.0.28 or later
			if ( function_exists('imagegif') ) {
				imagegif( $dst_img, $dst_file ) ;
				imagedestroy( $dst_img ) ;
			} else {
				$this->copy_file( $src_file , $dst_file ) ;
				return 2 ;	// copied
			}
			break ;

		case 2 :
			// JPEG
			imagejpeg( $dst_img, $dst_file ) ;
			imagedestroy( $dst_img ) ;
			break ;

		case 3 :
			// PNG
			imagepng( $dst_img, $dst_file ) ;
			imagedestroy( $dst_img ) ;
			break ;
	}

	imagedestroy( $src_img ) ;
	return 1 ;	// complete created
}


// Creating Thumbnail by ImageMagick
function create_thumb_by_imagick( $src_file , $dst_file )
{
	if( ! is_readable( $src_file ) ) {
		return 0 ;	// read fault
	}

	$this->unlink_file( $dst_file ) ;

	list( $width , $height , $type ) = getimagesize( $src_file ) ;

	list( $new_w , $new_h ) = $this->get_thumbnail_wh( $width , $height ) ;

	if( $width <= $new_w && $height <= $new_h ) {
		// only copy when small enough
		$this->copy_file( $src_file , $dst_file ) ;
		return 2 ;	// copied
	}

	// Make Thumb and check success
	$cmd = $this->_cfg_imagickpath .'convert -geometry '. $new_w .'x' .$new_h .' '. $src_file .' '. $dst_file;
	exec( $cmd ) ;

	if( ! is_readable( $dst_file ) ) {
		// can't exec convert, big thumbs!
		$this->copy_file( $src_file , $dst_file ) ;
		return 2 ;	// copied
	}

	return 1 ;	// complete created
}


// Creating Thumbnail by NetPBM
function create_thumb_by_netpbm( $src_file , $dst_file )
{
	if( ! is_readable( $src_file ) ) {
		return 0 ;	// read fault
	}

	$this->unlink_file( $dst_file ) ;
	list( $width , $height , $type ) = getimagesize( $src_file ) ;

	switch( $type ) 
	{
		case 1 :
			// GIF
			$pipe0  = $this->_cfg_netpbmpath .'giftopnm' ;
			$pipe2  = $this->_cfg_netpbmpath .'ppmquant 256 | ';
			$pipe2 .= $this->_cfg_netpbmpath .'ppmtogif' ;
			break ;

		case 2 :
			// JPEG
			$pipe0 = $this->_cfg_netpbmpath .'jpegtopnm' ;
			$pipe2 = $this->_cfg_netpbmpath .'pnmtojpeg' ;
			break ;

		case 3 :
			// PNG
			$pipe0 = $this->_cfg_netpbmpath. 'pngtopnm' ;
			$pipe2 = $this->_cfg_netpbmpath. 'pnmtopng' ;
			break ;

		default :
			$this->copy_file( $src_file , $dst_file ) ;
			return 2 ;	// copied
	}

	list( $new_w , $new_h ) = $this->get_thumbnail_wh( $width , $height ) ;

	if( $width <= $new_w && $height <= $new_h ) {
		// only copy when small enough
		$this->copy_file( $src_file , $dst_file ) ;
		return 2 ;	// copied
	}

	$pipe1 = $this->_cfg_netpbmpath .'pnmscale -xysize '. $new_w .' '. $new_h;

	// Make Thumb and check success
	$cmd = $pipe0 .' < '. $src_file .' | '. $pipe1 .' | '. $pipe2 .' > '. $dst_file;
	exec( $cmd ) ;

	if( ! is_readable( $dst_file ) ) {
		// can't exec convert, big thumbs!
		$this->copy_file( $src_file , $dst_file ) ;
		return 2 ;	// copied
	}

	return 1 ;	// complete created
}

function get_thumbnail_wh( $width , $height )
{
	switch( $this->_cfg_thumbrule ) 
	{
		case 'w' :
			$new_w = $this->_cfg_thumb_width ;
			$scale = $width / $new_w ;
			$new_h = intval( round( $height / $scale ) ) ;
			break ;

		case 'h' :
			$new_h = $this->_cfg_thumb_height ;
			$scale = $height / $new_h ;
			$new_w = intval( round( $width / $scale ) ) ;
			break ;

		case 'b' :
			if( $width > $height ) {
				$new_w = $this->_cfg_thumb_width ;
				$scale = $width / $new_w ; 
				$new_h = intval( round( $height / $scale ) ) ;
			} else {
				$new_h = $this->_cfg_thumb_height ;
				$scale = $height / $new_h ; 
				$new_w = intval( round( $width / $scale ) ) ;
			}
			break ;

		default :
			$new_w = $this->_cfg_thumb_width ;
			$new_h = $this->_cfg_thumb_height ;
			break ;
	}

	return array( $new_w , $new_h ) ;
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
function parse_ext( $file )
{
	return substr( strrchr( $file , '.' ) , 1 );
}

function is_normal_ext( $ext )
{
	if( in_array( strtolower( $ext ) , $this->_normal_exts ) ) {
		return true;
	}
	return false;
}

function unlink_file( $file )
{
	if ( $this->check_file( $file ) ) {
		return unlink( $file );
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

function rename_file( $old, $new )
{
	if ( $this->check_file( $old ) ) {
		return rename( $old, $new );
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