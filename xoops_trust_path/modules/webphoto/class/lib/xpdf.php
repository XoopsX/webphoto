<?php
// $Id: xpdf.php,v 1.3 2010/03/31 02:49:06 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-03-24 K.OHWADA
// pdftops()
//---------------------------------------------------------

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
	var $_cmd_path  = null;
	var $_msg_array = array();
	var $_DEBUG     = false;

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
	$this->clear_msg_array();
	$src = $root.'-000001.ppm';
	$option = '-f '.$first.' -l '.$last.' -r '.$dpi;

	$ret = $this->pdftoppm( $pdf, $root, $option );
	if ( $ret == 0 ) {
		return $src;
	}
	return false ;
}

function pdf_to_ps( $pdf, $ps, $first=1, $last=1 )
{
	$this->clear_msg_array();
	$option = '-f '.$first.' -l '.$last;
	$ret = $this->pdftops( $pdf, $ps, $option );
	if ( $ret == 0 ) {
		return $ps;
	}
	return false ;
}

function pdf_to_text( $pdf, $txt, $enc='UTF-8' )
{
	$this->clear_msg_array();
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
	return $this->exec_cmd( $cmd );
}

function pdftops( $pdf, $ps, $option='' )
{
	$cmd = $this->_cmd_path .'pdftops '.$option.' '.$pdf.' '.$ps ;
	return $this->exec_cmd( $cmd );
}

function pdftotext( $pdf, $txt, $option='' )
{
	$cmd = $this->_cmd_path .'pdftotext '.$option.' '.$pdf.' '.$txt ;
	return $this->exec_cmd( $cmd );
}

function exec_cmd( $cmd )
{
	exec( "$cmd 2>&1", $ret_array, $ret_code ) ;
	if ( $this->_DEBUG ) {
		echo $cmd."<br />\n";
	}
	$this->set_msg( $cmd );
	$this->set_msg( $ret_array );
	return $ret_code;
}

//---------------------------------------------------------
// version
//---------------------------------------------------------
function version( $path )
{
	$cmd = $path.'pdftotext -v 2>&1' ;
	exec( $cmd , $ret_array ) ;
	if( count( $ret_array ) > 0 ) {
		$ret = true ;
		$msg = $ret_array[0];

	} else {
		$ret = false ;
		$msg = "Error: ".$path."pdftotext can't be executed" ;
	}

	return array( $ret, $msg );
}

//---------------------------------------------------------
// msg
//---------------------------------------------------------
function clear_msg_array()
{
	$this->_msg_array = array();
}

function get_msg_array()
{
	return $this->_msg_array;
}

function set_msg( $ret_array )
{
	if ( is_array($ret_array) ) {
		foreach( $ret_array as $line ) {
			$this->_msg_array[] = $line ;
		}
	} else {
		$this->_msg_array[] = $ret_array ;
	}
}

// --- class end ---
}

?>