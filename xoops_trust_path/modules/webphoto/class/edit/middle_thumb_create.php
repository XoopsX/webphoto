<?php
// $Id: middle_thumb_create.php,v 1.1 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_middle_thumb_create
//=========================================================
class webphoto_edit_middle_thumb_create extends webphoto_edit_base_create
{
	var $_image_create_class;

	var $_cfg_makethumb;
	var $_cfg_middle_width ;
	var $_cfg_middle_height ;
	var $_cfg_thumb_width ;
	var $_cfg_thumb_height ;

	var $_icon_tmp_file = null ;

	var $_SUB_DIR_MIDDLES = 'middles';
	var $_SUB_DIR_THUMBS  = 'thumbs';
	var $_BORDER_OPTION = ' -border 1 ';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_middle_thumb_create( $dirname )
{
	$this->webphoto_edit_base_create( $dirname );

	$this->_image_create_class =& webphoto_image_create::getInstance( $dirname );

	$this->_cfg_makethumb     = $this->get_config_by_name( 'makethumb' );
	$this->_cfg_middle_width  = $this->get_config_by_name( 'middle_width' ) ;
	$this->_cfg_middle_height = $this->get_config_by_name( 'middle_height' ) ;
	$this->_cfg_thumb_width   = $this->get_config_by_name( 'thumb_width' ) ;
	$this->_cfg_thumb_height  = $this->get_config_by_name( 'thumb_height' ) ;

}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_middle_thumb_create( $dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// create middle image
//---------------------------------------------------------
function create_middle_param( $param )
{
	$this->clear_msg_array();

	$param = $this->add_src_ext( $param );
	if ( ! $this->check_perm( $param ) ) {
		return null ;
	}

	$middle_param = $this->create_middle_image( $param );
	if ( is_array($middle_param) ) {
		$this->set_msg( 'create middle' );
	} else {
		$this->set_msg( ' fail to create middle', true ) ;
	}
	return $middle_param ;
}

function create_middle_image( $param )
{
	$param['sub_dir']    = $this->_SUB_DIR_MIDDLES ;
	$param['file_kind']  = _C_WEBPHOTO_FILE_KIND_MIDDLE ;
	$param['max_width']  = $this->_cfg_middle_width ;
	$param['max_height'] = $this->_cfg_middle_height ;

	return $this->create_image_common( $param );
}

//---------------------------------------------------------
// create thmub image
//---------------------------------------------------------
function create_thumb_param( $param )
{
	$this->clear_msg_array();

	$param = $this->add_src_ext( $param );
	if ( ! $this->check_perm( $param ) ) {
		return null ;
	}

	$thumb_param = $this->create_thumb_image( $param );
	if ( is_array($thumb_param) ) {
		$this->set_msg( 'create thumb' );
	} else {
		$this->set_msg( 'fail to create thumb', true ) ;
	}
	return $thumb_param ;
}

function create_thumb_image( $param )
{
	$param['sub_dir']    = $this->_SUB_DIR_THUMBS ;
	$param['file_kind']  = _C_WEBPHOTO_FILE_KIND_THUMB ;
	$param['max_width']  = $this->_cfg_thumb_width ;
	$param['max_height'] = $this->_cfg_thumb_height ;

	return $this->create_image_common( $param );
}

//---------------------------------------------------------
// common
//---------------------------------------------------------
function add_src_ext( $param )
{
	$src_file = $param['src_file'];
	$src_ext  = isset($param['src_ext']) ? $param['src_ext'] : null ;
	if ( empty($src_ext) ) {
		$param['src_ext'] = $this->parse_ext( $src_file );
	}
	return $param ;
}

function check_perm( $param )
{
	if ( empty( $param['src_file'] )  ) {
		return false ;
	}
	if ( ! is_readable( $param['src_file'] )  ) {
		return false ;
	}
	if ( ! $this->is_image_ext( $param['src_ext'] ) ) {
		return false ;
	}
	if ( ! $this->_cfg_makethumb ) {
		return false ;
	}
	return true;
}

function create_image_common( $param )
{
	$item_id    = $param['item_id'];
	$src_file   = $param['src_file'];
	$src_ext    = $param['src_ext'];
	$sub_dir    = $param['sub_dir'] ;
	$max_width  = $param['max_width'] ;
	$max_height = $param['max_height'] ;
	$file_kind  = $param['file_kind'] ;
	$icon_name  = isset($param['icon_name']) ? $param['icon_name'] : null ;

	$name_param = $this->build_random_name_param( $item_id, $src_ext, $sub_dir );
	$name  = $name_param['name'] ;
	$path  = $name_param['path'] ;
	$file  = $name_param['file'] ;
	$url   = $name_param['url']  ;

	$ret = $this->_image_create_class->cmd_resize( 
		$src_file, $file, $max_width, $max_height );

	if (( $ret == _C_WEBPHOTO_IMAGE_READFAULT )||
	    ( $ret == _C_WEBPHOTO_IMAGE_SKIPPED )) {
		return null ;
	}

	if ( $icon_name ) {
		$this->add_icon( $file, $src_ext, $icon_name);
	}

	$image_param = $this->build_image_file_param(
		$path, $name, $src_ext, $file_kind );

	return $image_param ;
}

function add_icon( $thumb_file, $src_ext, $icon_name )
{
	$icon_file = $this->_ROOT_EXTS_DIR .'/'. $icon_name .'.'. $this->_EXT_PNG ;
	if ( ! is_file($icon_file) ) {
		return false ;
	}

	$icon_file = $this->resize_icon( $thumb_file , $icon_file );
	if ( empty($icon_file) ) {
		return false ;
	}

	$tmp_file = $this->_TMP_DIR .'/'. uniqid( 'tmp_' ) .'.'. $src_ext;
	$this->_image_create_class->cmd_add_icon( $thumb_file, $tmp_file, $icon_file );
	if ( ! is_file($tmp_file) ) {
		return false ;
	}

	unlink( $thumb_file );

	$this->_image_create_class->cmd_convert( $tmp_file, $thumb_file, $this->_BORDER_OPTION );
	if ( ! is_file($thumb_file) ) {
		return false ;
	}

	unlink( $tmp_file );
	if ( is_file( $this->_icon_tmp_file ) ) {
		unlink(   $this->_icon_tmp_file ) ;
	}
	return true ;
}

function resize_icon( $thumb_file , $icon_file )
{
	$this->_icon_tmp_file = null;

	$image_size = GetImageSize( $thumb_file ) ;
	if ( is_array($image_size) ) {
		$thumb_width    = $image_size[0];
		$thumb_height   = $image_size[1];
	} else {
		return false;
	}

	$image_size = GetImageSize( $icon_file ) ;
	if ( is_array($image_size) ) {
		$icon_width    = $image_size[0];
		$icon_height   = $image_size[1];
	} else {
		return false;
	}

	$max_width  = $thumb_width  / 2 ;
	$max_height = $thumb_height / 2 ;
	$icon_tmp_file = $this->_TMP_DIR .'/'. uniqid( 'tmp_' ) .'.'. $this->_EXT_PNG ;

// resize icon
	if (( $icon_width  > $max_width  ) ||
	    ( $icon_height > $max_height )) {

		$this->_image_create_class->cmd_resize( 
			$icon_file, $icon_tmp_file, $max_width, $max_height );
			if ( is_file($icon_tmp_file) ) {
				$icon_file            = $icon_tmp_file ;
				$this->_icon_tmp_file = $icon_tmp_file ;
			}
	}

	return $icon_file ;
}

// --- class end ---
}

?>