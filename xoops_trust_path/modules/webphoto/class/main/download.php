<?php
// $Id: download.php,v 1.4 2010/09/19 07:14:52 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-16 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-09-17 K.OHWADA
// webphoto_lib_download
// 2008-12-12 K.OHWADA
// check_perm -> check_item_perm
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_download
//=========================================================
class webphoto_main_download extends webphoto_file_read
{
	var $_readfile_class ;

	var $_TIME_FAIL = 5;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_download( $dirname , $trust_dirname )
{
	$this->webphoto_file_read( $dirname, $trust_dirname );

	$this->_readfile_class =& webphoto_lib_readfile::getInstance();
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main_download( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	$item_id   = $this->_post_class->get_post_get_int('item_id');
	$file_kind = $this->_post_class->get_post_get_int('file_kind');

	$item_row = $this->get_item_row( $item_id );
	if ( !is_array($item_row) ) {
		redirect_header( $this->_MODULE_URL, $this->_TIME_FAIL, $this->_error );
		exit();
	}

// check perm down
	if ( !$this->check_item_perm( $item_row['item_perm_down'] ) ) {
		redirect_header( $this->_MODULE_URL, $this->_TIME_FAIL, _NOPERM );
		exit();
	}

	$file_row = $this->get_file_row( $item_row, $file_kind );
	if ( !is_array($file_row) ) {
		redirect_header( $this->_MODULE_URL, $this->_TIME_FAIL, $this->_error );
		exit();
	}

	$name = $file_row['file_name'] ;
	$mime = $file_row['file_mime'] ;
	$size = $file_row['file_size'] ;
	$file = $file_row['file_full'] ;

	$this->_readfile_class->readfile_down( $file, $mime, $name );

	exit();
}

// --- class end ---
}
?>