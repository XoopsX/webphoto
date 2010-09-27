<?php
// $Id: base_create.php,v 1.7 2010/09/27 03:42:54 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-09-20 K.OHWADA
// set_result()
// 2009-11-11 K.OHWADA
// webphoto_base_this
// $trust_dirname
// 2009-10-25 K.OHWADA
// is_jpeg_ext()
// 2009-01-25 K.OHWADA
// is_swf_ext()
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_base_create
//=========================================================
class webphoto_edit_base_create extends webphoto_base_this
{
	var $_msg_class;
	var $_mime_class;

	var $_result       = null;
	var $_flag_created = false ;
	var $_flag_failed  = false ;

	var $_IMAGE_MEDIUM = 'image';
	var $_EXT_PNG      = 'png';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_base_create( $dirname, $trust_dirname )
{
	$this->webphoto_base_this( $dirname, $trust_dirname );

	$this->_mime_class  =& webphoto_mime::getInstance( $dirname, $trust_dirname );

// each msg box
	$this->_msg_class   = new webphoto_lib_msg();
	$this->_error_class = new webphoto_lib_error();
}

//---------------------------------------------------------
// file
//---------------------------------------------------------
function build_random_name_param( $item_id, $src_ext, $sub_dir )
{
	$name = $this->build_random_file_name( $item_id, $src_ext );
	$path = $this->_UPLOADS_PATH .'/'. $sub_dir .'/'. $name ;
	$file = XOOPS_ROOT_PATH . $path ;
	$url  = XOOPS_URL       . $path ;

	$arr = array(
		'name' => $name ,
		'path' => $path ,
		'file' => $file ,
		'url'  => $url ,
	);
	return $arr ;
}

function build_image_file_param( $path, $name, $ext=null, $kind=null )
{
	$info = $this->build_image_info( $path, $ext );

	$arr = array(
		'url'     => XOOPS_URL . $path ,
		'path'    => $path ,
		'name'    => $name ,
		'ext'     => $info['ext'] ,
		'width'   => $info['width'] ,
		'height'  => $info['height'] ,
		'mime'    => $info['mime'] ,
		'medium'  => $info['medium'] ,
		'size'    => $info['size'] ,
	);

	if ( $kind ) {
		$arr['kind'] = $kind ;
	}

	return $arr;
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
		if ( $this->is_image_ext( $ext ) ) {
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
// msg class
//---------------------------------------------------------
function clear_msg_array()
{
	$this->_msg_class->clear_msg_array() ;
}

function get_msg_array()
{
	return $this->_msg_class->get_msg_array() ;
}

function set_msg( $msg, $flag_highlight=false )
{
	return $this->_msg_class->set_msg( $msg, $flag_highlight ) ;
}

//---------------------------------------------------------
// error class
//---------------------------------------------------------
function clear_errors()
{
	$this->_error_class->clear_errors() ;
}

function get_errors()
{
	return $this->_error_class->get_errors() ;
}

function set_error( $msg )
{
	return $this->_error_class->set_error( $msg ) ;
}

//---------------------------------------------------------
// get param
//---------------------------------------------------------
function set_result( $v )
{
	$this->_result = $v;
}

function set_flag_created()
{
	$this->_flag_created = true;
}

function set_flag_failed()
{
	$this->_flag_failed = true ;
}

function clear_flags()
{
	$this->_flag_created = false;
	$this->_flag_failed  = false;
}

function get_result()
{
	return $this->_result;
}

function get_flag_created()
{
	return $this->_flag_created ;
}

function get_flag_failed()
{
	return $this->_flag_failed ;
}

// --- class end ---
}

?>