<?php
// $Id: index.php,v 1.19 2010/05/08 06:30:19 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-05-08 K.OHWADA
// BUG: total is wrong
// 2010-02-15 K.OHWADA
// build_execution_time()
// 2010-01-10 K.OHWADA
// webphoto_show_list -> webphoto_factory
// 2009-11-11 K.OHWADA
// get_ini()
// 2009-10-25 K.OHWADA
// webphoto_show_list
// 2009-09-25 K.OHWADA
// Notice [PHP]: Undefined variable: main_rows
// 2009-05-30 K.OHWADA
// random_more_url_s -> show_random_more
// 2009-04-10 K.OHWADA
// build_main_param()
// 2009-03-15 K.OHWADA
// add_box_list() -> add_show_js_windows()
// 2008-12-12 K.OHWADA
// public_class
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// QR code
// 2008-07-01 K.OHWADA
// build_navi() -> build_main_navi()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_index
//=========================================================
class webphoto_main_index extends webphoto_factory
{
	var $main_class; 
	var $date_class;
	var $place_class;
	var $tag_class;
	var $user_class;
	var $search_class;

	var $_ini_tagcloud_list_limit;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_index( $dirname , $trust_dirname )
{
	$this->webphoto_factory( $dirname , $trust_dirname );

	$this->_main_class 
		=& webphoto_main::getInstance( $dirname , $trust_dirname );

	$this->_date_class 
		=& webphoto_date::getInstance( $dirname , $trust_dirname );

	$this->_place_class 
		=& webphoto_place::getInstance( $dirname , $trust_dirname );

	$this->_tag_class 
		=& webphoto_tag::getInstance( $dirname , $trust_dirname );

	$this->_user_class 
		=& webphoto_user::getInstance( $dirname , $trust_dirname );

	$this->_search_class 
		=& webphoto_search::getInstance( $dirname , $trust_dirname );

	$this->set_template_main( 'main_index.html' );

	$this->_ini_tagcloud_list_limit  = $this->get_ini('tagcloud_list_limit');
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main_index( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// init
//---------------------------------------------------------
function init()
{
	$this->init_factory();
	$this->set_template_main_by_mode( $this->_mode );
	$this->init_preload();
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	switch( $this->_mode )
	{
		case 'category':
		case 'date':
		case 'place':
		case 'tag':
		case 'user':
			$ret = $this->page_main();
			break;

		case 'search':
		default:
			$ret = $this->build_page_detail( $this->_mode, $this->_param );
			break;
	}
	$arr = array_merge(
		$ret ,
		$this->build_execution_time()
	);
	return $arr;
}

function page_main()
{
	if ( $this->page_sel() ) {
		return $this->build_page_detail( $this->_mode, $this->_param );
	}
	return $this->build_page_list( $this->_mode );
}

function page_sel()
{
	switch( $this->_mode )
	{
		case 'user':
			$ret = $this->_user_class->page_sel( $this->_param );
			break;

		default:
			$ret = $this->page_sel_default();
			break;
	}
	return $ret;
}

function page_sel_default()
{
	if ( $this->_param ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// page list
//---------------------------------------------------------
function build_page_list( $mode )
{
	$this->show_array_set_list_by_mode( $mode );

	list( $photo_list, $photo_rows, $category_photo_list, $error ) 
		= $this->build_photo_list_for_list( $mode );
	$show_gmap = $this->set_tpl_gmap_for_list_with_check( $photo_rows );

	$this->xoops_header_array_set_by_mode( $mode ) ;
	$this->xoops_header_param();
	$this->xoops_header_rss_with_check(  $this->_MODE_DEFAULT, null );
	$this->xoops_header_gmap_with_check( $show_gmap );
	$this->xoops_header_assign();

	$this->show_param();
	$this->set_tpl_common();
	$this->set_tpl_mode( $mode );
	$this->set_tpl_title_for_list( $mode );
	$this->set_tpl_photo_list( $photo_list );
	$this->set_tpl_error( $error );
	$this->set_tpl_category_photo_list( $category_photo_list );
	$this->set_tpl_timeline_with_check( $photo_rows );
	$this->set_tpl_tagcloud_with_check( $this->_ini_tagcloud_list_limit );

	$this->set_tpl_show_js_windows();
	return $this->tpl_get();
}

function build_photo_list_for_list( $mode )
{
	$arr   = array();
	$error = null;

	switch( $mode )
	{
		case 'category':
			list( $category_photo_list, $photo_rows )
				= $this->_category_class->build_photo_list_for_list();
			return array(null, $photo_rows, $category_photo_list, null);
			break;

		case 'date':
			$arr   = $this->_date_class->build_rows_for_list();
			$error = $this->get_constant('DATE_NOT_SET');
			break;

		case 'place':
			$arr   = $this->_place_class->build_rows_for_list();
			$error = $this->get_constant('PLACE_NOT_SET');
			break;

		case 'tag':
			$arr   = $this->_tag_class->build_rows_for_list();
			$error = $this->get_constant('NO_TAG');
			break;

		case 'user':
			$arr = $this->_user_class->build_rows_for_list();
			break;

		default:
			break;
	}

	$photo_list = array();
	$photo_rows = array();

	if ( !is_array($arr) || !count($arr) ) {
		return array($photo_list, $photo_rows, null, $error);
	}

	foreach ( $arr as $a )
	{
		list( $title, $param, $total, $row ) = $a;
		$photo_list[] = $this->build_photo_list_for_list_by_row(
			$title, $param, $total, $row );
		$photo_rows[ $row['item_id'] ] = $row ;
	}

	return array($photo_list, $photo_rows, null, null);
}

function build_photo_list_for_list_by_row( $title, $param, $total, $row )
{
	$link  = $this->_uri_class->build_list_link( $this->_mode, $param ) ;
	$photo = $this->build_photo_by_row( $row );

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
// page detail
//---------------------------------------------------------
function build_page_detail( $mode, $param )
{
	$this->show_array_set_detail_by_mode( $mode );
	$this->set_tpl_show_page_detail( true );

	list( $title, $total, $rows, $photo_total_sum, $photo_small_sum ) 
		= $this->build_rows_for_detail( $mode, $param );

	if ( $mode == 'search' ) {
		$query_param = $this->_search_class->build_query_param( $total );
		$query_array = $query_param['search_query_array'];
		$this->_photo_class->set_flag_highlight( true );
		$this->_photo_class->set_keyword_array( $query_array );
	}

	$photo_list = $this->build_photo_list_for_detail( $rows );

	$show_gmap = $this->set_tpl_gmap_for_detail_with_check( 
		$mode, $rows, $this->_cat_id );

	$show_ligthtbox = false;
	if ( $this->show_check('photo') && isset($rows[0]) ) {
		$show_ligthtbox = $this->set_tpl_photo_for_detail( $rows[0] );
	}

	$this->xoops_header_array_set_by_mode( $mode ) ;
	$this->xoops_header_param();
	$this->xoops_header_rss_with_check(  $mode, null );
	$this->xoops_header_gmap_with_check( $show_gmap );
	$this->xoops_header_lightbox_with_check( $show_ligthtbox );
	$this->xoops_header_assign();

	$this->show_param();
	$this->set_tpl_common();
	$this->set_tpl_mode( $mode );
	$this->set_tpl_title( $title );
	$this->set_tpl_qr_with_check( 0 );
	$this->set_tpl_notification_select_with_check();
	$this->set_tpl_tagcloud_with_check( $this->_cfg_tags );
	$this->set_tpl_photo_list( $photo_list );
	$this->set_tpl_photo_total_sum(  $photo_total_sum );
	$this->set_tpl_photo_small_sum( $photo_small_sum );
	$this->set_tpl_cat_id( $this->_cat_id );
	$this->set_tpl_catpath_with_check( $this->_cat_id );
	$this->set_tpl_catlist_with_check( $this->_cat_id );

// for detail
	$this->set_tpl_timeline_with_check( $rows );
	$this->set_tpl_total_for_detail( $mode, $total );

	if ( $mode == 'search' ) {
		$this->tpl_merge( $query_param );
	}

	$this->set_tpl_show_js_windows();
	return $this->tpl_get();
}

function build_rows_for_detail( $mode, $param )
{
	$orderby = $this->_orderby;
	$limit   = $this->_PHOTO_LIMIT;
	$start   = $this->_start;

	$param_out      = null;

// BUG: total is wrong
	$photo_total_sum = 0;
	$photo_small_sum = 0;

	switch( $mode )
	{
		case 'category':
			list( $title, $total, $rows, $photo_total_sum, $photo_small_sum )
				= $this->category_build_rows_for_detail( $param );
			break;

		case 'date':
			list( $title, $total, $rows, $param_out ) 
				= $this->_date_class->build_rows_for_detail(
					$param, $orderby, $limit, $start );
			break;

		case 'place':
			list( $title, $total, $rows ) 
				= $this->_place_class->build_rows_for_detail( 
					$param, $orderby, $limit, $start );
			break;

		case 'tag':
			list( $title, $total, $rows ) 
				= $this->_tag_class->build_rows_for_detail( 
					$param, $orderby, $limit, $start );
			break;

		case 'user':
			list( $title, $total, $rows ) 
				= $this->_user_class->build_rows_for_detail( 
					$param, $orderby, $limit, $start );
			break;

		case 'search':
			list( $title, $total, $rows ) 
				= $this->_search_class->build_rows_for_detail( 
					$param, $orderby, $limit, $start );
			break;

		default:
			list( $title, $total, $rows ) 
				= $this->_main_class->build_rows_for_detail( 
					$mode, $this->_get_sort, $limit, $start );
			break;
	}

	if ( $param_out ) {
		$this->set_param_out( $param_out );
	}

	return array( $title, $total, $rows, $photo_total_sum, $photo_small_sum );
}

// --- class end ---
}

?>