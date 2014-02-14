<?php
// $Id: group_permission.php,v 1.6 2010/02/17 04:34:47 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-02-15 K.OHWADA
// add $flag_admin in has_perm()
// 2009-11-11 K.OHWADA
// webphoto_inc_handler -> webphoto_inc_base_ini
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
// 2008-07-01 K.OHWADA
// webphoto_xoops_base -> xoops_gethandler()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_group_permission
// caller webphoto_permission webphoto_inc_xoops_version
//=========================================================
class webphoto_inc_group_permission extends webphoto_inc_base_ini
{
	var $_cached_perms = array();

	var $_xoops_mid = 0;
	var $_xoops_uid = 0;
	var $_xoops_groups = null;
	var $_is_module_adimin = false;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_group_permission( $dirname , $trust_dirname )
{
	$this->webphoto_inc_base_ini();
	$this->init_base_ini( $dirname , $trust_dirname );
	$this->init_handler( $dirname );

	$this->_init_xoops( $dirname );
	$this->_init_permission( $dirname );
}

function &getSingleton( $dirname , $trust_dirname )
{
	static $singletons;
	if ( !isset( $singletons[ $dirname ] ) ) {
		$singletons[ $dirname ] = new webphoto_inc_group_permission( $dirname , $trust_dirname );
	}
	return $singletons[ $dirname ];
}

//---------------------------------------------------------
// has permit
//---------------------------------------------------------
function has_perm( $name, $flag_admin=false )
{
	if ( $flag_admin && $this->_is_module_adimin ) {
		return true;
	}
	$bit = constant( strtoupper( '_B_WEBPHOTO_GPERM_' .$name ) );
	return $this->_has_perm_by_bit( $bit );
}

//---------------------------------------------------------
// cache
//---------------------------------------------------------
function _has_perm_by_bit( $bit )
{
	if ( $this->_cached_perms & $bit ) {
		return true; 
	}
	return false;
}

//---------------------------------------------------------
// xoops_group_permission
//---------------------------------------------------------
function _init_permission( $dirname )
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

	$this->_cached_perms = $perms ;
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
		$this->_is_module_adimin = $xoopsUser->isAdmin( $this->_xoops_mid );
	}
}

// --- class end ---
}

?>