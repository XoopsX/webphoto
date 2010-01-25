<?php
// $Id: update_check.php,v 1.6 2010/01/25 10:03:07 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-01-10 K.OHWADA
// webphoto_lib_base -> webphoto_base_ini
// check_210()
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_item_handler
// 2009-03-15 K.OHWADA
// check_130()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_update_check 
//=========================================================
class webphoto_admin_update_check extends webphoto_base_ini
{
	var $_item_handler;
	var $_file_handler;
	var $_player_handler;
	var $_photo_handler;

	var $_item_count_all;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_update_check ( $dirname , $trust_dirname )
{
	$this->webphoto_base_ini( $dirname , $trust_dirname );

	$this->_item_handler   =& webphoto_item_handler::getInstance(
		$dirname , $trust_dirname );
	$this->_file_handler   =& webphoto_file_handler::getInstance(   
		$dirname , $trust_dirname );
	$this->_player_handler =& webphoto_player_handler::getInstance( 
		$dirname , $trust_dirname );
	$this->_photo_handler  =& webphoto_photo_handler::getInstance( $dirname );

	$this->_item_count_all = $this->_item_handler->get_count_all();

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
		$msg  = '<a href="'. $this->get_url('040') .'">';
		$msg .= _AM_WEBPHOTO_MUST_UPDATE ;
		$msg .= '</a>';
		$str  = $this->build_error_msg( $msg, '', false );

	} elseif ( $this->check_050() ) {
		$msg  = '<a href="'. $this->get_url('050') .'">';
		$msg .= _AM_WEBPHOTO_MUST_UPDATE ;
		$msg .= '</a>';
		$str  = $this->build_error_msg( $msg, '', false );

	} elseif ( $this->check_130() ) {
		$msg  = '<a href="'. $this->get_url('130') .'">';
		$msg .= _AM_WEBPHOTO_MUST_UPDATE ;
		$msg .= '</a>';
		$str  = $this->build_error_msg( $msg, '', false );

	} elseif ( $this->check_210() ) {
		$msg  = '<a href="'. $this->get_url('210') .'">';
		$msg .= _AM_WEBPHOTO_MUST_UPDATE ;
		$msg .= '</a>';
		$str  = $this->build_error_msg( $msg, '', false );
	}

	return $str;
}

function check_040()
{
	if ( $this->_item_count_all > 0 ) {
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

function check_130()
{
	if ( $this->_item_count_all == 0 ) {
		return false;
	}
	if ( $this->_file_handler->get_count_by_kind( _C_WEBPHOTO_FILE_KIND_SMALL ) == 0 ) {
		return true;
	}
	return false;
}

function check_210()
{
	if ( $this->_item_count_all == 0 ) {
		return false;
	}
	if ( $this->_item_handler->get_count_photo() == 0 ) {
		return false;
	}
	if ( $this->_item_handler->get_count_photo_detail_onclick() == 0 ) {
		return true;
	}
	return false;
}

function get_url( $ver )
{
	$url = $this->_MODULE_URL .'/admin/index.php?fct=update_'.$ver ;
	return $url;
}

// --- class end ---
}

?>