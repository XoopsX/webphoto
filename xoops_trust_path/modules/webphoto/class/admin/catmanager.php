<?php
// $Id: catmanager.php,v 1.3 2008/09/12 22:51:27 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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

	var $_get_catid;

	var $_ADMIN_CAT_PHP;

	var $_CAT_MAIN_WIDTH  = _C_WEBPHOTO_CAT_MAIN_WIDTH_DEFAULT;
	var $_CAT_MAIN_HEIGHT = _C_WEBPHOTO_CAT_MAIN_HEIGHT_DEFAULT;
	var $_CAT_SUB_WIDTH   = _C_WEBPHOTO_CAT_SUB_WIDTH_DEFAULT;
	var $_CAT_SUB_HEIGHT  = _C_WEBPHOTO_CAT_SUB_HEIGHT_DEFAULT;

	var $_TIME_SUCCESS = 1;
	var $_TIME_FAIL    = 5;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_catmanager( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_delete_class =& webphoto_photo_delete::getInstance( $dirname );

	$this->_ADMIN_CAT_PHP = $this->_MODULE_URL .'/admin/index.php?fct=catmanager';
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
	if ( ! $this->check_token() ) {
		redirect_header( $this->_ADMIN_INDEX_PHP, $this->_TIME_FAIL, $this->get_token_errors() );
	}

	$post_pid = $this->_post_class->get_post_int('cat_pid');

	$row = $this->_cat_handler->create( true );
	$row_insert = $this->_build_row_by_post( $row );
	$row_insert = $this->_build_img_size( $row_insert );

	$newid = $this->_cat_handler->insert( $row_insert );
	if ( !$newid ) {
		$msg  = "DB Error: insert category";
		$msg .= '<br />'.$this->get_format_error();
		redirect_header( $this->_ADMIN_CAT_PHP , $this->_TIME_FAIL , $msg ) ;
		exit();
	}

	// Check if cid == pid
	if( $newid == $post_pid ) {
		$this->_cat_handler->update_pid( $newid, 0 );
	}

	redirect_header( $this->_ADMIN_CAT_PHP , $this->_TIME_SUCCESS , _AM_WEBPHOTO_CAT_INSERTED ) ;
	exit ;
}

function _build_row_by_post( $row )
{
	$row['cat_pid']         = $this->_post_class->get_post_int('cat_pid');
	$row['cat_gicon_id']    = $this->_post_class->get_post_int('cat_gicon_id');
	$row['cat_weight']      = $this->_post_class->get_post_int('cat_weight');
	$row['cat_title']       = $this->_post_class->get_post_text('cat_title');
	$row['cat_description'] = $this->_post_class->get_post_text('cat_description');
	$row['cat_img_path']    = $this->_build_img_path_by_post();
	$row['cat_perm_post']   = $this->_build_perm_post_by_post();

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

function _build_perm_post_by_post()
{
	$allow_all = $this->_post_class->get_post_int('perm_post_allow_all');
	$perm_arr  = $this->_post_class->get_post('perm_post');

	if ( $allow_all == _C_WEBPHOTO_YES ) {
		return _C_WEBPHOTO_PERM_ALLOW_ALL;
	}

	if ( !is_array($perm_arr) || !count($perm_arr) ) {
		return _C_WEBPHOTO_PERM_DENOY_ALL;
	}

	$arr = array();
	foreach( $perm_arr as $k => $v ) 
	{
		if ( $v == _C_WEBPHOTO_YES ) {
			$arr[] = $k;
		}
	}

	if ( !is_array($arr) || !count($arr) ) {
		return _C_WEBPHOTO_PERM_DENOY_ALL;
	}

	$ret  = _C_WEBPHOTO_PERM_SEPARATOR;
	$ret .= implode( _C_WEBPHOTO_PERM_SEPARATOR, $arr );
	$ret .= _C_WEBPHOTO_PERM_SEPARATOR;
	return $ret;
}

function _build_img_size( $row )
{
	$img_path = $row['cat_img_path'];
	if ( empty($img_path) ) { 
		return $row;
	}

	$full_path = XOOPS_ROOT_PATH . $img_path ;
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
			$width, $height, $this->_CAT_MAIN_WIDTH, $this->_CAT_MAIN_HEIGHT );

	list( $sub_width, $sub_height ) 
		= $this->adjust_image_size(
			$width, $height, $this->_CAT_SUB_WIDTH, $this->_CAT_SUB_HEIGHT );

	$row['cat_orig_width']  = $width;
	$row['cat_orig_height'] = $height;
	$row['cat_main_width']  = $main_width;
	$row['cat_main_height'] = $main_height;
	$row['cat_sub_width']   = $sub_width;
	$row['cat_sub_height']  = $sub_height;

	return $row;
}

