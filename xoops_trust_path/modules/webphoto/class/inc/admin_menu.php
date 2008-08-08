<?php
// $Id: admin_menu.php,v 1.2 2008/08/08 04:36:09 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-01 K.OHWADA
// added maillog_manager
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_admin_menu
//=========================================================
class webphoto_inc_admin_menu
{
	var $_DIRNAME;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_admin_menu()
{
	// dummy
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_inc_admin_menu();
	}
	return $instance;
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
	$menu[2]['title'] = 'PHOTOMANAGER' ;
	$menu[2]['fct']   = 'photomanager';
	$menu[3]['title'] = 'ADMISSION' ;
	$menu[3]['fct']   = 'admission';
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

	$menu[9]['title'] = 'BATCH' ;
	$menu[9]['fct']   = 'batch';
	$menu[10]['title'] = 'IMPORT' ;
	$menu[10]['fct']   = 'import';
	$menu[11]['title'] = 'EXPORT' ;
	$menu[11]['fct']   = 'export';

// added for webphoto
	$menu[12]['title'] = 'IMPORT_MYALBUM' ;
	$menu[12]['fct']   = 'import_myalbum';
	$menu[13]['title'] = 'CHECKTABLES' ;
	$menu[13]['fct']   = 'checktables';

	$menu[14]['title'] = 'PHOTO_TABLE_MANAGE' ;
	$menu[14]['fct']   = 'photo_table_manage';
	$menu[15]['title'] = 'CAT_TABLE_MANAGE' ;
	$menu[15]['fct']   = 'cat_table_manage';
	$menu[16]['title'] = 'VOTE_TABLE_MANAGE' ;
	$menu[16]['fct']   = 'vote_table_manage';
	$menu[17]['title'] = 'GICON_TABLE_MANAGE' ;
	$menu[17]['fct']   = 'gicon_table_manage';
	$menu[18]['title'] = 'MIME_TABLE_MANAGE' ;
	$menu[18]['fct']   = 'mime_table_manage';
	$menu[19]['title'] = 'TAG_TABLE_MANAGE' ;
	$menu[19]['fct']   = 'tag_table_manage';
	$menu[20]['title'] = 'P2T_TABLE_MANAGE' ;
	$menu[20]['fct']   = 'p2t_table_manage';
	$menu[21]['title'] = 'SYNO_TABLE_MANAGE' ;
	$menu[21]['fct']   = 'syno_table_manage';
	$menu[22]['title'] = 'USER_TABLE_MANAGE' ;
	$menu[22]['fct']   = 'user_table_manage';
	$menu[23]['title'] = 'MAILLOG_TABLE_MANAGE' ;
	$menu[23]['fct']   = 'maillog_table_manage';

	return $menu;
}

function build_menu( $dirname )
{
	$this->_init( $dirname );

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

//---------------------------------------------------------
// langauge
//---------------------------------------------------------
function _init( $dirname )
{
	$this->_DIRNAME = $dirname;
}

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