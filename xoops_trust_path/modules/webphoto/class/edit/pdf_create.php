<?php
// $Id: pdf_create.php,v 1.1 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_pdf_create
//=========================================================
class webphoto_edit_pdf_create extends webphoto_edit_base_create
{
	var $_ext_class ;

	var $_SUB_DIR_PDFS = 'pdfs';
	var $_EXT_PDF      = 'pdf';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_pdf_create( $dirname , $trust_dirname )
{
	$this->webphoto_edit_base_create( $dirname );

	$this->_ext_class =& webphoto_ext::getInstance( $dirname , $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_pdf_create( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// create pdf
//---------------------------------------------------------
function create_param( $param )
{
	$this->clear_msg_array();

	$item_id  = $param['item_id'];
	$src_file = $param['src_file'];
	$src_ext  = $param['src_ext'];
	$src_kind = $param['src_kind'];

	if ( ! $this->is_general_kind( $src_kind ) ) {
		return null ;
	}

// return input file is pdf 
	if ( $this->is_pdf_ext( $src_ext ) ) {
		return null ;
	}

	$pdf_param = $this->create_pdf( $item_id, $src_file ) ;
	if ( !is_array($pdf_param) ) {
		return null;
	}

	return $pdf_param ;
}

function create_pdf( $item_id, $src_file )
{
	$this->_flag_created = false ;
	$this->_flag_failed  = false ;

	$pdf_param = null ;

	$name_param =$this->build_random_name_param( $item_id, $this->_EXT_PDF, $this->_SUB_DIR_PDFS );
	$name  = $name_param['name'] ;
	$path  = $name_param['path'] ;
	$file  = $name_param['file'] ;
	$url   = $name_param['url']  ;

	$param = array(
		'src_file' => $src_file ,
		'pdf_file' => $file ,
	);

	$ret = $this->_ext_class->create_pdf( $item_id, $param ) ;

// created
	if ( $ret == 1 ) {
		$this->set_flag_created() ;
		$this->set_msg( 'create pdf' );
		$pdf_param = array(
			'url'    => $url ,
			'file'   => $file ,
			'path'   => $path ,
			'name'   => $name ,
			'ext'    => $this->_PDF_EXT ,
			'mime'   => $this->_PDF_MIME ,
			'medium' => $this->_PDF_MEDIUM ,
			'size'   => filesize( $pdf_file ) ,
			'kind'   => _C_WEBPHOTO_FILE_KIND_PDF ,
		);

// failed
	} elseif ( $ret == -1 ) {
		$this->set_flag_failed() ;
		$this->set_msg( 'fail to create pdf', true ) ;
	}

	return $pdf_param ;
}

// --- class end ---
}

?>