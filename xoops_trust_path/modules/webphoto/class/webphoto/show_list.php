<?php
// $Id: show_list.php,v 1.1 2008/06/21 12:22:23 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_show_list
//=========================================================
class webphoto_show_list extends webphoto_show_main
{
	var $_mode  = null;
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
	$mode = webphoto_fct() ;

	$this->_get_tag      = $this->_pathinfo_class->get('tag');
	$this->_get_date     = $this->_pathinfo_class->get('date');
	$this->_get_place    = $this->_pathinfo_class->get('place');
	$this->_get_uid      = $this->_pathinfo_class->get_int('uid',  $this->_UID_DEFAULT );
	$this->_get_query    = trim( $this->_pathinfo_class->get_text('query') );
	$this->_get_page     = $this->_pathinfo_class->get_int('page', $this->_PAGE_DEFAULT );
	$this->_get_sort     = $this->get_photo_sort_name_by_pathinfo();

	$path0 = $this->_pathinfo_class->get_path( 0 );
	$path1 = $this->_pathinfo_class->get_path( 1 );

	if ( empty($mode) ) {
		$mode = $path0;
	}

	$this->_mode  = $mode;
	$this->_param = null;

	switch ( $mode )
	{
		case 'category':
			if ( $this->_get_catid ) {
				$this->_param = $this->_get_catid;
			} else {
				$this->_param = intval($path1);
			}
			break;

		case 'user':
			$this->_param = $this->_UID_DEFAULT;
			if ( $this->_get_uid >= 0 ) {
				$this->_param = $this->_get_uid;
			} elseif (( $this->_get_uid == $this->_UID_DEFAULT ) &&
				      ( $path1 !== false )) {
				$this->_param = intval($path1);
			}
			break;

		case 'tag':
			if ( $this->_get_tag ) {
				$this->_param = $this->_get_tag;
			} else {
				$this->_param = $path1;
			}
			break;

		case 'date':
			if ( $this->_get_date ) {
				$this->_param = $this->_get_date;
			} else {
				$this->_param = $path1;
			}
			break;

		case 'place':
			if ( $this->_get_place ) {
				$this->_param = $this->_get_place;
			} else {
				$this->_param = $path1;
			}
			break;

		case 'search':
			if ( $this->_get_query ) {
				$this->_param = $this->_get_query;
			} else {
				$this->_param = $path1;
			}
			break;

		case 'myphoto':
			$this->_mode  = 'user';
			$this->_param = $this->_xoops_uid;
			break;
	}

	$this->set_param_out( $this->_param );
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
	if ( empty($title) ) {
		$const = 'title_'. $this->_mode .'_list';
		$title = $this->get_constant( $const );
	}

	$title_s   = $this->sanitize( $title );
	$total_all = $this->_photo_handler->get_count_public();

	$arr = array(
		'xoops_pagetitle'   => $title_s ,
		'title_bread_crumb' => $title_s,
		'sub_title_s'       => $title_s ,
		'show_photo_desc'   => $show_photo_desc ,
		'use_popbox_js'     => $this->_USE_POPBOX_JS ,
		'use_box_js'        => $this->_USE_BOX_JS ,
		'photo_total_all'   => $total_all ,
		'lang_thereare'     => sprintf( $this->get_constant('S_THEREARE') , $total_all ),
		'photo_list'        => $this->list_get_photo_list() ,
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
		$link  = 'index.php/'. $this->_mode .'/'. rawurlencode($param) .'/';
	}

	if ( empty($photo) && is_array($row) ) {
		$photo = $this->build_photo_show( $row );
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
	$total_all = $this->_photo_handler->get_count_public();

	$arr = array(
		'mode'              => $this->_mode,
		'cat_id'            => $this->_get_catid,
		'uid'               => $this->_get_uid,
		'tag_name'          => $this->_get_tag,
		'page'              => $this->_get_page,
		'sort'              => $this->_get_sort,
		'param_sort'        => $this->build_param_sort( $this->_mode ) ,
		'lang_cursortedby'  => $this->get_lang_sortby( $this->_get_sort ),
		'use_popbox_js'     => $this->_USE_POPBOX_JS ,
		'use_box_js'        => $this->_USE_BOX_JS ,
		'show_photo_desc'   => $show_photo_desc ,
		'photo_total_all'   => $total_all ,
		'lang_thereare'     => $this->build_lang_thereare( $total_all ) ,
	);
	return $arr;
}

function list_build_navi( $total, $limit, $get_page=null, $get_sort=null )
{
	return $this->build_navi_with_check_sort( $this->_mode, $total, $limit, $get_page, $get_sort );
}

function list_build_random_more( $total, $url=null )
{
	if ( empty($url) ) {
		$url = 'index.php/'. $this->_mode .'/'. urlencode( $this->_param_out ) .'/';
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
// show main
//---------------------------------------------------------
// overwrite
function build_param_common()
{
	$str = '';

	switch ( $this->_mode )
	{
		case 'category':
		case 'user':
			$str .= '/'.$this->_mode;
			$str .= '/'.intval($this->_param_out);
			return $str;

		case 'tag':
		case 'date':
		case 'place':
		case 'search':
			$str .= '/'.$this->_mode;
			$str .= '/'. rawurlencode($this->_param_out);
			return $str;
	}

	return $str;
}

// --- class end ---
}

?>