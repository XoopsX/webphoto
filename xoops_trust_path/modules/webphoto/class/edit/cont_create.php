<?php
// $Id: cont_create.php,v 1.2 2009/01/25 10:25:27 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_cont_create
//=========================================================
class webphoto_edit_cont_create extends webphoto_edit_base_create
{
	var $_image_create_class;

	var $_cfg_width ;
	var $_cfg_height ;

	var $_cont_param   = null;
	var $_flag_resized = false ;

	var $_SUB_DIR_PHOTOS = 'photos';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_cont_create( $dirname )
{
	$this->webphoto_edit_base_create( $dirname );

	$this->_image_create_class =& webphoto_image_create::getInstance( $dirname );

	$this->_cfg_width  = $this->get_config_by_name( 'width' ) ;
	$this->_cfg_height = $this->get_config_by_name( 'height' ) ;
}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_cont_create( $dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// create image file
//---------------------------------------------------------
function create_param( $param )
{
	$this->clear_msg_array();

	if ( $this->is_image_kind( $param['src_kind'] ) ) {
		return $this->create_image_param( $param );
	}
		return $this->create_general_param( $param );
}

function create_general_param( $p )
{
	$item_id       = $p['item_id'] ;
	$src_file      = $p['src_file'];
	$src_ext       = $p['src_ext'];
	$src_mime      = isset($p['src_mime'])      ? $p['src_mime'] : null ;
	$item_duration = isset($p['item_duration']) ? intval($p['item_duration']) : 0 ;
	$item_width    = isset($p['item_width'])    ? intval($p['item_width'])    : 0 ;
	$item_height   = isset($p['item_height'])   ? intval($p['item_height'])   : 0 ;

	$name_param = $this->build_random_name_param( $item_id, $src_ext, $this->_SUB_DIR_PHOTOS );
	$name  = $name_param['name'] ;
	$path  = $name_param['path'] ;
	$file  = $name_param['file'] ;
	$url   = $name_param['url']  ;

	if ( empty($src_mime) ) {
		$src_mime = $this->ext_to_mime( $src_ext );
	}

	$medium = $this->mime_to_medium( $src_mime );

	copy( $src_file, $file );

	$arr = array(
		'url'      => XOOPS_URL . $path ,
		'path'     => $path ,
		'name'     => $name ,
		'ext'      => $src_ext ,
		'mime'     => $src_mime ,
		'medium'   => $medium ,
		'width'    => $item_width ,
		'height'   => $item_height ,
		'duration' => $item_duration ,
		'size'     => filesize($src_file) ,
		'kind'     =>_C_WEBPHOTO_FILE_KIND_CONT
	);

	$this->_cont_param = $arr;
	return 0;
}

function create_image_param( $param )
{
	$this->_ret_code = 0 ;
	$this->_flag_resized = false;

	$ret = $this->create_image( $param );

	if ( $ret == _C_WEBPHOTO_IMAGE_READFAULT ) {
		$this->set_msg( 'cannot read file', true );
		return _C_WEBPHOTO_ERR_FILEREAD;
	}
	if ( $ret == _C_WEBPHOTO_IMAGE_RESIZE ) {
		$this->_flag_resized = true;
		$this->set_msg( 'resize photo' );
	}

	if ( !is_array( $this->_cont_param ) ) {
		$this->set_msg( 'fail to create photo', true );
		return _C_WEBPHOTO_ERR_CREATE_PHOTO ;
	}

	return 0 ;
}

function create_image( $param )
{
	$item_id  = $param['item_id'] ;
	$src_file = $param['src_file'];
	$src_ext  = $param['src_ext'];
	$rotate   = isset($param['rotate']) ? intval($param['rotate']) : 0 ;

	$this->_cont_param = null;

	$name_param = $this->build_random_name_param( $item_id, $src_ext, $this->_SUB_DIR_PHOTOS );
	$name  = $name_param['name'] ;
	$path  = $name_param['path'] ;
	$file  = $name_param['file'] ;
	$url   = $name_param['url']  ;

	$ret = $this->resize_image( $src_file, $file, $rotate );
	if ( $ret < 0 ) {
		return $ret; 
	}

	$this->_cont_param = $this->build_image_file_param(
		$path, $name, $src_ext, _C_WEBPHOTO_FILE_KIND_CONT );

	return $ret;
}

//---------------------------------------------------------
// image_create_class
//---------------------------------------------------------
// admin/redothumb.php
function resize_image( $src_file, $dst_file, $rotate=0 )
{
	$ret = $this->_image_create_class->cmd_resize_rotate( 
		$src_file, $dst_file, $this->_cfg_width, $this->_cfg_height, $rotate );
}

function has_resize()
{
	return $this->_image_create_class->has_resize() ;
}

function has_rotate()
{
	return $this->_image_create_class->has_rotate() ;
}

//---------------------------------------------------------
// get param
//---------------------------------------------------------
function get_param()
{
	return $this->_cont_param ;
}

function get_flag_resized()
{
	return $this->_flag_resized ;
}

// --- class end ---
}

?>