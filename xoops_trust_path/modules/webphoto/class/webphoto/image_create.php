<?php
// $Id: image_create.php,v 1.3 2008/07/05 12:54:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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

	var $_cfg_makethumb ;
	var $_has_resize = false;
	var $_has_rotate = false;

	var $_photo_info = null;
	var $_thumb_info = null;
	var $_image_info = null;
	var $_image_thumb_info = null;

	var $_URL_DAFAULT_IMAGE;
	var $_URL_PIXEL_IMAGE ;
	var $_FILE_PIXEL_IMAGE ;

	var $_ICON_EXT_DIR ;
	var $_ICON_EXT_DEFAULT ;

	var $_EXT_PNG = 'png';

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

	$this->_cfg_makethumb = $this->get_config_by_name( 'makethumb' ) ;

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
	$this->_image_cmd_class->set_width(        $this->get_config_by_name( 'width' ) );
	$this->_image_cmd_class->set_height(       $this->get_config_by_name( 'height' ) );
	$this->_image_cmd_class->set_thumb_width(  $this->get_config_by_name( 'thumb_width' ) );
	$this->_image_cmd_class->set_thumb_height( $this->get_config_by_name( 'thumb_height' ) );
	$this->_image_cmd_class->set_thumbrule(    $this->get_config_by_name( 'thumbrule' ) );
	$this->_image_cmd_class->set_normal_exts(  $this->get_normal_exts() );
	$this->_image_cmd_class->set_thumbs_path(  $this->_THUMBS_PATH );
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

//---------------------------------------------------------
// create photo
//---------------------------------------------------------
function create_photo( $src_file, $photo_id )
{
	$this->_photo_info = null;

	$photo_ext  = $this->parse_ext( $src_file );
	$photo_name = $this->build_photo_name( $photo_id, $photo_ext );
	$photo_path = $this->_PHOTOS_PATH .'/'. $photo_name;
	$photo_file = XOOPS_ROOT_PATH . $photo_path;

// modify photo
	if ( $this->is_normal_ext( $photo_ext ) ) {
		$ret = $this->cmd_modify_photo( $src_file , $photo_file );
		if ( $ret < 0 ) {
			return $ret; 
		}

// copy
	} else {
		$this->copy_file( $src_file , $photo_file ) ;
		$ret = _C_WEBPHOTO_IMAGE_COPIED ;
	}

	$this->_photo_info = $this->build_photo_full_info( $photo_path, $photo_name, $photo_ext );

	return $ret;
}

//---------------------------------------------------------
// create thumb
//---------------------------------------------------------
function create_thumb_from_upload( $photo_id, $tmp_name )
{
	$this->_thumb_info = null;

// check upload
	if ( empty($tmp_name) ) {
		return _C_WEBPHOTO_IMAGE_SKIPPED;	// no action
	}

// create thumb image in upload
	$this->reset_mode_rotate();

	$ret = $this->create_image( $this->_THUMBS_PATH, $photo_id, $tmp_name );
	if ( $ret < 0 ) {
		return $ret; 
	}

	$image_info = $this->get_image_info();
	if ( !is_array($image_info) ) {
		return _C_WEBPHOTO_ERR_FILEREAD;
	}

	$name = $image_info['name'] ;
	$path = $image_info['path'] ; 
	$ext  = $image_info['ext'] ;

	$this->_thumb_info = $this->build_thumb_info_full( $path, $name, $ext );
	return $ret;
}

function create_thumb_from_photo( $photo_id, $photo_path, $photo_ext )
{
	$this->_thumb_info = null;

// check config
	if ( !$this->_cfg_makethumb ) {
		return _C_WEBPHOTO_IMAGE_SKIPPED;
	}

	if ( ! $this->is_normal_ext( $photo_ext ) ) {
		return _C_WEBPHOTO_IMAGE_SKIPPED ;
	}

	$photo_file = XOOPS_ROOT_PATH . $photo_path;
	$photo_node = $this->build_photo_node( $photo_id );
	$photo_name = $photo_node .'.'. $photo_ext ;

// check main photo
	if ( empty($photo_path) ) {
		return _C_WEBPHOTO_IMAGE_SKIPPED;
	}

// return error if not read file
	if ( !is_readable( $photo_file ) ) {
		return _C_WEBPHOTO_IMAGE_READFAULT ;
	}

	$ret = $this->cmd_create_thumb( $photo_file , $photo_node , $photo_ext );
	if (( $ret == _C_WEBPHOTO_IMAGE_READFAULT )||
	    ( $ret == _C_WEBPHOTO_IMAGE_SKIPPED )) {
		return $ret;
	}

	$thumb_path = $this->_image_cmd_class->get_thumb_path() ;
	$thumb_name = $this->_image_cmd_class->get_thumb_name() ;
	$thumb_ext  = $this->_image_cmd_class->get_thumb_ext() ;

	$this->_thumb_info = $this->build_thumb_info_full( $thumb_path, $thumb_name, $thumb_ext );
	return $ret;
}

