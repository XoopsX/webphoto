<?php
// $Id: item_delete.php,v 1.3 2010/11/16 23:43:38 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-11 K.OHWADA
// file_id_to_item_name()
// 2009-11-11 K.OHWADA
// $trust_dirname
// 2009-01-10 K.OHWADA
// webphoto_photo_delete -> webphoto_edit_item_delete
// 2008-10-01 K.OHWADA
// delete_photo_by_item_row()
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// added delete_maillogs();
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_item_delete
//=========================================================
class webphoto_edit_item_delete extends webphoto_lib_error
{
	var $_item_handler;
	var $_file_handler;
	var $_vote_handler;
	var $_p2t_handler;
	var $_maillog_handler;
	var $_mail_unlink_class ;
	var $_utility_class;

	var $_MODULE_ID = 0;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_item_delete( $dirname , $trust_dirname )
{
	$this->webphoto_lib_error();

	$this->_item_handler      
		=& webphoto_item_handler::getInstance( $dirname , $trust_dirname );
	$this->_file_handler      
		=& webphoto_file_handler::getInstance( $dirname , $trust_dirname );
	$this->_vote_handler      
		=& webphoto_vote_handler::getInstance( $dirname , $trust_dirname );
	$this->_p2t_handler       
		=& webphoto_p2t_handler::getInstance(  $dirname , $trust_dirname );
	$this->_maillog_handler   
		=& webphoto_maillog_handler::getInstance( $dirname , $trust_dirname );

	$this->_mail_unlink_class =& webphoto_edit_mail_unlink::getInstance( $dirname );
	$this->_utility_class =& webphoto_lib_utility::getInstance();

	$this->_init_xoops_param();
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_item_delete( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// delete
//---------------------------------------------------------
function delete_photo_by_item_id( $item_id )
{
	$item_row = $this->_item_handler->get_row_by_id( $item_id );
	return $this->delete_photo_by_item_row( $item_row ) ;
}

function delete_photo_by_item_row( $item_row )
{
	if ( !is_array($item_row) ) {
		return true;	// no action
	}

	$item_id = $item_row['item_id'] ;

	$this->delete_files_with_file( $item_row );
	$this->delete_maillogs( $item_id );

	$ret = $this->_item_handler->delete_by_id( $item_id );
	if ( !$ret ) {
		$this->set_error( $this->_item_handler->get_errors() );
	}

	$ret = $this->_p2t_handler->delete_by_photoid( $item_id );
	if ( !$ret ) {
		$this->set_error( $this->_p2t_handler->get_errors() );
	}

	$ret = $this->_vote_handler->delete_by_photoid( $item_id );
	if ( !$ret ) {
		$this->set_error( $this->_vote_handler->get_errors() );
	}

	xoops_comment_delete( $this->_MODULE_ID , $item_id ) ;
	xoops_notification_deletebyitem( $this->_MODULE_ID , 'photo' , $item_id ) ;

	return $this->return_code();
}

function delete_files_with_file( $item_row )
{
	$item_id = $item_row['item_id'];

// unlink files
	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_FILE_ID; $i++ ) {
		$name = $this->_item_handler->file_id_to_item_name( $i );
		$file_id = $item_row[ $name ];
		if ( $file_id > 0 ) {
			$file_path = $this->unlink_file_by_id( $file_id );
		}
	}

	$ret = $this->_file_handler->delete_by_itemid( $item_id );
	if ( !$ret ) {
		$this->set_error( $this->_file_handler->get_errors() );
	}
	return $ret;
}

function delete_maillogs( $photo_id )
{
	$maillog_rows = $this->_maillog_handler->get_rows_by_photoid( $photo_id );
	if ( ! is_array($maillog_rows) ) {
		return true;	// no action
	}

	foreach ( $maillog_rows as $maillog_row ) {
		$this->delete_maillog_single( $photo_id, $maillog_row );
	}
}

function delete_maillog_single( $photo_id, $maillog_row )
{
	$photo_id_array = $this->_maillog_handler->build_photo_ids_row_to_array( $maillog_row );
	if ( is_array($photo_id_array) && ( count($photo_id_array) > 1 ) ) {
		return $this->remove_maillog_photoid( $photo_id, $photo_id_array, $maillog_row );
	}
	return $this->delete_maillog_with_file( $maillog_row );
}

function remove_maillog_photoid( $photo_id, $photo_id_array, $maillog_row )
{
	$arr = array();
	foreach ( $photo_id_array as $id ) {
		if ( $id != $photo_id ) {
			$arr[] = $id ;
		}
	}

	$row_update = $maillog_row ;
	$row_update['maillo_photo_ids'] 
		= $this->_maillog_handler->build_photo_ids_array_to_str( $arr );

	$ret = $this->_maillog_handler->update( $row_update );
	if ( !$ret ) {
		$this->set_error( $this->_maillog_handler->get_errors() );
	}
	return $ret;
}

function delete_maillog_with_file( $maillog_row )
{
	$this->_mail_unlink_class->unlink_by_maillog_row( $maillog_row );

	$ret = $this->_maillog_handler->delete( $maillog_row );
	if ( !$ret ) {
		$this->set_error( $this->_maillog_handler->get_errors() );
	}
	return $ret;
}

function unlink_file_by_id( $file_id )
{
	$file = $this->_file_handler->get_full_path_by_id( $file_id );
	$this->_utility_class->unlink_file( $file );
}

//---------------------------------------------------------
// xoops param
//---------------------------------------------------------
function _init_xoops_param()
{
	global $xoopsModule;
	if ( is_object($xoopsModule) ) {
		$this->_MODULE_ID = $xoopsModule->mid();
	}
}

// --- class end ---
}

?>