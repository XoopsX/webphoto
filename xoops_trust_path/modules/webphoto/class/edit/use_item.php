<?php
// $Id: use_item.php,v 1.2 2010/02/17 04:34:47 ohwada Exp $

//=========================================================
// webphoto module
// 2010-02-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-02-15 K.OHWADA
// check_edit()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_use_item
//=========================================================
class webphoto_edit_use_item extends webphoto_base_this
{
	var $_cfg_gmap_apikey;
	var $_cfg_perm_item_read;

	var $_item_array;
	var $_show_array;
	var $_edit_array;

	var $_flag_admin = false;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_use_item( $dirname, $trust_dirname )
{
	$this->webphoto_base_this( $dirname, $trust_dirname );

	$this->_cfg_gmap_apikey    = $this->get_config_by_name( 'gmap_apikey' );
	$this->_cfg_perm_item_read = $this->get_config_by_name( 'perm_item_read' );

	$this->_item_array = $this->explode_ini('submit_item_list');
	$this->_show_array = $this->explode_ini('submit_show_list');
	$this->_edit_array = $this->explode_ini('edit_list');
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_use_item( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// set param
//---------------------------------------------------------
function set_flag_admin( $val )
{
	$this->_flag_admin = (bool)$val;
}

//---------------------------------------------------------
// submit edit form
//---------------------------------------------------------
function use_item_perm_read()
{
	if (( $this->_cfg_perm_item_read > 0 ) && 
	      $this->use_item_or_admin('perm_read') ) {
		return true;
	}
	return false;
}

function use_item_perm_level()
{
	if (( $this->_cfg_perm_item_read > 0 ) && 
		  $this->use_item('perm_level') ) {
		return true;
	}
	return false;
}

function editable_item_perm_level()
{
	if ( $this->use_item_perm_level() &&
	     $this->check_edit_or_admin('perm_level') ) {
		return true;
	}
	return false;
}

function use_gmap()
{
	if ( $this->_cfg_gmap_apikey && $this->check_show_or_admin('gmap') ) {
		return true;
	}
	return false;
}

function use_item_or_admin( $key )
{
	if ( $this->_flag_admin || $this->use_item( $key ) ) {
		return true;
	}
	return false;
}

function check_show_or_admin( $key )
{
	if ( $this->_flag_admin || $this->check_show( $key ) ) {
		return true;
	}
	return false;
}

function check_edit_or_admin( $key )
{
	if ( $this->_flag_admin || $this->check_edit( $key ) ) {
		return true;
	}
	return false;
}

function use_item( $key )
{
	return in_array( $key, $this->_item_array );
}

function check_show( $key )
{
	return in_array( $key, $this->_show_array );
}

function check_edit( $key )
{
	return in_array( $key, $this->_edit_array );
}

// --- class end ---
}

?>