<?php
// $Id: show_image.php,v 1.1 2008/11/21 07:56:57 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-16 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_show_image
//=========================================================
class webphoto_show_image
{
	var $_config_class;
	var $_item_handler;
	var $_file_handler;
	var $_kind_class;
	var $_utility_class;

	var $_max_middle_width;
	var $_max_middle_height;
	var $_max_thumb_width;
	var $_max_thumb_height;

	var $_DIRNAME;
	var $_MODULE_URL;
	var $_MODULE_DIR;

	var $_URL_DEFAULT_IMAGE;
	var $_URL_PIXEL_IMAGE;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_show_image( $dirname )
{
	$this->_config_class  =& webphoto_config::getInstance( $dirname );
	$this->_item_handler  =& webphoto_item_handler::getInstance( $dirname );
	$this->_file_handler  =& webphoto_file_handler::getInstance( $dirname );
	$this->_kind_class    =& webphoto_kind::getInstance();
	$this->_utility_class =& webphoto_lib_utility::getInstance();

	list( $this->_max_middle_width, $this->_max_middle_height )
		= $this->_config_class->get_middle_wh();

	list( $this->_max_thumb_width, $this->_max_thumb_height )
		= $this->_config_class->get_thumb_wh();

	$this->_DIRNAME    = $dirname ;
	$this->_MODULE_URL = XOOPS_URL       .'/modules/'. $dirname;
	$this->_MODULE_DIR = XOOPS_ROOT_PATH .'/modules/'. $dirname;

	$this->_URL_DEFAULT_IMAGE  = $this->_MODULE_URL .'/images/exts/default.png';
	$this->_URL_PIXEL_IMAGE    = $this->_MODULE_URL .'/images/icons/pixel_trans.png';
}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_show_image( $dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// image
//---------------------------------------------------------
function build_image_by_item_row( $item_row, $default )
{
	if ( ! is_array($item_row) ) {
		return false;
	}

	$cont_row   = $this->get_cached_file_row_by_name( $item_row, _C_WEBPHOTO_ITEM_FILE_CONT );
	$thumb_row  = $this->get_cached_file_row_by_name( $item_row, _C_WEBPHOTO_ITEM_FILE_THUMB );
	$middle_row = $this->get_cached_file_row_by_name( $item_row, _C_WEBPHOTO_ITEM_FILE_MIDDLE );

	$param = array(
		'item_row'       => $item_row ,
		'cont_row'       => $cont_row ,
		'thumb_row'      => $thumb_row ,
		'middle_row'     => $middle_row ,
		'photo_default'  => $default ,
		'thumb_default'  => $default ,
		'middle_default' => $default ,
	);

	return $this->build_image_by_param( $param );
}

function build_image_by_param( $param )
{
	if ( ! is_array($param) ) {
		return false;
	}

	$item_row       = $param['item_row'] ;
	$cont_row       = $param['cont_row'] ;
	$thumb_row      = $param['thumb_row'] ;
	$middle_row     = $param['middle_row'] ;
	$photo_default  = $param['photo_default'] ;
	$thumb_default  = $param['thumb_default'] ;
	$middle_default = $param['middle_default'] ;

	if ( ! is_array($item_row) ) {
		return false;
	}

	$media_url         = '';
	$img_photo_src     = '';
	$img_photo_width   = 0 ;
	$img_photo_height  = 0 ;
	$img_thumb_src     = '';
	$img_thumb_width   = 0 ;
	$img_thumb_height  = 0 ;
	$img_middle_src    = '';
	$img_middle_width  = 0 ;
	$img_middle_height = 0 ;
	$is_normal_image   = false ;

	$kind            = $item_row['item_kind'] ;
	$title           = $item_row['item_title'] ;
	$external_url    = $item_row['item_external_url'] ;
	$external_thumb  = $item_row['item_external_thumb'] ;
	$external_middle = $item_row['item_external_middle'] ;

	$is_image_kind   = $this->is_image_kind( $kind );

	list( $cont_url, $cont_width, $cont_height ) =
		$this->get_file_image( $cont_row ) ;

	list( $thumb_url, $thumb_width, $thumb_height ) =
		$this->get_file_image( $thumb_row ) ;

	list( $middle_url, $middle_width, $middle_height ) =
		$this->get_file_image( $middle_row ) ;

// link file
	if ( $cont_url  ) {
		$media_url = $cont_url;

	} elseif ( $external_url ) {
		$media_url = $external_url;
	}

// photo image
	if ( $cont_url && $is_image_kind ) {
		$img_photo_src    = $cont_url;
		$img_photo_width  = $cont_width ;
		$img_photo_height = $cont_height ;
		$is_normal_image  = true ;

	} elseif ( $external_url && $is_image_kind ) {
		$img_photo_src    = $external_url;
		$is_normal_image  = true ;

	} elseif ( $middle_url ) {
		$img_photo_src    = $middle_url;
		$img_photo_width  = $middle_width ;
		$img_photo_height = $middle_height ;

	} elseif ( $thumb_url ) {
		$img_photo_src    = $thumb_url;
		$img_photo_width  = $thumb_width ;
		$img_photo_height = $thumb_height ;

	} elseif ( $external_middle ) {
		$img_photo_src = $external_middle ;

	} elseif ( $external_thumb ) {
		$img_photo_src = $external_thumb ;

	} elseif( $photo_default ) {
		$img_photo_src = $this->_URL_DEFAULT_IMAGE ;
	}

// thumb image
	if ( $thumb_url ) {
		$img_thumb_src    = $thumb_url ;
		$img_thumb_width  = $thumb_width ;
		$img_thumb_height = $thumb_height ;

	} elseif ( $external_thumb ) {
		$img_thumb_src    = $external_thumb ;

	} elseif ( $cont_url && $is_image_kind ) {
		$img_thumb_src    = $cont_url;
		$img_thumb_width  = $cont_width;
		$img_thumb_height = $cont_height;

	} elseif ( $external_url && $is_image_kind ) {
		$img_thumb_src    = $external_url ;

	} elseif( $thumb_default ) {
		$img_thumb_src    = $this->_URL_PIXEL_IMAGE;
		$img_thumb_width  = 1;
		$img_thumb_height = 1;
	}

// middle image
	if ( $middle_url ) {
		$img_middle_src    = $middle_url;
		$img_middle_width  = $middle_width ;
		$img_middle_height = $middle_height ;

	} elseif ( $external_middle ) {
		$img_middle_src    = $external_middle ;

	} elseif ( $cont_url && $is_image_kind ) {
		$img_middle_src    = $cont_url;
		$img_middle_width  = $cont_width;
		$img_middle_height = $cont_height;

	} elseif ( $external_url && $is_image_kind ) {
		$img_middle_src    = $external_url ;

	} elseif ( $thumb_url ) {
		$img_middle_src    = $thumb_url ;
		$img_middle_width  = $thumb_width ;
		$img_middle_height = $thumb_height ;

	} elseif ( $external_thumb ) {
		$img_middle_src    = $external_thumb ;

	} elseif( $middle_default ) {
		$img_middle_src    = $this->_URL_DEFAULT_IMAGE;
		$img_middle_width  = 1;
		$img_middle_height = 1;
	}

	list( $img_middle_width, $img_middle_height )
		= $this->adjust_middle_size( $img_middle_width, $img_middle_height );

	list( $img_thumb_width, $img_thumb_height )
		= $this->adjust_thumb_size( $img_thumb_width, $img_thumb_height );

	$arr = array(
		'cont_url'          => $cont_url ,
		'cont_width'        => $cont_width ,
		'cont_height'       => $cont_height ,
		'thumb_url'         => $thumb_url ,
		'thumb_width'       => $thumb_width ,
		'thumb_height'      => $thumb_height ,
		'middle_url'        => $middle_url ,
		'middle_width'      => $middle_width ,
		'middle_height'     => $middle_height ,
		'media_url'         => $media_url ,
		'img_photo_src'     => $img_photo_src ,
		'img_photo_width'   => $img_photo_width ,
		'img_photo_height'  => $img_photo_height ,
		'img_middle_src'    => $img_middle_src ,
		'img_middle_width'  => $img_middle_width ,
		'img_middle_height' => $img_middle_height ,
		'img_thumb_src'     => $img_thumb_src ,
		'img_thumb_width'   => $img_thumb_width ,
		'img_thumb_height'  => $img_thumb_height ,
		'is_normal_image'   => $is_normal_image ,
	);

	return $arr;
}

function get_cached_file_row_by_name( $item_row, $item_name )
{
	if ( isset(    $item_row[ $item_name ] ) ) {
		$file_id = $item_row[ $item_name ] ;
	} else {
		return false;
	}

	if ( $file_id > 0 ) {
		return $this->_file_handler->get_cached_row_by_id( $file_id );
	}

	return false ;
}

function get_file_image( $file_row )
{
	$url    = null ;
	$width  = 0 ;
	$height = 0 ;

	if ( is_array($file_row) ) {
		$url    = $file_row['file_url'] ;
		$width  = $file_row['file_width'] ;
		$height = $file_row['file_height'] ;
	}

	return array( $url, $width, $height );
}

//---------------------------------------------------------
// kind class
//---------------------------------------------------------
function is_image_kind( $kind )
{
	return $this->_kind_class->is_image_kind( $kind ) ;
}

//---------------------------------------------------------
// adjust
//---------------------------------------------------------
function adjust_thumb_size( $width, $height )
{
	return $this->adjust_image_size( $width, $height, $this->_max_thumb_width, $this->_max_thumb_height );
}

function adjust_middle_size( $width, $height )
{
	return $this->adjust_image_size( $width, $height, $this->_max_middle_width, $this->_max_middle_height );
}

function adjust_image_size( $width, $height, $max_width, $max_height )
{
	if ( $width && $height && $max_width && $max_height ) {
		return $this->_utility_class->adjust_image_size( $width, $height, $max_width, $max_height );
	}
	return array( 0, 0 );
}

// --- class end ---
}

?>