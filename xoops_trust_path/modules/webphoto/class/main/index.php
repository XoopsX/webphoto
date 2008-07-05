<?php
// $Id: index.php,v 1.2 2008/07/05 12:54:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-07-01 K.OHWADA
// build_navi() -> build_main_navi()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_index
//=========================================================
class webphoto_main_index extends webphoto_show_main
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_index( $dirname , $trust_dirname )
{
	$this->webphoto_show_main( $dirname , $trust_dirname );
	$this->init_preload();
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
// main
//---------------------------------------------------------
function main()
{
	$show_photo = false;
	$photos     = null;

	$mode  = $this->_get_action();
	$limit = $this->_MAX_PHOTOS;
	$start = $this->pagenavi_calc_start( $limit );

	$total = $this->_photo_handler->get_count_public();

	if ( $total > 0 ) {
		$show_photo = true;
		$photos     = $this->_get_photos_by_mode( $mode, $limit, $start );
	}

	$sub_title_s = $this->sanitize( $this->get_constant( 'TITLE_'. $mode ) ); 

	$init_param     = $this->build_init_param( $mode, true );
	$tagcloud_param = $this->_build_tagcloud_param( $mode );
	$catlist_param  = $this->_build_catlist_param( $mode );
	$noti_param     = $this->_build_notification_select_param( $mode );
	$navi_param     = $this->_build_navi_param( $mode, $total, $limit );

	$gmap_param = $this->_build_gmap_param( $mode );
	$show_gmap  = $gmap_param['show_gmap'];

	$this->assign_xoops_header( $mode, null, $show_gmap );

	$arr = array(
		'xoops_pagetitle'   => $this->sanitize( $this->_MODULE_NAME ),
		'title_bread_crumb' => $sub_title_s,
		'total_bread_crumb' => $total,
		'sub_title_s'       => $sub_title_s ,
		'sub_desc_s'        => '' , 
		'photo_total'       => $total,
		'photos'            => $photos,
		'show_photo'        => $show_photo , 
		'show_nomatch'      => $this->build_show_nomatch( $total ) ,
		'random_more_url_s' => $this->_build_random_more_url_s_by_mode( $mode ) ,
		'index_desc'        => $this->_build_index_desc( $mode ) ,
	);

	$ret = array_merge( $arr, $init_param, $navi_param, $tagcloud_param, $catlist_param, $gmap_param, $noti_param );
	return $this->add_box_list( $ret );
}

//---------------------------------------------------------
// get param from url
//---------------------------------------------------------
function _get_action()
{
	$this->get_pathinfo_param();

	if ( $this->_get_op == 'latest' ) {
		return 'latest';
	} elseif ( $this->_get_op == 'popular' ) {
		return 'popular';
	} elseif ( $this->_get_op == 'highrate' ) {
		return 'highrate';
	} elseif ( $this->_get_op == 'random' ) {
		return 'random';
	}

	return $this->_ACTION_DEFAULT;
}

//---------------------------------------------------------
// latest etc
//---------------------------------------------------------
function _get_photos_by_mode( $mode, $limit, $start )
{
	$orderby  = $this->_sort_class->mode_to_orderby( $mode );
	$rows     = $this->_photo_handler->get_rows_public_by_orderby( $orderby, $limit, $start );
	return $this->build_photo_show_from_rows( $rows );
}

function _build_random_more_url_s_by_mode( $mode )
{
	if ( $mode != 'random' ) {
		return null;
	}

	$url = 'index.php/random/';
	return $this->sanitize( $this->add_viewtype( $url ) );
}

//---------------------------------------------------------
// index desc
//---------------------------------------------------------
function _build_index_desc( $mode )
{
	if ( $this->check_show_common( $mode, 'desc' ) ) {
		return $this->_config_class->get_by_name('index_desc');
	}
	return null;
}

//---------------------------------------------------------
// cat list
//---------------------------------------------------------
function _build_catlist_param( $mode )
{
	if ( $this->check_show_catlist( $mode ) ) {
		return $this->build_catlist(
			0, $this->_TOP_CATLIST_COLS, $this->_TOP_CATLIST_DELMITA );
	}

	$arr = array(
		'show_cat_list' => false
	);
	return $arr;
}

//---------------------------------------------------------
// tag cloud
//---------------------------------------------------------
function _build_tagcloud_param( $mode )
{
	if ( $this->check_show_tagcloud( $mode ) ) {
		return $this->_tag_class->build_tagcloud( $this->_MAX_TAG_CLOUD );
	}

	$arr = array(
		'show_tagcloud' => false
	);
	return $arr;
}

//---------------------------------------------------------
// gmap
//---------------------------------------------------------
function _build_gmap_param( $mode )
{
	if ( $this->check_show_gmap( $mode ) ) {
		return $this->build_gmap( 0, $this->_MAX_GMAPS );
	}

	$arr = array(
		'show_gmap' => false
	);
	return $arr;
}

//---------------------------------------------------------
// notification_select
//---------------------------------------------------------
function _build_notification_select_param( $mode )
{
	if ( $this->check_show_notification( $mode ) ) {
		return $this->build_notification_select();
	}

	$arr = array(
		'show_notification_select' => false
	);
	return $arr;

}

//---------------------------------------------------------
// navi
//---------------------------------------------------------
function _build_navi_param( $mode, $total, $limit )
{
	if ( $this->check_show_navi( $mode, $this->_get_sort ) ) {
		return $this->build_main_navi( $mode, $total, $limit ) ;
	}

	$arr = array(
		'show_navi' => false
	);
	return $arr;
}

// --- class end ---
}

?>