<?php
// $Id: pdf.php,v 1.2 2009/11/29 07:34:23 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// $trust_dirname 
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_ext_pdf
//=========================================================
class webphoto_ext_pdf extends webphoto_ext_base
{
	var $_pdf_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_ext_pdf( $dirname, $trust_dirname )
{
	$this->webphoto_ext_base( $dirname, $trust_dirname );

	$this->_pdf_class 
		=& webphoto_pdf::getInstance( $dirname, $trust_dirname );

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
	return $this->match_ext_kind( $ext, _C_WEBPHOTO_MIME_KIND_OFFICE_PDF );
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