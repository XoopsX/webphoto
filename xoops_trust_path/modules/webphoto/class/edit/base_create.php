<?php
// $Id: base_create.php,v 1.9 2010/11/16 23:43:38 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-11 K.OHWADA
// build_file_full_path()
// 2010-10-01 K.OHWADA
// create_copy_param()
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

	var $_param_ext    = null ;
	var	$_param_mime   = null ;
	var $_param_medium = null ;
	var $_param_kind   = null ;
	var $_param_dir    = null;
	var $_msg_created  = null ;
	var $_msg_failed   = null ;

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
// create copy param
//---------------------------------------------------------
function create_copy_param( $param )
{
	$item_id  = $param['item_id'];
	$src_file = $param['src_file'];

	$name_param = $this->build_name_param( $item_id );
	$file  = $name_param['file'] ;

	copy( $src_file, $file );

	return $this->build_copy_result( $name_param );
}

//---------------------------------------------------------
// file
//---------------------------------------------------------
function build_name_param( $item_id )
{
	return $this->build_random_name_param( 
		$item_id, $this->_param_ext, $this->_param_dir );
}

function build_random_name_param( $item_id, $src_ext, $sub_dir )
{
	$name = $this->build_random_file_name( $item_id, $src_ext );
	$path = $this->_UPLOADS_PATH .'/'. $sub_dir .'/'. $name ;
	$file = $this->build_file_full_path( $path );
	$url  = $this->build_file_full_url(  $path );

	$arr = array(
		'name' => $name ,
		'path' => $path ,
		'file' => $file ,
		'url'  => $url ,
	);
	return $arr ;
}

function build_image_file_param( $path, $name, $ext, $kind )
{
	$info = $this->build_image_info( $path, $ext );

	$arr = array(
		'url'     => $this->build_file_full_url(  $path ) ,
		'file'    => $this->build_file_full_path( $path ) ,
		'path'    => $path ,
		'name'    => $name ,
		'ext'     => $ext ,
		'kind'    => $kind ,
		'width'   => $info['width'] ,
		'height'  => $info['height'] ,
		'size'    => $info['size'] ,
		'mime'    => $info['mime'] ,
		'medium'  => $info['medium'] ,
	);

	return $arr;
}

function build_image_info( $path, $ext )
{
	$size     = 0;
	$width    = 0;
	$height   = 0;
	$mime     = '';
	$medium   = '';
	$is_image = false;

	$file = $this->build_file_full_path( $path );

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

function build_file_param_by_name_param( $name_param )
{
	$name  = $name_param['name'] ;
	$path  = $name_param['path'] ;
	$file  = $name_param['file'] ;
	$url   = $name_param['url']  ;

	$info  = $this->build_image_info( $path, $this->_param_ext );

	$param = array(
		'url'    => $url ,
		'file'   => $file ,
		'path'   => $path ,
		'name'   => $name ,
		'width'  => $info['width'] ,
		'height' => $info['height'] ,
		'size'   => filesize( $file ) ,
		'ext'    => $this->_param_ext,
		'mime'   => $this->_param_mime ,
		'medium' => $this->_param_medium ,
		'kind'   => $this->_param_kind ,
	);
	return $param;
}

function build_result( $ret, $name_param )
{
	$file_param = null;
	$this->_flag_created = false ;
	$this->_flag_failed  = false ;

// created
	if ( $ret == 1 ) {
		$this->set_flag_created() ;
		$this->set_msg( $this->_msg_created );
		$file_param = $this->build_file_param_by_name_param( $name_param );

// failed
	} elseif ( $ret == -1 ) {
		$this->set_flag_failed() ;
		$this->set_msg( $this->_msg_failed, true ) ;
	}

	return $file_param ;
}

function build_copy_result( $name_param )
{
	if ( file_exists( $name_param['file'] ) ) {
		$ret = 1;
	} else {
		$ret = -1;
	}

	return $this->build_result( $ret, $name_param );
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