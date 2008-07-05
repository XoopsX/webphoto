<?php
// $Id: uri.php,v 1.1 2008/07/05 12:54:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-07-01 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_uri
//=========================================================
class webphoto_uri
{
	var $_xoops_class;
	var $_config_class;
	var $_pathinfo_class;

	var $_cfg_use_pathinfo;

	var $_DIRNAME ;
	var $_MODULE_URL ;
	var $_MODULE_DIR ;

	var $_SEPARATOR ;

	var $_MARK_SLASH = '/' ;
	var $_MARK_COLON = ':' ;
	var $_HTML_AMP   = '&amp;' ;
	var $_HTML_SLASH = '&#047;' ;
	var $_HTML_COLON = '&#058;' ;

	var $_UID_DEFAULT = -1;	// not set
	var $_PARAM_NAME  = 'p';
	var $_PATH_FIRST  = 0 ;
	var $_PATH_SECOND = 1 ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_uri( $dirname )
{
	$this->_DIRNAME    = $dirname ;
	$this->_MODULE_URL = XOOPS_URL       .'/modules/'. $dirname;
	$this->_MODULE_DIR = XOOPS_ROOT_PATH .'/modules/'. $dirname;

	$this->_xoops_class    =& webphoto_xoops_base::getInstance();
	$this->_pathinfo_class =& webphoto_lib_pathinfo::getInstance();
	$this->_config_class   =& webphoto_config::getInstance( $dirname );

	$this->_cfg_use_pathinfo = $this->_config_class->get_by_name('use_pathinfo');

	if ( $this->_cfg_use_pathinfo ) {
		$this->_SEPARATOR = $this->_MARK_SLASH ;
	} else {
		$this->_SEPARATOR = $this->_HTML_AMP ;
	}
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

function build_tag( $tag )
{
	return $this->build_full_uri_mode_param( 'tag', $this->rawurlencode_encode_str( $tag ) );
}

function build_full_uri_mode_param( $mode, $param )
{
	$str = $this->build_full_uri_mode( $mode ) ;
	if ( $this->_cfg_use_pathinfo ) {
		$str .= '/'. $param .'/' ; 
	} else {
		$str .= '&amp;'. $this->_PARAM_NAME .'='. $param ;
	}
	return $str;
}

function build_full_uri_mode( $mode )
{
	$str = $this->_MODULE_URL .'/index.php' ;
	if ( $this->_cfg_use_pathinfo ) {
		$str .= '/'. $this->sanitize($mode) ; 
	} else {
		$str .= '?fct='. $this->sanitize($mode) ;
	}
	return $str;
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

function get_separator()
{
	return $this->_SEPARATOR ;
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

//---------------------------------------------------------
// encode
//---------------------------------------------------------
function rawurlencode_encode_str( $str )
{
	return rawurlencode( $this->encode_str( $str ) );
}

function encode_str( $str )
{
	$str = $this->encode_slash( $str );
	return $this->encode_colon( $str );
}

function decode_str( $str )
{
	$str = $this->decode_slash( $str );
	return $this->decode_colon( $str );
}

function encode_slash( $str )
{
	return str_replace( $this->_MARK_SLASH, $this->_HTML_SLASH, $str );
}

function encode_colon( $str )
{
	return str_replace( $this->_MARK_COLON, $this->_HTML_COLON, $str );
}

function decode_slash( $str )
{
	return str_replace( $this->_HTML_SLASH, $this->_MARK_SLASH, $str );
}

function decode_colon( $str )
{
	return str_replace( $this->_HTML_COLON, $this->_MARK_COLON, $str );
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
function sanitize( $str )
{
	return htmlspecialchars( $str, ENT_QUOTES );
}

// --- class end ---
}

?>