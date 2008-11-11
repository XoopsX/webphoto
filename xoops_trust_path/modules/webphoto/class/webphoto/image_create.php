<?php
// $Id: image_create.php,v 1.9 2008/11/11 06:53:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-11-08 K.OHWADA
// cmd_modify_photo() -> cmd_resize_rotate()
// 2008-10-01 K.OHWADA
// use _MIDDLES_PATH
// 2008-08-24 K.OHWADA
// added create_middle_from_image_file()
// 2008-08-01 K.OHWADA
// added create_thumb_from_image_file(), copy_thumb_icon_in_dir()
// 2008-07-01 K.OHWADA
// create_photo_thumb()
//  -> create_photo() create_thumb_from_upload() etc
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_image_create
//=========================================================
class webphoto_image_create extends webphoto_image_info
{
	var $_image_cmd_class;

	var $_cfg_makethumb     = false ;
	var $_cfg_width         = 0 ;
	var $_cfg_height        = 0 ;
	var $_cfg_thumb_width   = 0 ;
	var $_cfg_thumb_height  = 0 ;
	var $_cfg_middle_width  = 0 ;
	var $_cfg_middle_height = 0 ;

	var $_has_resize = false;
	var $_has_rotate = false;

	var $_cont_param   = null;
	var $_thumb_param  = null;
	var $_middle_param = null;
	var $_image_info   = null;

	var $_URL_DAFAULT_IMAGE;
	var $_URL_PIXEL_IMAGE ;
	var $_FILE_PIXEL_IMAGE ;

	var $_ICON_EXT_DIR ;
	var $_ICON_EXT_DEFAULT ;

	var $_EXT_PNG = 'png';

