<?php
// $Id: item_table_manage.php,v 1.2 2008/08/25 23:33:51 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-24 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_item_table_manage
//=========================================================
class webphoto_admin_item_table_manage extends webphoto_lib_manage
{
	var $_build_class;
	var $_delete_class;

	var $_URL_SIZE = 80;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_item_table_manage( $dirname , $trust_dirname )
{
	$this->webphoto_lib_manage( $dirname , $trust_dirname );
	$this->set_manage_handler( webphoto_item_handler::getInstance( $dirname ) );
	$this->set_manage_title_by_name( 'ITEM_TABLE_MANAGE' );

	$this->set_manage_list_column_array(
		array( 'item_title', 'item_uid' ) );

	$this->_build_class   =& webphoto_photo_build::getInstance( $dirname );
	$this->_delete_class  =& webphoto_photo_delete::getInstance( $dirname );

}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_item_table_manage( $dirname , $trust_dirname );
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
function _build_row_add()
{
	$row = $this->_build_row_by_post();
	$row['item_search'] = $this->_build_class->build_search( $row );
	return $row;
}

function _build_row_edit()
{
	$row = $this->_build_row_by_post();
	$row['item_search'] = $this->_build_class->build_search( $row );
	return $row;
}

function _build_row_by_post()
{
	$row = array(
		'item_datetime'       => $this->_manage_handler->build_datetime_by_post( 'item_datetime' ) ,

		'item_id'             => $this->_post_class->get_post_get_int( 'item_id' ),
		'item_time_create'    => $this->_post_class->get_post_int(   'item_time_create' ),
		'item_time_update'    => $this->_post_class->get_post_int(   'item_time_update' ),
		'item_cat_id'         => $this->_post_class->get_post_int(   'item_cat_id' ),
		'item_gicon_id'       => $this->_post_class->get_post_int(   'item_gicon_id' ),
		'item_uid'            => $this->_post_class->get_post_int(   'item_uid' ),
		'item_kind'           => $this->_post_class->get_post_int(   'item_kind' ),
		'item_ext'            => $this->_post_class->get_post_text(  'item_ext' ),
		'item_title'          => $this->_post_class->get_post_text(  'item_title' ),
		'item_place'          => $this->_post_class->get_post_text(  'item_place' ),
		'item_equipment'      => $this->_post_class->get_post_text(  'item_equipment' ),
		'item_gmap_latitude'  => $this->_post_class->get_post_float( 'item_gmap_latitude' ),
		'item_gmap_longitude' => $this->_post_class->get_post_float( 'item_gmap_longitude' ),
		'item_gmap_zoom'      => $this->_post_class->get_post_int(   'item_gmap_zoom' ),
		'item_gmap_type'      => $this->_post_class->get_post_int(   'item_gmap_type' ),
		'item_perm_read'      => $this->_post_class->get_post_text(  'item_perm_read' ),
		'item_status'         => $this->_post_class->get_post_int(   'item_status' ),
		'item_hits'           => $this->_post_class->get_post_int(   'item_hits' ),
		'item_exif'           => $this->_post_class->get_post_text(  'item_exif' ),
		'item_description'    => $this->_post_class->get_post_text(  'item_description' ),

//		'item_rating'         => $this->_post_class->get_post_float( 'item_rating' ),
//		'item_votes'          => $this->_post_class->get_post_int(   'item_votes' ),
//		'item_comments'       => $this->_post_class->get_post_int(   'item_comments' ),
//		'item_search'         => $this->_post_class->get_post_text(  'item_search' ),

	);

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_FILE_ID; $i++ ) 
	{
		$name = 'item_file_id_'.$i;
		$row[ $name ] = $this->_post_class->get_post_int( $name );
	}

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_TEXT; $i++ ) 
	{
		$name = 'item_text_'.$i;
		$row[ $name ] = $this->_post_class->get_post_text( $name );
	}

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

	echo $this->_build_row_manage_id();
	echo $this->build_comp_text( 'item_title' );
	echo $this->build_comp_text( 'item_time_create' );
	echo $this->build_comp_text( 'item_time_update' );
	echo $this->build_comp_text( 'item_cat_id' );
	echo $this->build_comp_text( 'item_uid' );

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_FILE_ID; $i++ ) {
		echo $this->_build_row_file_id( $i );
	}

	echo $this->build_comp_text( 'item_kind' );
	echo $this->build_comp_text( 'item_ext' );
	echo $this->build_comp_text( 'item_datetime' );
	echo $this->build_comp_text( 'item_place' );
	echo $this->build_comp_text( 'item_equipment' );
	echo $this->build_comp_text( 'item_gicon_id' );
	echo $this->build_comp_text( 'item_gmap_latitude' );
	echo $this->build_comp_text( 'item_gmap_longitude' );
	echo $this->build_comp_text( 'item_gmap_zoom' );
	echo $this->build_comp_text( 'item_gmap_type' );
	echo $this->build_comp_text( 'item_perm_read' );
	echo $this->build_comp_text( 'item_status' );
	echo $this->build_comp_text( 'item_hits' );

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_TEXT; $i++ ) {
		echo $this->build_comp_text( 'item_text_'.$i );
	}

	echo $this->build_comp_textarea( 'item_description' );
	echo $this->build_comp_textarea( 'item_exif' );

	echo $this->build_comp_label( 'item_rating' );
	echo $this->build_comp_label( 'item_votes' );
	echo $this->build_comp_label( 'item_comments' );
	echo $this->build_comp_label( 'item_search' );

	echo $this->build_manage_submit();

	echo "</table></form>\n";
}

function _build_row_manage_id()
{
	$title = $this->get_constant( $this->_manage_id_name );
	if ( empty($title) ) {
		$title = $this->_MANAGE_TITLE_ID_DEFAULT;
	}
	$id = $this->get_manage_id();
	if ( $id > 0 ) {
		$url = $this->_MODULE_URL.'/index.php?fct=photo&amp;photo_id='.$id ;
		$ele = '<a href="'. $url .'">photo: '. $id .'</a>';
	} else {
		$ele = $this->substitute_empty( $id );
	}
	return $this->build_line_ele( $title, $ele );
}

function _build_row_file_id( $i )
{
	$name  = 'item_file_id_'.$i ;
	$value = intval( $this->get_row_by_key( $name ) );
	$ele   = $this->build_input_text( $name, $value );
	if ( $value > 0 ) {
		$url  = $this->_MODULE_URL.'/admin/index.php?fct=file_table_manage&amp;op=form&amp;id='.$value;
		$ele .= "<br />\n";
		$ele .= '<a href="'. $url .'">file table: '. $value .'</a>';
	}
	return $this->build_line_ele( $this->get_constant( $name ), $ele );
}

//---------------------------------------------------------
// delete
//---------------------------------------------------------
function manage_delete()
{
	$this->_delete_class->delete_photo( $this->get_post_id() );

	redirect_header( $this->_THIS_FCT_URL, $this->_MANAGE_TIME_SUCCESS, 'Deleted' );
	exit();
}

// --- class end ---
}

?>