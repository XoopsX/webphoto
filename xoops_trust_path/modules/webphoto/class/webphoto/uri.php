<?php
// $Id: uri.php,v 1.11 2011/06/05 07:23:40 ohwada Exp $

//=========================================================
// webphoto module
// 2008-07-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-06-04 K.OHWADA
// build_photo_id_title()
// 2010-11-03 K.OHWADA
// move get_pathinfo_param() to webphoto_uri_parse
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

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_uri( $dirname )
{
	$this->webphoto_inc_uri( $dirname );

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
// buiid uri
//---------------------------------------------------------
function build_photo_id_title( $id, $title, $target='_blank', $flag_amp_sanitize=true, $flag_title_sanitize=true  )
{
	$str  = $this->build_photo_a_href( $id, $target, $flag_amp_sanitize );
	$str .= $id;

	if ( $title ) {
		$str .= ' : ';
		if ( $flag_title_sanitize ) {
			$str .= $this->sanitize( $title );
		} else {
			$str .= $title;
		}
	}

	$str .= '</a>';
	return $str ;
}

function build_photo_id( $id, $target='_blank', $flag_amp_sanitize=true )
{
	$str  = $this->build_photo_a_href( $id, $target, $flag_amp_sanitize );
	$str .= $id;
	$str .= '</a>';
	return $str ;
}

function build_photo_title( $title, $target='_blank', $flag_amp_sanitize=true, $flag_title_sanitize=true )
{
	$str  = $this->build_photo_a_href( $id, $target, $flag_amp_sanitize );
	if ( $flag_title_sanitize ) {
		$str .= $this->sanitize( $title );
	} else {
		$str .= $title;
	}
	$str .= '</a>';
	return $str ;
}

function build_photo_a_href( $id, $target='_blank', $flag_amp_sanitize=true )
{
	$url = $this->build_photo( $id, $flag_amp_sanitize );
	if ( $target ) {
		$str = '<a href="'. $url .'" target="'. $target .'">';
	} else {
		$str = '<a href="'. $url .'>';
	}
	return $str ;
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
	$str  = $this->build_full_uri_mode( 'photo' );
	$str .= $this->build_part_uri_param_name();
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
	return $this->build_uri_extention( $param );
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