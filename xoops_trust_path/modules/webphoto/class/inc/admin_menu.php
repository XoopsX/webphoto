<?php
// $Id: admin_menu.php,v 1.6 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-01-10 K.OHWADA
// comment photo_table_manage
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
// 2008-10-01 K.OHWADA
// define_sub_menu()
// player_manager etc
// 2008-08-24 K.OHWADA
// added item_table_manage
// 2008-08-01 K.OHWADA
// added maillog_manager
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_admin_menu
// caller webphoto_lib_admin_menu admin/menu.php
//=========================================================
class webphoto_inc_admin_menu
{
	var $_DIRNAME;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_admin_menu( $dirname )
{
	$this->_DIRNAME = $dirname;
}

function &getSingleton( $dirname )
{
	static $singletons;
	if ( !isset( $singletons[ $dirname ] ) ) {
		$singletons[ $dirname ] = new webphoto_inc_admin_menu( $dirname );
	}
	return $singletons[ $dirname ];
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function define_menu()
{
// base on myalbum
	$menu[0]['title'] = 'CHECKCONFIGS' ;
	$menu[0]['fct']   = '';
	$menu[1]['title'] = 'CATMANAGER' ;
	$menu[1]['fct']   = 'catmanager';
	$menu[2]['title'] = 'ITEM_MANAGER' ;
	$menu[2]['fct']   = 'item_manager';
	$menu[3]['title'] = 'PHOTOMANAGER' ;
	$menu[3]['fct']   = 'photomanager';
	$menu[4]['title'] = 'REDOTHUMB' ;
	$menu[4]['fct']   = 'redothumbs';
	$menu[5]['title'] = 'GROUPPERM' ;
	$menu[5]['fct']   = 'groupperm';

// added for webphoto
	$menu[6]['title'] = 'GICONMANAGER' ;
	$menu[6]['fct']   = 'giconmanager';
	$menu[7]['title'] = 'MIMETYPES' ;
	$menu[7]['fct']   = 'mimetypes';
	$menu[8]['title'] = 'MAILLOG_MANAGER' ;
	$menu[8]['fct']   = 'maillog_manager';
	$menu[9]['title'] = 'PLAYER_MANAGER' ;
	$menu[9]['fct']   = 'player_manager';

	$menu[10]['title'] = 'BATCH' ;
	$menu[10]['fct']   = 'batch';
	$menu[11]['title'] = 'IMPORT' ;
	$menu[11]['fct']   = 'import';
	$menu[12]['title'] = 'EXPORT' ;
	$menu[12]['fct']   = 'export';

// added for webphoto
	$menu[13]['title'] = 'IMPORT_MYALBUM' ;
	$menu[13]['fct']   = 'import_myalbum';
	$menu[14]['title'] = 'CHECKTABLES' ;
	$menu[14]['fct']   = 'checktables';
	$menu[15]['title'] = 'UPDATE' ;
	$menu[15]['fct']   = 'update';

	return $menu;
}

function define_sub_menu()
{
	$menu[1]['title'] = 'ITEM_TABLE_MANAGE' ;
	$menu[1]['fct']   = 'item_table_manage';
	$menu[2]['title'] = 'FILE_TABLE_MANAGE' ;
	$menu[2]['fct']   = 'file_table_manage';
	$menu[3]['title'] = 'CAT_TABLE_MANAGE' ;
	$menu[3]['fct']   = 'cat_table_manage';
	$menu[4]['title'] = 'VOTE_TABLE_MANAGE' ;
	$menu[4]['fct']   = 'vote_table_manage';
	$menu[5]['title'] = 'GICON_TABLE_MANAGE' ;
	$menu[5]['fct']   = 'gicon_table_manage';
	$menu[6]['title'] = 'MIME_TABLE_MANAGE' ;
	$menu[6]['fct']   = 'mime_table_manage';
	$menu[7]['title'] = 'TAG_TABLE_MANAGE' ;
	$menu[7]['fct']   = 'tag_table_manage';
	$menu[8]['title'] = 'P2T_TABLE_MANAGE' ;
	$menu[8]['fct']   = 'p2t_table_manage';
	$menu[9]['title'] = 'SYNO_TABLE_MANAGE' ;
	$menu[9]['fct']   = 'syno_table_manage';
	$menu[10]['title'] = 'USER_TABLE_MANAGE' ;
	$menu[10]['fct']   = 'user_table_manage';
	$menu[11]['title'] = 'MAILLOG_TABLE_MANAGE' ;
	$menu[11]['fct']   = 'maillog_table_manage';
	$menu[12]['title'] = 'PLAYER_TABLE_MANAGE' ;
	$menu[12]['fct']   = 'player_table_manage';
	$menu[13]['title'] = 'FLASHVAR_TABLE_MANAGE' ;
	$menu[13]['fct']   = 'flashvar_table_manage';

//	$menu[20]['title'] = 'PHOTO_TABLE_MANAGE' ;
//	$menu[20]['fct']   = 'photo_table_manage';

	return $menu;
}

function build_menu()
{
	$menu = $this->define_menu();

	foreach( $menu as $k => $v )
	{
		$title = $this->_constant( $v['title'] ) ;
		$link  = 'admin/index.php' ;
		if ( $v['fct'] ) {
			$link .= '?fct='. $v['fct'] ;
		}
		$arr[ $k ] = array(
			'title' => $title ,
			'link'  => $link ,
		);
	}

	return $arr;
}

function build_sub_menu( )
{
	$menu = $this->define_sub_menu();

	foreach( $menu as $k => $v )
	{
		$title = $this->_constant( $v['title'] ) ;
		$link  = 'admin/index.php' ;
		if ( $v['fct'] ) {
			$link .= '?fct='. $v['fct'] ;
		}
		$arr[ $k ] = array(
			'title' => $title ,
			'link'  => $link ,
		);
	}

	return $arr;
}

//---------------------------------------------------------
// langauge
//---------------------------------------------------------
function _constant( $name )
{
	$const_name = $this->_constant_name( $name );
	if ( defined($const_name) ) {
		return constant( $this->_constant_name( $name ) );
	}
	return $const_name;
}

function _constant_name( $name )
{
	return strtoupper( '_MI_' . $this->_DIRNAME . '_ADMENU_' . $name );
}

// --- class end ---
}

?>