<?php
// $Id: search.php,v 1.14 2011/06/05 07:23:40 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-06-04 K.OHWADA
// webphoto_inc_uri
// 2010-06-06 K.OHWADA
// BUG: overwrited by the previous data
// 2009-11-11 K.OHWADA
// $trust_dirname
// get_ini()
// 2008-12-12 K.OHWADA
// webphoto_inc_public
// 2008-11-29 K.OHWADA
// build_show_file_image()
// 2008-10-01 K.OHWADA
// BUG : implode() : Bad arguments
// 2008-08-24 K.OHWADA
// table_photo -> table_item
// 2008-07-01 K.OHWADA
// used use_pathinfo
// used is_video_mime()
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_search
//=========================================================
class webphoto_inc_search extends webphoto_inc_public
{
	var $_uri_class;

	var $_SHOW_IMAGE = true ;
	var $_SHOW_ICON  = false ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_search( $dirname, $trust_dirname )
{
	$this->webphoto_inc_public();
	$this->init_public( $dirname , $trust_dirname );
	$this->auto_publish();

	$this->set_normal_exts( _C_WEBPHOTO_IMAGE_EXTS );

	$this->_uri_class =& webphoto_inc_uri::getSingleton( $dirname );

// preload
	$show_image = $this->get_ini( 'search_show_image' );
	$show_icon  = $this->get_ini( 'search_show_icon' );

	if ( $show_image ) {
		$this->_SHOW_IMAGE = $show_image ;
	}
	if ( $show_icon ) {
		$this->_SHOW_ICON = $show_icon ;
	}
}

function &getSingleton( $dirname, $trust_dirname )
{
	static $singletons;
	if ( !isset( $singletons[ $dirname ] ) ) {
		$singletons[ $dirname ] = new webphoto_inc_search( $dirname, $trust_dirname );
	}
	return $singletons[ $dirname ];
}

//---------------------------------------------------------
// public
//---------------------------------------------------------
function search( $query_array, $andor, $limit, $offset, $uid )
{
	$item_rows = $this->get_item_rows_for_search( $query_array, $andor, $uid, $limit, $offset );
	if ( !is_array($item_rows) ) {
		return array(); 
	}

// no query_array called by userinfo
	$keywords = null ;
	if ( is_array($query_array) ) {
		$keywords = urlencode( implode(' ', $query_array) );
	}

	$i   = 0;
	$ret = array();

	foreach( $item_rows as $item_row )
	{
		$item_id = $item_row['item_id'];

		list( $img_url, $img_width, $img_height ) =
			$this->build_img_url( $item_row, $this->_SHOW_IMAGE, $this->_SHOW_ICON );

		$link = $this->_uri_class->build_search_photo_keywords( $item_id, $keywords ) ;

// BUG: overwrited by the previous data
		$arr = array();

		$arr['link']    = $link;
		$arr['title']   = $item_row['item_title'];
		$arr['time']    = $item_row['item_time_update'];
		$arr['uid']     = $item_row['item_uid'];
		$arr['image']   = 'images/icons/search.png';
		$arr['context'] = $this->_build_context( $item_row, $query_array );

		if ( $img_url ) {
			$arr['img_url']    = $img_url;
			$arr['img_width']  = $img_width;
			$arr['img_height'] = $img_height;
		}

		$ret[ $i ] = $arr;
		$i++;
	}

	return $ret;
}

//---------------------------------------------------------
// private
//---------------------------------------------------------
function _build_context( $row, $query_array )
{
	$str = $this->build_item_description( $row );
	$str = preg_replace("/>/", '> ', $str);
	$str = strip_tags( $str );

// this function is defined in happy_linux module
	if ( function_exists('happy_linux_build_search_context') ) {
		$str = happy_linux_build_search_context( $str, $query_array );

// this function is defined in search module
	} elseif ( function_exists('search_make_context') ) {
		$str = search_make_context( $str, $query_array );
	}

	return $str;
}

// --- class end ---
}

?>