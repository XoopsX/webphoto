<?php
// $Id: groupperm_form.php,v 1.4 2010/02/17 04:34:47 ohwada Exp $

//=========================================================
// webphoto module
// 2009-12-06 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-02-15 K.OHWADA
// check_right()
// 2010-01-20 K.OHWADA
// XOOPS_CUBE_LEGACY
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_lib_groupperm_form
// refer myalubum's MyXoopsGroupPermForm
//=========================================================

class webphoto_lib_groupperm_form
{
	var $_module_handler;
	var $_member_handler;
	var $_groupperm_handler;

	var $_CHECKED = 'checked="checked"';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_groupperm_form()
{
	$this->_module_handler    =& xoops_gethandler('module');
	$this->_member_handler    =& xoops_gethandler('member');
	$this->_groupperm_handler =& xoops_gethandler('groupperm');
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_groupperm_form();
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function build_param( $mod_id, $action=null )
{
	$arr = array( 
		'cols'          => 4 ,
		'modid'         => $mod_id ,
		'action'        => $action ,
		'g_ticket'      => $this->get_token() ,
		'xoops_dirname' => $this->get_dirname( $mod_id ) ,
	);
	if ( defined( 'XOOPS_CUBE_LEGACY' ) ) {
		$arr['xoops_cube_legacy'] = XOOPS_CUBE_LEGACY;
	}
	return array_merge( $arr , $this->get_lang() );
}

function build_group_list( $mod_id, $perm_name, $item_array, $flag_admin=false )
{
	$system_list = $this->_member_handler->getGroupList();

	$group_list = array();
	foreach (array_keys($system_list) as $id) 
	{
		$group_list[ $id ] = $this->build_group_list_single( 
			$mod_id, $id, $system_list[ $id ], $perm_name, $item_array, $flag_admin );
	}

	return $group_list;
}

function build_group_list_single( $mod_id, $group_id, $group_name, $perm_name, $item_array, $flag_admin=false )
{
	$module_admin_right = $this->check_right( 'module_admin', $mod_id, $group_id ) ;
	$module_read_right  = $this->check_right( 'module_read',  $mod_id, $group_id ) ;

	$all_checked = ( $flag_admin && $module_admin_right );

	$item_id_array = $this->_groupperm_handler->getItemIds( $perm_name, $group_id, $mod_id );

	$item_list = array();
	foreach( $item_array as $item_id => $item_name ) 
	{
		$item_list[ $item_id ] = array(
			'item_id'   => $item_id ,
			'item_name' => $item_name ,
			'checked'   => $this->build_checked_array( $item_id, $item_id_array, $all_checked ) ,
		);
	}

	$group_list = array(
		'group_id'   => $group_id ,
		'group_name' => $group_name ,
		'perm_name'  => $perm_name , 
		'item_list'  => $item_list ,
		'module_admin_checked' => $this->build_checked( $module_admin_right ) ,
		'module_read_checked'  => $this->build_checked( $module_read_right ) ,
	);

	return $group_list;
}

function check_right( $perm_name, $mod_id, $group_id )
{
	return $this->_groupperm_handler->checkRight( $perm_name, $mod_id, $group_id );
}

function build_checked_array( $val, $array, $all_checked )
{
	if ( $all_checked ) {
		return $this->_CHECKED;
	}
	if ( is_array($array) && in_array( $val, $array ) ) {
		return $this->_CHECKED ;
	}
	return '';
}

function build_checked( $val )
{
	if ( $val ) {
		return $this->_CHECKED ;
	}
	return '';
}

function get_dirname( $id )
{
	$obj = $this->_module_handler->get( $id );
	if ( is_object($obj) ) {
		return $obj->getVar( 'dirname', 'n' );
	}
	return false;
}

function get_group_name( $id )
{
	$obj = $this->_member_handler->getGroup( $id );
	if ( is_object($obj) ) {
		return $obj->getVar('name');
	}
	return false;
}

function get_token()
{
	global $xoopsGTicket;
	if ( is_object($xoopsGTicket) ) {
		return $xoopsGTicket->issue( __LINE__ ) ;
	}
	return false;
}

function get_lang()
{
	$arr = array( 
		'lang_none'   => _NONE,
		'lang_all'    => _ALL,
		'lang_submit' => _SUBMIT,
		'lang_cancel' => _CANCEL,
	);
	return $arr;
}

// --- class end ---
}

?>