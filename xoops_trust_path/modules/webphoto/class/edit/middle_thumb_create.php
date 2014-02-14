<?php
// $Id: middle_thumb_create.php,v 1.6 2010/10/08 15:53:16 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// create_image_params()
// 2009-11-11 K.OHWADA
// $trust_dirname
// 2009-04-10 K.OHWADA
// build_icon_file()
// 2009-03-15 K.OHWADA
// create_small_param()
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_middle_thumb_create
//=========================================================
class webphoto_edit_middle_thumb_create extends webphoto_edit_base_create
{
	var $_image_create_class;

	var $_cfg_makethumb;
	var $_cfg_width ;
	var $_cfg_height ;
	var $_cfg_middle_width ;
	var $_cfg_middle_height ;
	var $_cfg_thumb_width ;
	var $_cfg_thumb_height ;
	var $_cfg_small_width ;
	var $_cfg_small_height ;

	var $_icon_tmp_file = null ;

	var $_SUB_DIR_LARGES  = 'larges';
	var $_SUB_DIR_MIDDLES = 'middles';
	var $_SUB_DIR_THUMBS  = 'thumbs';
	var $_SUB_DIR_SMALLS  = 'smalls';
	var $_BORDER_OPTION   = ' -border 1 ';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_middle_thumb_create( $dirname , $trust_dirname  )
{
	$this->webphoto_edit_base_create( $dirname , $trust_dirname  );

	$this->_image_create_class =& webphoto_image_create::getInstance( $dirname );

	$this->_cfg_makethumb     = $this->get_config_by_name( 'makethumb' );
	$this->_cfg_width         = $this->get_config_by_name( 'width' ) ;
	$this->_cfg_height        = $this->get_config_by_name( 'height' ) ;
	$this->_cfg_middle_width  = $this->get_config_by_name( 'middle_width' ) ;
	$this->_cfg_middle_height = $this->get_config_by_name( 'middle_height' ) ;
	$this->_cfg_thumb_width   = $this->get_config_by_name( 'thumb_width' ) ;
	$this->_cfg_thumb_height  = $this->get_config_by_name( 'thumb_height' ) ;
	$this->_cfg_small_width   = $this->get_config_by_name( 'small_width' ) ;
	$this->_cfg_small_height  = $this->get_config_by_name( 'small_height' ) ;

}

function &getInstance( $dirname , $trust_dirname  )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_middle_thumb_create( $dirname , $trust_dirname  );
	}
	return $instance;
}

//---------------------------------------------------------
// create image
//---------------------------------------------------------
function create_image_params( $param )
{
	$this->clear_msg_array();

	$item_ext = $param['item_ext'] ;
	$src_file = $param['src_file'] ;
	$src_ext  = isset($param['src_ext']) ? $param['src_ext'] : null ;

// add ext
	if ( empty($src_ext) ) {
		$param['src_ext'] = $this->parse_ext( $src_file );
	}

// check
	if ( empty( $src_file )  ) {
		return false ;
	}
	if ( ! is_readable( $src_file )  ) {
		return false ;
	}
	if ( ! $this->is_image_ext( $src_ext ) ) {
		return false ;
	}
	if ( ! $this->_cfg_makethumb ) {
		return false ;
	}

	$large     = '';
	$flag_jpeg = false;
	$icon_name = '';

// set large if image
	if ( $this->is_image_ext($item_ext) ) {
		$flag_jpeg = true;

// set icon if not image
	} else {
		$icon_name = $item_ext;
	}

	$param['icon_name'] = $icon_name ;

	$middle = $this->create_middle_param( $param );
	$small  = $this->create_small_param(  $param );
	$thumb  = $this->create_thumb_param(  $param );

	if ( $flag_jpeg ) {
		$large = $this->create_large_param( $param );
	}

	$file_params = array(
		'large'  => $large ,
		'middle' => $middle ,
		'small'  => $small ,
		'thumb'  => $thumb ,
	);

	return $file_params;
}

