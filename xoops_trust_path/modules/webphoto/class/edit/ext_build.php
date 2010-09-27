<?php
// $Id: ext_build.php,v 1.1 2010/09/27 03:44:45 ohwada Exp $

//=========================================================
// webphoto module
// 2010-09-20 K.OHWADA
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
function get_duration_size( $row, $src_file )
{
	$param = $row ;
	$param['src_file'] = $src_file ;
	$param['src_ext']  = $row['item_ext'] ;

	$extra_param = $this->_ext_class->execute( 'duration_size' ,$param );

	if ( is_array($extra_param) ) {
		$this->set_result( $extra_param ) ;
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

function get_file_full_by_key( $arr, $key )
{
	return $this->_file_handler->get_file_full_by_key( $arr, $key );
}

// --- class end ---
}

?>