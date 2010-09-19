<?php
// $Id: image.php,v 1.4 2010/09/19 06:43:11 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-16 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-09-17 K.OHWADA
// webphoto_lib_readfile
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_image
//=========================================================
class webphoto_main_image extends webphoto_file_read
{
	var $_readfile_class ;
	var $_kind_class ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_image( $dirname, $trust_dirname )
{
	$this->webphoto_file_read( $dirname, $trust_dirname );

	$this->_readfile_class =& webphoto_lib_readfile::getInstance();
	$this->_kind_class =& webphoto_kind::getInstance();
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main_image( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// public
//---------------------------------------------------------
function main()
{
	$item_id   = $this->_post_class->get_post_get_int('item_id');
	$file_kind = $this->_post_class->get_post_get_int('file_kind');

	$item_row = $this->get_item_row( $item_id );
	if ( !is_array($item_row) ) {
		exit();
	}

	$file_row = $this->get_file_row( $item_row, $file_kind );
	if ( !is_array($file_row) ) {
		exit();
	}

	$ext  = $file_row['file_ext'] ;
	$mime = $file_row['file_mime'] ;
	$size = $file_row['file_size'] ;
	$file = $file_row['file_full'] ;

	if ( ! $this->_kind_class->is_image_ext( $ext ) ) {
		exit();
	}

	$this->_readfile_class->readfile_view( $file, $mime );

	exit();
}

// --- class end ---
}

?>