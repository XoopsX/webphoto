<?php
// $Id: show_list.php,v 1.7 2009/03/20 10:37:35 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-03-15 K.OHWADA
// build_init_show()
// 2008-12-12 K.OHWADA
// public_class
// 2008-12-07 K.OHWADA
// build_photo_show() -> build_photo_show_main()
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// 2008-07-01 K.OHWADA
// used get_list_pathinfo_param()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_show_list
//=========================================================
class webphoto_show_list extends webphoto_show_main
{
	var $_param = null;
	var $_param_out = null;

	var $_get_uid     = -1;	// not set
	var $_UID_DEFAULT = -1;	// not set

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_show_list( $dirname , $trust_dirname )
{
	$this->webphoto_show_main( $dirname , $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_show_list( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function list_get_template()
{
	$this->list_get_pathinfo_param();

	if ( $this->list_sel() ) {
		$ret = $this->_DIRNAME . '_main_index.html';
	} else {
		$ret = $this->_DIRNAME . '_main_list.html';
	}
	return $ret;
}

function list_main()
{
	if ( $this->list_sel() ) {
		return $this->list_build_detail( $this->_param );
	}
	return $this->list_build_list();
}

function list_sel()
{
	if ( $this->_param ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// get pathinfo param
//---------------------------------------------------------
function list_get_pathinfo_param()
{
	$this->_param    = $this->get_uri_list_pathinfo_param() ;
	$this->_get_page = $this->get_pathinfo_page() ;
	$this->_get_sort = $this->get_photo_sort_name_by_pathinfo();

	$this->set_param_out( $this->_param );

	switch ( $this->_mode )
	{
		case 'myphoto':
			$this->_mode  = 'user';
			break;
	}
}

function set_param_out( $val )
{
	$this->_param_out = $val;
}

//---------------------------------------------------------
// list
//---------------------------------------------------------
function list_build_list()
{
	return $this->list_build_list_default();
}

function list_build_list_default()
{
	$this->assign_xoops_header_default();
	return $this->list_build_list_common();
}

function list_build_list_common( $show_photo_desc=false, $title=null )
{
	$mode = $this->_mode;

	if ( empty($title) ) {
		$const = 'title_'. $mode .'_list';
		$title = $this->get_constant( $const );
	}

	$title_s   = $this->sanitize( $title );
	$total_all = $this->_public_class->get_count();

	$arr = array(
		'xoops_pagetitle'    => $title_s ,
		'title_bread_crumb'  => $title_s,
		'sub_title_s'        => $title_s ,
		'show_photo_desc'    => $show_photo_desc ,
		'use_popbox_js'      => $this->_USE_POPBOX_JS ,
		'use_box_js'         => $this->_USE_BOX_JS ,
		'photo_total_all'    => $total_all ,
		'lang_thereare'      => sprintf( $this->get_constant('S_THEREARE') , $total_all ),
		'photo_list'         => $this->list_get_photo_list() ,
	);
	return array_merge( $arr, $this->build_init_show( $mode ) );
}

// overwrite
function list_get_photo_list()
{
	// dummy
}

function list_build_photo_array( $title, $param, $total, $row, $link=null, $photo=null )
{
	if ( empty($link) && $param ) {
		$link = $this->build_uri_list_link( $param ) ;
	}

	if ( empty($photo) && is_array($row) ) {
		$photo = $this->build_photo_show_main( $row );
	}

	$arr = array(
		'title'   => $title ,
		'title_s' => $this->sanitize( $title ) ,
		'link'    => $link ,
		'link_s'  => $this->sanitize( $link ) ,
		'total'   => $total ,
		'photo'   => $photo ,
	);
	return $arr;
}

//---------------------------------------------------------
// detail list
//---------------------------------------------------------
// overwrite
function list_build_detail( $param )
{
	// dummy
}

function list_build_detail_common( $title, $total, $rows, $photos=null )
{
	$title_s = $this->sanitize( $title );

	$show_photo = false ; 
	$photos     = null;

	if ( empty($photos) && is_array($rows) && count($rows) ) {
		$photos     = $this->build_photo_show_from_rows( $rows );
	}

	if ( is_array($photos) && count($photos) ) {
		$show_photo = true ; 
	}

	$arr = array(
		'xoops_pagetitle'   => $title_s ,
		'title_bread_crumb' => $title_s ,
		'total_bread_crumb' => $total ,
		'sub_title_s'       => $title_s ,
		'sub_desc_s'        => '' ,
		'show_photo'        => $show_photo , 
		'photo_total'       => $total ,
		'photos'            => $photos ,
		'show_nomatch'      => $this->build_show_nomatch( $total ) ,
		'show_sort'         => $this->build_show_sort( $total ) ,
		'random_more_url_s' => $this->list_build_random_more( $total ) ,
	);
	return $arr;
}

function list_build_init_param( $show_photo_desc=false )
{
	$total_all = $this->_public_class->get_count();

	$arr = array(
		'mode'               => $this->_mode,
		'page'               => $this->_get_page,
		'sort'               => $this->_get_sort,
		'param_sort'         => $this->build_uri_list_sort() ,
		'lang_cursortedby'   => $this->get_lang_sortby( $this->_get_sort ),
		'use_popbox_js'      => $this->_USE_POPBOX_JS ,
		'use_box_js'         => $this->_USE_BOX_JS ,
		'show_photo_desc'    => $show_photo_desc ,
		'photo_total_all'    => $total_all ,
		'lang_thereare'      => $this->build_lang_thereare( $total_all ) ,
	);
	return array_merge( $arr, $this->build_init_show( $this->_mode ) );
}

function list_build_random_more( $total, $url=null )
{
	if ( empty($url) ) {
		$url = $this->build_uri_list_link( $this->_param_out ) ;
	}
	return $this->build_random_more_url_with_check_sort( $url, $total );
}

function list_assign_xoops_header( $rss_param=null, $flag_gmap=false )
{
	if ( empty($rss_param) ) {
		$rss_param = $this->_param_out;
	}

	$this->assign_xoops_header( $this->_mode, $rss_param, $flag_gmap );
}

//---------------------------------------------------------
// navi
//---------------------------------------------------------
function list_build_navi( $total, $limit, $get_page=null, $get_sort=null )
{
	if ( empty($get_sort) ) {
		$get_sort = $this->_get_sort;
	}

	if ( $this->check_show_navi_sort( $get_sort ) ) {
		$url = $this->build_uri_list_navi_url( $get_sort );
		return $this->build_navi( $url, $total, $limit, $get_page );
	}

	$arr = array(
		'show_navi' => false
	);
	return $arr;
}

//---------------------------------------------------------
// uri class
//---------------------------------------------------------
function get_uri_list_pathinfo_param()
{
	return $this->_uri_class->get_list_pathinfo_param( $this->_mode );
}

function build_uri_list_navi_url( $get_sort )
{
	return $this->_uri_class->build_list_navi_url( $this->_mode, $this->_param_out, $get_sort );
}

function build_uri_list_sort()
{
	return $this->_uri_class->build_list_sort(
		$this->_mode, $this->_param_out, $this->_get_viewtype );
}

function build_uri_list_link( $param )
{
	return $this->_uri_class->build_list_link( $this->_mode, $param );
}

// --- class end ---
}

?>