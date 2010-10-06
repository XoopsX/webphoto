<?php
// $Id: ext_build.php,v 1.2 2010/10/06 02:22:46 ohwada Exp $

//=========================================================
// webphoto module
// 2010-10-01 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_ext_build
//=========================================================
class webphoto_edit_ext_build extends webphoto_edit_base_create
{
	var $_ext_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_ext_build( $dirname , $trust_dirname )
{
	$this->webphoto_edit_base_create( $dirname , $trust_dirname );

	$this->_ext_class  
		=& webphoto_ext::getInstance( $dirname , $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_ext_build( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// function
//---------------------------------------------------------
function get_exif( $row, $src_file )
{
	$param = $this->_ext_class->execute(
		'exif', 
		$this->build_param( $row, $src_file ) );

	if ( is_array($param) ) {
		$this->set_result( $param ) ;
		return 1;
	}

	return 0;
}

function get_video_info( $row, $src_file )
{
	$param = $this->_ext_class->execute(
		'video_info', 
		$this->build_param( $row, $src_file ) );

	if ( is_array($param) ) {
		$this->set_result( $param ) ;
		return 1;
	}

	return 0;
}

function get_text_content( $row, $file_id_array )
{
	$file_cont = $this->get_file_full_by_key( $file_id_array, 'cont_id' ) ;
	$file_pdf  = $this->get_file_full_by_key( $file_id_array, 'pdf_id' ) ;

	$param = $row ;
	$param['src_ext']   = $row['item_ext'] ;
	$param['file_cont'] = $file_cont ;
	$param['file_pdf']  = $file_pdf  ;

	$extra_param = $this->_ext_class->execute( 'text_content', $param );

	if ( isset( $extra_param['content'] ) ) {
		$this->set_result( $extra_param['content'] ) ;
		return 1;

	} elseif ( isset( $extra_param['errors'] ) ) {
		$this->set_error( $extra_param['errors'] );
		return -1;
	}

	return 0;
}

//---------------------------------------------------------
// uitlity
//---------------------------------------------------------
function build_param( $row, $src_file )
{
	$param = $row ;
	$param['src_ext']  = $row['item_ext'] ;
	$param['src_file'] = $src_file ;
	return $param;
}

function get_file_full_by_key( $arr, $key )
{
	return $this->_file_handler->get_file_full_by_key( $arr, $key );
}

// --- class end ---
}

?>