<?php
// $Id: photo_delete.php,v 1.1 2008/06/21 12:22:23 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_photo_delete
//=========================================================
class webphoto_photo_delete extends webphoto_lib_error
{
	var $_photo_handler;
	var $_vote_handler;
	var $_p2t_handler;

	var $_MODULE_ID = 0;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_photo_delete( $dirname )
{
	$this->webphoto_lib_error();

	$this->_photo_handler =& webphoto_photo_handler::getInstance( $dirname );
	$this->_vote_handler  =& webphoto_vote_handler::getInstance( $dirname );
	$this->_p2t_handler   =& webphoto_p2t_handler::getInstance(  $dirname );

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

	$row = $this->_photo_handler->get_row_by_id( $photo_id );
	if ( !is_array($row) ) {
		return true;	// no action
	}

	$file_path  = $row['photo_file_path'];
	$photo_path = $row['photo_cont_path'];
	$thumb_path = $row['photo_thumb_path'];

	$ret = $this->_photo_handler->delete_by_id( $photo_id );
	if ( !$ret ) {
		$this->set_error( $this->_photo_handler->get_errors() );
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

	$this->unlink_path( $file_path );
	$this->unlink_path( $photo_path );
	$this->unlink_path( $thumb_path );

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