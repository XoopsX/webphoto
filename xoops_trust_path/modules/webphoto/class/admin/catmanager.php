<?php
// $Id: catmanager.php,v 1.8 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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
class webphoto_admin_catmanager extends webphoto_base_this
{
	var $_delete_class;
	var $_upload_class;
	var $_image_cmd_class;

	var $_cfg_cat_width  ;
	var $_cfg_csub_width ;
	var $_cfg_perm_cat_read ;

	var $_get_catid;

	var $_error_upload = false;

	var $_THIS_FCT = 'catmanager';
	var $_THIS_URL;

	var $_CAT_FIELD_NAME  = _C_WEBPHOTO_UPLOAD_FIELD_CATEGORY ;

	var $_TIME_SUCCESS = 1;
	var $_TIME_FAIL    = 5;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_catmanager( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_delete_class    =& webphoto_edit_item_delete::getInstance( $dirname );
	$this->_upload_class    =& webphoto_upload::getInstance( $dirname , $trust_dirname );
	$this->_image_cmd_class =& webphoto_lib_image_cmd::getInstance();

	$this->_cfg_cat_width     = $this->_config_class->get_by_name( 'cat_width' );
	$this->_cfg_csub_width    = $this->_config_class->get_by_name( 'csub_width' );
	$this->_cfg_perm_cat_read = $this->_config_class->get_by_name( 'perm_cat_read' );

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

	xoops_cp_footer();
	exit();
}

function _get_action()
{
	$action  = $this->_post_class->get_post_text('action');
	$confirm = $this->_post_class->get_post_text('del_confirm');
	$delcat  = $this->_post_class->get_post_text('delcat');
	$cat_id  = $this->_post_class->get_post_int('cat_id');

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
	$post_pid   = $this->_post_class->get_post_int('cat_pid');
	$post_title = $this->_post_class->get_post_text('cat_title');

	$error = null;

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
	$row_insert = $this->_build_row( $row );

	$newid = $this->_cat_handler->insert( $row_insert );
	if ( !$newid ) {
		$msg  = "DB Error: insert category";
		$msg .= '<br />'.$this->get_format_error();
		redirect_header( $this->_THIS_URL , $this->_TIME_FAIL , $msg ) ;
		exit();
	}

	// Check if cid == pid
	if( $newid == $post_pid ) {
		$this->_cat_handler->update_pid( $newid, 0 );
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

function _build_row( $row )
{
	$row = $this->_build_row_by_post( $row );
	$row = $this->_build_img_name( $row );
	$row = $this->_build_img_size( $row );
	return $row ;
}

function _build_row_by_post( $row )
{
	$row['cat_pid']         = $this->_post_class->get_post_int('cat_pid');
	$row['cat_gicon_id']    = $this->_post_class->get_post_int('cat_gicon_id');
	$row['cat_weight']      = $this->_post_class->get_post_int('cat_weight');
	$row['cat_title']       = $this->_post_class->get_post_text('cat_title');
	$row['cat_description'] = $this->_post_class->get_post_text('cat_description');
	$row['cat_perm_post']   = $this->get_group_perms_str_by_post('cat_perm_post_ids');

	if ( $this->_cfg_perm_cat_read > 0 ) {
		$row['cat_perm_read'] = $this->get_group_perms_str_by_post('cat_perm_read_ids');
	}

	return $row;
}

function _build_img_path_by_post()
{
	$img_path = $this->_post_class->get_post_text('cat_img_path');

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
	$post_img_name  = $this->_post_class->get_post_text('cat_img_name');
	$post_img_path  = $this->_build_img_path_by_post();

	if ( $fetch_img_name ) {
		$row['cat_img_name'] = $fetch_img_name ;
		$row['cat_img_path'] = '' ;

	} elseif ( $post_img_name ) {
		$row['cat_img_name'] = $post_img_name ;
		$row['cat_img_path'] = '' ;

	} elseif( $post_img_path ) {
		$row['cat_img_name'] = '' ;
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
		$full_path = XOOPS_ROOT_PATH . $img_path ;

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
		$this->_image_cmd_class->resize_rotate( 
			$tmp_file, $cat_file, $this->_cfg_cat_width, $this->_cfg_cat_width );
		return $media_name ;	// success
	}

	return null ;
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

	$post_catid = $this->_post_class->get_post_int('cat_id');
	$post_pid   = $this->_post_class->get_post_int('cat_pid');

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
	$row_update = $this->_build_row( $row );

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

	$url = $this->_THIS_URL.'&amp;disp=edit&amp;cat_id='.$post_catid;

	if ( $this->_error_upload ) {
		$msg  = $this->get_format_error();
		$msg .= "<br />\n";
		$msg .= _AM_WEBPHOTO_CAT_UPDATED ;
		redirect_header( $url, $this->_TIME_FAIL , $msg ) ;
		exit();
	}

	redirect_header( $url , $this->_TIME_SUCCESS , _AM_WEBPHOTO_CAT_UPDATED ) ;
	exit() ;
}

function _update_child( $cat_id, $row_update )
{
	$post_perm_child = $this->_post_class->get_post_int('perm_child');

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
	$post_catid = $this->_post_class->get_post_int('cat_id');

	//get all categories under the specified category
	$children = $this->_cat_handler->getAllChildId( $post_catid ) ;

	foreach( $children as $ch_id ) 
	{
		$ret = $this->_cat_handler->delete_by_id( $ch_id );
		if ( !$ret ) {
			$this->set_error( $this->_cat_handler->get_errors() );
		}

		xoops_notification_deletebyitem( $this->_MODULE_ID , 'category' , $ch_id ) ;
		$this->_delete_photos_by_catid( $ch_id );
	}

	$ret = $this->_cat_handler->delete_by_id( $post_catid );
	if ( !$ret ) {
		$this->set_error( $this->_cat_handler->get_errors() );
	}

	xoops_notification_deletebyitem( $this->_MODULE_ID , 'category' , $post_catid ) ;
	$this->_delete_photos_by_catid( $post_catid );

	if ( $this->has_error() ) {
		$msg  = "DB Error: delete category <br />";
		$msg .= $this->get_format_error();
		redirect_header( $this->_THIS_URL , $this->_TIME_FAIL , $msg ) ;
		exit();
	}

	redirect_header( $this->_THIS_URL , $this->_TIME_SUCCESS , _AM_WEBPHOTO_CATDELETED ) ;
	exit() ;
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

	$weight_arr    = $this->_post_class->get_post('weight');
	$oldweight_arr = $this->_post_class->get_post('oldweight');

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

	$param = array(
		'mode'   => 'edit',
		'parent' => null,
	);

	$this->_print_cat_form( $row, $param );
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

	$get_catid = $this->_post_class->get_post_int('cat_id');

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

// --- class end ---
}

?>