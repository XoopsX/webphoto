<?php
// $Id: search.php,v 1.11 2008/12/20 06:11:27 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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
	var $_SHOW_IMAGE = true ;
	var $_SHOW_ICON  = false ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_search( $dirname )
{
	$this->webphoto_inc_public();
	$this->init_public( $dirname );
	$this->auto_publish( $dirname );
	$this->set_normal_exts( _C_WEBPHOTO_IMAGE_EXTS );

// preload
	$name_image= strtoupper( '_P_'. $dirname .'_SEARCH_SHOW_IMAGE' );
	$name_icon = strtoupper( '_P_'. $dirname .'_SEARCH_SHOW_ICON' );

	if ( defined( $name_image ) ) {
		$this->_SHOW_IMAGE = constant( $name_image );
	}
	if ( defined( $name_icon ) ) {
		$this->_SHOW_ICON = constant( $name_icon );
	}
}

function &getSingleton( $dirname )
{
	static $singletons;
	if ( !isset( $singletons[ $dirname ] ) ) {
		$singletons[ $dirname ] = new webphoto_inc_search( $dirname );
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
		$item_id   = $item_row['item_id'];
		$item_kind = $item_row['item_kind'];

		$img_url    = null ;
		$img_width  = 0 ;
		$img_height = 0 ;

		$is_image  = $this->is_image_kind( $item_kind );
		$thumb_row = $this->get_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_THUMB );

		list( $thumb_url, $thumb_width, $thumb_height ) =
			$this->build_show_file_image( $thumb_row ) ;

		list( $icon_url, $icon_width, $icon_height ) =
			$this->build_show_icon_image( $item_row );

		if ( $is_image || $this->_SHOW_IMAGE ) {
			if ( $thumb_url ) {
				$img_url    = $thumb_url;
				$img_width  = $thumb_width;
				$img_height = $thumb_height;

			} elseif ( $this->_SHOW_ICON && $icon_url ) {
				$img_url    = $icon_url;
				$img_width  = $icon_width;
				$img_height = $icon_height;	
			}
		}

		if ( $this->_cfg_use_pathinfo ) {
			$link = 'index.php/photo/'. $item_id .'/keywords='. $keywords .'/' ;
		} else {
			$link = 'index.php?fct=photo&amp;p='. $item_id .'&amp;keywords='. $keywords ;
		}

		$arr['link']    = $link ;
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