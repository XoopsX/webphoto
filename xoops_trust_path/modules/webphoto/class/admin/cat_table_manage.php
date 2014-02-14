<?php
// $Id: cat_table_manage.php,v 1.3 2009/12/16 13:32:34 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-12-06 K.OHWADA
// cat_group_id
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_cat_handler
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_cat_table_manage
//=========================================================
class webphoto_admin_cat_table_manage extends webphoto_lib_manage
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_cat_table_manage( $dirname , $trust_dirname )
{
	$this->webphoto_lib_manage( $dirname , $trust_dirname );
	$this->set_manage_handler( 
		webphoto_cat_handler::getInstance( $dirname , $trust_dirname ) );
	$this->set_manage_title_by_name( 'CAT_TABLE_MANAGE' );

	$this->set_manage_list_column_array(
		array( 'cat_title', 'cat_pid' ) );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_cat_table_manage( $dirname , $trust_dirname );
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
		'cat_id'             => $this->_post_class->get_post_get_int( 'cat_id' ),
		'cat_time_create'    => $this->_post_class->get_post_int(   'cat_time_create' ),
		'cat_time_update'    => $this->_post_class->get_post_int(   'cat_time_update' ),
		'cat_gicon_id'       => $this->_post_class->get_post_int(   'cat_gicon_id' ),
		'cat_forum_id'       => $this->_post_class->get_post_int(   'cat_forum_id' ),
		'cat_group_id'       => $this->_post_class->get_post_int(   'cat_group_id' ),
		'cat_pid'            => $this->_post_class->get_post_int(   'cat_pid' ),
		'cat_title'          => $this->_post_class->get_post_text(  'cat_title' ),
		'cat_img_path'       => $this->_post_class->get_post_text(  'cat_img_path' ),
		'cat_weight'         => $this->_post_class->get_post_int(   'cat_weight' ),
		'cat_depth'          => $this->_post_class->get_post_int(   'cat_depth' ),
		'cat_allowed_ext'    => $this->_post_class->get_post_text(  'cat_allowed_ext' ),
		'cat_img_mode'       => $this->_post_class->get_post_int(   'cat_img_mode' ),
		'cat_orig_width'     => $this->_post_class->get_post_int(   'cat_orig_width' ),
		'cat_orig_height'    => $this->_post_class->get_post_int(   'cat_orig_height' ),
		'cat_main_width'     => $this->_post_class->get_post_int(   'cat_main_width' ),
		'cat_main_height'    => $this->_post_class->get_post_int(   'cat_main_height' ),
		'cat_sub_width'      => $this->_post_class->get_post_int(   'cat_sub_width' ),
		'cat_sub_height'     => $this->_post_class->get_post_int(   'cat_sub_height' ),
		'cat_item_type'      => $this->_post_class->get_post_int(   'cat_item_type' ),
		'cat_gmap_mode'      => $this->_post_class->get_post_int(   'cat_gmap_mode' ),
		'cat_gmap_latitude'  => $this->_post_class->get_post_float( 'cat_gmap_latitude' ),
		'cat_gmap_longitude' => $this->_post_class->get_post_float( 'cat_gmap_longitude' ),
		'cat_gmap_zoom'      => $this->_post_class->get_post_int(   'cat_gmap_zoom' ),
		'cat_gmap_type'      => $this->_post_class->get_post_int(   'cat_gmap_type' ),
		'cat_perm_read'      => $this->_post_class->get_post_text(  'cat_perm_read' ),
		'cat_perm_post'      => $this->_post_class->get_post_text(  'cat_perm_post' ),
		'cat_text1'          => $this->_post_class->get_post_text(  'cat_text1' ),
		'cat_text2'          => $this->_post_class->get_post_text(  'cat_text2' ),
		'cat_text3'          => $this->_post_class->get_post_text(  'cat_text3' ),
		'cat_text4'          => $this->_post_class->get_post_text(  'cat_text4' ),
		'cat_text5'          => $this->_post_class->get_post_text(  'cat_text5' ),
		'cat_description'    => $this->_post_class->get_post_text(  'cat_description' ),
	);

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_CAT_TEXT; $i++ ) 
	{
		$name = 'cat_text'.$i;
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
	echo $this->build_comp_text( 'cat_title' );
	echo $this->build_comp_text( 'cat_time_create' );
	echo $this->build_comp_text( 'cat_time_update' );
	echo $this->build_comp_text( 'cat_pid' );
	echo $this->build_comp_text( 'cat_img_path' );
	echo $this->build_comp_text( 'cat_img_mode' );
	echo $this->build_comp_text( 'cat_orig_width' );
	echo $this->build_comp_text( 'cat_orig_height' );
	echo $this->build_comp_text( 'cat_main_width' );
	echo $this->build_comp_text( 'cat_main_height' );
	echo $this->build_comp_text( 'cat_sub_width' );
	echo $this->build_comp_text( 'cat_sub_height' );
	echo $this->build_comp_text( 'cat_weight' );
	echo $this->build_comp_text( 'cat_depth' );
	echo $this->build_comp_text( 'cat_allowed_ext' );
	echo $this->build_comp_text( 'cat_gicon_id' );
	echo $this->build_comp_text( 'cat_forum_id' );
	echo $this->build_comp_text( 'cat_group_id' );
	echo $this->build_comp_text( 'cat_item_type' );
	echo $this->build_comp_text( 'cat_gmap_mode' );
	echo $this->build_comp_text( 'cat_gmap_latitude' );
	echo $this->build_comp_text( 'cat_gmap_longitude' );
	echo $this->build_comp_text( 'cat_gmap_zoom' );
	echo $this->build_comp_text( 'cat_gmap_type' );
	echo $this->build_comp_text( 'cat_perm_read' );
	echo $this->build_comp_text( 'cat_perm_post' );

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_CAT_TEXT; $i++ ) {
		echo $this->build_comp_text( 'cat_text'.$i );
	}

	echo $this->build_comp_textarea( 'cat_description' );

	echo $this->build_manage_submit();

	echo "</table></form>\n";
}

// --- class end ---
}

?>