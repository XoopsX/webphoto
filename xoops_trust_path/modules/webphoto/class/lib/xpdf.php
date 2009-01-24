<?php
// $Id: xpdf.php,v 1.1 2009/01/24 07:13:12 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_lib_xpdf
//=========================================================

//---------------------------------------------------------
// retune code
//   0  No error.
//   1  Error opening a PDF file.
//   2  Error opening an output file.
//   3  Error related to PDF permissions.
//   99 Other error.
//---------------------------------------------------------

class webphoto_lib_xpdf
{
	var $_cmd_path = null;
	var $_DEBUG    = false;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_xpdf()
{
	// dummy
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_xpdf();
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function set_cmd_path( $val )
{
	$this->_cmd_path = $val ;
}

function set_debug( $val )
{
	$this->_DEBUG = (bool)$val ;
}

function pdf_to_ppm( $pdf, $root, $first=1, $last=1, $dpi=100 )
{
	$src = $root.'-000001.ppm';

//	$option = '-f '.$first.' -l '.$last.' -r '.$dpi;
	$option = '-l '.$last.' -r '.$dpi;

	$ret = $this->pdftoppm( $pdf, $root, $option );
	if ( $ret == 0 ) {
		return $src;
	}
	return false ;
}

function pdf_to_text( $pdf, $txt, $enc='UTF-8' )
{
	$option = '-enc '.$enc;
	$ret = $this->pdftotext( $pdf, $txt, $option );
	if ( $ret == 0 ) {
		return true ;
	}
	return false ;
}

function pdftoppm( $pdf, $root, $option='' )
{
	$cmd = $this->_cmd_path .'pdftoppm '.$option.' '.$pdf.' '.$root ;
	exec( $cmd, $ret_array, $ret_code ) ;
	if ( $this->_DEBUG ) {
		echo $cmd."<br />\n";
	}
	return $ret_code;
}

function pdftotext( $pdf, $txt, $option='' )
{
	$cmd = $this->_cmd_path .'pdftotext '.$option.' '.$pdf.' '.$txt ;
	exec( $cmd, $ret_array, $ret_code ) ;
	if ( $this->_DEBUG ) {
		echo $cmd."<br />\n";
	}
	return $ret_code;
}

//---------------------------------------------------------
// version
//---------------------------------------------------------
function version( $path )
{
	$cmd = $path.'pdftoppm -v 2>&1' ;
	exec( $cmd , $ret_array ) ;
	if( count( $ret_array ) > 0 ) {
		$ret = true ;
		$msg = $ret_array[0];

	} else {
		$ret = false ;
		$msg = "Error: ".$path."pdftoppm can't be executed" ;
	}

	return array( $ret, $msg );
}

// --- class end ---
}

?>