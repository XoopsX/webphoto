<?php
// $Id: photo_delete.php,v 1.2 2008/08/25 19:28:05 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_photo_delete
//=========================================================
class webphoto_photo_delete extends webphoto_lib_error
{
	var $_item_handler;
	var $_file_handler;
	var $_vote_handler;
	var $_p2t_handler;

	var $_MODULE_ID = 0;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_photo_delete( $dirname )
{
	$this->webphoto_lib_error();

	$this->_item_handler =& webphoto_item_handler::getInstance( $dirname );
	$this->_file_handler =& webphoto_file_handler::getInstance( $dirname );
	$this->_vote_handler =& webphoto_vote_handler::getInstance( $dirname );
	$this->_p2t_handler  =& webphoto_p2t_handler::getInstance(  $dirname );

	$this->_init_xoops_param();
}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_photo_delete( $dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// delete
//---------------------------------------------------------
function delete_photo( $photo_id )
{
	$photo_id = intval($photo_id);

	$item_row = $this->_item_handler->get_row_by_id( $photo_id );
	if ( !is_array($item_row) ) {
		return true;	// no action
	}

// unlink files
	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_FILE_ID; $i++ ) {
		$file_id = $item_row[ 'item_file_id_'.$i ];
		if ( $file_id > 0 ) {
			$file_path = $this->_file_handler->get_cached_value_by_id_name(
				$file_id, 'file_path' );
			$this->unlink_path( $file_path );
		}
	}

	$ret = $this->_item_handler->delete_by_id( $photo_id );
	if ( !$ret ) {
		$this->set_error( $this->_item_handler->get_errors() );
	}

	$ret = $this->_file_handler->delete_by_itemid( $photo_id );
	if ( !$ret ) {
		$this->set_error( $this->_file_handler->get_errors() );
	}

	$ret = $this->_p2t_handler->delete_by_photoid( $photo_id );
	if ( !$ret ) {
		$this->set_error( $this->_p2t_handler->get_errors() );
	}

	$ret = $this->_vote_handler->delete_by_photoid( $photo_id );
	if ( !$ret ) {
		$this->set_error( $this->_vote_handler->get_errors() );
	}

	xoops_comment_delete( $this->_MODULE_ID , $photo_id ) ;
	xoops_notification_deletebyitem( $this->_MODULE_ID , 'photo' , $photo_id ) ;

	return $this->return_code();
}

function unlink_path( $path )
{
	$file = XOOPS_ROOT_PATH . $path;
	if ( $path && $file && file_exists($file) && is_file($file) && !is_dir($file) ) {
		unlink( $file );
	}
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