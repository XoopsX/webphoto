<?php
// $Id: weblinks.php,v 1.2 2009/02/01 09:04:29 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-25 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_weblinks
//=========================================================
class webphoto_inc_weblinks extends webphoto_inc_public
{
	var $_catlist_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_weblinks()
{
	$this->webphoto_inc_public();
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_inc_weblinks();
	}
	return $instance;
}

//---------------------------------------------------------
// public
//---------------------------------------------------------
function albums( $opts )
{
	$dirname = isset( $opts['dirname'] ) ? $opts['dirname'] : '';

	if ( empty($dirname) ) {
		return null;
	}

	$inc_class =& webphoto_inc_catlist::getSingleton( $dirname );
	return $inc_class->get_cat_titles();
}

function photos( $opts )
{
	$dirname     = isset( $opts['dirname'] )      ? $opts['dirname']                : '';
	$width       = isset( $opts['width'] )        ? intval( $opts['width'] )        : 140;
	$album_limit = isset( $opts['album_limit'] )  ? intval( $opts['album_limit'] )  : 1;
	$album_id    = isset( $opts['album_id'] )     ? intval( $opts['album_id'] )     : 0;
	$mode_sub    = isset( $opts['mode_sub'] )     ? intval( $opts['mode_sub'] )     : 1;
	$cycle       = isset( $opts['cycle'] )        ? intval( $opts['cycle'] )        : 60;
	$cols        = isset( $opts['cols'] )         ? intval( $opts['cols'] )         : 3;
	$title_max   = isset( $opts['title_max'] )    ? intval( $opts['title_max'] )    : 20;

	if ( empty($dirname) ) {
		return null;
	}

	$cache_time       = 0 ;
	$disable_renderer = true ; 

	$options = array(
		0 => $dirname,		// dirname
		1 => $album_limit,	// photos_num
		2 => $album_id,		// cat_limitation
		3 => $mode_sub,		// cat_limit_recursive
		4 => $title_max,	// title_max_length
	    5 => $cols,			// cols
		6 => $cache_time,	// cache_time
		'disable_renderer' => $disable_renderer , 
	);

	$inc_class =& webphoto_inc_blocks::getInstance();
	$block = $inc_class->rphoto_show( $options );

	if ( !is_array($block) || !count($block) ) {
		return null;
	}

	if ( !is_array($block['photo']) || !count($block['photo']) ) {
		return null;
	}

	$href_base       = XOOPS_URL .'/modules/'. $dirname .'/index.php';
	$use_pathinfo    = $block['use_pathinfo'] ;
	$attribs_default = 'width="'. $block['cfg_thumb_width'] .'"';

	$ret = array();
	foreach ( $block['photo'] as $photo )
	{
		$ret[] = array(
			'href'        => $this->build_href_photo( $photo, $href_base, $use_pathinfo ) ,
			'cat_href'    => $this->build_href_cat(   $photo, $href_base, $use_pathinfo ) ,
			'title'       => $photo['title_s'] ,
			'cat_title'   => $photo['cat_title_s'] ,
			'img_src'     => $photo['img_thumb_src_s'] ,
			'img_attribs' => $this->build_attribs( $photo, $attribs_default ) ,
		);
	}

	return $ret ;
}

function build_href_photo( $photo, $href_base, $use_pathinfo )
{
	$photo_id = $photo['photo_id'];

	if ( $use_pathinfo ) {
		$href = $href_base .'/photo/'. $photo_id .'/';
	} else {
		$href = $href_base. '?fct=photo&amp;p='. $photo_id ;
	}
	return $href;
}

function build_href_cat( $photo, $href_base, $use_pathinfo )
{
	$cat_id = $photo['item_cat_id'];

	if ( $use_pathinfo ) {
		$href = $href_base .'/category/'. $cat_id .'/' ;
	} else {
		$href = $href_base. '?fct=category&amp;p='. $cat_id ;
	}
	return $href;
}

function build_attribs( $photo, $attribs_default )
{
	if ( $photo['img_thumb_width'] && $photo['img_thumb_height'] ) {
		$attribs = 'width="'. $photo['img_thumb_width'] .'" height="'. $photo['img_thumb_height'] .'"';
	} else {
		$attribs = $attribs_default ;
	}
	return $attribs;
}

// --- class end ---
}

?>