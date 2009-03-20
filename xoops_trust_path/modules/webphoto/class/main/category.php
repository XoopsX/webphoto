<?php
// $Id: category.php,v 1.6 2009/03/20 04:18:09 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-03-15 K.OHWADA
// add_box_list() -> add_show_js_windows()
// 2008-12-12 K.OHWADA
// public_class
// 2008-09-13 K.OHWADA
// show cat_id for submit
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// 2008-07-01 K.OHWADA
// used set_mode()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_category
//=========================================================
class webphoto_main_category extends webphoto_show_list
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_category( $dirname , $trust_dirname )
{
	$this->webphoto_show_list( $dirname , $trust_dirname );
	$this->set_mode( 'category' );
	$this->init_preload();
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main_category( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// list
//---------------------------------------------------------
// overwrite
function list_build_list()
{
	$this->assign_xoops_header_default();

	$param1 = $this->list_build_list_common();
	$param2 = $this->build_catlist(
		0, $this->_TOP_CATLIST_COLS, $this->_TOP_CATLIST_DELMITA );

	$ret = array_merge( $param1, $param2 );
	return $this->add_show_js_windows( $ret );
}

// overwrite
function list_get_photo_list()
{
	$cat_rows = $this->_public_class->get_cat_all_tree_array();
	if ( !is_array($cat_rows) || !count($cat_rows) ) {
		return false;
	}

	$arr = array();
	foreach ( $cat_rows as $row )
	{
		$cat_id = $row['cat_id'];

		$show_catpath = false;

		$catpath = $this->build_cat_path( $cat_id );
		if ( is_array($catpath) && count($catpath) ) {
			$show_catpath = true;
		}

		list( $photo, $total, $this_sum ) = $this->_get_photo_for_list( $cat_id );

		$arr[] = array(
			'title'        => '' ,
			'title_s'      => '' ,
			'link'         => '' ,
			'link_s'       => '' ,
			'total'        => $total ,
			'photo'        => $photo ,
			'sum'          => $this_sum ,
			'show_catpath' => $show_catpath ,
			'catpath'      => $catpath ,
		);

	}

	return $arr;
}

function _get_photo_for_list( $cat_id )
{
	$photo = null;

	list( $rows, $total, $this_sum ) =
		$this->_public_class->get_rows_total_by_catid( 
			$cat_id, $this->_PHOTO_LIST_ORDER, $this->_PHOTO_LIST_LIMIT ) ;

	if ( is_array($rows) && count($rows) ) {
		$photo = $this->build_photo_show( $rows[0] );
	}

	return array( $photo, $total, $this_sum );
}

//---------------------------------------------------------
// detail list
//---------------------------------------------------------
// overwrite
function list_build_detail( $cat_id )
{

// for xoops notification
	$_GET['cat_id'] = $cat_id;

	$limit = $this->_MAX_PHOTOS;
	$start = $this->pagenavi_calc_start( $limit );

	$init_param = $this->list_build_init_param( true );

	$cat_param = $this->_build_category( $cat_id, $limit, $start );
	$title      = $cat_param['cat_title'] ;
	$total      = $cat_param['cat_photo_total'] ;
	$photo_rows = $cat_param['cat_photo_rows'] ;
	$show_sort  = $cat_param['cat_show_sort'] ;

	$param = $this->list_build_detail_common( $title, $total, $photo_rows );
	$param['title_bread_crumb'] = '' ;
	$param['sub_title_s']       = '' ;
	$param['show_sort']         = $show_sort ;

// for submit
	$param['cat_id']            = $cat_id ;

	$navi_param = $this->list_build_navi( $total, $limit );

	$catlist_param = $this->build_catlist(
		$cat_id, $this->_CAT_CATLIST_COLS, $this->_CAT_CATLIST_DELMITA );

	$gmap_param = $this->build_gmap( $cat_id, $this->_MAX_GMAPS );
	$show_gmap  = $gmap_param['show_gmap'];

	$noti_param = $this->build_notification_select();

	$this->list_assign_xoops_header( $cat_id, $show_gmap );

	$ret= array_merge( $param, $init_param, $cat_param, $navi_param, $catlist_param, $gmap_param, $noti_param );
	return $this->add_show_js_windows( $ret );
}

function _build_category( $cat_id, $limit, $start )
{
	$row = $this->_public_class->get_cat_row( $cat_id );

	if ( !is_array( $row ) ) {
		$arr = array(
			'cat_title'       => '',
			'cat_photo_total' => 0,
			'cat_photo_rows'  => null,
			'cat_show_sort'   => false,

			'photo_sum'      => 0,
			'show_catpath'   => false , 
			'catpath'        => '' , 
			'cat_desc_disp'  => '' , 
		);
		return $arr;
	}

	$cat_title = $row['cat_title'];

	$orderby = $this->get_orderby_by_post();

	$show_sort     = false ;
	$show_catpath  = false ;

	list( $photo_rows, $total, $this_sum ) =
		$this->_public_class->get_rows_total_by_catid( 
			$cat_id, $orderby, $limit, $start, true );

	if (( $this_sum > 1 ) ||
	    ( $this_sum == 0 ) && ( $total > 1 )) {
		$show_sort = true ;
	}

	$catpath = $this->build_cat_path( $cat_id );
	if ( is_array($catpath) && count($catpath) ) {
		$show_catpath = true;
	}

	$arr = array(
		'cat_title'       => $cat_title,
		'cat_photo_total' => $total,
		'cat_photo_rows'  => $photo_rows,
		'cat_show_sort'   => $show_sort,

		'photo_sum'      => $this_sum,
		'show_catpath'   => $show_catpath , 
		'catpath'        => $catpath , 
		'cat_desc_disp'  => $this->_cat_handler->build_show_desc_disp( $row ) , 
	);

	return $arr;

}

// --- class end ---
}

?>