<?php
// $Id: jpeg_create.php,v 1.4 2010/10/06 02:22:46 ohwada Exp $

//=========================================================
// webphoto module
// 2009-10-25 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
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
	var $_image_create_class;

	var $_is_cmyk = false;
	var $_rotate  = 0;

	var $_SUB_DIR_JPEGS = 'jpegs';

	var $_JPEG_EXT    = 'jpg';
	var $_JPEG_MIME   = 'image/jpeg';
	var $_JPEG_MEDIUM = 'image';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_jpeg_create( $dirname , $trust_dirname )
{
	$this->webphoto_edit_base_create( $dirname , $trust_dirname );

	$this->_ext_class 
		=& webphoto_ext::getInstance( $dirname , $trust_dirname );

	$this->_image_create_class =& webphoto_image_create::getInstance( $dirname );

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
	$src_file = $param['src_file'] ;
	$src_ext  = $param['src_ext'] ;
	$pdf_file = isset($param['pdf_file']) ? $param['pdf_file'] : null;
	$rotate   = isset($param['rotate_angle']) ?  $param['rotate_angle'] : 0 ;

	$this->_is_cmyk = false;
	$this->_rotate  = 0 ;

// set flag if image, rotate
	if ( $this->is_image_ext( $src_ext ) ) {
		if ( $rotate ) {
			$this->_rotate = $rotate ;
		}
	}

// set flag if jpeg, cmyk
	if ( $this->is_jpeg_ext( $src_ext ) ) {
		if ( $this->is_image_cmyk( $src_file ) ) {
			$this->_is_cmyk = true;
		}
	}

	$jpeg_param = $this->create_jpeg( $item_id, $src_file, $src_ext, $pdf_file ) ;
	if ( !is_array($jpeg_param) ) {
		return null;
	}

	return $jpeg_param ;
}

function create_jpeg( $item_id, $src_file, $src_ext, $pdf_file )
{
	$this->_flag_created = false ;
	$this->_flag_failed  = false ;

	$jpeg_param = null ;

	$name_param =$this->build_random_name_param( $item_id, $this->_JPEG_EXT, $this->_SUB_DIR_JPEGS );
	$name  = $name_param['name'] ;
	$path  = $name_param['path'] ;
	$file  = $name_param['file'] ;
	$url   = $name_param['url']  ;

	$param = array(
		'src_file'  => $src_file ,
		'src_ext'   => $src_ext ,
		'pdf_file'  => $pdf_file ,
		'jpeg_file' => $file ,
		'is_cmyk'   => $this->_is_cmyk ,
		'rotate'    => $this->_rotate ,
	);

	$ret = $this->_ext_class->execute( 'jpeg', $param ) ;

// created
	if ( $ret == 1 ) {
		$this->set_flag_created() ;
		$this->set_msg( 'create jpeg' );
		$jpeg_param = $this->build_jpeg_param( $name_param );

// failed
	} elseif ( $ret == -1 ) {
		$this->set_flag_failed() ;
		$this->set_msg( 'fail to create jpeg', true ) ;
	}

	return $jpeg_param ;
}

function build_jpeg_param( $name_param )
{
	$name  = $name_param['name'] ;
	$path  = $name_param['path'] ;
	$file  = $name_param['file'] ;
	$url   = $name_param['url']  ;

	$param = array(
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
	return $param;
}

function is_cmyk()
{
	return $this->_is_cmyk;
}

//---------------------------------------------------------
// create copy param (for jpeg)
//---------------------------------------------------------
function create_copy_param( $param )
{
	$this->clear_msg_array();

	$item_id  = $param['item_id'];
	$src_file = $param['src_file'] ;

	$name_param = $this->build_random_name_param( $item_id, $this->_JPEG_EXT, $this->_SUB_DIR_JPEGS );
	$jpeg_file  = $name_param['file'] ;

	copy( $src_file, $jpeg_file );

	if ( !file_exists($jpeg_file) ) {
		$this->set_flag_failed() ;
		$this->set_msg( 'fail to create jpeg', true ) ;
		return false;
	}

	$this->set_flag_created() ;
	$this->set_msg( 'create jpeg' );
	$jpeg_param = $this->build_jpeg_param( $name_param );
	return $jpeg_param ;

}

//---------------------------------------------------------
// create image param (for gif, png)
//---------------------------------------------------------
function create_image_param( $param )
{
	$this->clear_msg_array();

	$item_id  = $param['item_id'];
	$src_file = $param['src_file'] ;

	$name_param = $this->build_random_name_param( $item_id, $this->_JPEG_EXT, $this->_SUB_DIR_JPEGS );
	$jpeg_file  = $name_param['file'] ;

	$this->_image_create_class->cmd_rotate( $src_file, $jpeg_file, 0 );

	if ( !file_exists($jpeg_file) ) {
		$this->set_flag_failed() ;
		$this->set_msg( 'fail to create jpeg', true ) ;
		return false;
	}

	$this->set_flag_created() ;
	$this->set_msg( 'create jpeg' );
	$jpeg_param = $this->build_jpeg_param( $name_param );
	return $jpeg_param ;

}

// --- class end ---
}

?>