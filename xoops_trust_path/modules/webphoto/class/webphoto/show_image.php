<?php
// $Id: show_image.php,v 1.7 2010/11/16 23:43:38 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-16 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-11 K.OHWADA
// get_cached_file_extend_row_by_name()
// 2010-10-01 K.OHWADA
// adjust_large_size()
// 2009-11-11 K.OHWADA
// $trust_dirname
// 2009-03-15 K.OHWADA
// small_url
// 2008-12-07 K.OHWADA
// build_img_tag_by_item_row()
// 2008-11-29 K.OHWADA
// build_show_file_image()
// BUG: not work when external image
// is_image_kind -> is_src_image_kind
//---------------------------------------------------------

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

	var $_max_large_width;
	var $_max_large_height;
	var $_max_middle_width;
	var $_max_middle_height;
	var $_max_thumb_width;
	var $_max_thumb_height;

	var $_DIRNAME;
	var $_MODULE_URL;
	var $_MODULE_DIR;

	var $_ROOT_EXTS_URL ;
	var $_DEFAULT_ICON_SRC;
	var $_PIXEL_ICON_SRC;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_show_image( $dirname , $trust_dirname )
{
	$this->_config_class  =& webphoto_config::getInstance( $dirname );
	$this->_kind_class    =& webphoto_kind::getInstance();
	$this->_utility_class =& webphoto_lib_utility::getInstance();

	$this->_item_handler  =& webphoto_item_handler::getInstance( 
		$dirname , $trust_dirname );
	$this->_file_handler  =& webphoto_file_handler::getInstance( 
		$dirname , $trust_dirname  );

	list( $this->_max_large_width, $this->_max_large_height )
		= $this->_config_class->get_large_wh();

	list( $this->_max_middle_width, $this->_max_middle_height )
		= $this->_config_class->get_middle_wh();

	list( $this->_max_thumb_width, $this->_max_thumb_height )
		= $this->_config_class->get_thumb_wh();

	$this->_DIRNAME    = $dirname ;
	$this->_MODULE_URL = XOOPS_URL       .'/modules/'. $dirname;
	$this->_MODULE_DIR = XOOPS_ROOT_PATH .'/modules/'. $dirname;

	$this->_ROOT_EXTS_URL    = $this->_MODULE_URL .'/images/exts';
	$this->_DEFAULT_ICON_SRC = $this->_MODULE_URL .'/images/exts/default.png';
	$this->_PIXEL_ICON_SRC   = $this->_MODULE_URL .'/images/icons/pixel_trans.png';

}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_show_image( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// image
//---------------------------------------------------------
function build_img_tag_by_item_row( $item_row )
{
	$arr = $this->build_image_title_by_item_row( $item_row ) ;

	$title_s = $arr['title_s'] ;
	$src_s   = $arr['img_thumb_src_s'] ;
	$width   = $arr['img_thumb_width'] ;
	$height  = $arr['img_thumb_height'] ;

	$str = null;

	if ( $src_s && $width && $height ) {
		$str = '<img src="'. $src_s .'" alt="'. $title_s .'" border="0" width="'. $width .'" height="'. $height .'" />'."\n";
	} elseif ( $src_s ) {
		$str = '<img src="'. $src_s .'" alt="'. $title_s .'" border="0" width="'. $this->_max_thumb_width .'"/>'."\n";
	}

	return $str ;
}

function build_image_title_by_item_row( $item_row, $default=true )
{
	$item_id   = $item_row['item_id'];
	$title     = $item_row['item_title'];
	$thumb_src = null ;

	$arr = $this->build_image_by_item_row( $item_row, $default );
	if ( is_array($arr) ) {
		$arr['img_thumb_src_s'] = $this->sanitize( $arr['img_thumb_src'] ) ;

	} else {
		$arr = array(
			'img_thumb_src'    => null ,
			'img_thumb_src_s'  => null ,
			'img_thumb_width'  => 0 ,
			'img_thumb_height' => 0 ,
		);
	}

	$arr['item_id']   = $item_id ;
	$arr['photo_id']  = $item_id ;
	$arr['title']     = $title ;
	$arr['title_s']   = $this->sanitize( $title ) ;

	return $arr;
}

function build_image_by_item_row( $item_row, $default )
{
	if ( ! is_array($item_row) ) {
		return false;
	}

	$cont_row   = $this->get_cached_file_extend_row_by_name( 
		$item_row, _C_WEBPHOTO_ITEM_FILE_CONT );
	$thumb_row  = $this->get_cached_file_extend_row_by_name( 
		$item_row, _C_WEBPHOTO_ITEM_FILE_THUMB );
	$large_row  = $this->get_cached_file_extend_row_by_name( 
		$item_row, _C_WEBPHOTO_ITEM_FILE_LARGE );
	$middle_row = $this->get_cached_file_extend_row_by_name( 
		$item_row, _C_WEBPHOTO_ITEM_FILE_MIDDLE );
	$small_row  = $this->get_cached_file_extend_row_by_name( 
		$item_row, _C_WEBPHOTO_ITEM_FILE_SMALL );

	$param = array(
		'item_row'       => $item_row ,
		'cont_row'       => $cont_row ,
		'thumb_row'      => $thumb_row ,
		'large_row'      => $large_row ,
		'middle_row'     => $middle_row ,
		'small_row'      => $small_row ,
		'photo_default'  => $default ,
		'thumb_default'  => $default ,
		'large_default'  => $default ,
		'middle_default' => $default ,
		'small_default'  => $default ,
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
	$large_row      = $param['large_row'] ;
	$middle_row     = $param['middle_row'] ;
	$small_row      = $param['small_row'] ;
	$photo_default  = $param['photo_default'] ;
	$thumb_default  = $param['thumb_default'] ;
	$large_default  = $param['large_default'] ;
	$middle_default = $param['middle_default'] ;
	$small_default  = $param['small_default'] ;

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
	$img_large_src     = '';
	$img_large_width   = 0 ;
	$img_large_height  = 0 ;
	$img_middle_src    = '';
	$img_middle_width  = 0 ;
	$img_middle_height = 0 ;
	$is_normal_image   = false ;

	$kind            = $item_row['item_kind'] ;
	$external_url    = $item_row['item_external_url'] ;
	$external_thumb  = $item_row['item_external_thumb'] ;
	$external_middle = $item_row['item_external_middle'] ;
	$external_large  = '' ;

// BUG: not work when external image
	$is_image_kind   = $this->is_src_image_kind( $kind );

	list( $icon_url, $icon_width, $icon_height ) =
		$this->build_show_icon_image( $item_row ) ;

	list( $cont_url, $cont_width, $cont_height ) =
		$this->build_show_file_image( $cont_row ) ;

	list( $thumb_url, $thumb_width, $thumb_height ) =
		$this->build_show_file_image( $thumb_row ) ;

	list( $large_url, $large_width, $large_height ) =
		$this->build_show_file_image( $large_row ) ;

	list( $middle_url, $middle_width, $middle_height ) =
		$this->build_show_file_image( $middle_row ) ;

	list( $small_url, $small_width, $small_height ) =
		$this->build_show_file_image( $small_row ) ;

// link file
	if ( $cont_url ) {
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

	} elseif( $photo_default ) {
		$img_photo_src = $this->_DEFAULT_ICON_SRC ;
	}

// thumb image
	if ( $thumb_url ) {
		$img_thumb_src    = $thumb_url ;
		$img_thumb_width  = $thumb_width ;
		$img_thumb_height = $thumb_height ;

	} elseif ( $external_thumb ) {
		$img_thumb_src    = $external_thumb ;

	} elseif ( $icon_url ) {
		$img_thumb_src    = $icon_url ;
		$img_thumb_width  = $icon_width;
		$img_thumb_height = $icon_height;

	} elseif ( $cont_url && $is_image_kind ) {
		$img_thumb_src    = $cont_url;
		$img_thumb_width  = $cont_width;
		$img_thumb_height = $cont_height;

	} elseif ( $external_url && $is_image_kind ) {
		$img_thumb_src    = $external_url ;

	} elseif( $thumb_default ) {
		$img_thumb_src    = $this->_PIXEL_ICON_SRC;
		$img_thumb_width  = 1;
		$img_thumb_height = 1;
	}

// large image
	if ( $large_url ) {
		$img_large_src    = $large_url;
		$img_large_width  = $large_width ;
		$img_large_height = $large_height ;

	} elseif ( $external_large ) {
		$img_large_src    = $external_large ;

	} elseif ( $cont_url && $is_image_kind ) {
		$img_large_src    = $cont_url;
		$img_large_width  = $cont_width;
		$img_large_height = $cont_height;

	} elseif ( $external_url && $is_image_kind ) {
		$img_large_src    = $external_url ;

	} elseif ( $thumb_url ) {
		$img_large_src    = $thumb_url ;
		$img_large_width  = $thumb_width ;
		$img_large_height = $thumb_height ;

	} elseif ( $external_thumb ) {
		$img_large_src    = $external_thumb ;

	} elseif ( $icon_url ) {
		$img_large_src    = $icon_url ;
		$img_large_width  = $icon_width;
		$img_large_height = $icon_height;

	} elseif( $large_default ) {
		$img_large_src    = $this->_DEFAULT_ICON_SRC;
		$img_large_width  = 1;
		$img_large_height = 1;
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

	} elseif ( $icon_url ) {
		$img_middle_src    = $icon_url ;
		$img_middle_width  = $icon_width;
		$img_middle_height = $icon_height;

	} elseif( $middle_default ) {
		$img_middle_src    = $this->_DEFAULT_ICON_SRC;
		$img_middle_width  = 1;
		$img_middle_height = 1;
	}

	list( $img_large_width, $img_large_height )
		= $this->adjust_middle_size( $img_large_width, $img_large_height );

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
		'large_url'         => $large_url ,
		'large_width'       => $large_width ,
		'large_height'      => $large_height ,
		'middle_url'        => $middle_url ,
		'middle_width'      => $middle_width ,
		'middle_height'     => $middle_height ,
		'small_url'         => $small_url ,
		'small_width'       => $small_width ,
		'small_height'      => $small_height ,
		'icon_url'          => $icon_url ,
		'icon_width'        => $icon_width ,
		'icon_height'       => $icon_height ,
		'media_url'         => $media_url ,
		'img_photo_src'     => $img_photo_src ,
		'img_photo_width'   => $img_photo_width ,
		'img_photo_height'  => $img_photo_height ,
		'img_large_src'     => $img_large_src ,
		'img_large_width'   => $img_large_width ,
		'img_large_height'  => $img_large_height ,
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

function get_cached_file_extend_row_by_name( $item_row, $item_name )
{
	if ( isset(    $item_row[ $item_name ] ) ) {
		$file_id = $item_row[ $item_name ] ;
	} else {
		return false;
	}

	if ( $file_id > 0 ) {
		return $this->_file_handler->get_cached_extend_row_by_id( $file_id );
	}

	return false ;
}

function build_show_icon_image( $item_row )
{
	return $this->_item_handler->build_show_icon_image( 
		$item_row, $this->_ROOT_EXTS_URL );
}

function build_show_file_image( $file_row )
{
	return $this->_file_handler->build_show_file_image( $file_row );
}

//---------------------------------------------------------
// kind class
//---------------------------------------------------------
function is_src_image_kind( $kind )
{
	return $this->_kind_class->is_src_image_kind( $kind ) ;
}

//---------------------------------------------------------
// adjust
//---------------------------------------------------------
function adjust_thumb_size( $width, $height )
{
	return $this->adjust_image_size( $width, $height, $this->_max_thumb_width, $this->_max_thumb_height );
}

function adjust_large_size( $width, $height )
{
	return $this->adjust_image_size( $width, $height, $this->_max_large_width, $this->_max_large_height );
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

//---------------------------------------------------------
// sanitize
//---------------------------------------------------------
function sanitize( $str )
{
	return htmlspecialchars( $str, ENT_QUOTES );
}

// --- class end ---
}

?>