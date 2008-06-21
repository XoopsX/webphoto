<?php
// $Id: admin_menu.php,v 1.1 2008/06/21 12:22:26 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

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

	$menu[8]['title'] = 'BATCH' ;
	$menu[8]['fct']   = 'batch';
	$menu[9]['title'] = 'IMPORT' ;
	$menu[9]['fct']   = 'import';
	$menu[10]['title'] = 'EXPORT' ;
	$menu[10]['fct']   = 'export';

// added for webphoto
	$menu[11]['title'] = 'IMPORT_MYALBUM' ;
	$menu[11]['fct']   = 'import_myalbum';
	$menu[12]['title'] = 'CHECKTABLES' ;
	$menu[12]['fct']   = 'checktables';

	$menu[13]['title'] = 'PHOTO_TABLE_MANAGE' ;
	$menu[13]['fct']   = 'photo_table_manage';
	$menu[14]['title'] = 'CAT_TABLE_MANAGE' ;
	$menu[14]['fct']   = 'cat_table_manage';
	$menu[15]['title'] = 'VOTE_TABLE_MANAGE' ;
	$menu[15]['fct']   = 'vote_table_manage';
	$menu[16]['title'] = 'GICON_TABLE_MANAGE' ;
	$menu[16]['fct']   = 'gicon_table_manage';
	$menu[17]['title'] = 'MIME_TABLE_MANAGE' ;
	$menu[17]['fct']   = 'mime_table_manage';
	$menu[18]['title'] = 'TAG_TABLE_MANAGE' ;
	$menu[18]['fct']   = 'tag_table_manage';
	$menu[19]['title'] = 'P2T_TABLE_MANAGE' ;
	$menu[19]['fct']   = 'p2t_table_manage';
	$menu[20]['title'] = 'SYNO_TABLE_MANAGE' ;
	$menu[20]['fct']   = 'syno_table_manage';

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
	return constant( $this->_constant_name( $name ) );
}

function _constant_name( $name )
{
	return strtoupper( '_MI_' . $this->_DIRNAME . '_ADMENU_' . $name );
}

// --- class end ---
}

?>