	var $_flag_chmod = false;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_image_create( $dirname , $trust_dirname )
{
	$this->webphoto_image_info( $dirname , $trust_dirname );

	$this->_URL_DAFAULT_IMAGE = $this->_MODULE_URL .'/images/exts/default.png' ;
	$this->_URL_PIXEL_IMAGE   = $this->_MODULE_URL .'/images/icons/pixel_trans.png' ;
	$this->_FILE_PIXEL_IMAGE  = $this->_MODULE_DIR .'/images/icons/pixel_trans.png' ;

	$this->_ICON_EXT_DIR     = $this->_TRUST_DIR .'/images/exts' ;
	$this->_ICON_EXT_DEFAULT = $this->_ICON_EXT_DIR .'/default.png';

	$this->_cfg_makethumb     = $this->get_config_by_name( 'makethumb' ) ;
	$this->_cfg_width         = $this->get_config_by_name( 'width' ) ;
	$this->_cfg_height        = $this->get_config_by_name( 'height' ) ;
	$this->_cfg_thumb_width   = $this->get_config_by_name( 'thumb_width' ) ;
	$this->_cfg_thumb_height  = $this->get_config_by_name( 'thumb_height' ) ;
	$this->_cfg_middle_width  = $this->get_config_by_name( 'middle_width' ) ;
	$this->_cfg_middle_height = $this->get_config_by_name( 'middle_height' ) ;

	$this->_init_image_cmd();
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_image_create( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// init
//---------------------------------------------------------
function _init_image_cmd()
{
	$WATERMARK = $this->_TRUST_DIR .'/images/watermark.png' ;

	$this->_image_cmd_class =& webphoto_lib_image_cmd::getInstance();

	$this->_image_cmd_class->set_imagingpipe(  $this->get_config_by_name( 'imagingpipe' ) );
	$this->_image_cmd_class->set_forcegd2(     $this->get_config_by_name( 'forcegd2' ) );
	$this->_image_cmd_class->set_imagickpath(  $this->get_config_by_name( 'imagickpath' ) );
	$this->_image_cmd_class->set_netpbmpath(   $this->get_config_by_name( 'netpbmpath' ) );
	$this->_image_cmd_class->set_jpeg_quality( $this->get_config_by_name( 'jpeg_quality' ) );

	$this->_image_cmd_class->set_normal_exts(  $this->get_normal_exts() );
	$this->_image_cmd_class->set_watermark(    $WATERMARK );

	$this->_has_resize = $this->_image_cmd_class->has_resize();
	$this->_has_rotate = $this->_image_cmd_class->has_rotate();
}

function has_resize()
{
	return $this->_has_resize ;
}

function has_rotate()
{
	return $this->_has_rotate ;
}

function set_flag_chmod( $val )
{
	$this->_flag_chmod = (bool)$val ;
	$this->_image_cmd_class->set_flag_chmod( $val );
}

//---------------------------------------------------------
// create photo
//---------------------------------------------------------
function create_photo( $src_file, $photo_id, $rotate )
{
	$this->_cont_param = null;

	$photo_ext  = $this->parse_ext( $src_file );
	$photo_name = $this->build_photo_name( $photo_id, $photo_ext );
	$photo_path = $this->_PHOTOS_PATH .'/'. $photo_name;
	$photo_file = XOOPS_ROOT_PATH . $photo_path;

// modify photo
	if ( $this->is_normal_ext( $photo_ext ) ) {
		$ret = $this->resize_photo( $src_file, $photo_file, $rotate );
		if ( $ret < 0 ) {
			return $ret; 
		}

// copy
	} else {
		$this->copy_file( $src_file , $photo_file, $this->_flag_chmod ) ;
		$ret = _C_WEBPHOTO_IMAGE_COPIED ;
	}

	$this->_cont_param = $this->build_file_param(
		$photo_path, $photo_name, $photo_ext, _C_WEBPHOTO_FILE_KIND_CONT );

	return $ret;
}

function resize_photo( $src_file, $photo_file, $rotate=0 )
{
	$ret = $this->cmd_resize_rotate( 
		$src_file, $photo_file, $this->_cfg_width, $this->_cfg_height, $rotate );
}

function get_cont_param()
{
	return $this->_cont_param;
}

//---------------------------------------------------------
// create thumb, middle ( shrink size from orignal )
//---------------------------------------------------------
function create_thumb_from_photo_path( $photo_id, $src_path, $src_ext )
{
	$src_file = XOOPS_ROOT_PATH . $src_path;
	return $this->create_thumb_from_image_file( $src_file, $photo_id, $src_ext ) ;
}

function create_thumb_from_image_file( $src_file, $photo_id, $src_ext=null )
{
	$param = array(
		'photo_id'   => $photo_id ,
		'src_file'   => $src_file ,
		'src_ext'    => $src_ext ,
		'base_path'  => $this->_THUMBS_PATH ,
		'max_width'  => $this->_cfg_thumb_width ,
		'max_height' => $this->_cfg_thumb_height ,
		'file_kind'  => _C_WEBPHOTO_FILE_KIND_THUMB ,
	);

	$ret = $this->create_thumb_common( $param );
	$this->_thumb_param = $this->_thumb_common_param ;
	return $ret;
}

function create_middle_from_image_file( $src_file, $photo_id, $src_ext=null )
{
	$param = array(
		'photo_id'   => $photo_id ,
		'src_file'   => $src_file ,
		'src_ext'    => $src_ext ,
		'base_path'  => $this->_MIDDLES_PATH ,
		'max_width'  => $this->_cfg_middle_width ,
		'max_height' => $this->_cfg_middle_height ,
		'file_kind'  => _C_WEBPHOTO_FILE_KIND_MIDDLE ,
	);

	$ret = $this->create_thumb_common( $param );
	$this->_middle_param = $this->_thumb_common_param ;
	return $ret;
}

function create_thumb_common( $param )
{
	$this->_thumb_common_param = null;

	$photo_id   = $param['photo_id'];
	$src_file   = $param['src_file'];
	$src_ext    = $param['src_ext'];
	$base_path  = $param['base_path'] ;
	$max_width  = $param['max_width'] ;
	$max_height = $param['max_height'] ;
	$file_kind  = $param['file_kind'] ;

	if ( empty($src_ext) ) {
		$src_ext = $this->parse_ext( $src_file );
	}

// check config
	if ( ! $this->_cfg_makethumb ) {
		return _C_WEBPHOTO_IMAGE_SKIPPED;
	}

	if ( ! $this->is_normal_ext( $src_ext ) ) {
		return _C_WEBPHOTO_IMAGE_SKIPPED ;
	}

	$photo_node = $this->build_photo_node( $photo_id );
	$photo_name = $photo_node .'.'. $src_ext ;

// check main photo
	if ( empty($src_file) ) {
		return _C_WEBPHOTO_IMAGE_SKIPPED;
	}

// return error if not read file
	if ( !is_readable( $src_file ) ) {
		return _C_WEBPHOTO_IMAGE_READFAULT ;
	}

	$thumb_name = $photo_node .'.'. $src_ext ;
	$thumb_path = $base_path .'/'. $thumb_name;
	$thumb_file = XOOPS_ROOT_PATH . $thumb_path ;

	$ret = $this->cmd_resize_rotate( 
		$src_file, $thumb_file, $max_width, $max_height );

	if (( $ret == _C_WEBPHOTO_IMAGE_READFAULT )||
	    ( $ret == _C_WEBPHOTO_IMAGE_SKIPPED )) {
		return $ret;
	}

	$this->_thumb_common_param = $this->build_file_param(
		$thumb_path, $thumb_name, $src_ext, $file_kind );

	return $ret;
}

//---------------------------------------------------------
// create thumb, middle ( copy icon )
//---------------------------------------------------------
function create_thumb_icon( $photo_id, $photo_ext )
{
	$ret = $this->create_thumb_icon_common( 
		$photo_id, $photo_ext, $this->_THUMBS_PATH, _C_WEBPHOTO_FILE_KIND_THUMB );

	$this->_thumb_param = $this->_thumb_param_common ;
	return $ret;
}

function create_middle_icon( $photo_id, $photo_ext )
{
	$ret = $this->create_thumb_icon_common( 
		$photo_id, $photo_ext, $this->_MIDDLES_PATH, _C_WEBPHOTO_FILE_KIND_MIDDLE );

	$this->_middle_param = $this->_thumb_param_common ;
	return $ret;
}

function create_thumb_icon_common( $photo_id, $photo_ext, $base_path, $file_kind )
{
	$dir  = XOOPS_ROOT_PATH . $base_path ;
	$node = $this->build_photo_node( $photo_id );

	$thumb_name = $this->copy_thumb_icon_in_dir( $dir, $node, $photo_ext );
	$thumb_path = $base_path .'/'. $thumb_name ;

	$this->_thumb_param_common = $this->build_file_param( 
		$thumb_path, $thumb_name, $this->_EXT_PNG, $file_kind );

	return _C_WEBPHOTO_IMAGE_ICON ;	// icon (not normal exts)
}

function copy_thumb_icon_in_dir( $dir, $node, $ext )
{
	$name_png = $node .'.'. $this->_EXT_PNG ;
	$ext_png  = $ext  .'.'. $this->_EXT_PNG ;

	$thumb_file_png = $dir .'/'. $name_png;
	$icon_file_png  = $this->_ICON_EXT_DIR .'/'. $ext_png;

	$this->unlink_file( $thumb_file_png ) ;

	if ( is_file( $icon_file_png ) ) {
		$this->copy_file( $icon_file_png , $thumb_file_png, $this->_flag_chmod  ) ;
	} else {
		$this->copy_file( $this->_ICON_EXT_DEFAULT, $thumb_file_png, $this->_flag_chmod ) ;
	}

	return $name_png ;
}

function get_thumb_param()
{
	return $this->_thumb_param;
}

function get_middle_param()
{
	return $this->_middle_param;
}

//---------------------------------------------------------
// no image thumbs
//---------------------------------------------------------
function create_no_image_thumb( $photo_id )
{
	$ext   = $this->_EXT_PNG ;
	$name  = $photo_id .'.'. $ext ;
	$path  = $this->_THUMBS_PATH .'/'. $name ;
	$fille = XOOPS_ROOT_PATH . $path ;
	copy( $this->_FILE_PIXEL_IMAGE, $file ) ;

	$param = array(
		'url'     => XOOPS_URL . $path ,
		'path'    => $path ,
		'name'    => $name ,
		'ext'     => $ext ,
		'mime'    => 'image/png' ,
		'medium'  => 'image' ,
		'width'   => 0 ,
		'height'  => 0 ,
		'size'    => filesize($file) ,
	);

	return $param ;
}

//---------------------------------------------------------
// preview for submit
//---------------------------------------------------------
function create_preview_new( $preview_name, $photo_tmp_name )
{
	$src_path = $this->_TMP_DIR .'/'. $photo_tmp_name;
	$dst_path = $this->_TMP_DIR .'/'. $preview_name;
	rename( $src_path , $dst_path ) ;

	return $this->build_preview( $preview_name ) ;
}

function build_preview( $preview_name )
{
	$thumb_width     = 0;
	$thumb_height    = 0;
	$is_normal_image = false;

	$ext = $this->parse_ext( $preview_name );

	$path_photo   = $this->_TMP_DIR .'/'. $preview_name;
	$ahref_file   = $this->_MODULE_URL.'/index.php?fct=image&amp;name='. rawurlencode( $preview_name ) ;
	$imgsrc_thumb = $ahref_file;

// image type
	if ( $this->is_normal_ext( $ext ) ) {
		$is_normal_image = true;

		$size = GetImageSize( $path_photo ) ;
		if ( is_array($size) ) {
			$photo_width  = $size[0];
			$photo_height = $size[1];

			list ( $thumb_width, $thumb_height )
				= $this->adjust_thumb_size( $photo_width, $photo_height );
		}

// other type
	} else {
		$imgsrc_thumb = $this->_URL_DAFAULT_IMAGE;
	}

	$arr = array(
		'ahref_file'      => $ahref_file ,
		'imgsrc_thumb'    => $imgsrc_thumb ,
		'thumb_width'     => $thumb_width ,
		'thumb_height'    => $thumb_height ,
		'is_normal_image' => $is_normal_image ,
	);
	return $arr;

}

function build_no_image_preview()
{
	$arr = array(
		'ahref_photo'     => '' ,
		'imgsrc_thumb'    => $this->_URL_PIXEL_IMAGE ,
		'thumb_width'     => $this->_max_thumb_width ,
		'thumb_height'    => $this->_max_thumb_height ,
		'is_normal_image' => false,
	);
	return $arr;
}

//---------------------------------------------------------
// image cmd class
//---------------------------------------------------------
function cmd_resize_rotate( $src_file, $dst_file, $max_width, $max_height, $rotate=0 )
{
	return $this->_image_cmd_class->resize_rotate( 
		 $src_file, $dst_file, $max_width, $max_height, $rotate );
}

// --- class end ---
}

?>