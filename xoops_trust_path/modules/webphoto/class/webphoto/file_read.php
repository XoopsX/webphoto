<?php
// $Id: file_read.php,v 1.1 2008/11/19 10:27:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-16 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_file_read
//=========================================================
class webphoto_file_read extends webphoto_lib_base
{
	var $_item_handler;
	var $_file_handler;
	var $_multibyte_class;
	var $_post_class;

	var $_error = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_file_read( $dirname , $trust_dirname )
{
	$this->webphoto_lib_base( $dirname, $trust_dirname );

	$this->_item_handler    =& webphoto_item_handler::getInstance( $dirname );
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
function get_item_row( $item_id )
{
	$item_row = $this->_item_handler->get_row_by_id( $item_id );
	if ( ! is_array($item_row ) ) {
		$this->_error = $this->get_constant( 'NOMATCH_PHOTO' ) ;
		return false;
	}

	$status = $item_row['item_status'] ;

	if ( $status <= 0 ) {
		$this->_error = $this->get_constant( 'NOMATCH_PHOTO' ) ;
		return false;
	}

	return $item_row ;
}

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

	if ( ! file_exists($file) ) {
		$this->_error = $this->get_constant( 'NO_FILE' ) ;
		return false;
	}

	$file_row['file_full'] = $file;
	return $file_row ;
}

function check_perm( $perm )
{
	return $this->_item_handler->check_perm( $perm, $this->_xoops_groups );
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