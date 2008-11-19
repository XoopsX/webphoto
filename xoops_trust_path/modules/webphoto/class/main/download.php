<?php
// $Id: download.php,v 1.1 2008/11/19 10:26:45 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-16 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_download
//=========================================================
class webphoto_main_download extends webphoto_file_read
{
	var $_TIME_FAIL = 5;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_download( $dirname , $trust_dirname )
{
	$this->webphoto_file_read( $dirname, $trust_dirname );
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
	if ( !$this->check_perm( $item_row['item_perm_down'] ) ) {
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

	$this->zlib_off();
	$this->http_output_pass();
	session_cache_limiter('none');
	session_start();
	$this->header_down( $mime, $size, $name );

	readfile($file);
	exit();
}

// --- class end ---
}
?>