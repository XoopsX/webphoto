<?php
// $Id: pdf.php,v 1.1 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_ext_pdf
//=========================================================
class webphoto_ext_pdf extends webphoto_ext_base
{
	var $_pdf_class;

	var $_PDF_EXTS = array('pdf');

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_ext_pdf( $dirname )
{
	$this->webphoto_ext_base( $dirname );

	$this->_pdf_class =& webphoto_pdf::getInstance( $dirname );

	$this->set_debug_by_name( 'PDF' );
}

//---------------------------------------------------------
// check ext
//---------------------------------------------------------
function is_ext( $ext )
{
	return $this->is_pdf_ext( $ext );
}

function is_pdf_ext( $ext )
{
	return $this->is_ext_in_array( $ext, $this->_PDF_EXTS );
}

//---------------------------------------------------------
// create image
//---------------------------------------------------------
function create_image( $param )
{
	$item_id  = $param['item_id'];
	$src_file = $param['src_file'];

	return $this->_pdf_class->create_image( $item_id, $src_file );
}

//---------------------------------------------------------
// text content
//---------------------------------------------------------
function get_text_content( $param )
{
	$file = isset($param['file_cont']) ? $param['file_cont'] : null ;
	return $this->_pdf_class->get_text_content( $file );
}

// --- class end ---
}

?>