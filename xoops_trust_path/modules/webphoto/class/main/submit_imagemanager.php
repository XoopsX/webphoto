<?php
// $Id: submit_imagemanager.php,v 1.12 2010/11/16 23:43:38 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-11 K.OHWADA
// typo $image_params
// 2010-10-01 K.OHWADA
// create_image_params_by_photo()
// 2010-03-18 K.OHWADA
// format_and_insert_item()
// 2009-04-19 K.OHWADA
// use template
// 2009-01-10 K.OHWADA
// webphoto_imagemanager_submit -> webphoto_edit_imagemanager_submit
// 2009-01-04 K.OHWADA
// webphoto_photo_edit_form -> webphoto_imagemanager_form
// 2008-12-12 K.OHWADA
// set_flag_row_extend()
// 2008-10-01 K.OHWADA
// webphoto_photo_action
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// 2008-07-01 K.OHWADA
// used upload_fetch_photo()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_submit_imagemanager
//=========================================================
class webphoto_main_submit_imagemanager extends webphoto_edit_imagemanager_submit
{
	var $_THIS_CLOSE_FCT  = 'close';
	var $_THIS_CLOSE_URL ;

	var $_TIME_SUCCESS = 3;
	var $_TIME_FAILED  = 5;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_submit_imagemanager( $dirname , $trust_dirname )
{
	$this->webphoto_edit_imagemanager_submit( $dirname , $trust_dirname );

	$this->_THIS_CLOSE_URL = $this->_MODULE_URL .'/index.php?fct='. $this->_THIS_CLOSE_FCT ;
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance))  {
		$instance = new webphoto_main_submit_imagemanager( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	$ret = $this->submit_check();
	if ( !$ret ) {
		redirect_header( 
			$this->get_redirect_url() , 
			$this->get_redirect_time() ,
			$this->get_redirect_msg()
		) ;
		exit();
	}

	$op = $this->get_post_text( 'op' );
	switch ( $op ) 
	{
		case 'submit':
			$this->_submit();
			break;
	}

	$this->_print_form_imagemanager();
}

//---------------------------------------------------------
// create item row
//---------------------------------------------------------
function _create_item_row_default()
{
	$row = $this->_item_create_class->create( true );
	$row['item_cat_id'] = $this->get_post_cat_id() ;
	return $row ;
}

function _create_item_row_by_post()
{
	$row = $this->_item_create_class->create( true );
	$row['item_cat_id'] = $this->get_post_cat_id() ;
	$row['item_title']  = $this->get_post_text( 'item_title' ) ;
	return $row ;
}

//---------------------------------------------------------
// submit
//---------------------------------------------------------
function _submit()
{
	$is_failed = false;

// exit if error
	$this->check_token_and_redirect( $this->_THIS_CLOSE_URL, $this->_TIME_FAILED );

	$ret1 = $this->_submit_exec();
	$ret2 = $this->build_failed_msg( $ret1 );
	if ( !$ret2 ) {
		$is_failed = true;
	}

	list( $url, $time, $msg ) = $this->build_redirect( 
		$this->_build_redirect_param( $is_failed ) );

	redirect_header( $url, $time, $msg );
	exit();
}

function _submit_exec()
{
	$this->clear_msg_array();

	$item_row = $this->_create_item_row_by_post();

	$ret = $this->submit_exec_check( $item_row );
	if ( $ret < 0 ) {
		return $ret ;
	}

	$ret = $this->_submit_exec_fetch( $item_row );
	if ( $ret < 0 ) {
		return $ret ;
	}

	$item_row    = $this->_row_fetch ;
	$photo_name  = $this->_photo_tmp_name ;

// --- insert item ---
	$item_row = $this->build_item_row_submit_insert( $item_row );
	$item_id  = $this->format_and_insert_item( $item_row );
	if ( !$item_id ) {
		return _C_WEBPHOTO_ERR_DB ;
	}

	$item_row['item_id'] = $item_id;
	$this->_row_create = $item_row;

// --- insert files
	$ret = $this->_insert_media_files( $item_row, $photo_name );
	if ( $ret < 0 ) {
		return $ret;
	}

// --- update item ---
	$item_row = $this->build_item_row_submit_update( $item_row);
	$ret = $this->format_and_update_item( $item_row );
	if ( !$ret ) {
		return _C_WEBPHOTO_ERR_DB;
	}
	$this->_row_create = $item_row;

	$this->unlink_uploaded_files();
}

function _submit_exec_fetch( $item_row )
{
	$this->_row_fetch = $item_row ;

// Check if upload file name specified
	if ( ! $this->check_xoops_upload_file( $flag_thumb=false ) ) {
		return _C_WEBPHOTO_ERR_NO_SPECIFIED;
	}

	$ret = $this->submit_exec_fetch_photo( $item_row );
	if ( $ret < 0 ) { 
		return $ret;	// failed
	}
	if ( empty($this->_photo_tmp_name) ) {
		return _C_WEBPHOTO_ERR_NO_IMAGE;
	}
	return 0;
}

function _build_redirect_param( $is_failed )
{
	$param = array(
		'is_failed'   => $is_failed ,
		'url_success' => $this->_THIS_CLOSE_URL ,
		'url_failed'  => $this->_THIS_CLOSE_URL , 
		'msg_success' => $this->get_constant('SUBMIT_RECEIVED') ,
	);
	return $param ;
}

//---------------------------------------------------------
// media files 
//---------------------------------------------------------
function _insert_media_files( $item_row )
{
	$ret = $this->_create_media_file_params( $item_row );
	if ( $ret < 0 ) {
		return $ret;
	}

// --- insert file ---
	$this->_file_id_array = $this->insert_media_files_from_params( $item_row );
	return 0;
}

function _create_media_file_params( $item_row )
{
	$item_ext  = $item_row['item_ext'] ;

	$this->init_photo_create();
	$photo_param = $this->build_photo_param( $item_row );

	list( $ret, $cont_param ) = $this->create_cont_param( $photo_param );
	if ( $ret < 0 ) {
		return $ret ;
	}

	$file_params = array(
		'cont'   => $cont_param ,
	);

	if ( is_array($cont_param) ) {
		$image_params = $this->create_image_params_by_photo( $photo_param );
		if ( is_array($image_params) ) {
			$file_params = $file_params + $image_params ;
		}
	}

	$this->_media_file_params = $file_params ;
	return 0;
}

//---------------------------------------------------------
// print form
//---------------------------------------------------------
function _print_form_imagemanager()
{
	$form_class =& webphoto_edit_imagemanager_form::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );

	$template = 'db:'. $this->_DIRNAME .'_main_submit_imagemanager.html';

	$row = $this->_create_item_row_default() ;

	$param = array(
		'has_resize'    => $this->_has_image_resize,
		'allowed_exts'  => $this->get_normal_exts() ,
	);

	$arr = $form_class->build_form_imagemanager( $row, $param );
	$arr['xoops_themecss'] = $this->_xoops_class->get_xoops_themecss();

	$tpl = new XoopsTpl() ;
	$tpl->assign( $arr ) ;
	$tpl->display( $template ) ;
}

// --- class end ---
}

?>