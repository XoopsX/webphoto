<?php
// $Id: pdf.php,v 1.3 2010/10/06 02:22:46 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// create_image() -> create_jpeg()
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
	return $this->match_ext_kind( $ext, _C_WEBPHOTO_MIME_KIND_OFFICE_PDF );
}

//---------------------------------------------------------
// create jpeg
//---------------------------------------------------------
function create_jpeg( $param )
{
	$src_file  = $param['src_file'] ;
	$jpeg_file = $param['jpeg_file'] ;
	return $this->_pdf_class->create_jpeg( $src_file, $jpeg_file );
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