<?php
// $Id: photo.php,v 1.25 2010/11/04 02:23:19 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-03 K.OHWADA
// get_photo_keyword_array()
// 2010-01-10 K.OHWADA
// webphoto_show_list -> webphoto_factory
// 2009-12-06 K.OHWADA
// $_SHOW_PHOTO_SUMMARY
// 2009-11-11 K.OHWADA
// webphoto_show_list -> webphoto_show_main_photo
// 2009-10-25 K.OHWADA
// webphoto_show_list
// _C_WEBPHOTO_CODEINFO_FILE_LIST
// 2009-05-17 K.OHWADA
// $_SHOW_PHOTO_SUMMARY
// 2009-04-19 K.OHWADA
// sub_title -> catpath
// 2009-04-18 K.OHWADA
// BUG: not show description
// 2009-03-15 K.OHWADA
// add_box_list() -> add_show_js_windows()
// 2009-01-25 K.OHWADA
// build_movie() -> build_movie_by_item_row()
// 2008-12-12 K.OHWADA
// webphoto_item_public
// 2008-12-07 K.OHWADA
// build_photo_show() -> build_photo_show_main()
// 2008-11-16 K.OHWADA
// _build_code()
// refresh_cache_by_item_row()
// 2008-10-01 K.OHWADA
// update_hits() -> countup_hits()
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// QR code
// 2008-07-01 K.OHWADA
// used build_uri_photo() build_photo_pagenavi()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_photo
//=========================================================
class webphoto_main_photo extends webphoto_factory
{
	var $_TIME_SUCCESS = 1;
	var $_TIME_FAIL    = 5;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_photo( $dirname , $trust_dirname )
{
	$this->webphoto_factory( $dirname , $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main_photo( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// init
//---------------------------------------------------------
function init()
{
	$this->init_factory();
	$this->set_mode( 'photo' );
	$this->set_template_main( 'main_photo.html' );

	$this->init_preload();
}

//---------------------------------------------------------
// check
//---------------------------------------------------------
function check_edittag()
{
	$this->_photo_class->check_photo_edittag();
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	$this->init();

	$mode = $this->_mode;

// load row
	$row = $this->_photo_class->get_photo_row();
	$photo_id  = $row['item_id'];
	$photo_uid = $row['item_uid'];
	$cat_id    = $row['item_cat_id'];
	$title     = $this->sanitize( $row['item_title'] );

// for xoops comment & notification
	$_GET['photo_id'] = $photo_id;

	$this->_photo_class->set_flag_highlight( true );
	$this->_photo_class->set_keyword_array(
		$this->_uri_parse_class->get_photo_keyword_array() );

// countup hits
	if ( $this->_photo_class->check_not_owner( $row['item_uid'] ) ) {
		$this->_item_handler->countup_hits( $photo_id, true );
	}

	$this->show_array_set_detail_by_mode( $mode );

	$show_gmap      = $this->set_tpl_gmap_for_photo_with_check( $row );
	$show_ligthtbox = $this->set_tpl_photo_for_detail( $row );

	$this->xoops_header_array_set_by_mode( $mode ) ;
	$this->xoops_header_param();
	$this->xoops_header_rss_with_check( 'category', $cat_id );
	$this->xoops_header_gmap_with_check( $show_gmap );
	$this->xoops_header_lightbox_with_check( $show_ligthtbox );
	$this->xoops_header_assign();

// same as main
	$this->show_param();
	$this->set_tpl_common();
	$this->set_tpl_mode( $mode );
	$this->set_tpl_title( $title );
	$this->set_tpl_qr_with_check( $photo_id );
	$this->set_tpl_notification_select_with_check();
	$this->set_tpl_tagcloud_with_check( $this->_cfg_tags );
	$this->set_tpl_cat_id(  $cat_id );
	$this->set_tpl_catpath_with_check( $cat_id );
	$this->set_tpl_catlist_with_check( $cat_id );

// photo
	$this->set_tpl_photo_tags( $photo_id );
	$this->set_tpl_photo_nav( $photo_id, $cat_id );

	if ( $this->show_check('photo_list') ) {
		list( $title, $total, $rows, $phpto_sum ) 
			= $this->category_build_rows_for_detail( $cat_id );
		$photo_list = $this->build_photo_list_for_detail( $rows );
		$this->set_tpl_photo_list( $photo_list );
		$this->set_tpl_total_for_detail( $mode, $total );
		$this->set_tpl_timeline_with_check( $rows );
	}

	$this->set_tpl_show_js_windows();
	return $this->tpl_get();
}

//---------------------------------------------------------
// xoops comment
//---------------------------------------------------------
function comment_view()
{
	$this->_photo_class->comment_view();
}

// --- class end ---
}

?>