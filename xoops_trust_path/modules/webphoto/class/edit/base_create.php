<?php
// $Id: base_create.php,v 1.2 2009/01/24 15:33:44 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_base_create
//=========================================================
class webphoto_edit_base_create
{
	var $_config_class;
	var $_utility_class;
	var $_msg_class;
	var $_kind_class;

	var $_flag_created = false ;
	var $_flag_failed  = false ;

	var $_DIRNAME;
	var $_MODULE_URL;
	var $_MODULE_DIR;
	var $_TMP_DIR;
	var $_UPLOADS_PATH ;
	var $_ROOT_EXTS_DIR;

	var $_IMAGE_MEDIUM = 'image';
	var $_EXT_PNG      = 'png';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_base_create( $dirname )
{
	$this->_DIRNAME       = $dirname ;
	$this->_MODULE_URL    = XOOPS_URL       .'/modules/'. $dirname;
	$this->_MODULE_DIR    = XOOPS_ROOT_PATH .'/modules/'. $dirname;

	$this->_utility_class =& webphoto_lib_utility::getInstance();
	$this->_kind_class    =& webphoto_kind::getInstance();
	$this->_config_class  =& webphoto_config::getInstance( $dirname );

// each msg box
	$this->_msg_class   = new webphoto_lib_msg();
	$this->_error_class = new webphoto_lib_error();

	$this->_TMP_DIR       = $this->_config_class->get_work_dir( 'tmp' );
	$this->_UPLOADS_PATH  = $this->_config_class->get_uploads_path();
	$this->_ROOT_EXTS_DIR = $this->_MODULE_DIR .'/images/exts';
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
// config class
//---------------------------------------------------------
function get_config_by_name( $name )
{
	return $this->_config_class->get_by_name( $name );
}

//---------------------------------------------------------
// kind class
//---------------------------------------------------------
function is_image_ext( $ext )
{
	return $this->_kind_class->is_image_ext( $ext ) ;
}

function is_flash_ext( $ext )
{
	return $this->_kind_class->is_flash_ext( $ext ) ;
}

function is_video_docomo_ext( $ext )
{
	return $this->_kind_class->is_video_docomo_ext( $ext ) ;
}

function is_pdf_ext( $ext )
{
	return $this->_kind_class->is_flash_ext( $ext ) ;
}

function is_general_kind( $kind )
{
	return $this->_kind_class->is_general_kind( $kind ) ;
}

function is_image_kind( $kind )
{
	return $this->_kind_class->is_image_kind( $kind ) ;
}

function is_video_kind( $kind )
{
	return $this->_kind_class->is_video_kind( $kind ) ;
}

//---------------------------------------------------------
// utility class
//---------------------------------------------------------
function parse_ext( $file )
{
	return $this->_utility_class->parse_ext( $file );
}

function build_random_file_name( $id, $ext, $extra=null )
{
	return $this->_utility_class->build_random_file_name( $id, $ext, $extra );
}

//---------------------------------------------------------
// mime class
//---------------------------------------------------------
function ext_to_kind( $ext )
{
	return $this->_mime_class->ext_to_kind( $ext );
}

function ext_to_mime( $ext )
{
	return $this->_mime_class->ext_to_mime( $ext );
}

function mime_to_medium( $mime )
{
	return $this->_mime_class->mime_to_medium( $mime );
}

function get_my_allowed_mimes()
{
	return $this->_mime_class->get_my_allowed_mimes();
}

function is_my_allow_ext( $ext )
{
	return $this->_mime_class->is_my_allow_ext( $ext );
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
function set_flag_created()
{
	$this->_flag_created = true;
}

function set_flag_failed()
{
	$this->_flag_failed = true ;
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