// substitute with photo image
function create_thumb_substitute( $photo_path, $photo_ext )
{
	$this->_thumb_info = null;

// check main photo
	if ( empty($photo_path) ) {
		return _C_WEBPHOTO_IMAGE_SKIPPED;
	}

// return error if not read file
	if ( !is_readable( XOOPS_ROOT_PATH . $photo_path ) ) {
		return _C_WEBPHOTO_ERR_FILEREAD;
	}

	$info = $this->build_thumb_info_full( $photo_path, '', $photo_ext );
	$info['photo_thumb_path'] = '' ;

	$this->_thumb_info = $info;

	return 0;
}

// Copy Thumbnail from directory of icons
function create_thumb_icon( $photo_id, $photo_ext )
{
	$this->_thumb_info = null;

	$node = $this->build_photo_node( $photo_id );

	list( $thumb_path, $thumb_name, $thumb_ext )
		= $this->copy_thumb_icon( $this->_THUMBS_PATH, $node, $photo_ext );

	$this->_thumb_info = $this->build_thumb_info_full( $thumb_path, $thumb_name, $thumb_ext );

	return _C_WEBPHOTO_IMAGE_ICON ;	// icon (not normal exts)
}

function copy_thumb_icon( $base_path, $node, $ext )
{
	$name_ext = $node .'.'. $ext ;
	$name_png = $node .'.'. $this->_EXT_PNG ;
	$ext_png  = $ext  .'.'. $this->_EXT_PNG ;

	$thumb_path_png = $base_path .'/'. $name_png;
	$thumb_file_png = XOOPS_ROOT_PATH . $thumb_path_png ;
	$icon_file_png  = $this->_ICON_EXT_DIR .'/'. $ext_png;

	$this->unlink_file( $thumb_file_png ) ;

	if ( is_file( $icon_file_png ) ) {
		$this->copy_file( $icon_file_png , $thumb_file_png ) ;
	} else {
		$this->copy_file( $this->_ICON_EXT_DEFAULT, $thumb_file_png ) ;
	}

	return array( $thumb_path_png, $name_png, $this->_EXT_PNG );
}

function get_photo_info()
{
	return $this->_photo_info;
}

function get_thumb_info()
{
	return $this->_thumb_info;
}

//---------------------------------------------------------
// create image
//---------------------------------------------------------
function create_image( $base_path, $id, $tmp_name )
{
	$this->_image_info = null;

	$tmp_file = $this->_TMP_DIR .'/'. $tmp_name;

// skip if not set tmp_name
	if ( empty($tmp_name) ) {
		return 0;	// no action
	}

// return error if not read tmp_file
	if( ! is_readable( $tmp_file ) ) {
		return _C_WEBPHOTO_ERR_FILEREAD;
	}

	$ext  = $this->parse_ext( $tmp_name );
	$name = $this->build_photo_name( $id, $ext );
	$path = $base_path .'/'. $name;
	$file = XOOPS_ROOT_PATH . $path;

	$ret = $this->cmd_modify_photo( $tmp_file , $file );
	if ( $ret == 0 ) {
		return _C_WEBPHOTO_ERR_FILEREAD;
	}

	$this->_image_info = array(
		'name' => $name ,
		'path' => $path , 
		'ext'  => $ext ,
	);

	return $ret;	// 1,2,5
}

function get_image_info()
{
	return $this->_image_info;
}

//---------------------------------------------------------
// no image thumbs
//---------------------------------------------------------
function create_no_image_thumb( $photo_id )
{
	$id_png = $photo_id .'.'. $this->_EXT_PNG ;

// dummy thumb
	$thumb_path = XOOPS_ROOT_PATH . $this->_THUMBS_PATH .'/'. $id_png;
	$thumb_url  = XOOPS_URL       . $this->_THUMBS_PATH .'/'. $id_png;
	copy( $this->_FILE_PIXEL_IMAGE, $thumb_path ) ;

	$arr = array(
		'photo_thumb_url'    => $thumb_url ,
		'photo_thumb_path'   => $thumb_path ,
		'photo_thumb_width'  => 1 ,
		'photo_thumb_height' => 1 ,
	);
	return $arr;
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
	$ahref_file   = $this->_TMP_URL .'/'. $preview_name;
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
function cmd_modify_photo( $src_file , $dst_file )
{
	return $this->_image_cmd_class->modify_photo( $src_file , $dst_file );
}

function cmd_create_thumb( $src_file , $node , $ext )
{
	return $this->_image_cmd_class->create_thumb( $src_file , $node , $ext );
}

function set_mode_rotate_by_post()
{
	$rotate = $this->_post_class->get_post( 'rotate' );
	$this->_image_cmd_class->set_mode_rotate(  $rotate );
}

function reset_mode_rotate()
{
	$this->_image_cmd_class->set_mode_rotate( null );
}

// --- class end ---
}

?>