<?php
// $Id: item_public.php,v 1.3 2011/05/10 02:56:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-12-12 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// webphoto_lib_base -> webphoto_base_ini
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_item_handler
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_item_public
//=========================================================
class webphoto_item_public extends webphoto_base_ini
{
	var $_config_class;
	var $_cat_handler;
	var $_item_handler;

	var $_cfg_perm_cat_read;
	var $_cfg_perm_item_read;

	var $_item_row = null ;
	var $_error    = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_item_public( $dirname , $trust_dirname )
{
	$this->webphoto_base_ini( $dirname, $trust_dirname );

	$this->_config_class =& webphoto_config::getInstance( $dirname );
	$this->_cat_handler  =& webphoto_cat_handler::getInstance( 
		$dirname, $trust_dirname );
	$this->_item_handler =& webphoto_item_handler::getInstance( 
		$dirname, $trust_dirname );

	$this->_cfg_perm_cat_read  = $this->_config_class->get_by_name( 'perm_cat_read' ) ;
	$this->_cfg_perm_item_read = $this->_config_class->get_by_name( 'perm_item_read' ) ;
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_item_public( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function get_item_row( $item_id )
{
	$row = $this->_item_handler->get_row_by_id( $item_id );
	if ( ! is_array($row ) ) {
		$this->_error = $this->get_constant( 'NOMATCH_PHOTO' ) ;
		return false;
	}

	$cat_id = $row['item_cat_id'] ;
	$status = $row['item_status'] ;

	if ( $status <= 0 ) {
		$this->_error = $this->get_constant( 'NOMATCH_PHOTO' ) ;
		return false;
	}

	if ( $this->_cfg_perm_item_read != _C_WEBPHOTO_OPT_PERM_READ_ALL ) {
		if ( ! $this->_item_handler->check_perm_read_by_row( $row ) ) {

			$this->_error = $this->get_constant( 'NOMATCH_PHOTO' ) ;
			return false;
		}
	}

	if ( $this->_cfg_perm_cat_read != _C_WEBPHOTO_OPT_PERM_READ_ALL ) {
		if ( ! $this->_cat_handler->check_perm_read_by_id( $cat_id ) ) {

			$this->_error = $this->get_constant( 'NOMATCH_PHOTO' ) ;
			return false;
		}
	}

	$this->_item_row = $row ;
	return $row ;
}

function check_item_perm( $perm )
{
	return $this->_item_handler->check_perm_by_perm_groups( $perm ) ; 
}

// --- class end ---
}
?>