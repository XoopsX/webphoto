<?php
// $Id: groupperm_form.php,v 1.2 2010/02/17 04:34:47 ohwada Exp $

//=========================================================
// webphoto module
// 2009-12-06 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-02-15 K.OHWADA
// $_FLAG_PERM_ADMIN
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_groupperm_form
//=========================================================
class webphoto_admin_groupperm_form extends webphoto_edit_base
{
	var $_def_class;
	var $_form_class;

	var $_TEMPLATE;
	var $_FLAG_PERM_ADMIN = false;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_groupperm_form( $dirname , $trust_dirname )
{
	$this->webphoto_edit_base( $dirname , $trust_dirname );

	$this->_form_class =& webphoto_lib_groupperm_form::getInstance();
	$this->_def_class  =& webphoto_inc_gperm_def::getInstance();

	$this->_TEMPLATE = 'db:'. $dirname .'_form_admin_groupperm.html';
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_groupperm_form( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function build_form( $fct )
{
	$group_list = $this->_form_class->build_group_list(
		$this->_MODULE_ID , 
		_C_WEBPHOTO_GPERM_NAME , 
		$this->_def_class->get_perm_list(),
		$this->_FLAG_PERM_ADMIN );

	$group_list = $this->rebuild_group_list( $group_list );

	$hidden_list = array(
		array( 'name' => 'fct' , 'value' => $fct ) ,
	);

	$param = $this->build_param( $group_list ,  $hidden_list );

	$tpl = new XoopsTpl() ;
	$tpl->assign( $param ) ;
	return $tpl->fetch( $this->_TEMPLATE ) ;
}

function build_form_by_groupid( $group_id, $fct, $cat_id )
{
	$group_list = $this->_form_class->build_group_list_single( 
		$this->_MODULE_ID, 
		$group_id, 
		$this->_form_class->get_group_name( $group_id ), 
		_C_WEBPHOTO_GPERM_NAME, 
		$this->_def_class->get_perm_list() );

	$hidden_list = array(
		array( 'name' => 'fct' ,    'value' => $fct ) ,
		array( 'name' => 'cat_id' , 'value' => $cat_id ) ,
	);

	$param = $this->build_param( array($group_list), $hidden_list );

	$tpl = new XoopsTpl() ;
	$tpl->assign( $param ) ;
	return $tpl->fetch( $this->_TEMPLATE ) ;
}

function build_param( $group_list, $hidden_list )
{
	$param = $this->_form_class->build_param( $this->_MODULE_ID , $this->_ADMIN_INDEX_PHP );
	$param['lang_title_groupperm'] = $this->get_admin_title( 'GROUPPERM' );
	$param['lang_group_mod_category'] = _AM_WEBPHOTO_GROUP_MOD_CATEGORY ;
	$param['group_list']  = $group_list ;
	$param['hidden_list'] = $hidden_list ;

	return array_merge( $param , $this->get_gperm_lang() );
}

function get_gperm_lang()
{
	$arr = array(
		'lang_gperm_module_admin' => _AM_WEBPHOTO_GPERM_MODULE_ADMIN ,
		'lang_gperm_module_read'  => _AM_WEBPHOTO_GPERM_MODULE_READ ,
	);
	return $arr;
}

function rebuild_group_list( $group_list )
{
	list( $groupid_admin, $groupid_user ) 
		= $this->get_mod_groupid();

	list( $cat_rows, $cat_groupid_array )
		= $this->get_cat_rows_by_groupid();

	$new_list = array();
	foreach ( $group_list as $id => $group ) 
	{
		$mod_right_name = '';
		if ( $id == $groupid_admin ) {
			$mod_right_name = _AM_WEBPHOTO_GROUP_MOD_ADMIN;
		} elseif ( $id == $groupid_user ) {
			$mod_right_name = _AM_WEBPHOTO_GROUP_MOD_USER;
		}

		$cat_id    = 0;
		$cat_title = '';
		if ( in_array( $id, $cat_groupid_array ) ) {
			$cat_row   = $cat_rows[ $id ];
			$cat_id    = $cat_row['cat_id'];
			$cat_title = $cat_row['cat_title'];
		}

		$group['mod_right_name'] = $mod_right_name;
		$group['cat_id']    = $cat_id;
		$group['cat_title'] = $cat_title;

		$new_list[ $id ] = $group;
	}

	return $new_list;
}

function get_mod_groupid()
{
	$groupid_admin = 0;
	$groupid_user  = 0;

	if ( $this->get_ini('xoops_version_cfg_groupid_admin') ) {
		$groupid_admin = $this->get_config_by_name('groupid_admin');
	}
	if ( $this->get_ini('xoops_version_cfg_groupid_user') ) {
		$groupid_user  = $this->get_config_by_name('groupid_user');
	}

	return array( $groupid_admin, $groupid_user );
}

function get_cat_rows_by_groupid()
{
	$groupid_array   = array();
	$rows_by_groupid = array();
	$rows = $this->_cat_handler->get_rows_all_asc();

	foreach ( $rows as $row ) 
	{
		$id = $row['cat_group_id'];
		if ( $id > 0 ) {
			$groupid_array[] = $id;
			$rows_by_groupid[ $id ] = $row;
		}
	}

	return array( $rows_by_groupid, $groupid_array );
}

// --- class end ---
}

?>