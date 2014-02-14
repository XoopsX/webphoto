<?php
// $Id: jpeg_create.php,v 1.6 2010/11/16 23:43:38 ohwada Exp $

//=========================================================
// webphoto module
// 2009-10-25 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-11 K.OHWADA
// build_image_file_param();
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

	var $_param_ext    = 'jpg' ;
	var $_param_dir    = 'jpegs';
	var	$_param_mime   = 'image/jpeg' ;
	var $_param_medium = 'image' ;
	var $_param_kind   = _C_WEBPHOTO_FILE_KIND_JPEG ;
	var $_msg_created  = 'create jpeg' ;
	var $_msg_failed   = 'fail to create jpeg' ;

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
	$name_param =$this->build_name_param( $item_id );
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

	return $this->build_result( $ret, $name_param );
}

function is_cmyk()
{
	return $this->_is_cmyk;
}

//---------------------------------------------------------
// create image param (for gif, png)
//---------------------------------------------------------
function create_image_param( $param )
{
	$item_id  = $param['item_id'];
	$src_file = $param['src_file'] ;

	$name_param = $this->build_name_param( $item_id );
	$jpeg_file  = $name_param['file'] ;

	$this->_image_create_class->cmd_rotate( $src_file, $jpeg_file, 0 );

	return $this->build_copy_result( $name_param );
}

// --- class end ---
}

?>