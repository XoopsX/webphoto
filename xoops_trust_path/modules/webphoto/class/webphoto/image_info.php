<?php
// $Id: image_info.php,v 1.1 2008/06/21 12:22:24 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;


//=========================================================
// class webphoto_image_info
//=========================================================
class webphoto_image_info extends webphoto_base_this
{
	var $_max_middle_width;
	var $_max_middle_height;
	var $_max_thumb_width;
	var $_max_thumb_height;

	var $_IMAGE_MEDIUM = 'image';

	var $_ASCII_LOWER_A = 97; 
	var $_ASCII_LOWER_Z = 122;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_image_info( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	list( $this->_max_middle_width, $this->_max_middle_height )
		= $this->_config_class->get_middle_wh();

	list( $this->_max_thumb_width, $this->_max_thumb_height )
		= $this->_config_class->get_thumb_wh();
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_image_info( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// photo name
//---------------------------------------------------------
function build_photo_name( $id, $ext, $extra=null )
{
	$str  = $this->build_photo_node( $id, $extra );
	$str .= '.'.$ext;
	return $str;
}

function build_photo_node( $id, $extra=null )
{
	$alphabet = $this->build_random_alphabet();
	$str  = $alphabet;
	$str .= $this->build_format_id( $id );
	if ( $extra ) {
		$str .= $extra;
	}
	$str .= $this->build_uniqid( $alphabet );
	return $str;
}

function build_random_alphabet()
{
// one lower alphabet ( a - z )
	$str = chr( rand( $this->_ASCII_LOWER_A, $this->_ASCII_LOWER_Z ) );
	return $str;
}

function build_uniqid( $alphabet )
{
	return uniqid( $alphabet );
}

function build_format_id( $id )
{
	$str = sprintf( "%05d", $id );
	return $str;
}

//---------------------------------------------------------
// photo info
//---------------------------------------------------------
function build_photo_info( $path, $ext=null )
{
	$middle_width  = 0;
	$middle_height = 0;

	$info = $this->build_image_info( $path, $ext );

	if ( $info['is_image'] ) {
		list( $middle_width, $middle_height ) 
			= $this->adjust_middle_size( $info['width'], $info['height'] );
	}

	$info['middle_width']  = $middle_width;
	$info['middle_height'] = $middle_height;

	return $info;
}

function build_thumb_info( $path, $ext=null )
{
	$thumb_width  = 0;
	$thumb_height = 0;

	$info = $this->build_image_info( $path, $ext );

	if ( $info['width'] && $info['height'] ) {
		list ( $thumb_width, $thumb_height )
			= $this->adjust_thumb_size( $info['width'], $info['height'] );
	}

	$info['thumb_width']  = $thumb_width;
	$info['thumb_height'] = $thumb_height;

	return $info;
}

function build_image_info( $path, $ext=null )
{
	$size     = 0;
	$width    = 0;
	$height   = 0;
	$mime     = '';
	$medium   = '';
	$is_image = false;

	$file = XOOPS_ROOT_PATH . $path;

	if ( empty($ext) ) {
		$ext  = $this->parse_ext( $path );
	}

	if ( is_readable( $file ) ) {
		if ( $this->is_normal_ext( $ext ) ) {
			$image_size = GetImageSize( $file ) ;
			if ( is_array($image_size) ) {
				$width    = $image_size[0];
				$height   = $image_size[1];
				$mime     = $image_size['mime'];
				$medium   = $this->_IMAGE_MEDIUM;
				$is_image = true;
			}
		}
		$size = filesize( $file );
	}

	$arr = array(
		'path'     => $path ,
		'ext'      => $ext ,
		'size'     => $size ,
		'width'    => $width ,
		'height'   => $height ,
		'mime'     => $mime ,
		'medium'   => $medium ,
		'is_image' => $is_image ,
	);

	return $arr;
}

//---------------------------------------------------------
// utlity
//---------------------------------------------------------
function adjust_thumb_size( $width, $height )
{
	if ( $width && $height && $this->_max_thumb_width && $this->_max_thumb_height ) {
		return $this->adjust_image_size( $width, $height, $this->_max_thumb_width, $this->_max_thumb_height );
	}

	return array( 0, 0 );
}

function adjust_middle_size( $width, $height )
{
	if ( $width && $height && $this->_max_middle_width && $this->_max_middle_height ) {
		return $this->adjust_image_size( $width, $height, $this->_max_middle_width, $this->_max_middle_height );
	}

	return array( 0, 0 );
}

//---------------------------------------------------------
// for admin/checkconfig.php
//---------------------------------------------------------
function clear_tmp_files_in_tmp_dir()
{
	return $this->clear_tmp_files( $this->_TMP_DIR );
}

function clear_tmp_files( $dir_path , $prefix='tmp_' )
{
	// return if directory can't be opened
	if( ! ( $dir = @opendir( $dir_path ) ) ) {
		return 0 ;
	}

	$ret = 0 ;
	$prefix_len = strlen( $prefix ) ;
	while( ( $file = readdir( $dir ) ) !== false ) 
	{
		if( strncmp( $file , $prefix , $prefix_len ) === 0 ) {
			if( @unlink( $dir_path .'/'. $file ) ) { 
				$ret ++ ;
			}
		}
	}
	closedir( $dir ) ;

	return $ret ;
}

// --- class end ---
}

?>