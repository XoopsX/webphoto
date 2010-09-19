<?php
// $Id: file_read.php,v 1.5 2010/09/19 06:43:11 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-16 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-09-17 K.OHWADA
// BUG: slash '/' is unnecessary
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_file_handler
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

	$this->_file_handler    =& webphoto_file_handler::getInstance( 
		$dirname, $trust_dirname );
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

// BUG: slash '/' is unnecessary
//	$file = XOOPS_ROOT_PATH .'/'. $path ;
	$file = XOOPS_ROOT_PATH . $path ;

	if ( empty($path) || !file_exists($file) ) {
		$this->_error = $this->get_constant( 'NO_FILE' ) ;
		return false;
	}

	$file_row['file_full'] = $file;
	return $file_row ;
}

// --- class end ---
}
?>