//---------------------------------------------------------
// create copy param
//---------------------------------------------------------
function create_copy_param( $param )
{
	$this->clear_msg_array();

	$src_kind = $param['src_kind'] ;

	switch ( $src_kind )
	{
		case _C_WEBPHOTO_FILE_KIND_THUMB :
			$ret = $this->create_thumb_param( $param );
			break;

		case _C_WEBPHOTO_FILE_KIND_LARGE :
			$ret = $this->create_large_param( $param );
			break;

		case _C_WEBPHOTO_FILE_KIND_MIDDLE :
			$ret = $this->create_middle_param( $param );
			break;

		case _C_WEBPHOTO_FILE_KIND_SMALL:
			$ret = $this->create_small_param( $param );
			break;
	}

	return $ret;
}

//---------------------------------------------------------
// create large image
//---------------------------------------------------------
function create_large_param( $param )
{
	$param['sub_dir']    = $this->_SUB_DIR_LARGES ;
	$param['file_kind']  = _C_WEBPHOTO_FILE_KIND_LARGE ;
	$param['max_width']  = $this->_cfg_width ;
	$param['max_height'] = $this->_cfg_height ;
	$param['msg_name']   = 'large' ;

	return $this->create_image_common( $param );
}

//---------------------------------------------------------
// create middle image
//---------------------------------------------------------
function create_middle_param( $param )
{
	$param['sub_dir']    = $this->_SUB_DIR_MIDDLES ;
	$param['file_kind']  = _C_WEBPHOTO_FILE_KIND_MIDDLE ;
	$param['max_width']  = $this->_cfg_middle_width ;
	$param['max_height'] = $this->_cfg_middle_height ;
	$param['msg_name']   = 'middle' ;

	return $this->create_image_common( $param );
}

//---------------------------------------------------------
// create thumb image
//---------------------------------------------------------
function create_thumb_param( $param )
{
	$param['sub_dir']    = $this->_SUB_DIR_THUMBS ;
	$param['file_kind']  = _C_WEBPHOTO_FILE_KIND_THUMB ;
	$param['max_width']  = $this->_cfg_thumb_width ;
	$param['max_height'] = $this->_cfg_thumb_height ;
	$param['msg_name']   = 'thumb' ;

	return $this->create_image_common( $param );
}

//---------------------------------------------------------
// create small image
//---------------------------------------------------------
function create_small_param( $param )
{
	$param['sub_dir']    = $this->_SUB_DIR_SMALLS ;
	$param['file_kind']  = _C_WEBPHOTO_FILE_KIND_SMALL ;
	$param['max_width']  = $this->_cfg_small_width ;
	$param['max_height'] = $this->_cfg_small_height ;
	$param['msg_name']   = 'small' ;

	return $this->create_image_common( $param );
}

//---------------------------------------------------------
// common
//---------------------------------------------------------
function create_image_common( $param )
{
	$name = $param['msg_name'] ;

	$param_out = $this->create_image_common_2( $param );
	if ( is_array($param_out) ) {
		$this->set_msg( 'create '.$name );
	} else {
		$this->set_msg( 'fail to create '.$name , true ) ;
	}

	return $param_out;
}

function create_image_common_2( $param )
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
		$this->add_icon( $file, $src_ext, $icon_name );
	}

	$image_param = $this->build_image_file_param(
		$path, $name, $src_ext, $file_kind );

	return $image_param ;
}

function add_icon( $thumb_file, $src_ext, $icon_name )
{
	$icon_file = $this->build_icon_file( $icon_name, true );
	if ( ! is_file($icon_file) ) {
		return false ;
	}

	$icon_file = $this->resize_icon( $thumb_file , $icon_file );
	if ( empty($icon_file) ) {
		return false ;
	}

	$tmp_file = $this->build_tmp_file( $src_ext );
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

function build_icon_file( $icon_name, $flag_ext )
{
	$file = $this->_ROOT_EXTS_DIR .'/'. $icon_name ;
	if ( $flag_ext ) {
		$file .= '.'. $this->_EXT_PNG ;
	}
	return $file;
}

function build_tmp_file( $ext )
{
	$file = $this->_TMP_DIR .'/'. uniqid( _C_WEBPHOTO_UPLOADER_PREFIX ) .'.'. $ext;
	return $file;
}

// --- class end ---
}

?>