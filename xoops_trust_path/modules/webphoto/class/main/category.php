<?php
// $Id: category.php,v 1.2 2008/07/05 12:54:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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
	return $this->add_box_list( $ret );
}

// overwrite
function list_get_photo_list()
{
	$cat_rows = $this->_cat_handler->get_all_tree_array();
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

		list( $total, $this_sum, $photo ) = $this->_get_photo_for_list( $cat_id );

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

	$catid_array = $this->_cat_handler->get_all_child_id( $cat_id );
	array_push( $catid_array , $cat_id ) ;

	$this_sum = $this->_photo_handler->get_count_public_by_catid( $cat_id );
	$total    = $this->_photo_handler->get_count_public_by_catid_array( $catid_array );

	if ( $total > 0 ) {

// this category
		if ( $this_sum > 0 ) {
			$photo_rows = $this->_photo_handler->get_rows_public_by_catid_orderby( 
				$cat_id, $this->_PHOTO_LIST_ORDER, $this->_PHOTO_LIST_LIMIT );

			if ( is_array($photo_rows) && count($photo_rows) ) {
				$photo = $this->build_photo_show( $photo_rows[0] );
			}
		}
	}

	return array( $total, $this_sum, $photo );
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

	$navi_param = $this->list_build_navi( $total, $limit );

	$catlist_param = $this->build_catlist(
		$cat_id, $this->_CAT_CATLIST_COLS, $this->_CAT_CATLIST_DELMITA );

	$gmap_param = $this->build_gmap( $cat_id, $this->_MAX_GMAPS );
	$show_gmap  = $gmap_param['show_gmap'];

	$noti_param = $this->build_notification_select();

	$this->list_assign_xoops_header( $cat_id, $show_gmap );

	$ret= array_merge( $param, $init_param, $cat_param, $navi_param, $catlist_param, $gmap_param, $noti_param );
	return $this->add_box_list( $ret );
}

function _build_category( $cat_id, $limit, $start )
{
	$row = $this->_cat_handler->get_cached_row_by_id( $cat_id );
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

	list( $total, $this_sum, $photo_rows ) 
		= $this->_get_photos_for_detail( $cat_id, $orderby, $limit, $start );

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

function _get_photos_for_detail( $cat_id, $orderby, $limit, $start )
{
	$photo_rows = null;

	$catid_array = $this->_cat_handler->get_all_child_id( $cat_id );
	array_push( $catid_array , $cat_id ) ;

	$this_sum = $this->_photo_handler->get_count_public_by_catid( $cat_id );
	$total    = $this->_photo_handler->get_count_public_by_catid_array( $catid_array );

	if ( $total > 0 ) {

// this category
		if ( $this_sum > 0 ) {
			$where = $this->_photo_handler->build_where_public_by_catid( $cat_id );

// this category & all children
		} else {
			$where = $this->_photo_handler->build_where_public_by_catid_array( $catid_array );
		}

		$photo_rows = $this->_photo_handler->get_rows_by_where_orderby(
			$where, $orderby, $limit, $start );
	}

	return array( $total, $this_sum, $photo_rows );
}

// --- class end ---
}

?>