<?php
// $Id: show_list.php,v 1.12 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// webphoto_show_main -> webphoto_show_main_photo
// 2009-10-25 K.OHWADA
// build_photos_param_in_category()
// 2009-06-28 K.OHWADA
// set_list_mode()
// 2009-05-30 K.OHWADA
// BUG : not show cat_id
// 2009-04-10 K.OHWADA
// build_common_param()
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
class webphoto_show_list extends webphoto_show_main_photo
{
	var $_get_uid     = -1;	// not set
	var $_UID_DEFAULT = -1;	// not set

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_show_list( $dirname , $trust_dirname )
{
	$this->webphoto_show_main_photo( $dirname , $trust_dirname );
}

public static function &getInstance( $dirname , $trust_dirname )
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
		$str = $this->_TEMPLATE_DETAIL;
	} else {
		$str = $this->_TEMPLATE_LIST;
	}
	$ret = $this->_DIRNAME . '_'. $str;
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
	$this->get_pathinfo_param();
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

	$title_s = $this->sanitize( $title );

	$param = array(
		'xoops_pagetitle'    => $title_s ,
		'title_bread_crumb'  => $title_s,
		'sub_title_s'        => $title_s ,
		'photo_list'         => $this->list_get_photo_list() ,
	);

	$arr = array_merge( 
		$param, 
		$this->build_common_param( $mode, $show_photo_desc ) 
	);
	return $arr;
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
		$photos = $this->build_photo_show_from_rows( $rows );
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

// BUG : not show cat_id
function list_build_init_param( $show_photo_desc=false, $cat_id=0 )
{
	$param = $this->build_common_param( $this->_mode, $show_photo_desc, $cat_id ) ;
	$param['param_sort'] = $this->build_uri_list_sort() ;
	return $param;
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
function build_uri_list_navi_url( $get_sort )
{
	return $this->_uri_class->build_list_navi_url(
		$this->_mode, $this->_param_out, $get_sort );
}

function build_uri_list_sort()
{
	return $this->_uri_class->build_list_sort(
		$this->_mode, $this->_param_out, $this->_get_viewtype );
}

// --- class end ---
}

?>