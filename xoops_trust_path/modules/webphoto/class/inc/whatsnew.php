<?php
// $Id: whatsnew.php,v 1.9 2008/12/18 13:23:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-12-12 K.OHWADA
// webphoto_inc_public
// 2008-11-29 K.OHWADA
// build_show_file_image() etc
// 2008-08-24 K.OHWADA
// table_photo -> table_item
// 2008-07-01 K.OHWADA
// used use_pathinfo
// used is_video_mime()
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_whatsnew
//=========================================================
class webphoto_inc_whatsnew extends webphoto_inc_public
{
	var $_SHOW_IMAGE = true ;
	var $_SHOW_ICON  = false ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_whatsnew()
{
	$this->webphoto_inc_public();
	$this->set_normal_exts( _C_WEBPHOTO_IMAGE_EXTS );
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_inc_whatsnew();
	}
	return $instance;
}

function _init( $dirname )
{
	$this->init_handler( $dirname );
	$this->init_xoops_config( $dirname );
	$this->auto_publish( $dirname );

// preload
	$name_image= strtoupper( '_P_'. $dirname .'_WHATSNEW_SHOW_IMAGE' );
	$name_icon = strtoupper( '_P_'. $dirname .'_WHATSNEW_SHOW_ICON' );

	if ( defined( $name_image ) ) {
		$this->_SHOW_IMAGE = constant( $name_image );
	}
	if ( defined( $name_icon ) ) {
		$this->_SHOW_ICON = constant( $name_icon );
	}
}

//---------------------------------------------------------
// public
//---------------------------------------------------------
function whatsnew( $dirname , $limit=0 , $offset=0 )
{
	$this->_init( $dirname );

	$item_rows = $this->get_item_rows_for_whatsnew( $limit, $offset );
	if ( !is_array($item_rows) ) {
		return array(); 
	}

	$i   = 0;
	$ret = array();

	foreach( $item_rows as $item_row )
	{
		$cat_title  = null;
		$cont_mime  = null;
		$image      = null ;
		$width      = 0 ;
		$height     = 0 ;

		$item_id      = $item_row['item_id'];
		$cat_id       = $item_row['item_cat_id'];
		$time_update  = $item_row['item_time_update'];
		$time_create  = $item_row['item_time_create'];
		$item_kind    = $item_row['item_kind'];

		$is_image  = $this->is_image_kind( $item_kind );

		$cat_row = $this->get_cat_cached_row_by_id( $cat_id );
		if ( is_array($cat_row) ) {
			$cat_title = $cat_row['cat_title'];
		}

		$cont_row  = $this->get_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_CONT );
		$thumb_row = $this->get_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_THUMB );

		if ( is_array($cont_row) ) {
			$cont_mime = $cont_row['file_mime'];
		}

		list( $cont_url, $cont_width, $cont_height ) =
			$this->build_show_file_image( $cont_row ) ;

		list( $thumb_url, $thumb_width, $thumb_height ) =
			$this->build_show_file_image( $thumb_row ) ;

		list( $icon_url, $icon_width, $icon_height ) =
			$this->build_show_icon_image( $item_row );

		if ( $is_image || $this->_SHOW_IMAGE ) {
			if ( $thumb_url ) {
				$image  = $thumb_url;
				$width  = $thumb_width;
				$height = $thumb_height;

			} elseif ( $this->_SHOW_ICON && $icon_url ) {
				$imgage = $icon_url;
				$width  = $icon_width;
				$height = $icon_height;	
			}
		}

		if ( $this->_cfg_use_pathinfo ) {
			$link     = $this->_MODULE_URL.'/index.php/photo/'.    $item_id .'/' ;
			$cat_link = $this->_MODULE_URL.'/index.php/category/'. $cat_id .'/' ;
		} else {
			$link     = $this->_MODULE_URL.'/index.php?fct=photo&amp;p='.    $item_id ;
			$cat_link = $this->_MODULE_URL.'/index.php?fct=category&amp;p='. $cat_id ;
		}

		$arr = array(
			'link'     => $link ,
			'cat_link' => $cat_link ,
			'title'    => $item_row['item_title'] ,
			'cat_name' => $cat_title ,
			'uid'      => $item_row['item_uid'] ,
			'hits'     => $item_row['item_hits'] ,
			'time'     => $time_update ,

// atom
			'id'          => $item_id ,
			'modified'    => $time_update ,
			'issued'      => $time_create ,
			'created'     => $time_create ,
			'description' => $this->build_item_description( $item_row ) ,
		);

		$is_image = $this->is_image_kind( $item_kind );
		$is_video = $this->is_video_kind( $item_kind );

// photo image
		if ( $image ) {
			$arr['image']  = $image ;
			$arr['width']  = $width ;
			$arr['height'] = $height ;
		}

// media rss
		if ( $is_image ) {
			if ( $cont_url ) {
				$arr['content_url']      = $cont_url ;
				$arr['content_width']    = $cont_width ;
				$arr['content_height']   = $cont_height ;
				$arr['content_type']     = $cont_mime ;
			}
			if ( $thumb_url ) {
				$arr['thumbnail_url']    = $thumb_url ;
				$arr['thumbnail_width']  = $thumb_width ;
				$arr['thumbnail_height'] = $thumb_height ;
			}
		}

// geo rss
		if ( $this->_is_gmap( $item_row ) ) {
			$arr['geo_lat']  = floatval( $item_row['item_gmap_latitude'] ) ;
			$arr['geo_long'] = floatval( $item_row['item_gmap_longitude'] ) ;
		}

		$ret[ $i ] = $arr;
		$i++;
	}

	return $ret;
}

//---------------------------------------------------------
// private
//---------------------------------------------------------
function _is_gmap( $row )
{
	if (( floatval( $row['item_gmap_latitude'] )  != 0 )||
	    ( floatval( $row['item_gmap_longitude'] ) != 0 )||
	    ( intval(   $row['item_gmap_zoom'] )      != 0 )) {
		return true;
	}
	return false;
}

// --- class end ---
}

?>