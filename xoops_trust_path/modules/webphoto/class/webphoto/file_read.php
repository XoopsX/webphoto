<?php
// $Id: file_read.php,v 1.3 2008/12/18 13:23:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-16 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-12-12 K.OHWADA
// webphoto_item_public
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_file_read
//=========================================================
class webphoto_file_read extends webphoto_item_public
{
	var $_file_handler;
	var $_multibyte_class;
	var $_post_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_file_read( $dirname , $trust_dirname )
{
	$this->webphoto_item_public( $dirname, $trust_dirname );

	$this->_file_handler    =& webphoto_file_handler::getInstance( $dirname );
	$this->_multibyte_class =& webphoto_lib_multibyte::getInstance();
	$this->_post_class      =& webphoto_lib_post::getInstance();
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_file_read( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function get_file_row( $item_row, $file_kind )
{
	$item_file_id = 'item_file_id_'.$file_kind ;

	if ( isset( $item_row[ $item_file_id ] ) ) { 
		$file_id = $item_row[ $item_file_id ] ;
	} else {
		$this->_error = $this->get_constant( 'NO_FILE' ) ;
		return false;
	}

	$file_row = $this->_file_handler->get_row_by_id( $file_id );
	if ( ! is_array($file_row ) ) {
		$this->_error = $this->get_constant( 'NO_FILE' ) ;
		return false;
	}

	$path = $file_row['file_path'] ;
	$file = XOOPS_ROOT_PATH .'/'. $path ;

	if ( empty($path) || !file_exists($file) ) {
		$this->_error = $this->get_constant( 'NO_FILE' ) ;
		return false;
	}

	$file_row['file_full'] = $file;
	return $file_row ;
}

function http_output_pass()
{
	return $this->_multibyte_class->m_mb_http_output('pass');
}

function zlib_off()
{
	if (ini_get('zlib.output_compression')) {
		ini_set('zlib.output_compression', 'Off'); 
	}
}

function header_view( $mime, $size )
{
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	header('Cache-Control: no-store, no-cache, max-age=1, s-maxage=1, must-revalidate, post-check=0, pre-check=0');
	header('Content-Type: '. $mime );
	header('Content-Length: '. $size );
}

function header_down( $mime, $size, $name )
{
	header('Pragma: public');
	header('Cache-Control: must-revaitem_idate, post-check=0, pre-check=0');
	header('Content-Description: File Transfer');
	header('Content-Type: '. $mime );
	header('Content-Length: '. $size );
	header('Content-Disposition: attachment; filename=' . $name );
}

function header_xml()
{
	header ('Content-Type:text/xml; charset=utf-8');
}

// --- class end ---
}
?>