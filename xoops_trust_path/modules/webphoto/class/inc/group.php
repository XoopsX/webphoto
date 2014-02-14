<?php
// $Id: group.php,v 1.1 2009/12/16 13:36:20 ohwada Exp $

//=========================================================
// webphoto module
// 2009-12-06 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_group
//=========================================================
class webphoto_inc_group
{
	var $_member_handler;
	var $_groupperm_handler;

	var $_DIRNAME;
	var $_MODULE_ID = 0;

	var $_SYSTEM_GROUPS = 
		array( XOOPS_GROUP_ADMIN, XOOPS_GROUP_USERS, XOOPS_GROUP_ANONYMOUS );

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_group( $dirname )
{
	$this->_DIRNAME = $dirname;

	$this->_member_handler    =& xoops_gethandler('member');
	$this->_groupperm_handler =& xoops_gethandler('groupperm');

	$this->_init_xoops_module( $dirname );
}

function &getSingleton( $dirname )
{
	static $singletons;
	if ( !isset( $singletons[ $dirname ] ) ) {
		$singletons[ $dirname ] = new webphoto_inc_group( $dirname );
	}
	return $singletons[ $dirname ];
}

//---------------------------------------------------------
// group
//---------------------------------------------------------
function delete_group( $group_id )
{
	$group_id = intval($group_id);
	if ( $group_id <= 0 ) {
		return false;	// no action
	}
	if ( in_array( $group_id, $this->_SYSTEM_GROUPS ) ) {
		return false;	// no action
	}
	$this->delete_member_group(   $group_id );
	$this->delete_gperm_by_group( $group_id );
	return true;
}

//---------------------------------------------------------
// member handler
//---------------------------------------------------------
function create_member_group( $name, $desc )
{
	$group = $this->_member_handler->createGroup();
	$group->setVar( 'name',        $name );
	$group->setVar( 'description', $desc );

	$ret = $this->_member_handler->insertGroup( $group );
	if ( !$ret ) {
		return false;
	}

	return $group->getVar('groupid');
}

function delete_member_group( $group_id )
{
	$group = $this->_member_handler->getGroup( $group_id );
	$this->_member_handler->deleteGroup( $group ); 
}

//---------------------------------------------------------
// groupperm handler
//---------------------------------------------------------
function create_gperm_webphoto_groupid( $groupid, $perms )
{
	foreach( $perms as $id ) {
		$this->create_gperm_webphoto_itemid( $groupid, $id );
	}
}

function create_gperm_module_admin( $groupid )
{
	$gperm = $this->_groupperm_handler->create();
	$gperm->setVar('gperm_name',    'module_admin');
	$gperm->setVar('gperm_groupid', $groupid);
	$gperm->setVar('gperm_modid',   1);
	$gperm->setVar('gperm_itemid',  $this->_MODULE_ID);
	$this->_groupperm_handler->insert($gperm);
	unset($gperm);
}

function create_gperm_module_read( $groupid )
{
	$gperm = $this->_groupperm_handler->create();
	$gperm->setVar("gperm_name",    'module_read' );
	$gperm->setVar("gperm_groupid", $groupid );
	$gperm->setVar("gperm_modid",   1 );
	$gperm->setVar("gperm_itemid",  $this->_MODULE_ID );
	$this->_groupperm_handler->insert($gperm) ;
	unset($gperm);
}

function create_gperm_webphoto_itemid( $groupid, $itemid )
{
	$gperm =& $this->_groupperm_handler->create();
	$gperm->setVar("gperm_name",    _C_WEBPHOTO_GPERM_NAME );
	$gperm->setVar("gperm_groupid", $groupid );
	$gperm->setVar("gperm_modid",   $this->_MODULE_ID );
	$gperm->setVar("gperm_itemid",  $itemid );
	$this->_groupperm_handler->insert($gperm) ;
	unset($gperm);
}

function delete_gperm_by_group( $group_id, $mod_id=null )
{
	$this->_groupperm_handler->deleteByGroup( $group_id, $mod_id );
}

//---------------------------------------------------------
// xoops_module
//---------------------------------------------------------
function _init_xoops_module( $dirname )
{
	$module_handler =& xoops_gethandler('module');
	$module = $module_handler->getByDirname( $dirname );
	if ( is_object($module) ) {
		$this->_MODULE_ID = $module->getVar( 'mid' );
	}
}

// --- class end ---
}

?>