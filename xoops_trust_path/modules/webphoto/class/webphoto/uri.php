<?php
// $Id: uri.php,v 1.8 2010/01/26 08:25:45 ohwada Exp $

//=========================================================
// webphoto module
// 2008-07-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-01-10 K.OHWADA
// build_navi_url()
// 2009-10-25 K.OHWADA
// build_list_navi_url_kind()
// 2009-03-15 K.OHWADA
// flag_amp_sanitize in build_photo()
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

//	var $_UID_DEFAULT = -1;	// not set

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
function get_pathinfo_param( $mode_orig )
{
	$isset = $this->_pathinfo_class->isset_param( $this->_PARAM_NAME  );
	$p     = $this->_pathinfo_class->get(         $this->_PARAM_NAME );

	$path_second = $this->_pathinfo_class->get_path( $this->_PATH_SECOND );

	switch ( $mode_orig )
	{
		case 'category':
			$p = $this->get_pathinfo_id( 'cat_id' );
			break;

		case 'user':
			$uid = _C_WEBPHOTO_UID_DEFAULT;	// not set
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

function build_photo( $id, $flag_amp_sanitize=true )
{
	return $this->build_full_uri_mode_param( 'photo', intval($id), $flag_amp_sanitize );
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
function build_navi_url( $mode, $param, $sort, $kind, $viewtype=null )
{
	$str  = $this->_MODULE_URL .'/index.php';
	$str .= $this->build_mode_param( $mode, $param, true );
	$str .= $this->build_sort( $sort );
	$str .= $this->build_kind( $kind );
	$str .= $this->build_viewtype( $viewtype );
	return $str ;
}

function build_param_sort( $mode, $param, $kind, $viewtype=null )
{
	$str  = $this->build_mode_param( $mode, $param, true );
	$str .= $this->build_kind( $kind );
	$str .= $this->build_viewtype( $viewtype );
	$str .= $this->get_separator();
	return $str ;
}

function build_mode_param( $mode, $param, $flag_head_slash=false )
{
	switch ( $mode )
	{
		case 'category':
		case 'user':
			$str_1 = $mode .'/'. intval($param) ;
			$str_2 = '?fct='. $mode .'&amp;p='. intval($param);
			break;

		case 'tag':
		case 'date':
		case 'place':
		case 'search':
			$str_1 = $mode .'/'. rawurlencode($param) ;
			$str_2 = '?fct='. $mode .'&amp;p='. rawurlencode($param) ;
			break;

		default:
			$str_1 = $this->sanitize($mode) ;
			$str_2 = '?op='. $this->sanitize($mode);
			break;
	}

	if ( $this->_cfg_use_pathinfo ) {
		if ( $flag_head_slash ) {
			$str = '/'. $str_1;
		} else {
			$str = $str_1 ;
		}
	} else {
		$str = $str_2 ;
	}

	return $str;
}

function build_sort( $val )
{
	return $this->build_param_str( 'sort', $val );
}

function build_kind( $val )
{
	return $this->build_param_str( 'kind', $val );
}

function build_viewtype( $val )
{
	return $this->build_param_str( 'viewtype', $val );
}

function build_page( $val )
{
	return $this->build_param_int( 'page', $val );
}

function build_param_str( $name, $val )
{
	$str = '';
	if ( $val ) {
		$str = $this->_SEPARATOR . $name. '='. $this->sanitize($val);
	}
	return $str;
}

function build_param_int( $name, $val )
{
	$str = '';
	if ( $val ) {
		$str = $this->_SEPARATOR . $name. '='. intval($val);
	}
	return $str;
}


//---------------------------------------------------------
// buiid uri for show_list
//---------------------------------------------------------
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