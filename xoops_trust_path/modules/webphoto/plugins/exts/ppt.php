<?php
// $Id: ppt.php,v 1.1 2009/01/29 04:28:09 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-25 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_ext_ppt
//=========================================================
class webphoto_ext_ppt extends webphoto_ext_base
{
	var $_pdf_class;
	var $_jod_class;

	var $_PPT_EXTS = array( 'ppt' );

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_ext_ppt( $dirname )
{
	$this->webphoto_ext_base( $dirname );

	$this->_pdf_class =& webphoto_pdf::getInstance( $dirname );
	$this->_jod_class =& webphoto_jodconverter::getInstance( $dirname );

	$this->set_debug_by_name( 'PPT' );
}

//---------------------------------------------------------
// check ext
//---------------------------------------------------------
function is_ext( $ext )
{
	return $this->is_ppt_ext( $ext );
}

function is_ppt_ext( $ext )
{
	return $this->is_ext_in_array( $ext, $this->_PPT_EXTS );
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
// create swf
//---------------------------------------------------------
function create_swf( $param )
{
	$src_file = $param['src_file'] ;
	$swf_file = $param['swf_file'] ;
	return $this->_jod_class->create_swf( $src_file, $swf_file );
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
	return $this->_jod_class->get_text_content_for_xls_ppt( $file_cont );
}

// --- class end ---
}

?>