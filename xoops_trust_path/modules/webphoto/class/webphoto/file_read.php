<?php
// $Id: file_read.php,v 1.7 2011/05/10 02:56:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-16 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-05-01 K.OHWADA
// webphoto_lib_multibyte -> webphoto_multibyte
// 2010-11-11 K.OHWADA
// get_extend_row_by_id()
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
	var $_utility_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_file_read( $dirname , $trust_dirname )
{
	$this->webphoto_item_public( $dirname, $trust_dirname );

	$this->_file_handler    =& webphoto_file_handler::getInstance( 
		$dirname, $trust_dirname );
	$this->_multibyte_class =& webphoto_multibyte::getInstance();
	$this->_post_class      =& webphoto_lib_post::getInstance();
	$this->_utility_class   =& webphoto_lib_utility::getInstance();

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
function get_file_row( $item_row, $kind )
{
	$file_id = $this->_item_handler->build_value_fileid_by_kind( 
		$item_row, $kind );

	if ( $file_id == 0 ) {
		$this->_error = $this->get_constant( 'NO_FILE' ) ;
		return false;
	}

	$file_row = $this->_file_handler->get_extend_row_by_id( $file_id );
	if ( !is_array($file_row) ) {
		$this->_error = $this->get_constant( 'NO_FILE' ) ;
		return false;
	}

	$exists = $file_row['full_path_exists'];

	if ( ! $exists ) {
		$this->_error = $this->get_constant( 'NO_FILE' ) ;
		return false;
	}

	return $file_row ;
}

// --- class end ---
}
?>