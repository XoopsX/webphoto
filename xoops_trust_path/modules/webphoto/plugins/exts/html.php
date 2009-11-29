<?php
// $Id: html.php,v 1.2 2009/11/29 07:34:23 ohwada Exp $

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
class webphoto_ext_html extends webphoto_ext_base
{
	var $_HTML_EXTS = array('html', 'htm');

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_ext_html( $dirname, $trust_dirname )
{
	$this->webphoto_ext_base( $dirname, $trust_dirname );
}

//---------------------------------------------------------
// check ext
//---------------------------------------------------------
function is_ext( $ext )
{
	return $this->is_html_ext( $ext );
}

function is_html_ext( $ext )
{
	return $this->is_ext_in_array( $ext, $this->_HTML_EXTS );
}

//---------------------------------------------------------
// create image
//---------------------------------------------------------

//---------------------------------------------------------
// text content
//---------------------------------------------------------
function get_text_content( $param )
{
	$file_cont = isset($param['file_cont'])  ? $param['file_cont'] : null ;

	if ( !is_file($file_cont) ) {
		return false;
	}

	$text = file_get_contents( $file_cont );

	$encoding = $this->find_html_encoding( $text, true );
	if ( $encoding) {
		$text = $this->_multibyte_class->convert_encoding( $text, _CHARSET, $encoding );
	}

	$text = $this->_multibyte_class->build_plane_text( $text );
	return $text;
}

//---------------------------------------------------------
// find HTML encoding
// < meta http-equiv="Content-Type" content="text/html;charset=UTF-8" >
//---------------------------------------------------------
function find_html_encoding( $text, $flag_auto=false )
{
	$encoding = null;
	if ( preg_match('/<(meta.*Content-Type.*)>/is', $text, $match1) ) {
		if ( preg_match('/charset=([a-zA-Z0-9\-\_]+)/is', $match1[1], $match2) ) {
			$encoding = trim($match2[1]);
		}
	}
	if ( empty($encoding) && $flag_auto ) {
		$encoding = $this->_multibyte_class->m_mb_detect_encoding( $text );
	}
	return $encoding;
}

// --- class end ---
}

?>