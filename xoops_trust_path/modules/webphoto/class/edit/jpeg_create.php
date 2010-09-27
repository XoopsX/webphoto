<?php
// $Id: jpeg_create.php,v 1.3 2010/09/27 03:42:54 ohwada Exp $

//=========================================================
// webphoto module
// 2009-10-25 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-09-20 K.OHWADA
// create_jpeg() -> execute()
// 2009-11-11 K.OHWADA
// $trust_dirname
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_jpeg_create
//=========================================================
class webphoto_edit_jpeg_create extends webphoto_edit_base_create
{
	var $_ext_class ;

	var $_SUB_DIR_JPEGS = 'jpegs';
	var $_EXT_JPEG      = 'jpeg';

	var $_JPEG_EXT    = 'jpg';
	var $_JPEG_MIME   = 'image/jpeg';
	var $_JPEG_MEDIUM = 'image';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_jpeg_create( $dirname , $trust_dirname )
{
	$this->webphoto_edit_base_create( $dirname , $trust_dirname );

	$this->_ext_class =& webphoto_ext::getInstance( $dirname , $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_jpeg_create( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// create jpeg
//---------------------------------------------------------
function create_param( $param )
{
	$this->clear_msg_array();

	$item_id  = $param['item_id'];
	$src_file = $param['src_file'];
	$src_ext  = $param['src_ext'];
	$src_kind = $param['src_kind'];

//	if ( ! $this->is_general_kind( $src_kind ) ) {
//		return null ;
//	}

// return input file is jpeg 
	if ( $this->is_jpeg_ext( $src_ext ) ) {
		return null ;
	}

	$jpeg_param = $this->create_jpeg( $item_id, $src_file, $src_ext ) ;
	if ( !is_array($jpeg_param) ) {
		return null;
	}

	return $jpeg_param ;
}

function create_jpeg( $item_id, $src_file, $src_ext )
{
	$this->_flag_created = false ;
	$this->_flag_failed  = false ;

	$jpeg_param = null ;

	$name_param =$this->build_random_name_param( $item_id, $this->_EXT_JPEG, $this->_SUB_DIR_JPEGS );
	$name  = $name_param['name'] ;
	$path  = $name_param['path'] ;
	$file  = $name_param['file'] ;
	$url   = $name_param['url']  ;

	$param = array(
		'src_file'  => $src_file ,
		'src_ext'   => $src_ext ,
		'jpeg_file' => $file ,
	);

	$ret = $this->_ext_class->execute( 'jpeg', $param ) ;

// created
	if ( $ret == 1 ) {
		$this->set_flag_created() ;
		$this->set_msg( 'create jpeg' );
		$jpeg_param = array(
			'url'    => $url ,
			'file'   => $file ,
			'path'   => $path ,
			'name'   => $name ,
			'ext'    => $this->_JPEG_EXT ,
			'mime'   => $this->_JPEG_MIME ,
			'medium' => $this->_JPEG_MEDIUM ,
			'size'   => filesize( $file ) ,
			'kind'   => _C_WEBPHOTO_FILE_KIND_JPEG ,
		);

// failed
	} elseif ( $ret == -1 ) {
		$this->set_flag_failed() ;
		$this->set_msg( 'fail to create jpeg', true ) ;
	}

	return $jpeg_param ;
}

// --- class end ---
}

?>