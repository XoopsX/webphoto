<?php
// $Id: group_permission.php,v 1.2 2008/07/05 12:54:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-07-01 K.OHWADA
// correct SQL error
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_group_permission
//=========================================================
class webphoto_inc_group_permission extends webphoto_inc_handler
{
	var $_xoops_class;

	var $_cached_perms = array();

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_group_permission()
{
	$this->webphoto_inc_handler();

	$this->_xoops_class =& webphoto_xoops_base::getInstance();
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_inc_group_permission();
	}
	return $instance;
}

function init( $dirname )
{
	$this->init_handler( $dirname );
	$this->_init_cache( $dirname );
}

//---------------------------------------------------------
// has permit
//---------------------------------------------------------
function has_perm( $name )
{
	$bit = constant( strtoupper( '_B_WEBPHOTO_GPERM_' .$name ) );
	return $this->_has_perm_by_bit( $bit );
}

//---------------------------------------------------------
// cache
//---------------------------------------------------------
function _has_perm_by_bit( $bit )
{
	if ( $this->_get_cached_perm() & $bit ) {
		return true; 
	}
	return false;
}

function _get_cached_perm()
{
	$uid = $this->_get_uid();
	if ( isset( $this->_cached_perms[ $this->_DIRNAME ][ $uid ] ) ) {
		return  $this->_cached_perms[ $this->_DIRNAME ][ $uid ] ;
	}
	return false;
}

function _init_cache( $dirname )
{
	$uid = $this->_get_uid();

// probably uid is unnecessary
// because one process runing by same user

// set if not set
	if ( !isset( $this->_cached_perms[ $dirname ][ $uid ] ) ) {
		$this->_cached_perms[ $dirname ][ $uid ]
			= $this->_get_permission( $dirname ) ; 
	}
}

//---------------------------------------------------------
// xoops_group_permission
//---------------------------------------------------------
function _get_permission( $dirname )
{
	$perms = 0 ;

// correct SQL error
// no action when not installed this module
	$mid = $this->_get_mid( $dirname );
	if ( empty($mid) ) {
		return $perms;
	}

	$sql  = "SELECT gperm_itemid FROM ". $this->_db->prefix( 'group_permission' );
	$sql .= " WHERE gperm_modid=". intval($mid) ;
	$sql .= " AND gperm_name=".$this->quote( _C_WEBPHOTO_GPERM_NAME );
	$sql .= " AND ( ". $this->_build_where_groupid(). " )";

	$rows = $this->get_rows_by_sql( $sql );
	if ( !is_array($rows) || !count($rows) ) {
		return 0;
	}

	foreach( $rows as $row ) {
		$perms |= $row['gperm_itemid'] ;
	}

	return $perms;
}

function _build_where_groupid()
{
	$groups = $this->_get_groups();

	if( is_array($groups) && count($groups) ) {
		$where = "gperm_groupid IN (" ;
		foreach( $groups as $groupid ) {
			$where .= "$groupid," ;
		}
		$where = substr( $where , 0 , -1 ) . ")" ;

	} else {
		$where = "gperm_groupid=".XOOPS_GROUP_ANONYMOUS ;
	}

	return $where;
}

//---------------------------------------------------------
// xoops class
//---------------------------------------------------------
function _get_mid( $dirname )
{
	return $this->_xoops_class->get_module_mid_by_dirname( $dirname );
}

function _get_uid()
{
	return $this->_xoops_class->get_my_user_uid();
}

function _get_groups()
{
	return $this->_xoops_class->get_my_user_groups();
}

// --- class end ---
}

?>