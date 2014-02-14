<?php
// $Id: photo_table_manage.php,v 1.4 2008/08/25 20:49:44 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-241 K.OHWADA
// NOT use webphoto_photo_build webphoto_photo_delete
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_photo_table_manage
//=========================================================
class webphoto_admin_photo_table_manage extends webphoto_lib_manage
{
	var $_build_class;
	var $_delete_class;

	var $_URL_SIZE = 80;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_photo_table_manage( $dirname , $trust_dirname )
{
	$this->webphoto_lib_manage( $dirname , $trust_dirname );
	$this->set_manage_handler( webphoto_photo_handler::getInstance( $dirname ) );
	$this->set_manage_title_by_name( 'PHOTO_TABLE_MANAGE' );

	$this->set_manage_list_column_array(
		array( 'photo_title', 'photo_uid' ) );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_photo_table_manage( $dirname , $trust_dirname );
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
		'photo_datetime'       => $this->_manage_handler->build_datetime_by_post( 'photo_datetime' ) ,

		'photo_id'             => $this->_post_class->get_post_get_int( 'photo_id' ),
		'photo_time_create'    => $this->_post_class->get_post_int(   'photo_time_create' ),
		'photo_time_update'    => $this->_post_class->get_post_int(   'photo_time_update' ),
		'photo_cat_id'         => $this->_post_class->get_post_int(   'photo_cat_id' ),
		'photo_gicon_id'       => $this->_post_class->get_post_int(   'photo_gicon_id' ),
		'photo_uid'            => $this->_post_class->get_post_int(   'photo_uid' ),
		'photo_title'          => $this->_post_class->get_post_text(  'photo_title' ),
		'photo_place'          => $this->_post_class->get_post_text(  'photo_place' ),
		'photo_equipment'      => $this->_post_class->get_post_text(  'photo_equipment' ),
		'photo_file_url'       => $this->_post_class->get_post_url(   'photo_file_url' ),
		'photo_file_path'      => $this->_post_class->get_post_text(  'photo_file_path' ),
		'photo_file_name'      => $this->_post_class->get_post_text(  'photo_file_name' ),
		'photo_file_ext'       => $this->_post_class->get_post_text(  'photo_file_ext' ),
		'photo_file_mime'      => $this->_post_class->get_post_text(  'photo_file_mime' ),
		'photo_file_medium'    => $this->_post_class->get_post_text(  'photo_file_medium' ),
		'photo_file_size'      => $this->_post_class->get_post_int(   'photo_file_size' ),
		'photo_cont_url'       => $this->_post_class->get_post_url(   'photo_cont_url' ),
		'photo_cont_path'      => $this->_post_class->get_post_text(  'photo_cont_path' ),
		'photo_cont_name'      => $this->_post_class->get_post_text(  'photo_cont_name' ),
		'photo_cont_ext'       => $this->_post_class->get_post_text(  'photo_cont_ext' ),
		'photo_cont_mime'      => $this->_post_class->get_post_text(  'photo_cont_mime' ),
		'photo_cont_medium'    => $this->_post_class->get_post_text(  'photo_cont_medium' ),
		'photo_cont_size'      => $this->_post_class->get_post_int(   'photo_cont_size' ),
		'photo_cont_width'     => $this->_post_class->get_post_int(   'photo_cont_width' ),
		'photo_cont_height'    => $this->_post_class->get_post_int(   'photo_cont_height' ),
		'photo_cont_duration'  => $this->_post_class->get_post_int(   'photo_cont_duration' ),
		'photo_middle_width'   => $this->_post_class->get_post_int(   'photo_middle_width' ),
		'photo_middle_height'  => $this->_post_class->get_post_int(   'photo_middle_height' ),
		'photo_thumb_url'      => $this->_post_class->get_post_url(   'photo_thumb_url' ),
		'photo_thumb_path'     => $this->_post_class->get_post_text(  'photo_thumb_path' ),
		'photo_thumb_name'     => $this->_post_class->get_post_text(  'photo_thumb_name' ),
		'photo_thumb_ext'      => $this->_post_class->get_post_text(  'photo_thumb_ext' ),
		'photo_thumb_mime'     => $this->_post_class->get_post_text(  'photo_thumb_mime' ),
		'photo_thumb_medium'   => $this->_post_class->get_post_text(  'photo_thumb_medium' ),
		'photo_thumb_size'     => $this->_post_class->get_post_int(   'photo_thumb_size' ),
		'photo_thumb_width'    => $this->_post_class->get_post_int(   'photo_thumb_width' ),
		'photo_thumb_height'   => $this->_post_class->get_post_int(   'photo_thumb_height' ),
		'photo_gmap_latitude'  => $this->_post_class->get_post_float( 'photo_gmap_latitude' ),
		'photo_gmap_longitude' => $this->_post_class->get_post_float( 'photo_gmap_longitude' ),
		'photo_gmap_zoom'      => $this->_post_class->get_post_int(   'photo_gmap_zoom' ),
		'photo_gmap_type'      => $this->_post_class->get_post_int(   'photo_gmap_type' ),
		'photo_perm_read'      => $this->_post_class->get_post_text(  'photo_perm_read' ),
		'photo_status'         => $this->_post_class->get_post_int(   'photo_status' ),
		'photo_hits'           => $this->_post_class->get_post_int(   'photo_hits' ),
		'photo_description'    => $this->_post_class->get_post_text(  'photo_description' ),
		'photo_cont_exif'      => $this->_post_class->get_post_text(  'photo_cont_exif' ),

//		'photo_rating'         => $this->_post_class->get_post_float( 'photo_rating' ),
//		'photo_votes'          => $this->_post_class->get_post_int(   'photo_votes' ),
//		'photo_comments'       => $this->_post_class->get_post_int(   'photo_comments' ),
//		'photo_search'         => $this->_post_class->get_post_text(  'photo_search' ),

	);

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_PHOTO_TEXT; $i++ ) 
	{
		$name = 'photo_text'.$i;
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

	echo $this->build_manage_id();
	echo $this->build_comp_text( 'photo_title' );
	echo $this->build_comp_text( 'photo_time_create' );
	echo $this->build_comp_text( 'photo_time_update' );
	echo $this->build_comp_text( 'photo_cat_id' );
	echo $this->build_comp_text( 'photo_uid' );
	echo $this->build_comp_text( 'photo_datetime' );
	echo $this->build_comp_text( 'photo_place' );
	echo $this->build_comp_text( 'photo_equipment' );
	echo $this->build_comp_url(  'photo_file_url',  $this->_URL_SIZE, true );
	echo $this->build_comp_text( 'photo_file_path', $this->_URL_SIZE );
	echo $this->build_comp_text( 'photo_file_name' );
	echo $this->build_comp_text( 'photo_file_ext' );
	echo $this->build_comp_text( 'photo_file_mime' );
	echo $this->build_comp_text( 'photo_file_medium' );
	echo $this->build_comp_text( 'photo_file_size' );
	echo $this->build_comp_url(  'photo_cont_url',  $this->_URL_SIZE, true );
	echo $this->build_comp_text( 'photo_cont_path', $this->_URL_SIZE );
	echo $this->build_comp_text( 'photo_cont_name' );
	echo $this->build_comp_text( 'photo_cont_ext' );
	echo $this->build_comp_text( 'photo_cont_mime' );
	echo $this->build_comp_text( 'photo_cont_medium' );
	echo $this->build_comp_text( 'photo_cont_size' );
	echo $this->build_comp_text( 'photo_cont_width' );
	echo $this->build_comp_text( 'photo_cont_height' );
	echo $this->build_comp_text( 'photo_cont_duration' );
	echo $this->build_comp_text( 'photo_middle_width' );
	echo $this->build_comp_text( 'photo_middle_height' );
	echo $this->build_comp_url(  'photo_thumb_url',  $this->_URL_SIZE, true );
	echo $this->build_comp_text( 'photo_thumb_path', $this->_URL_SIZE );
	echo $this->build_comp_text( 'photo_thumb_name' );
	echo $this->build_comp_text( 'photo_thumb_ext' );
	echo $this->build_comp_text( 'photo_thumb_mime' );
	echo $this->build_comp_text( 'photo_thumb_medium' );
	echo $this->build_comp_text( 'photo_thumb_size' );
	echo $this->build_comp_text( 'photo_thumb_width' );
	echo $this->build_comp_text( 'photo_thumb_height' );
	echo $this->build_comp_text( 'photo_gicon_id' );
	echo $this->build_comp_text( 'photo_gmap_latitude' );
	echo $this->build_comp_text( 'photo_gmap_longitude' );
	echo $this->build_comp_text( 'photo_gmap_zoom' );
	echo $this->build_comp_text( 'photo_gmap_type' );
	echo $this->build_comp_text( 'photo_perm_read' );
	echo $this->build_comp_text( 'photo_status' );
	echo $this->build_comp_text( 'photo_hits' );

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_PHOTO_TEXT; $i++ ) {
		echo $this->build_comp_text( 'photo_text'.$i );
	}

	echo $this->build_comp_textarea( 'photo_description' );
	echo $this->build_comp_textarea( 'photo_cont_exif' );

	echo $this->build_comp_label( 'photo_rating' );
	echo $this->build_comp_label( 'photo_votes' );
	echo $this->build_comp_label( 'photo_comments' );
	echo $this->build_comp_label( 'photo_search' );

	echo $this->build_manage_submit();

	echo "</table></form>\n";
}

// --- class end ---
}

?>