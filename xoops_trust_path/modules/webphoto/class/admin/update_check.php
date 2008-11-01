<?php
// $Id: update_check.php,v 1.1 2008/11/01 23:53:35 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_update_check 
//=========================================================
class webphoto_admin_update_check extends webphoto_lib_base
{
	var $_item_handler;
	var $_player_handler;
	var $_photo_handler;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_update_check ( $dirname , $trust_dirname )
{
	$this->webphoto_lib_base( $dirname , $trust_dirname );

	$this->_item_handler   =& webphoto_player_handler::getInstance( $dirname );
	$this->_player_handler =& webphoto_player_handler::getInstance( $dirname );
	$this->_photo_handler  =& webphoto_photo_handler::getInstance(  $dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_update_check( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// check
//---------------------------------------------------------
function build_msg()
{
	$str = null ;

	if ( $this->check_040() ) {
		$msg  = '<a href="'. $this->get_url_040() .'">';
		$msg .= _AM_WEBPHOTO_MUST_UPDATE ;
		$msg .= '</a>';
		$str  = $this->build_error_msg( $msg, '', false );

	} elseif ( $this->check_050() ) {
		$msg  = '<a href="'. $this->get_url_050() .'">';
		$msg .= _AM_WEBPHOTO_MUST_UPDATE ;
		$msg .= '</a>';
		$str  = $this->build_error_msg( $msg, '', false );
	}

	return $str;
}

function check_040()
{
	if ( $this->_item_handler->get_count_all() > 0 ) {
		return false;
	}
	if ( $this->_photo_handler->get_count_all() > 0 ) {
		return true;
	}
	return false;
}

function check_050()
{
	if ( $this->_player_handler->get_count_all() == 0 ) {
		return true;
	}
	return false;
}

function get_url_040()
{
	$url = $this->_MODULE_URL .'/admin/index.php?fct=update_040' ;
	return $url;
}

function get_url_050()
{
	$url = $this->_MODULE_URL .'/admin/index.php?fct=update_050' ;
	return $url;
}

// --- class end ---
}

?>