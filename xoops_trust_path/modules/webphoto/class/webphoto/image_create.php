<?php
// $Id: image_create.php,v 1.1 2008/06/21 12:22:24 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;


//=========================================================
// class webphoto_image_create
//=========================================================
class webphoto_image_create extends webphoto_image_info
{
	var $_image_cmd_class;

	var $_image_info       = null;
	var $_photo_thumb_info = null;
	var $_thumb_info       = null;
	var $_msg_code = 0;
	
	var $_URL_DAFAULT_IMAGE;
	var $_URL_PIXEL_IMAGE ;
	var $_PATH_PIXEL_IMAGE ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_image_create( $dirname , $trust_dirname )
{
	$this->webphoto_image_info( $dirname , $trust_dirname );

	$this->_URL_DAFAULT_IMAGE = $this->_MODULE_URL .'/images/exts/default.png' ;
	$this->_URL_PIXEL_IMAGE   = $this->_MODULE_URL .'/images/icons/pixel_trans.png' ;
	$this->_PATH_PIXEL_IMAGE  = $this->_MODULE_DIR .'/images/icons/pixel_trans.png' ;

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
	$ICON_EXT_DIR = $this->_TRUST_DIR .'/images/exts' ;
	$WATERMARK    = $this->_TRUST_DIR .'/images/watermark.png' ;

	$this->_image_cmd_class =& webphoto_lib_image_cmd::getInstance();

	$this->_image_cmd_class->set_imagingpipe(  $this->get_config_by_name( 'imagingpipe' ) );
	$this->_image_cmd_class->set_forcegd2(     $this->get_config_by_name( 'forcegd2' ) );
	$this->_image_cmd_class->set_imagickpath(  $this->get_config_by_name( 'imagickpath' ) );
	$this->_image_cmd_class->set_netpbmpath(   $this->get_config_by_name( 'netpbmpath' ) );
	$this->_image_cmd_class->set_width(        $this->get_config_by_name( 'width' ) );
	$this->_image_cmd_class->set_height(       $this->get_config_by_name( 'height' ) );
	$this->_image_cmd_class->set_makethumb(    $this->get_config_by_name( 'makethumb' ) );
	$this->_image_cmd_class->set_thumb_width(  $this->get_config_by_name( 'thumb_width' ) );
	$this->_image_cmd_class->set_thumb_height( $this->get_config_by_name( 'thumb_height' ) );
	$this->_image_cmd_class->set_thumbrule(    $this->get_config_by_name( 'thumbrule' ) );
	$this->_image_cmd_class->set_normal_exts(  $this->get_normal_exts() );
	$this->_image_cmd_class->set_thumbs_path(  $this->_THUMBS_PATH );
	$this->_image_cmd_class->set_icon_dir(     $ICON_EXT_DIR );
	$this->_image_cmd_class->set_watermark(    $WATERMARK );

}

//---------------------------------------------------------
// create photo thumb
//---------------------------------------------------------
function create_photo_thumb( $photo_id, $file_tmp_name, $thumb_tmp_name )
{
	$cfg_makethumb = $this->get_config_by_name( 'makethumb' );

	$this->_photo_thumb_info = null;
	$this->_msg_code = 0 ; 

	$arr           = null;
	$file_url      = '';
	$file_path     = '';
	$file_name     = '';
	$file_ext      = '';
	$file_mime     = '';
	$file_medium   = '';
	$file_size     = 0;
	$photo_url     = '';
	$photo_path    = '';
	$photo_name    = '';
	$photo_ext     = '';
	$photo_mime    = '';
	$photo_medium  = '';
	$photo_size    = 0;
	$photo_width   = 0;
	$photo_height  = 0;
	$middle_width  = 0;
	$middle_height = 0;
	$thumb_url     = '';
	$thumb_path    = '';
	$thumb_name    = '';
	$thumb_ext     = '';
	$thumb_mime    = '';
	$thumb_medium  = '';
	$thumb_size    = 0;
	$thumb_width   = 0;
	$thumb_height  = 0;

	$flag_thumb      = false;
	$flag_substitute = false;

// create photo image
	if ( $file_tmp_name ) {
		$this->set_mode_rotate_by_post();

		$ret1 = $this->create_image( $this->_PHOTOS_PATH, $photo_id, $file_tmp_name );
		if ( $ret1 < 0 ) { return $ret1; }

		if ( $ret1 == _C_WEBPHOTO_IMAGE_RESIZE ) {
			$this->_msg_code = _C_WEBPHOTO_IMAGE_RESIZE; 
		}

		$photo_image_info = $this->get_image_info();
		if ( is_array($photo_image_info) ) {
			$photo_name = $photo_image_info['name'] ;
			$photo_path = $photo_image_info['path'] ; 
			$photo_ext  = $photo_image_info['ext'] ;
			$photo_url  = XOOPS_URL . $photo_path ;

			$photo_info = $this->build_photo_info( $photo_path, $photo_ext);
			$photo_mime    = $photo_info['mime'];
			$photo_medium  = $photo_info['medium'];
			$photo_size    = $photo_info['size'];
			$photo_width   = $photo_info['width'];
			$photo_height  = $photo_info['height'];
			$middle_width  = $photo_info['middle_width'];
			$middle_height = $photo_info['middle_height'];

			$file_url      = $photo_url;
			$file_path     = $photo_path;
			$file_name     = $photo_name;
			$file_ext      = $photo_ext;
			$file_mime     = $photo_mime;
			$file_medium   = $photo_medium;
			$file_size     = $photo_size;
		}
	}

// create thumb image in upload
	if ( $thumb_tmp_name ) {
		$this->reset_mode_rotate();

		$ret2 = $this->create_image( $this->_THUMBS_PATH, $photo_id, $thumb_tmp_name );
		if ( $ret2 < 0 ) { return $ret1; }

		$thumb_image_info = $this->get_image_info();
		if ( is_array($thumb_image_info) ) {
			$thumb_name = $thumb_image_info['name'] ;
			$thumb_path = $thumb_image_info['path'] ; 
			$thumb_ext  = $thumb_image_info['ext'] ;
			$thumb_url  = XOOPS_URL . $thumb_path ;
			$flag_thumb = true;
		}

// set thumb icon if main file uploaded
	} elseif ( $file_tmp_name ) {

// create thumb automatically
		if ( $cfg_makethumb ) {
			$ret4 = $this->create_thumb( $file_path , $photo_id , $file_ext );
			if ( $ret4 == _C_WEBPHOTO_IMAGE_READ_FAULT ) {
				return _C_WEBPHOTO_ERR_FILEREAD;
			}
			if ( $ret4 != _C_WEBPHOTO_IMAGE_SKIPPED ) {
				$thumb_image_info = $this->get_thumb_info();
				if ( is_array($thumb_image_info) ) {
					$thumb_name = $thumb_image_info['name'] ;
					$thumb_path = $thumb_image_info['path'] ; 
					$thumb_ext  = $thumb_image_info['ext'] ;
					$thumb_url  = XOOPS_URL . $thumb_path ;
					$flag_thumb = true;
				}
			}

// set thumb icon
		} else {
			$thumb_image_info 
				= $this->build_thumb_substitute( $photo_path, $file_ext );
			$thumb_name = '' ;
			$thumb_path = $thumb_image_info['url'] ; 
			$thumb_path = $thumb_image_info['path'] ; 
			$thumb_ext  = $thumb_image_info['ext'] ;
			$flag_thumb      = true;
			$flag_substitute = true;
		}
	}

	if ( $flag_thumb ) {
		$thumb_info = $this->build_thumb_info( $thumb_path, $thumb_ext);
		$thumb_mime    = $thumb_info['mime'];
		$thumb_medium  = $thumb_info['medium'];
		$thumb_size    = $thumb_info['size'];
		$thumb_width   = $thumb_info['thumb_width'];
		$thumb_height  = $thumb_info['thumb_height'];

		if ( $flag_substitute ) {
			$thumb_path = '';
		}
	}

	$this->clear_tmp_files_in_tmp_dir() ;

	if ( $file_tmp_name ) {
		$arr = array(
			'photo_file_url'      => $file_url ,
			'photo_file_path'     => $file_path ,
			'photo_file_name'     => $file_name ,
			'photo_file_ext'      => $file_ext ,
			'photo_file_mime'     => $file_mime ,
			'photo_file_medium'   => $file_medium ,
			'photo_file_size'     => $file_size ,
			'photo_cont_url'      => $photo_url ,
			'photo_cont_path'     => $photo_path ,
			'photo_cont_name'     => $photo_name ,
			'photo_cont_ext'      => $photo_ext ,
			'photo_cont_mime'     => $photo_mime ,
			'photo_cont_medium'   => $photo_medium ,
			'photo_cont_size'     => $photo_size ,
			'photo_cont_width'    => $photo_width ,
			'photo_cont_height'   => $photo_height ,
			'photo_middle_width'  => $middle_width ,
			'photo_middle_height' => $middle_height ,
			'photo_thumb_url'     => $thumb_url ,
			'photo_thumb_path'    => $thumb_path ,
			'photo_thumb_name'    => $thumb_name ,
			'photo_thumb_ext'     => $thumb_ext ,
			'photo_thumb_mime'    => $thumb_mime ,
			'photo_thumb_medium'  => $thumb_medium ,
			'photo_thumb_size'    => $thumb_size ,
			'photo_thumb_width'   => $thumb_width ,
			'photo_thumb_height'  => $thumb_height ,
		);
	}

	elseif ( $thumb_tmp_name ) {
		$arr = array(
			'photo_thumb_url'     => $thumb_url ,
			'photo_thumb_path'    => $thumb_path ,
			'photo_thumb_name'    => $thumb_name ,
			'photo_thumb_ext'     => $thumb_ext ,
			'photo_thumb_mime'    => $thumb_mime ,
			'photo_thumb_medium'  => $thumb_medium ,
			'photo_thumb_size'    => $thumb_size ,
			'photo_thumb_width'   => $thumb_width ,
			'photo_thumb_height'  => $thumb_height ,
		);
	}

	$this->_photo_thumb_info = $arr;

	return 0;
}

function build_thumb_substitute( $photo_path, $ext_in )
{
	$url  = '';
	$path = '';
	$ext  = '';

// main photo
	if ( $this->is_normal_ext( $ext_in ) && 
	     $photo_path &&
	     is_readable( XOOPS_ROOT_PAT.$photo_path ) ) {
		$path = $photo_path;
		$ext  = $ext_in;
		$url  = XOOPS_URL . $path;
	}

	$arr = array(
		'url'  => $url , 
		'path' => $path , 
		'ext'  => $ext ,
	);
	return $arr;
}

function get_photo_thumb_info()
{
	return $this->_photo_thumb_info;
}

function get_msg_code()
{
	return $this->_msg_code;
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

	$ret1 = $this->cmd_modify_photo( $tmp_file , $file );
	if ( $ret1 == 0 ) {
		return _C_WEBPHOTO_ERR_FILEREAD;
	}

	$this->_image_info = array(
		'name' => $name ,
		'path' => $path , 
		'ext'  => $ext ,
	);

	return $ret1;	// 1,2,5
}

function get_image_info()
{
	return $this->_image_info;
}

//---------------------------------------------------------
// create thumb
//---------------------------------------------------------
function create_thumb( $photo_path , $id , $ext )
{
	$this->_thumb_info = null;

	$file = XOOPS_ROOT_PATH . $photo_path;

// skip if not set path
	if ( empty($photo_path) ) {
		return _C_WEBPHOTO_IMAGE_SKIPPED ;
	}

// return error if not read file
	if ( !is_readable( $file ) ) {
		return _C_WEBPHOTO_IMAGE_READ_FAULT ;
	}

	$node = $this->build_photo_node( $id );

	$ret = $this->cmd_create_thumb( $file , $node , $ext );
	if (( $ret == _C_WEBPHOTO_IMAGE_READ_FAULT )||
	    ( $ret == _C_WEBPHOTO_IMAGE_SKIPPED )) {
		return $ret;
	}

	$this->_thumb_info = array(
		'path' => $this->_image_cmd_class->get_thumb_path(),
		'name' => $this->_image_cmd_class->get_thumb_name(),
		'ext'  => $this->_image_cmd_class->get_thumb_ext()
	 );

	return $ret;
}

function get_thumb_info()
{
	return $this->_thumb_info;
}

//---------------------------------------------------------
// no image thumbs
//---------------------------------------------------------
function create_no_image_thumb( $photo_id )
{
	$id_png = $photo_id.'.png';

// dummy thumb
	$thumb_path = XOOPS_ROOT_PATH . $this->_THUMBS_PATH .'/'. $id_png;
	$thumb_url  = XOOPS_URL       . $this->_THUMBS_PATH .'/'. $id_png;
	copy( $this->_PATH_PIXEL_IMAGE, $thumb_path ) ;

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