<?php
// $Id: groupperm.php,v 1.4 2009/12/16 13:32:34 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-12-06 K.OHWADA
// webphoto_lib_groupperm
// 2009-01-04 K.OHWADA
// _B_WEBPHOTO_GPERM_HTML
// 2008-08-01 K.OHWADA
// added _B_WEBPHOTO_GPERM_MAIL
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_groupperm
//=========================================================
class webphoto_admin_groupperm extends webphoto_edit_base
{
	var $_groupperm_class;
	var $_form_class;
	var $_def_class;

	var $_THIS_FCT = 'groupperm';
	var $_THIS_URL;

	var $_TIME_SUCCESS = 1;
	var $_TIME_FAIL    = 5;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_groupperm( $dirname , $trust_dirname )
{
	$this->webphoto_edit_base( $dirname , $trust_dirname );

	$this->_groupperm_class =& webphoto_lib_groupperm::getInstance();
	$this->_form_class =& webphoto_lib_groupperm_form::getInstance();
	$this->_def_class  =& webphoto_inc_gperm_def::getInstance();

	$this->_THIS_URL = $this->_MODULE_URL .'/admin/index.php?fct='.$this->_THIS_FCT;
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_groupperm( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	$perms = $this->get_post('perms');
	if ( is_array($perms) ) {
		$this->groupperm( $perms );
		exit();
	}

	xoops_cp_header() ;

	echo $this->build_admin_menu();
	echo $this->build_admin_title( 'GROUPPERM' );
	echo $this->build_form();

	xoops_cp_footer() ;
}

//---------------------------------------------------------
// groupperm
//---------------------------------------------------------
function groupperm( $perms )
{
	if ( ! $this->check_token() ) {
		redirect_header( $this->_THIS_URL, $this->_TIME_FAIL, $this->get_token_errors() );
		exit();
	}

	$this->_groupperm_class->modify( $this->_MODULE_ID, $perms, true );
	redirect_header( $this->_THIS_URL , $this->_TIME_SUCCESS , _AM_WEBPHOTO_GPERMUPDATED );
	exit() ;
}

//---------------------------------------------------------
// form
//---------------------------------------------------------
function build_form()
{
	$template = 'db:'. $this->_DIRNAME .'_form_admin_groupperm.html';

	$group_list = $this->_form_class->build_group_list(
		$this->_MODULE_ID , 
		_C_WEBPHOTO_GPERM_NAME , 
		$this->_def_class->get_perm_list() );

	$group_list = $this->rebuild_group_list( $group_list );

	$param = $this->_form_class->build_param( $this->_MODULE_ID , $this->_THIS_URL );
	$param['lang_title_groupperm']    = $this->get_admin_title( 'GROUPPERM' );
	$param['lang_group_mod_category'] = _AM_WEBPHOTO_GROUP_MOD_CATEGORY ;
	$param['group_list'] = $group_list ;

	$tpl = new XoopsTpl() ;
	$tpl->assign( $param ) ;
	return $tpl->fetch( $template ) ;
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