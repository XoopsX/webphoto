<?php
// $Id: index.php,v 1.5 2009/03/20 04:18:09 ohwada Exp $

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
	$show_photo      = false;
	$main_photos     = null;
	$timeline_photos = null ;

	$mode = $this->_get_action();
	$this->set_mode( $mode );

	$limit = $this->_MAX_PHOTOS;
	$start = $this->pagenavi_calc_start( $limit );
	$total = $this->_public_class->get_count();
	$unit  = $this->_post_class->get_get_text('unit');

	if ( $total > 0 ) {
		$show_photo = true;
		if ( $this->_MAX_TIMELINE > $this->_MAX_PHOTOS ) { 
			$timeline_photos = $this->_get_photos_by_mode( $this->_MAX_TIMELINE, $start );
			$main_photos     = array_slice( $timeline_photos, 0, $this->_MAX_PHOTOS );
		} else {
			$main_photos     = $this->_get_photos_by_mode(  $this->_MAX_PHOTOS, $start );
			$timeline_photos = array_slice( $main_photos, 0, $this->_MAX_TIMELINE );
		}
	}

	$sub_title_s = $this->sanitize( $this->get_constant( 'TITLE_'. $mode ) ); 

	$init_param     = $this->build_init_param( $mode, true );
	$tagcloud_param = $this->_build_tagcloud_param();
	$catlist_param  = $this->_build_catlist_param();
	$noti_param     = $this->_build_notification_select_param();
	$navi_param     = $this->_build_navi_param( $total, $limit );

	$gmap_param = $this->_build_gmap_param();
	$show_gmap  = $gmap_param['show_gmap'];

	$timeline_param = $this->_build_timeline_param( $unit, $timeline_photos );

	$this->assign_xoops_header( $mode, null, $show_gmap );

	$this->create_mobile_qr( 0 );

	$arr = array(
		'xoops_pagetitle'   => $this->sanitize( $this->_MODULE_NAME ),
		'title_bread_crumb' => $sub_title_s,
		'total_bread_crumb' => $total,
		'sub_title_s'       => $sub_title_s ,
		'sub_desc_s'        => '' , 
		'photo_total'       => $total,
		'photos'            => $main_photos,
		'show_photo'        => $show_photo , 
		'show_nomatch'      => $this->build_show_nomatch( $total ) ,
		'random_more_url_s' => $this->_build_random_more_url_s_by_mode() ,
		'index_desc'        => $this->_build_index_desc() ,
		'mobile_email'      => $this->get_mobile_email() ,
		'mobile_url'        => $this->build_mobile_url( 0 ) ,
		'mobile_url'        => $this->build_mobile_url( 0 ) ,
	);

	$ret = array_merge( $arr, $init_param, $navi_param, $tagcloud_param, $catlist_param, $gmap_param, $timeline_param, $noti_param );
	return $this->add_show_js_windows( $ret );
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
	} elseif ( $this->_get_op == 'map' ) {
		return 'map';
	} elseif ( $this->_get_op == 'timeline' ) {
		return 'timeline';
	}

	return $this->_ACTION_DEFAULT;
}

//---------------------------------------------------------
// latest etc
//---------------------------------------------------------
function _get_photos_by_mode( $limit, $start )
{
	$orderby  = $this->_sort_class->mode_to_orderby( $this->_mode );
	$rows     = $this->_public_class->get_rows_by_orderby( $orderby, $limit, $start );
	return $this->build_photo_show_from_rows( $rows );
}

function _build_random_more_url_s_by_mode()
{
	if ( $this->_mode != 'random' ) {
		return null;
	}

	$url = 'index.php/random/';
	return $this->sanitize( $this->add_viewtype( $url ) );
}

//---------------------------------------------------------
// index desc
//---------------------------------------------------------
function _build_index_desc()
{
	if ( $this->check_show_common( $this->_mode, 'desc' ) ) {
		return $this->_config_class->get_by_name('index_desc');
	}
	return null;
}

//---------------------------------------------------------
// cat list
//---------------------------------------------------------
function _build_catlist_param()
{
	if ( $this->check_show_catlist( $this->_mode ) ) {
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
function _build_tagcloud_param()
{
	$show     = false;
	$tagcloud = null;

	if ( $this->check_show_tagcloud( $this->_mode ) ) {
		$tagcloud = $this->_public_class->build_tagcloud( $this->_MAX_TAG_CLOUD );

		if ( is_array($tagcloud) && count($tagcloud) ) {
			$show = true;
		}

	}

	$arr = array(
		'show_tagcloud' => $show,
		'tagcloud'      => $tagcloud,
	);

	return $arr;
}

//---------------------------------------------------------
// gmap
//---------------------------------------------------------
function _build_gmap_param()
{
	if ( $this->check_show_gmap( $this->_mode ) ) {
		return $this->build_gmap( 0, $this->_MAX_GMAPS );
	}

	$arr = array(
		'show_gmap' => false
	);
	return $arr;
}

//---------------------------------------------------------
// timeline
//---------------------------------------------------------
function _build_timeline_param( $unit, $photos )
{
	if ( $this->check_show_timeline( $this->_mode ) ) {
		return $this->build_timeline( $unit, $photos );
	}

	$arr = array(
		'show_timeline' => false
	);
	return $arr;
}

//---------------------------------------------------------
// notification_select
//---------------------------------------------------------
function _build_notification_select_param()
{
	if ( $this->check_show_notification( $this->_mode ) ) {
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
function _build_navi_param( $total, $limit )
{
	if ( $this->check_show_navi( $this->_mode, $this->_get_sort ) ) {
		return $this->build_main_navi( $this->_mode, $total, $limit ) ;
	}

	$arr = array(
		'show_navi' => false
	);
	return $arr;
}

// --- class end ---
}

?>