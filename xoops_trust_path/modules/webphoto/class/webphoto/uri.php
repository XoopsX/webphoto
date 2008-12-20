<?php
// $Id: uri.php,v 1.4 2008/12/20 06:11:27 ohwada Exp $

//=========================================================
// webphoto module
// 2008-07-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
// 2008-11-29 K.OHWADA
// webphoto_inc_uri
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_uri
//=========================================================
class webphoto_uri extends webphoto_inc_uri
{
	var $_xoops_class;
	var $_config_class;
	var $_pathinfo_class;

	var $_UID_DEFAULT = -1;	// not set

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_uri( $dirname )
{
	$this->webphoto_inc_uri( $dirname );

	$this->_xoops_class    =& webphoto_xoops_base::getInstance();
	$this->_pathinfo_class =& webphoto_lib_pathinfo::getInstance();
	$this->_config_class   =& webphoto_config::getInstance( $dirname );

	$this->set_use_pathinfo(
		$this->_config_class->get_by_name('use_pathinfo') );
}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_uri( $dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// parse uri
//---------------------------------------------------------
function get_list_pathinfo_param( $mode )
{
	$isset = $this->_pathinfo_class->isset_param( $this->_PARAM_NAME  );
	$p     = $this->_pathinfo_class->get(         $this->_PARAM_NAME );

	$path_second = $this->_pathinfo_class->get_path( $this->_PATH_SECOND );

	switch ( $mode )
	{
		case 'category':
			$p = $this->get_pathinfo_id( 'cat_id' );
			break;

		case 'user':
			$uid = $this->_UID_DEFAULT;	// not set
			if ( $isset ) {
				$uid = $p;
			} elseif ( !$isset && ( $path_second !== false ) ) {
				$uid = intval($path_second);
			}
			$p = $uid;
			break;

		case 'tag':
		case 'date':
		case 'place':
		case 'search':
			if ( empty($p) ) {
				$p = $path_second;
			}
			break;

		case 'myphoto':
			$p = $this->_xoops_class->get_my_user_uid() ;
			break;
	}

	return $p;
}

function get_pathinfo_id( $id_name )
{
// POST
	$id = isset($_POST[ $id_name ]) ? intval($_POST[ $id_name ]) : 0 ;
	if ( $id > 0 ) { return $id; }

// GET
	$id = isset($_GET[ $id_name ]) ? intval($_GET[ $id_name ]) : 0 ;
	if ( $id > 0 ) { return $id; }

// PATH_INFO
	$id = $this->_pathinfo_class->get_int( $this->_PARAM_NAME );
	if ( $id > 0 ) { return $id; }

	$id = intval( $this->_pathinfo_class->get_path( $this->_PATH_SECOND ) );
	return $id;
}

//---------------------------------------------------------
// buiid uri
//---------------------------------------------------------
function build_operate( $op )
{
	if ( $this->_cfg_use_pathinfo ) {
		$str = $this->_MODULE_URL .'/index.php/'. $this->sanitize($op) .'/';
	} else {
		$str = $this->_MODULE_URL .'/index.php?op='. $this->sanitize($op) ;
	}
	return $str;
}

function build_photo_pagenavi()
{
	$str = $this->build_full_uri_mode( 'photo' );
	if ( $this->_cfg_use_pathinfo ) {
		$str .= '/';
	} else {
		$str .= '&amp;'. $this->_PARAM_NAME .'=' ;
	}
	return $str;
}

function build_photo( $id )
{
	return $this->build_full_uri_mode_param( 'photo', intval($id) );
}

function build_category( $id, $param=null )
{
	$str  = $this->build_full_uri_mode_param( 'category', intval($id) );
	$str .= $this->build_param( $param );
	return $str;
}

function build_user( $id )
{
	return $this->build_full_uri_mode_param( 'user', intval($id) );
}

function build_param( $param )
{
	if ( !is_array($param) || !count($param)) {
		return null;
	}

	$arr = array();
	foreach ( $param as $k => $v ) {
		$arr[] = $this->sanitize($k) .'='. $this->sanitize($v) ;
	}

	if ( $this->_cfg_use_pathinfo ) {
		$str = implode( $arr, '/' ) .'/' ;
	} else {
		$str = '&amp;'. implode( $arr, '&amp;' ) ;
	}

	return $str;
}

//---------------------------------------------------------
// buiid uri for show_main
//---------------------------------------------------------
function build_main_navi_url( $mode, $sort, $viewtype=null )
{
	$str  = $this->_MODULE_URL .'/index.php';
	$str .= $this->build_main_op( $mode, true );
	$str .= $this->build_sort( $sort );
	$str .= $this->build_viewtype( $viewtype );
	return $str ;
}

function build_main_sort( $mode, $viewtype=null )
{
	$str  = $this->build_main_op( $mode, true );
	$str .= $this->build_viewtype( $viewtype );
	$str .= $this->get_separator();
	return $str ;
}

function build_main_op( $op, $flag_head_slash=false )
{
	$str = '';
	if ( $op ) {
		if ( $this->_cfg_use_pathinfo ) {
			if ( $flag_head_slash ) {
				$str = '/' ;
			}
			$str .= $this->sanitize($op);
		} else {
			$str = '?op='. $this->sanitize($op) ;
		}
	}
	return $str;
}

function build_sort( $sort )
{
	$str = '';
	if ( $sort ) {
		$str = $this->_SEPARATOR .'sort='. $this->sanitize($sort);
	}
	return $str;
}

function build_viewtype( $viewtype )
{
	$str = '';
	if ( $viewtype ) {
		$str = $this->_SEPARATOR .'viewtype='. $this->sanitize($viewtype);
	}
	return $str;
}

function build_page( $page )
{
	$str = '';
	if ( $page ) {
		$str = $this->_SEPARATOR. 'page='. intval($page) ;
	}
	return $str;
}

//---------------------------------------------------------
// buiid uri for show_list
//---------------------------------------------------------
function build_list_navi_url( $mode, $param, $sort, $viewtype=null )
{
	$str  = $this->_MODULE_URL .'/index.php';
	$str .= $this->build_list_param( $mode, $param, true );
	$str .= $this->build_sort( $sort );
	$str .= $this->build_viewtype( $viewtype );
	return $str ;
}

function build_list_sort( $mode, $param, $viewtype=null )
{
	$str  = $this->build_list_param( $mode, $param, true );
	$str .= $this->build_viewtype( $viewtype );
	$str .= $this->get_separator();
	return $str ;
}

function build_list_param( $mode, $param, $flag_head_slash=false )
{
	$str_1 = '' ;
	if ( $flag_head_slash ) {
		$str_1 = '/' ;
	}

	$str_1 .= $mode .'/' ;
	$str_2  = '?fct='. $mode .'&amp;';

	switch ( $mode )
	{
		case 'category':
		case 'user':
			$str_1 .= intval($param) ;
			$str_2 .= 'p='. intval($param);
			break;

		case 'tags':
		case 'date':
		case 'place':
		case 'search':
		default:
			$str_1 .= rawurlencode($param) ;
			$str_2 .= 'p='. rawurlencode($param) ;
			break;
	}

	if ( $this->_cfg_use_pathinfo ) {
		$str = $str_1 ;
	} else {
		$str = $str_2 ;
	}

	return $str;
}

function build_list_link( $mode, $param )
{
// not sanitize
	if ( $this->_cfg_use_pathinfo ) {
		$str = 'index.php/'. $mode .'/'. rawurlencode($param) .'/' ;
	} else {
		$str = 'index.php?fct='. $mode .'&p='. rawurlencode($param) ;
	}
	return $str;
}

// --- class end ---
}

?>