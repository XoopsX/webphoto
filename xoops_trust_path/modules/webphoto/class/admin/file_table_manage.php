<?php
// $Id: file_table_manage.php,v 1.2 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-24 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_file_handler
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_file_table_manage
//=========================================================
class webphoto_admin_file_table_manage extends webphoto_lib_manage
{
	var $_URL_SIZE = 80;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_file_table_manage( $dirname , $trust_dirname )
{
	$this->webphoto_lib_manage( $dirname , $trust_dirname );
	$this->set_manage_handler( 
		webphoto_file_handler::getInstance( $dirname, $trust_dirname ) );
	$this->set_manage_title_by_name( 'FILE_TABLE_MANAGE' );

	$this->set_manage_list_column_array(
		array( 'file_name', 'file_mime' ) );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_file_table_manage( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	$this->_main();
}

//=========================================================
// override for caller
//=========================================================
function _build_row_by_post()
{
	$row = array(
		'file_id'          => $this->_post_class->get_post_get_int( 'file_id' ),
		'file_time_create' => $this->_post_class->get_post_int(  'file_time_create' ),
		'file_time_update' => $this->_post_class->get_post_int(  'file_time_update' ),
		'file_item_id'     => $this->_post_class->get_post_int(  'file_item_id' ),
		'file_kind'        => $this->_post_class->get_post_int(  'file_kind' ),
		'file_url'         => $this->_post_class->get_post_url(  'file_url' ),
		'file_path'        => $this->_post_class->get_post_text( 'file_path' ),
		'file_name'        => $this->_post_class->get_post_text( 'file_name' ),
		'file_ext'         => $this->_post_class->get_post_text( 'file_ext' ),
		'file_mime'        => $this->_post_class->get_post_text( 'file_mime' ),
		'file_medium'      => $this->_post_class->get_post_text( 'file_medium' ),
		'file_size'        => $this->_post_class->get_post_int(  'file_size' ),
		'file_width'       => $this->_post_class->get_post_int(  'file_width' ),
		'file_height'      => $this->_post_class->get_post_int(  'file_height' ),
		'file_duration'    => $this->_post_class->get_post_int(  'file_duration' ),
	);
	return $row;
}

//---------------------------------------------------------
// form
//---------------------------------------------------------
function _print_form( $row )
{
	echo $this->build_manage_form_begin( $row );

	echo $this->build_table_begin();
	echo $this->build_manage_header( );

	echo $this->build_manage_id();
	echo $this->build_comp_text( 'file_time_create' );
	echo $this->build_comp_text( 'file_time_update' );
	echo $this->_build_row_item_id() ;
	echo $this->build_comp_text( 'file_kind' );
	echo $this->build_comp_url(  'file_url',  $this->_URL_SIZE, true );
	echo $this->build_comp_text( 'file_path', $this->_URL_SIZE );
	echo $this->build_comp_text( 'file_name' );
	echo $this->build_comp_text( 'file_ext' );
	echo $this->build_comp_text( 'file_mime' );
	echo $this->build_comp_text( 'file_medium' );
	echo $this->build_comp_text( 'file_size' );
	echo $this->build_comp_text( 'file_width' );
	echo $this->build_comp_text( 'file_height' );
	echo $this->build_comp_text( 'file_duration' );

	echo $this->build_manage_submit();

	echo "</table></form>\n";
}

function _build_row_item_id()
{
	$name  = 'file_item_id' ;
	$value = intval( $this->get_row_by_key( $name ) );
	$ele   = $this->build_input_text( $name, $value );
	if ( $value > 0 ) {
		$url  = $this->_MODULE_URL.'/admin/index.php?fct=item_table_manage&op=form&id='.$value;
		$ele .= "<br />\n";
		$ele .= '<a href="'. $url .'">item table: '. $value .'</a>';
	}
	return $this->build_line_ele( $this->get_constant( $name ), $ele );
}

// --- class end ---
}

?>