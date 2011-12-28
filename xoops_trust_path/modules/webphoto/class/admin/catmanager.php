<?php
// $Id: catmanager.php,v 1.18 2011/12/28 16:16:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// webphoto_timeline_init
// 2010-11-11 K.OHWADA
// build_file_full_path()
// 2010-10-20 K.OHWADA
// remove set_flag_size_limit()
// 2010-02-15 K.OHWADA
// build_admin_footer()
// 2009-12-06 K.OHWADA
// cat_group_id
// _groupperm()
// BUG: not upload when over image size
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_edit_item_delete
// 2009-01-25 K.OHWADA
// cat_gmap_latitude
// 2009-01-14 K.OHWADA
// webphoto_photo_delete -> webphoto_edit_item_delete
// 2009-01-13 K.OHWADA
// Fatal error: Call to undefined method webphoto_cat_handler::get_all_child_id()
// 2008-12-12 K.OHWADA
// get_group_perms_str_by_post()
// 2008-11-08 K.OHWADA
// _fetch_image()
// _C_WEBPHOTO_CAT_MAIN_WIDTH_DEFAULT -> cfg_cat_width
// 2008-09-13 K.OHWADA
// BUG: fatal error
// photo_handler -> item_handler
// 2008-07-01 K.OHWADA
// xoops_error() -> build_error_msg()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_catmanager
//=========================================================
class webphoto_admin_catmanager extends webphoto_edit_base
{
	var $_delete_class;
	var $_upload_class;
	var $_image_create_class;
	var $_group_class;
	var $_gperm_def_class;
	var $_groupperm_class;
	var $_timeline_init_class;

	var $_cfg_cat_width  ;
	var $_cfg_csub_width ;
	var $_cfg_perm_cat_read ;
	var $_cfg_perm_item_read ;
	var $_ini_use_cat_group_id;

	var $_get_catid;

	var $_error_upload = false;

	var $_THIS_FCT = 'catmanager';
	var $_THIS_URL;
	var $_THIS_URL_FORM;

	var $_CAT_FIELD_NAME  = _C_WEBPHOTO_UPLOAD_FIELD_CATEGORY ;

	var $_USERLIST_LIMIT = 10;

