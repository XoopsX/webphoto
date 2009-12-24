<?php
// $Id: groupperm_form.php,v 1.2 2009/12/24 06:32:22 ohwada Exp $

//=========================================================
// webphoto module
// 2009-12-06 K.OHWADA
//=========================================================

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
	return array_merge( $arr , $this->get_lang() );
}

function build_group_list( $mod_id, $perm_name, $item_array )
{
	$system_list = $this->_member_handler->getGroupList();

	$group_list = array();
	foreach (array_keys($system_list) as $id) 
	{
		$group_list[ $id ] = $this->build_group_list_single( 
			$mod_id, $id, $system_list[ $id ], $perm_name, $item_array  );
	}

	return $group_list;
}

function build_group_list_single( $mod_id, $group_id, $group_name, $perm_name, $item_array )
{
	$item_id_array = $this->_groupperm_handler->getItemIds( $perm_name, $group_id, $mod_id );

	$item_list = array();
	foreach( $item_array as $item_id => $item_name ) 
	{
		$item_list[ $item_id ] = array(
			'item_id'   => $item_id ,
			'item_name' => $item_name ,
			'checked'   => $this->build_checked_array( $item_id, $item_id_array ) ,
		);
	}

	$group_list = array(
		'group_id'   => $group_id ,
		'group_name' => $group_name ,
		'perm_name'  => $perm_name , 
		'item_list'  => $item_list ,
		'module_admin_checked' => $this->get_checked_module( 'module_admin', $group_id ) ,
		'module_read_checked'  => $this->get_checked_module( 'module_read',  $group_id ) ,
	);

	return $group_list;
}

function get_checked_module( $perm_name, $id )
{
	$item_id_array = $this->_groupperm_handler->getItemIds( $perm_name, $id, 1 );
	return $this->build_checked_module( $item_id_array ) ;
}

function build_checked_module( $array )
{
	$str = '';
	if ( isset( $array[0] ) && $array[0] ) {
		$str = $this->_CHECKED ;
	}
	return $str;
}

function build_checked_array( $val, $array )
{
	$str = '';
	if ( is_array($array) && in_array( $val, $array ) ) {
		$str = $this->_CHECKED ;
	}
	return $str;
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