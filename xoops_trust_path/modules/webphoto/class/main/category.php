<?php
// $Id: category.php,v 1.9 2009/11/06 18:04:17 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-10-25 K.OHWADA
// build_photos_param_in_category()
// 2009-05-30 K.OHWADA
// BUG : not show cat_id
// 2009-05-17 K.OHWADA
// _build_cat_summary_disp()
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

	if ( _C_WEBPHOTO_COMMUNITY_USE ) {
		$this->_TEMPLATE_DETAIL = 'main_photo.html';
		$this->_SHOW_PHOTO_VIEW = true;
		$this->set_navi_mode( 'kind' );
	}
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

		$cat_desc_disp = $this->build_cat_desc_disp( $row ) ; 

		$arr[] = array(
			'title'            => '' ,
			'title_s'          => '' ,
			'link'             => '' ,
			'link_s'           => '' ,
			'total'            => $total ,
			'photo'            => $photo ,
			'sum'              => $this_sum ,
			'show_catpath'     => $show_catpath ,
			'catpath'          => $catpath ,
			'cat_desc_disp'    => $cat_desc_disp , 
			'cat_summary_disp' => $this->_build_cat_summary_disp( $cat_desc_disp )
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

function _build_cat_summary_disp( $desc )
{
	return $this->_multibyte_class->build_summary( $desc, $this->_cfg_cat_summary );
}

//---------------------------------------------------------
// detail list
//---------------------------------------------------------
// overwrite
function list_build_detail( $cat_id )
{

// BUG : not show cat_id
	$init_param = $this->list_build_init_param( true, $cat_id );

	$photo_param = $this->build_photos_param_in_category( $cat_id );
	$title       = $photo_param['cat_title'] ;
	$photo_rows  = $photo_param['cat_photo_rows'];

	$title_s = $this->sanitize( $title );
	$param = array(
		'xoops_pagetitle'   => $title_s ,
		'title_bread_crumb' => $title_s ,
	);

	if ( $this->_SHOW_PHOTO_VIEW && isset( $photo_rows[0] ) ) {
		$photo_param['photo'] = $this->build_photo_show_photo( $photo_rows[0] );
		$photo_param['show_photo_desc'] = true;
	}

	$catlist_param = $this->build_catlist(
		$cat_id, $this->_CAT_CATLIST_COLS, $this->_CAT_CATLIST_DELMITA );

	$gmap_param = $this->build_gmap( $cat_id, $this->_MAX_GMAPS );
	$show_gmap  = $gmap_param['show_gmap'];

	$noti_param = $this->build_notification_select( $cat_id );

	$this->list_assign_xoops_header( $cat_id, $show_gmap );

	$arr = array_merge( $init_param, $param, $photo_param, $catlist_param, $gmap_param, $noti_param );
	$arr['show_qr'] = false;

	return $this->add_show_js_windows( $arr );
}

// --- class end ---
}

?>