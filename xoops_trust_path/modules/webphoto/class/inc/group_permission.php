<?php
// $Id: group_permission.php,v 1.3 2008/07/06 04:41:31 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-07-01 K.OHWADA
// webphoto_xoops_base -> xoops_gethandler()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_group_permission
//=========================================================
class webphoto_inc_group_permission extends webphoto_inc_handler
{
	var $_cached_perms = array();

	var $_xoops_mid = 0;
	var $_xoops_uid = 0;
	var $_xoops_groups = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_group_permission()
{
	$this->webphoto_inc_handler();
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
	$this->_init_xoops( $dirname );
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
	if ( isset( $this->_cached_perms[ $this->_DIRNAME ][ $this->_xoops_uid ] ) ) {
		return  $this->_cached_perms[ $this->_DIRNAME ][ $this->_xoops_uid ] ;
	}
	return false;
}

function _init_cache( $dirname )
{
// probably uid is unnecessary
// because one process runing by same user

// set if not set
	if ( !isset( $this->_cached_perms[ $dirname ][ $this->_xoops_uid ] ) ) {
		$this->_cached_perms[ $dirname ][ $this->_xoops_uid ]
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
	if ( empty($this->_xoops_mid) ) {
		return $perms;
	}

	$sql  = "SELECT gperm_itemid FROM ". $this->_db->prefix( 'group_permission' );
	$sql .= " WHERE gperm_modid=". intval( $this->_xoops_mid ) ;
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
	if( is_array($this->_xoops_groups) && count($this->_xoops_groups) ) {
		$where = "gperm_groupid IN (" ;
		foreach( $this->_xoops_groups as $groupid ) {
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
function _init_xoops( $dirname )
{
	$module_handler =& xoops_gethandler('module');
	$module = $module_handler->getByDirname( $dirname );
	if ( is_object($module) ) {
		$this->_xoops_mid = $module->getVar( 'mid' );
	}

	global $xoopsUser;
	if ( is_object($xoopsUser) ) {
		$this->_xoops_uid    = $xoopsUser->getVar( 'uid' );
		$this->_xoops_groups = $xoopsUser->getGroups();
	}
}

// --- class end ---
}

?>