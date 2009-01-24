<?php
// $Id: flash_create.php,v 1.1 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_flash_create
//=========================================================
class webphoto_edit_flash_create extends webphoto_edit_base_create
{
	var $_mime_handler ;
	var $_ffmpeg_class ;

	var $_cfg_use_ffmpeg ;

	var $_cached_option_array = array();

	var $_SUB_DIR_FLASHS = 'flashs';
	var $_FLASH_EXT      = 'flv';
	var $_FLASH_MIME     = 'video/x-flv';
	var $_FLASH_MEDIUM   = 'video';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_flash_create( $dirname )
{
	$this->webphoto_edit_base_create( $dirname );

	$this->_mime_handler  =& webphoto_mime_handler::getInstance( $dirname );
	$this->_ffmpeg_class  =& webphoto_ffmpeg::getInstance( $dirname );

	$this->_cfg_use_ffmpeg = $this->get_config_by_name( 'use_ffmpeg' );
}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_flash_create( $dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// create flash
//---------------------------------------------------------
function create_param( $param )
{
	$this->clear_msg_array();

	$item_id        = $param['item_id'] ;
	$item_width     = $param['item_width'] ;
	$item_height    = $param['item_height'] ;
	$item_duration  = $param['item_duration'] ;
	$src_file       = $param['src_file'];
	$src_ext        = $param['src_ext'];
	$src_kind       = $param['src_kind'];

	if ( ! $this->_cfg_use_ffmpeg ) {
		return null ;
	}
	if ( ! $this->is_video_kind( $src_kind ) ) {
		return null ;
	}

// return input file is flash 
	if ( $this->is_flash_ext( $src_ext ) ) {
		return null ;
	}

	$arr = $this->create_flash( $item_id, $src_file, $src_ext ) ;
	if ( !is_array($arr) ) {
		return null;
	}

	$arr['width']    = $item_width ;
	$arr['height']   = $item_height ;
	$arr['duration'] = $item_duration ;

	return $arr ;

}

function create_flash( $item_id, $src_file, $src_ext )
{
	$this->_flag_created = false ;
	$this->_flag_failed  = false ;
	$this->_msg          = null ;

	$flash_param = null ;

	$name_param = $this->build_random_name_param( 
		$item_id, $this->_FLASH_EXT, $this->_SUB_DIR_FLASHS );
	$name  = $name_param['name'] ;
	$path  = $name_param['path'] ;
	$file  = $name_param['file'] ;
	$url   = $name_param['url']  ;

	$option = $this->get_cached_option_by_ext( $src_ext );

	$ret = $this->_ffmpeg_class->create_flash( $src_file, $file, $option );
	if ( $ret ) {
		$this->set_flag_created() ;
		$this->set_msg( 'create flash' );

		$flash_param = array(
			'url'    => $url ,
			'path'   => $path ,
			'name'   => $name ,
			'ext'    => $this->_FLASH_EXT ,
			'mime'   => $this->_FLASH_MIME ,
			'medium' => $this->_FLASH_MEDIUM ,
			'size'   => filesize( $file ) ,
			'kind'   => _C_WEBPHOTO_FILE_KIND_VIDEO_FLASH ,
		);

	} else {
		$this->set_flag_failed() ;
		$this->set_msg( 'fail to create flash', true ) ;
	}

	return $flash_param ;
}

//---------------------------------------------------------
// mime
//---------------------------------------------------------
function get_cached_option_by_ext( $ext )
{
	if ( isset( $this->_cached_option_array[ $ext ] ) ) {
		return  $this->_cached_option_array[ $ext ];
	}

	$row = $this->_mime_handler->get_cached_row_by_ext( $ext );
	if ( !is_array($row) ) {
		return false;
	}

	$option = trim( $row['mime_ffmpeg'] ) ;
	$this->_cached_option_array[ $ext ] = $option ;
	return $option ;
}

// --- class end ---
}

?>