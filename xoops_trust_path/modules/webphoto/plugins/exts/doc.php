<?php
// $Id: doc.php,v 1.1 2009/01/29 04:28:09 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-25 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_ext_doc
//=========================================================
class webphoto_ext_doc extends webphoto_ext_base
{
	var $_pdf_class;
	var $_jod_class;

	var $_DOC_EXTS = array( 'doc' );

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_ext_doc( $dirname )
{
	$this->webphoto_ext_base( $dirname );

	$this->_pdf_class =& webphoto_pdf::getInstance( $dirname );
	$this->_jod_class =& webphoto_jodconverter::getInstance( $dirname );

	$this->set_debug_by_name( 'DOC' );
}

//---------------------------------------------------------
// check ext
//---------------------------------------------------------
function is_ext( $ext )
{
	return $this->is_doc_ext( $ext );
}

function is_doc_ext( $ext )
{
	return $this->is_ext_in_array( $ext, $this->_DOC_EXTS );
}

//---------------------------------------------------------
// create pdf
//---------------------------------------------------------
function create_pdf( $param )
{
	$src_file = $param['src_file'] ;
	$pdf_file = $param['pdf_file'] ;
	return $this->_jod_class->create_pdf( $src_file, $pdf_file );
}

//---------------------------------------------------------
// create image
//---------------------------------------------------------
function create_image( $param )
{
	$item_id  = $param['item_id'];
	$item_ext = $param['item_ext'];
	$file_pdf = isset($param['file_pdf']) ? $param['file_pdf'] : null ;
	return $this->_pdf_class->create_image( $item_id, $file_pdf, $item_ext );
}

//---------------------------------------------------------
// text content
//---------------------------------------------------------
function get_text_content( $param )
{
	$file_cont = isset($param['file_cont']) ? $param['file_cont'] : null ;
	return $this->_jod_class->get_text_content_for_doc( $file_cont );
}

// --- class end ---
}

?>