//---------------------------------------------------------
// update
//---------------------------------------------------------
function _update()
{
	if ( ! $this->check_token() ) {
		redirect_header( $this->_ADMIN_INDEX_PHP, $this->_TIME_FAIL, $this->get_token_errors() );
	}

	$post_catid = $this->_post_class->get_post_int('cat_id');
	$post_pid   = $this->_post_class->get_post_int('cat_pid');

	// Check if new pid was a child of cid
	if ( $post_pid != 0 ) {
		$children   = $this->_cat_handler->get_all_child_id( $post_catid ) ;
		$children[] = $post_catid ;

		foreach( $children as $child ) 
		{
			if( $child == $post_pid ) {
				$msg = "category looping has occurred" ;
				redirect_header( $this->_ADMIN_CAT_PHP , $this->_TIME_FAIL , $msg ) ;
			}
		}
	}

	$row = $this->_cat_handler->get_row_by_id( $post_catid );
	$row_update = $this->_build_row_by_post( $row );
	$row_update = $this->_build_img_size( $row_update );

	$ret = $this->_cat_handler->update( $row_update );
	if ( !$ret ) {
		$msg  = "DB Error: update category <br />";
		$msg .= $this->get_format_error();
		redirect_header( $this->_ADMIN_CAT_PHP , $this->_TIME_FAIL , $msg ) ;
		exit();
	}

	$url = $this->_ADMIN_CAT_PHP.'&amp;disp=edit&amp;cat_id='.$post_catid;
	redirect_header( $url , $this->_TIME_SUCCESS , _AM_WEBPHOTO_CAT_UPDATED ) ;
	exit() ;
}

//---------------------------------------------------------
// delete
//---------------------------------------------------------
function _delete()
{
	if ( ! $this->check_token() ) {
		redirect_header( $this->_ADMIN_CAT_PHP, $this->_TIME_FAIL, $this->get_token_errors() );
		exit();
	}

	// Delete
	$post_catid = $this->_post_class->get_post_int('cat_id');

	//get all categories under the specified category
	$children = $this->_cat_handler->get_all_child_id( $post_catid ) ;

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
		redirect_header( $this->_ADMIN_CAT_PHP , $this->_TIME_FAIL , $msg ) ;
		exit();
	}

	redirect_header( $this->_ADMIN_CAT_PHP , $this->_TIME_SUCCESS , _AM_WEBPHOTO_CATDELETED ) ;
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
		$this->_delete_class->delete_photo( $row['item_id'] ) ;
	}
}


//---------------------------------------------------------
// weight
//---------------------------------------------------------
function _weight()
{
	if ( ! $this->check_token() ) {
		redirect_header( $this->_ADMIN_CAT_PHP, $this->_TIME_FAIL, $this->get_token_errors() );
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
		redirect_header( $this->_ADMIN_CAT_PHP , $this->_TIME_FAIL , $msg ) ;
		exit();
	}

	redirect_header( $this->_ADMIN_CAT_PHP , $this->_TIME_SUCCESS , _WEBPHOTO_DBUPDATED ) ;
	exit() ;
}

//---------------------------------------------------------
// print form
//---------------------------------------------------------
function _print_new_form()
{
	// New
	$row = $this->_cat_handler->create( true );
	$row['cat_pid'] = $this->_get_catid;

	$this->_print_cat_form( 'new', $row ) ;
}

function _print_edit_form()
{
	// Editing
	$row = $this->_cat_handler->get_row_by_id( $this->_get_catid );
	if ( !is_array($row ) ) {
		redirect_header( $this->_ADMIN_CAT_PHP , $this->_TIME_FAIL , _AM_WEBPHOTO_ERR_NO_RECORD ) ;
		exit();
	}

	$this->_print_cat_form( 'edit', $row );
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
	echo '<p><a href="'. $this->_ADMIN_CAT_PHP .'&amp;disp=new">';
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
function _print_cat_form( $mode, $row )
{
	$cat_form =& webphoto_admin_cat_form::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );
	$cat_form->print_form( $mode, $row );
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
	echo $this->build_bread_crumb( $this->get_admin_title( 'CATMANAGER' ), $this->_ADMIN_CAT_PHP );
	echo $this->build_admin_title( 'CATMANAGER' );

	$get_catid = $this->_post_class->get_post_int('cat_id');

	$row = $this->_cat_handler->get_row_by_id( $get_catid );
	if ( !is_array($row ) ) {
		redirect_header( $this->_ADMIN_CAT_PHP , $this->_TIME_FAIL , _AM_WEBPHOTO_ERR_NO_RECORD ) ;
		exit();
	}

	echo "<h4>". $this->sanitize( $row['cat_title'] ) ."</h4>\n";

	$cat_form =& webphoto_admin_cat_form::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );
	$cat_form->print_del_confirm( $get_catid );

	xoops_cp_footer();
}

// --- class end ---
}

?>