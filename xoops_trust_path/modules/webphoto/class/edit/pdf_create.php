<?php
// $Id: pdf_create.php,v 1.6 2010/11/16 23:43:38 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-25 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-11 K.OHWADA
// changed create_param()
// 2010-10-01 K.OHWADA
// create_pdf() -> execute()
// 2009-11-11 K.OHWADA
// $trust_dirname
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_pdf_create
//=========================================================
class webphoto_edit_pdf_create extends webphoto_edit_base_create
{
	var $_ext_class ;

	var $_param_ext    = 'pdf' ;
	var $_param_dir    = 'pdfs';
	var	$_param_mime   = 'application/pdf' ;
	var $_param_medium = '' ;
	var $_param_kind   = _C_WEBPHOTO_FILE_KIND_PDF ;
	var $_msg_created  = 'create pdf' ;
	var $_msg_failed   = 'fail to create pdf' ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_pdf_create( $dirname , $trust_dirname )
{
	$this->webphoto_edit_base_create( $dirname , $trust_dirname );

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

// return input file is pdf 
	if ( $this->is_pdf_ext( $src_ext ) ) {
		return null ;
	}

	$pdf_param = $this->create_pdf( $item_id, $src_file, $src_ext ) ;
	if ( !is_array($pdf_param) ) {
		return null;
	}

	return $pdf_param ;
}

function create_pdf( $item_id, $src_file, $src_ext )
{
	$name_param = $this->build_name_param( $item_id );
	$file  = $name_param['file'] ;

	$param = array(
		'src_file' => $src_file ,
		'src_ext'  => $src_ext ,
		'pdf_file' => $file ,
	);

	$ret = $this->_ext_class->execute( 'pdf', $param ) ;

	return $this->build_result( $ret, $name_param );
}

// --- class end ---
}

?>