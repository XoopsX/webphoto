<?php
// $Id: flash_create.php,v 1.5 2010/09/27 03:42:54 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-09-20 K.OHWADA
// webphoto_ext
// 2009-11-11 K.OHWADA
// $trust_dirname
// 2009-10-25 K.OHWADA
// remove get_cached_option_by_ext()
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_flash_create
//=========================================================
class webphoto_edit_flash_create extends webphoto_edit_base_create
{
	var $_ext_class ;

	var $_SUB_DIR_FLASHS = 'flashs';
	var $_FLASH_EXT      = 'flv';
	var $_FLASH_MIME     = 'video/x-flv';
	var $_FLASH_MEDIUM   = 'video';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_flash_create( $dirname , $trust_dirname  )
{
	$this->webphoto_edit_base_create( $dirname , $trust_dirname  );

	$this->_ext_class 
		=& webphoto_ext::getInstance( $dirname , $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_flash_create( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// create flash
//---------------------------------------------------------
function create( $param )
{
	$this->clear_msg_array();

	$item_id        = $param['item_id'] ;
	$item_width     = $param['item_width'] ;
	$item_height    = $param['item_height'] ;
	$item_duration  = $param['item_duration'] ;
	$src_file       = $param['src_file'];
	$src_ext        = $param['src_ext'];
	$src_kind       = $param['src_kind'];

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

	$param = array(
		'item_id'  => $item_id ,
		'src_file' => $src_file ,
		'src_ext'  => $src_ext ,
		'flv_file' => $file ,
	);

	$ret = $this->_ext_class->execute( 'flv', $param ) ;

// created
	if ( $ret == 1 ) {
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

// failed
	} elseif ( $ret == -1 ) {
		$this->set_flag_failed() ;
		$this->set_msg( 'fail to create flash', true ) ;
		$this->set_error( $this->_ext_class->get_errors() );
	}

	return $flash_param ;
}

// --- class end ---
}

?>