	var $_TIME_SUCCESS = 1;
	var $_TIME_FAIL    = 5;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_catmanager( $dirname , $trust_dirname )
{
	$this->webphoto_edit_base( $dirname , $trust_dirname );

	$this->_delete_class    
		=& webphoto_edit_item_delete::getInstance( $dirname , $trust_dirname  );
	$this->_upload_class    
		=& webphoto_upload::getInstance( $dirname , $trust_dirname );

	$this->_image_create_class =& webphoto_image_create::getInstance( $dirname );

	$this->_group_class     =& webphoto_inc_group::getSingleton( $dirname );
	$this->_gperm_def_class =& webphoto_inc_gperm_def::getInstance();
	$this->_groupperm_class =& webphoto_lib_groupperm::getInstance();
	$this->_timeline_init_class =& webphoto_timeline_init::getInstance( $dirname );

	$this->_cfg_cat_width      = $this->_config_class->get_by_name( 'cat_width' );
	$this->_cfg_csub_width     = $this->_config_class->get_by_name( 'csub_width' );
	$this->_cfg_perm_cat_read  = $this->_config_class->get_by_name( 'perm_cat_read' );
	$this->_cfg_perm_item_read = $this->_config_class->get_by_name( 'perm_item_read' );

	$this->_ini_use_cat_group_id = $this->get_ini('use_cat_group_id');

	$this->_THIS_URL = $this->_MODULE_URL .'/admin/index.php?fct='.$this->_THIS_FCT;
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_catmanager( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	switch ( $this->_get_action() )
	{
		case 'insert':
			$this->_insert();
			exit();

		case 'update':
			$this->_update();
			exit();

		case 'del_confirm':
			$this->_print_del_confirm();
			exit();

		case 'delete':
			$this->_delete();
			exit();

		case 'weight':
			$this->_weight();
			exit();

		case 'groupperm':
			$this->_groupperm();
			exit();

		default:
			break;
	}

	xoops_cp_header();
	echo $this->build_admin_menu();
	echo $this->build_admin_title( 'CATMANAGER' );

	switch ( $this->_get_disp() )
	{
		case 'new':
			$this->_print_new_form();
			break;

		case 'edit':
			$this->_print_edit_form();
			break;

		default:
			$this->_print_list();
			break;
	}

	echo $this->build_admin_footer();
	xoops_cp_footer();
	exit();
}

function _get_action()
{
	$action  = $this->get_post_text('action');
	$confirm = $this->get_post_text('del_confirm');
	$delcat  = $this->get_post_text('delcat');
	$cat_id  = $this->get_post_int('cat_id');
	$perms   = $this->get_post('perms');

	if ( $cat_id > 0 ) {
		$this->_THIS_URL_FORM = $this->_THIS_URL.'&amp;disp=edit&amp;cat_id='.$cat_id;
	} else {
		$this->_THIS_URL_FORM = $this->_THIS_URL;
	}

	$ret = '';
	if( $confirm ) {
		$ret = 'del_confirm';
	} elseif( $action == 'insert' ) {
		$ret = 'insert';
	} elseif ( ($action == 'update') && $cat_id ) {
		$ret = 'update';
	} elseif ( ($action == 'delete') && $cat_id ) {
		$ret = 'delete';
	} elseif ( $action == 'weight' ) {
		$ret = 'weight';
	} elseif ( is_array($perms) ) {
		$ret = 'groupperm';
	}
	return $ret;
}

function _get_disp()
{
	$disp             = $this->_post_class->get_get_text('disp');
	$this->_get_catid = $this->_post_class->get_get_int('cat_id');

	$ret = '';
	if( ( $disp == "edit") && ($this->_get_catid > 0) ) {
		$ret = 'edit';
	} elseif( $disp == "new" ) {
		$ret = 'new';
	}
	return $ret;
}

//---------------------------------------------------------
// insert
//---------------------------------------------------------
function _insert()
{
	$post_pid   = $this->get_post_int('cat_pid');
	$post_title = $this->get_post_text('cat_title');

	$error = null;
	$flag_update = false;

	if ( ! $this->check_token() ) {
		$error = $this->get_token_errors() ;
	}

	if ( empty($post_title) ) {
		$error = $this->get_constant( 'ERR_TITLE' ) ;
	}

	if ( $error ) {
		redirect_header( $this->_THIS_URL, $this->_TIME_FAIL, $error );
		exit() ;
	}

	$row = $this->_cat_handler->create( true );
	$row_insert = $this->_build_row_insert( $row );

	$newid = $this->_cat_handler->insert( $row_insert );
	if ( !$newid ) {
		$msg  = "DB Error: insert category";
		$msg .= '<br />'.$this->get_format_error();
		redirect_header( $this->_THIS_URL , $this->_TIME_FAIL , $msg ) ;
		exit();
	}

	$row_update = $this->_cat_handler->get_row_by_id( $newid );

	// Check if cid == pid
	if ( $newid == $post_pid ) {
		$row_update['cat_pid'] = 0;
		$flag_update = true;
	}

// if root category
	if ( $this->_show_group_id() && ( $row_update['cat_pid'] == 0 ) ) {
		$name = $row_update['cat_title'] ;
		$desc = 'cat id: '. $newid .' module id: '.$this->_MODULE_ID.' name: '.$this->_DIRNAME;
		$group_id = $this->_group_class->create_member_group( $name, $desc );

		if ( $group_id ) {
			$this->_group_class->create_gperm_module_read( $group_id );
			$this->_group_class->create_gperm_webphoto_groupid( 
				$group_id, $this->_gperm_def_class->get_perms_user() );
			$row_update['cat_group_id']  = $group_id;
			$row_update['cat_perm_post'] = $this->_build_cat_perm_post( $group_id );
			$flag_update = true;
		}
	}

	if ( $flag_update ) {
		$this->_cat_handler->update( $row_update );
	}

	if ( $this->_error_upload ) {
		$msg  = $this->get_format_error();
		$msg .= "<br />\n";
		$msg .= _AM_WEBPHOTO_CAT_INSERTED ;
		redirect_header( $this->_THIS_URL , $this->_TIME_FAIL , $msg ) ;
		exit() ;
	}

	redirect_header( $this->_THIS_URL , $this->_TIME_SUCCESS , _AM_WEBPHOTO_CAT_INSERTED ) ;
	exit() ;
}

function _build_row_insert( $row )
{
	return $this->_build_row( $row );
}

function _build_row_update( $row )
{
	$row = $this->_build_row( $row );
	$cat_group_id = $this->get_post_int( 'cat_group_id' );

	if ( $this->_show_group_id() ) {
// if not system group
		if ( ! $this->is_system_group( $cat_group_id ) ) {
			$row['cat_group_id'] = $cat_group_id ;
		}
	}
	return $row;
}

function _build_row( $row )
{
	$row = $this->_build_row_by_post( $row );
	$row = $this->_build_img_name( $row );
	$row = $this->_build_img_size( $row );
	return $row ;
}

function _build_row_by_post( $row )
{
	$row['cat_pid']            = $this->get_post_int('cat_pid');
	$row['cat_gicon_id']       = $this->get_post_int('cat_gicon_id');
	$row['cat_weight']         = $this->get_post_int('cat_weight');
	$row['cat_title']          = $this->get_post_text('cat_title');
	$row['cat_description']    = $this->get_post_text('cat_description');
	$row['cat_perm_post']      = $this->get_group_perms_str_by_post('cat_perm_post_ids');
	$row['cat_gmap_latitude']  = $this->get_post_float( 'cat_gmap_latitude' );
	$row['cat_gmap_longitude'] = $this->get_post_float( 'cat_gmap_longitude' );
	$row['cat_gmap_zoom']      = $this->get_post_int(   'cat_gmap_zoom' );
	$row['cat_timeline_mode']  = $this->get_post_int(   'cat_timeline_mode' );

	$cat_timeline_scale        = $this->get_post_text(   'cat_timeline_scale' );
	$row['cat_timeline_scale'] = $this->_timeline_init_class->unit_to_scale( $cat_timeline_scale ) ;

	if ( $this->_cfg_perm_cat_read > 0 ) {
		$row['cat_perm_read'] = $this->get_group_perms_str_by_post('cat_perm_read_ids');
	}

	return $row;
}

function _build_img_path_by_post()
{
	$img_path = $this->get_post_text('cat_img_path');

	if ( $this->check_http_null( $img_path ) ) {
		return '';
	} elseif ( $this->check_http_start( $img_path ) ) {
		return $img_path;
	}

	return $this->add_slash_to_head( $img_path );
}

function _build_img_name( $row )
{
// set img
	$fetch_img_name = $this->_fetch_image();
	$post_img_name  = $this->get_post_text('cat_img_name');
	$post_img_path  = $this->_build_img_path_by_post();

	$row['cat_img_name'] = '' ;
	$row['cat_img_path'] = '' ;

	if ( $fetch_img_name ) {
		$row['cat_img_name'] = $fetch_img_name ;
	} elseif ( $post_img_name ) {
		$row['cat_img_name'] = $post_img_name ;
	} elseif ( $post_img_path ) {
		$row['cat_img_path'] = $post_img_path ;
	}

	return $row;
}

function _build_img_size( $row )
{
	$img_name = $row['cat_img_name'];
	$img_path = $row['cat_img_path'];

	if ( $img_name ) { 
		$full_path = $this->_CATS_DIR .'/'. $img_name ;

	} elseif ( $img_path ) { 
		$full_path = $this->build_file_full_path( $img_path );

	} else {
		return $row;
	}

	if ( !file_exists($full_path) ) {
		return $row;
	}

	$image_size = GetImageSize( $full_path ) ;
	if ( !is_array($image_size) ) {
		return $row;
	}

	$width  = $image_size[0];
	$height = $image_size[1];

	list( $main_width, $main_height ) 
		= $this->adjust_image_size(
			$width, $height, $this->_cfg_cat_width, $this->_cfg_cat_width );

	list( $sub_width, $sub_height ) 
		= $this->adjust_image_size(
			$width, $height, $this->_cfg_csub_width, $this->_cfg_csub_width );

	$row['cat_orig_width']  = $width;
	$row['cat_orig_height'] = $height;
	$row['cat_main_width']  = $main_width;
	$row['cat_main_height'] = $main_height;
	$row['cat_sub_width']   = $sub_width;
	$row['cat_sub_height']  = $sub_height;

	return $row;
}

function _fetch_image()
{
	$this->_error_upload = false;

	$ret = $this->_upload_class->fetch_image( $this->_CAT_FIELD_NAME );
	if ( $ret < 0 ) { 
		$this->_error_upload = true;
		$this->set_error( 'WARNING failed to upload category image' );
		$this->set_error( $this->_upload_class->get_errors() );
		return null ;	// failed
	}

	$tmp_name   = $this->_upload_class->get_uploader_file_name() ;
	$media_name = $this->_upload_class->get_uploader_media_name() ;

	if ( $tmp_name && $media_name ) {
		$tmp_file = $this->_TMP_DIR   .'/'. $tmp_name;
		$cat_file = $this->_CATS_DIR  .'/'. $media_name ;
		$this->_image_create_class->cmd_resize_rotate( 
			$tmp_file, $cat_file, $this->_cfg_cat_width, $this->_cfg_cat_width );
		return $media_name ;	// success
	}

	return null ;
}

function _show_group_id()
{
	if (( $this->_cfg_perm_item_read > 0 ) && $this->_ini_use_cat_group_id ) {
		return true;
	}
	return false;
}

function _build_cat_perm_post( $group_id )
{
	$arr = array( XOOPS_GROUP_ADMIN, $group_id );
	$val = $this->_utility_class->array_to_perm( $arr, _C_WEBPHOTO_PERM_SEPARATOR );
	return $val;
}

//---------------------------------------------------------
// update
//---------------------------------------------------------
function _update()
{
	if ( ! $this->check_token() ) {
		redirect_header( $this->_ADMIN_INDEX_PHP, $this->_TIME_FAIL, $this->get_token_errors() );
		exit() ;
	}

	$post_catid = $this->get_post_int('cat_id');
	$post_pid   = $this->get_post_int('cat_pid');

	// Check if new pid was a child of cid
	if ( $post_pid != 0 ) {

// Fatal error: Call to undefined method webphoto_cat_handler::get_all_child_id()
		$children   = $this->_cat_handler->getAllChildId( $post_catid ) ;
		$children[] = $post_catid ;

		foreach( $children as $child ) 
		{
			if( $child == $post_pid ) {
				$msg = "category looping has occurred" ;
				redirect_header( $this->_THIS_URL , $this->_TIME_FAIL , $msg ) ;
				exit() ;
			}
		}
	}

	$row = $this->_cat_handler->get_row_by_id( $post_catid );
	$row_update = $this->_build_row_update( $row );

	$ret = $this->_cat_handler->update( $row_update );
	if ( !$ret ) {
		$msg  = "DB Error: update category <br />";
		$msg .= $this->get_format_error();
		redirect_header( $this->_THIS_URL , $this->_TIME_FAIL , $msg ) ;
		exit();
	}

	$ret = $this->_update_child( $post_catid, $row_update );
	if ( !$ret ) {
		$msg  = "DB Error: update category <br />";
		$msg .= $this->get_format_error();
		redirect_header( $this->_THIS_URL , $this->_TIME_FAIL , $msg ) ;
		exit();
	}

	if ( $this->_error_upload ) {
		$msg  = $this->get_format_error();
		$msg .= "<br />\n";
		$msg .= _AM_WEBPHOTO_CAT_UPDATED ;
		redirect_header( $this->_THIS_URL_FORM, $this->_TIME_FAIL , $msg ) ;
		exit();
	}

	redirect_header( $this->_THIS_URL_FORM , $this->_TIME_SUCCESS , _AM_WEBPHOTO_CAT_UPDATED ) ;
	exit() ;
}

function _update_child( $cat_id, $row_update )
{
	$post_perm_child = $this->get_post_int('perm_child');

	if ( $post_perm_child != _C_WEBPHOTO_YES ) {
		return true;	// no action
	}

	$id_arr = $this->_cat_handler->getAllChildId( $cat_id );
	if ( ! is_array($id_arr) || ! count($id_arr) ) {
		return true;	// no action
	}

	$err      = false ;
	$new_read = $row_update['cat_perm_read'] ;
	$new_post = $row_update['cat_perm_post'] ;

	foreach ( $id_arr as $id )
	{
		$row = $this->_cat_handler->get_row_by_id( $id );
		$current_read = $row['cat_perm_read'] ;
		$current_post = $row['cat_perm_post'] ;

// skip if no change
		if (( $current_read == $new_read )&&
		    ( $current_post == $new_post )) {
			continue ;
		}

		$row['cat_perm_read'] = $new_read ;
		$row['cat_perm_post'] = $new_post ;
		$ret = $this->_cat_handler->update( $row );
		if ( !$ret ) {
			$err = true;
		}
	}

	return ( ! $err );
}

//---------------------------------------------------------
// delete
//---------------------------------------------------------
function _delete()
{
	if ( ! $this->check_token() ) {
		redirect_header( $this->_THIS_URL, $this->_TIME_FAIL, $this->get_token_errors() );
		exit();
	}

	// Delete
	$post_catid = $this->get_post_int('cat_id');

	//get all categories under the specified category
	$children = $this->_cat_handler->getAllChildId( $post_catid ) ;

	foreach( $children as $ch_id ) {
		$this->_delete_single_by_catid( $ch_id );
	}

	$this->_delete_single_by_catid( $post_catid );

	if ( $this->has_error() ) {
		$msg  = "DB Error: delete category <br />";
		$msg .= $this->get_format_error();
		redirect_header( $this->_THIS_URL , $this->_TIME_FAIL , $msg ) ;
		exit();
	}

	redirect_header( $this->_THIS_URL , $this->_TIME_SUCCESS , _AM_WEBPHOTO_CATDELETED ) ;
	exit() ;
}

function _delete_single_by_catid( $cat_id )
{
	$row = $this->_cat_handler->get_row_by_id( $cat_id );
	$ret = $this->_cat_handler->delete( $row );
	if ( !$ret ) {
		$this->set_error( $this->_cat_handler->get_errors() );
	}

	$group_id = $row['cat_group_id'];

	xoops_notification_deletebyitem( $this->_MODULE_ID , 'category' , $cat_id ) ;
	$this->_delete_photos_by_catid( $cat_id );

	if ( $this->_show_group_id() ) {
// if not system group
		if ( ! $this->is_system_group( $group_id ) ) {
			$this->_group_class->delete_group( $group_id );
		}
	}
}

// Delete photos hit by the $whr clause
function _delete_photos_by_catid( $cat_id )
{
	$item_rows = $this->_item_handler->get_rows_by_catid( $cat_id );
	if ( !is_array($item_rows) || !count($item_rows) ) {
		return; 
	}

	foreach ( $item_rows as $row ) {

// Fatal error: Call to undefined method webphoto_photo_delete::delete_photo()
		$this->_delete_class->delete_photo_by_item_row( $row ) ;

	}
}

//---------------------------------------------------------
// weight
//---------------------------------------------------------
function _weight()
{
	if ( ! $this->check_token() ) {
		redirect_header( $this->_THIS_URL, $this->_TIME_FAIL, $this->get_token_errors() );
		exit();
	}

	$weight_arr    = $this->get_post('weight');
	$oldweight_arr = $this->get_post('oldweight');

	foreach( $weight_arr as $id => $weight ) 
	{
		if ( $weight == $oldweight_arr[ $id ] ) {
			continue;
		}

		$ret = $this->_cat_handler->update_weight( $id, $weight );
		if ( !$ret ) {
			$this->set_error( $this->_cat_handler->get_errors() );
		}
	}

	if ( $this->has_error() ) {
		$msg  = "DB Error: delete category <br />";
		$msg .= $this->get_format_error();
		redirect_header( $this->_THIS_URL , $this->_TIME_FAIL , $msg ) ;
		exit();
	}

	redirect_header( $this->_THIS_URL , $this->_TIME_SUCCESS , _WEBPHOTO_DBUPDATED ) ;
	exit() ;
}

//---------------------------------------------------------
// groupperm
//---------------------------------------------------------
function _groupperm()
{
	if ( ! $this->check_token() ) {
		redirect_header( $this->_THIS_URL_FORM, $this->_TIME_FAIL, $this->get_token_errors() );
		exit();
	}

	$this->_groupperm_class->modify( $this->_MODULE_ID, $this->get_post('perms'), true );
	redirect_header( $this->_THIS_URL_FORM , $this->_TIME_SUCCESS , _AM_WEBPHOTO_GPERMUPDATED );
	exit() ;
}

//---------------------------------------------------------
// print form
//---------------------------------------------------------
function _print_new_form()
{
// New
	$row = $this->_cat_handler->create( true );
	$row['cat_pid'] = $this->_get_catid ;

	$parent = null ;

	if ( $this->_get_catid > 0 ) {
		$parent_row = $this->_cat_handler->get_row_by_id( $this->_get_catid );
		if ( is_array($parent_row) ) {
			$row['cat_perm_read'] = $parent_row['cat_perm_read'] ;
			$row['cat_perm_post'] = $parent_row['cat_perm_post'] ;
			$parent               = $parent_row['cat_title'] ;
		}
	}

	$param = array(
		'mode'   => 'new',
		'parent' => $parent,
	);

	$this->_print_cat_form( $row, $param ) ;
}

function _print_edit_form()
{
// Editing
	$row = $this->_cat_handler->get_row_by_id( $this->_get_catid );
	if ( !is_array($row ) ) {
		redirect_header( $this->_THIS_URL , $this->_TIME_FAIL , _AM_WEBPHOTO_ERR_NO_RECORD ) ;
		exit();
	}

	$group_id = $row['cat_group_id'];

	$param = array(
		'mode'   => 'edit',
		'parent' => null,
	);

	$this->_print_cat_form( $row, $param );

	if ( $group_id > 0 ) {
		$this->_print_gperm_form( $group_id );
		$this->_print_member( $group_id );
	}
}

//---------------------------------------------------------
// print list
//---------------------------------------------------------
function _print_list( )
{
// Listing
	$order = 'cat_weight ASC, cat_title ASC';
	$cat_tree_array = $this->_cat_handler->get_child_tree_array( 0 , $order ) ;

// Get ghost categories
// caution : sometimes this error cause endless loop
	$rows = $this->_cat_handler->get_rows_ghost();
	if( is_array($rows) && count($rows) ) {
		foreach ( $rows as $row ) {
			$ret = $this->_cat_handler->update_pid( $row['cat_id'], 0 );
		}
		echo $this->build_error_msg( 'A Ghost Category found.' ) ;
		xoops_cp_footer();
		exit();
	}

	$img_catadd = '<img src="'. $this->_ICONS_URL.'/cat_add.png" width="18" height="15" alt="'. _AM_WEBPHOTO_CAT_LINK_MAKETOPCAT .'" title="'. _AM_WEBPHOTO_CAT_LINK_MAKETOPCAT .'" />'."\n";

	// Top links
	echo '<p><a href="'. $this->_THIS_URL .'&amp;disp=new">';
	echo _AM_WEBPHOTO_CAT_LINK_MAKETOPCAT;
	echo ' ';
	echo $img_catadd;
	echo "</a> &nbsp; ";
	echo '</p>'."\n" ;

	$this->_print_cat_list( $cat_tree_array );
}

//---------------------------------------------------------
// admin_cat_form
//---------------------------------------------------------
function _print_cat_form( $row, $param )
{
	$cat_form =& webphoto_admin_cat_form::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );
	$cat_form->print_form( $row, $param );
}

function _print_cat_list( $cat_tree_array )
{
	$cat_form =& webphoto_admin_cat_form::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );
	$cat_form->print_list( $cat_tree_array );
}

