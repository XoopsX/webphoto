<?php
// $Id: photo.php,v 1.20 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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
class webphoto_main_photo extends webphoto_show_main_photo
{
	var $_TIME_SUCCESS = 1;
	var $_TIME_FAIL    = 5;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_photo( $dirname , $trust_dirname )
{
	$this->webphoto_show_main_photo( $dirname , $trust_dirname );

	$this->set_mode( 'photo' );
	$this->set_flag_highlight( true );
	$this->set_template_main( 'main_photo.html' );

	$this->init_preload();

	if ( $this->get_ini('community_use') ) {
		$this->_SHOW_PHOTOS_IN_CAT = true;
		$this->set_navi_mode( 'kind' );
	}
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
// check
//---------------------------------------------------------
function check_edittag()
{
	$this->check_photo_edittag();
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	$photos_param = array();

	$this->get_pathinfo_param();

// load row
	$row = $this->_photo_row;
	$photo_id  = $row['item_id'];
	$photo_uid = $row['item_uid'];

// for xoops comment & notification
	$_GET['photo_id'] = $photo_id;

	$this->set_keyword_array_by_get();

// countup hits
	if ( $this->check_not_owner( $row['item_uid'] ) ) {
		$this->_item_handler->countup_hits( $photo_id, true );
	}

	$photo      = $this->build_photo_show_photo( $row );
	$gmap_param = $this->build_photo_gmap_param( $row );
	$show_gmap  = $gmap_param['show_gmap'];
	$cat_id     = $this->get_photo_catid_row_or_post( $row ) ;

	$this->assign_xoops_header( 'category', $cat_id, $show_gmap );

	$this->create_mobile_qr( $photo_id );

	$param = array(
		'xoops_pagetitle'    => $photo['title_s'],
		'photo'              => $photo,
		'catpath'            => $this->build_cat_path( $cat_id ) ,
		'photo_nav'          => $this->build_photo_navi( $photo_id, $cat_id ),
		'show_comment'       => true ,
		'show_photo_desc'    => true ,
		'show_photo_exif'    => true ,
		'show_photo_content' => $this->_SHOW_PHOTO_CONTENT ,
		'mobile_email'       => $this->get_mobile_email() ,
		'mobile_url'         => $this->build_mobile_url( $photo_id ) ,
		'mobile_qr_image'    => $this->build_mobile_filename( $photo_id ) ,
	);

	if ( $this->_SHOW_PHOTOS_IN_CAT ) {
		$photos_param = $this->build_photos_param_in_category( $cat_id ) ;
	}

// BUG: not show description
	$arr = array_merge( 
		$param, $gmap_param, $photos_param, 
		$this->build_main_param( $this->_mode, $this->_SHOW_PHOTO_SUMMARY ) ,
		$this->build_photo_tags_param( $photo_id ) ,
		$this->build_notification_select() 
	);

	return $this->add_show_js_windows( $arr );
}

// --- class end ---
}

?>