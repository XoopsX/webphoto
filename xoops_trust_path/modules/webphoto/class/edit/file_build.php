<?php
// $Id: file_build.php,v 1.1 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_file_build
//=========================================================
class webphoto_edit_file_build extends webphoto_edit_base_create
{
	var $_exif_class;
	var $_ext_class;
	var $_mime_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_file_build( $dirname , $trust_dirname )
{
	$this->webphoto_edit_base_create( $dirname );

	$this->_exif_class =& webphoto_exif::getInstance();
	$this->_ext_class  =& webphoto_ext::getInstance( $dirname , $trust_dirname );
	$this->_mime_class =& webphoto_mime::getInstance( $dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_file_build( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// item extention
//---------------------------------------------------------
function build_exif_duration( $row, $src_file )
{
	$this->clear_msg_array();

	$param = $row ;
	$param['src_file'] = $src_file ;
	$param['src_ext']  = $row['item_ext']  ;
	$param['src_kind'] = $row['item_kind'] ;

	$row = $this->build_row_exif(     $row, $param );
	$row = $this->build_row_duration( $row, $param );
	return $row ;
}

function build_content( $row, $file_cont, $file_pdf )
{
	$this->clear_msg_array();

	$param = $row ;
	$param['src_ext']   = $row['item_ext'] ;
	$param['file_cont'] = $file_cont ;
	$param['file_pdf']  = $file_pdf  ;

	$row = $this->build_row_content( $row, $param );
	return $row ;
}

function build_row_exif( $row, $param )
{
	$src_file = $param['src_file'];

	if ( ! $this->is_image_kind( $row['item_kind'] ) ) {
		return $row ;
	}

	$flag = $this->_exif_class->build_row_exif( $row, $src_file );
	if ( $flag == 0 ) {
		return $row ;

	} elseif ( $flag == 2 ) {
		$this->set_msg( 'get exif' ) ;

	} else {
		$this->set_msg( 'no exif' )  ;
	}

	$row = $this->_exif_class->get_row();
	return $row ;
}

function build_row_duration( $row, $param )
{
	$extra_param = $this->_ext_class->get_duration_size( $param );
	if ( is_array($extra_param) ) {
		$this->set_msg( 'get duration' ) ;
		$row['item_duration'] = $extra_param['duration'] ;
		$row['item_width']    = $extra_param['width'] ;
		$row['item_height']   = $extra_param['height'] ;
	}
	return $row ;
}

function build_row_content( $row, $param )
{
	$content = $this->_ext_class->get_text_content( $param );
	if ( $content ) {
		$row['item_content'] = $content ;
		$this->set_msg( 'get content' )  ;
	}
	return $row ;
}

// --- class end ---
}

?>