function _print_del_confirm()
{
	xoops_cp_header();
	echo $this->build_bread_crumb( $this->get_admin_title( 'CATMANAGER' ), $this->_THIS_URL );
	echo $this->build_admin_title( 'CATMANAGER' );

	$get_catid = $this->get_post_int('cat_id');

	$row = $this->_cat_handler->get_row_by_id( $get_catid );
	if ( !is_array($row ) ) {
		redirect_header( $this->_THIS_URL , $this->_TIME_FAIL , _AM_WEBPHOTO_ERR_NO_RECORD ) ;
		exit();
	}

	echo "<h4>". $this->sanitize( $row['cat_title'] ) ."</h4>\n";

	$cat_form =& webphoto_admin_cat_form::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );
	$cat_form->print_del_confirm( $get_catid );

	xoops_cp_footer();
	exit();
}

function _print_gperm_form( $group_id )
{
	$form_class =& webphoto_admin_groupperm_form::getInstance(
		$this->_DIRNAME , $this->_TRUST_DIRNAME );

	echo $form_class->build_form_by_groupid( 
		$group_id, $this->_THIS_FCT,  $this->_get_catid );
}

function _print_member( $group_id )
{
	$template = 'db:'. $this->_DIRNAME .'_inc_user_list.html';

	$userlist_class =& webphoto_lib_userlist::getInstance();
	$param = $userlist_class->build_param_by_groupid( $group_id, $this->_USERLIST_LIMIT );
	$param['xoops_dirname'] = $this->_DIRNAME ;

	$tpl = new XoopsTpl() ;
	$tpl->assign( $param ) ;
	$tpl->assign( $this->get_user_lang() ) ;
	echo $tpl->fetch( $template ) ;
}

function get_user_lang()
{
	$arr = array(
		'lang_user_uid'       => _AM_WEBPHOTO_USER_UID ,
		'lang_user_uname'     => _AM_WEBPHOTO_USER_UNAME ,
		'lang_user_name'      => _AM_WEBPHOTO_USER_NAME ,
		'lang_user_regdate'   => _AM_WEBPHOTO_USER_REGDATE,
		'lang_user_lastlogin' => _AM_WEBPHOTO_USER_LASTLOGIN ,
		'lang_user_post'      => _AM_WEBPHOTO_USER_POSTS ,
		'lang_user_level'     => _AM_WEBPHOTO_USER_LEVEL ,
		'lang_user_control'   => _AM_WEBPHOTO_USER_CONTROL ,
		'lang_user_total'     => _AM_WEBPHOTO_USER_TOTAL ,
		'lang_user_assign'    => _AM_WEBPHOTO_USER_ASSIGN ,
		'lang_user_user'      => _AM_WEBPHOTO_USER_USER ,
		'lang_user_edit'      => _EDIT ,
	);
	return $arr;
}

// --- class end ---
}

?>