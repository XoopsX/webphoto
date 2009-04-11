<?php
// $Id: gmap.php,v 1.11 2009/04/11 14:23:35 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-04-10 K.OHWADA
// function array_merge_unique()
// 2009-01-25 K.OHWADA
// webphoto_gmap_info -> webphoto_inc_gmap_info
// get_gmap_center()
// 2008-12-12 K.OHWADA
// webphoto_item_cat_handler
// 2008-11-29 K.OHWADA
// build_show_file_image()
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// used preload_init()
// 2008-07-01 K.OHWADA
// not use webphoto_convert_to_utf8()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_gmap
//=========================================================
class webphoto_gmap extends webphoto_base_this
{
	var $_gicon_handler;
	var $_item_cat_handler;
	var $_gmap_info_class;
	var $_catlist_class;

	var $_cfg_perm_cat_read ;
	var $_cfg_gmap_apikey ;
	var $_cfg_gmap_latitude  ;
	var $_cfg_gmap_longitude ;
	var $_cfg_gmap_zoom      ;

	var $_GMAP_ORDERBY_ASC    = 'item_id ASC';
	var $_GMAP_ORDERBY_LATEST = 'item_time_update DESC, item_id DESC';
	var $_GMAP_KEY_NAME = 'item_id' ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_gmap( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_gicon_handler   =& webphoto_gicon_handler::getInstance($dirname);
	$this->_gmap_info_class =& webphoto_inc_gmap_info::getSingleton( $dirname );

	$cfg_perm_item_read        = $this->get_config_by_name( 'perm_item_read' );
	$this->_cfg_perm_cat_read  = $this->get_config_by_name( 'perm_cat_read' );
	$this->_cfg_gmap_apikey    = $this->get_config_by_name( 'gmap_apikey' );
	$this->_cfg_gmap_latitude  = $this->get_config_by_name( 'gmap_latitude' );
	$this->_cfg_gmap_longitude = $this->get_config_by_name( 'gmap_longitude' );
	$this->_cfg_gmap_zoom      = $this->get_config_by_name( 'gmap_zoom' );

	$this->_item_cat_handler =& webphoto_item_cat_handler::getInstance( $dirname );
	$this->_item_cat_handler->set_perm_item_read( $cfg_perm_item_read );

	$this->_catlist_class =& webphoto_inc_catlist::getSingleton( $dirname );

	$this->preload_init();
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_gmap( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// index
//---------------------------------------------------------
function build_icon_list( $limit=0, $offset=0 )
{
	if ( empty( $this->_cfg_gmap_apikey ) ) { return null; }

	$rows = $this->_gicon_handler->get_rows_all_asc( $limit, $offset );
	if ( ! is_array($rows) ) { return null; }

	return $rows;
}

function build_show_from_rows( $item_rows )
{
	if ( empty( $this->_cfg_gmap_apikey ) ) {
		return null; 
	}

	if ( !is_array($item_rows) || !count($item_rows) ) {
		return null;
	}

	$arr = array();
	foreach ( $item_rows as $item_row ) {
		$arr[] = $this->_build_show_from_single_row( $item_row );
	}
	return $arr;
}

function _build_show_from_single_row( $item_row )
{
	$show                   = $item_row;
	$show['gmap_latitude']  = floatval( $item_row['item_gmap_latitude'] );
	$show['gmap_longitude'] = floatval( $item_row['item_gmap_longitude'] ) ;
	$show['gmap_icon_id']   = intval( $this->_build_icon_id( $item_row ) );
	$show['gmap_info']      = $this->_build_gmap_info( $item_row );
	return $show;
}

function _build_gmap_info( $item_row )
{
	$thumb_row = $this->get_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_THUMB );

	list( $thumb_url, $thumb_width, $thumb_height ) =
		$this->build_show_file_image( $thumb_row ) ;

	$param                 = $item_row ;
	$param['thumb_url']    = $thumb_url ;
	$param['thumb_width']  = $thumb_width ;
	$param['thumb_height'] = $thumb_height ;

	return $this->sanitize_control_code( 
		$this->_build_gmap_info_preload( $param ) );
}

function _build_gmap_info_preload( $param )
{
	if ( $this->_preload_class->exists_class( 'gmap_info' ) ) {
		return $this->_preload_class->exec_class_method(
			'gmap_info', 'build_info_extend', $param );
	}
	return $this->_gmap_info_class->build_info( $param );
}

function _build_icon_id( $item_row )
{
	if ( $item_row['item_gicon_id'] > 0 ) {
		return $item_row['item_gicon_id'];
	}
	return $this->_build_cat_gicon_id( $item_row );
}

function _build_cat_gicon_id( $item_row )
{
	return $this->_cat_handler->get_cached_value_by_id_name( 
		$item_row['item_cat_id'], 'cat_gicon_id' );
}

//---------------------------------------------------------
// photo
//---------------------------------------------------------
function build_show( $item_row )
{
	if ( empty( $this->_cfg_gmap_apikey ) ) { return null; }
	if ( ! $this->exist_gmap_item( $item_row ) ) { return null; } 

	return $this->_build_show_from_single_row( $item_row );
}

//---------------------------------------------------------
// gmap location
//---------------------------------------------------------
function get_gmap_center( $item_id=0, $cat_id=0 )
{
	$code       = 0 ;
	$latitude   = 0 ;
	$longitude  = 0 ;
	$zoom       = 0 ;

// config
	if ( $this->exist_gmap_cfg() ) {
		$code       = 1 ;
		$latitude   = $this->_cfg_gmap_latitude;
		$longitude  = $this->_cfg_gmap_longitude;
		$zoom       = $this->_cfg_gmap_zoom;
	}

// item
	if ( $item_id > 0 ) {
		$row = $this->_item_handler->get_cached_row_by_id( $item_id );
		if ( is_array($row) && $this->exist_gmap_item( $row ) ) { 
			$code       = 2 ;
			$latitude   = $row['item_gmap_latitude'];
			$longitude  = $row['item_gmap_longitude'];
			$zoom       = $row['item_gmap_zoom'];
		}

// cat
	} elseif ( $cat_id > 0 ) {
		$row = $this->_cat_handler->get_cached_row_by_id( $cat_id );
		if ( is_array($row) && $this->exist_gmap_cat( $row ) ) { 
			$code       = 3 ;
			$latitude   = $row['cat_gmap_latitude'];
			$longitude  = $row['cat_gmap_longitude'];
			$zoom       = $row['cat_gmap_zoom'];
		}
	}

	return array( $code, $latitude, $longitude, $zoom );
}

function exist_gmap_cfg()
{
	return $this->exist_gmap( 
		$this->_cfg_gmap_latitude , 
		$this->_cfg_gmap_longitude , 
		$this->_cfg_gmap_zoom );
}

function exist_gmap_item( $item_row )
{
	return $this->exist_gmap( 
		$item_row['item_gmap_latitude'] , 
		$item_row['item_gmap_longitude'] , 
		$item_row['item_gmap_zoom'] );
	
}

function exist_gmap_cat( $cat_row )
{
	return $this->exist_gmap( 
		$cat_row['cat_gmap_latitude'] , 
		$cat_row['cat_gmap_longitude'] , 
		$cat_row['cat_gmap_zoom'] );
}

function exist_gmap( $latitude, $longitude, $zoom )
{
	if ( $latitude == 0 ) {
		return false;
	}
	if ( $longitude == 0 ) {
		return false;
	}
	if ( $zoom == 0 ) {
		return false;
	}
	return true;
}

function build_list_location( $item_rows )
{
	$arr = array();
	foreach ( $item_rows as $item_row )
	{
		$row             = $item_row;
		$row['info']     = $this->_build_gmap_info( $item_row );
		$row['gicon_id'] = $this->_build_icon_id(   $item_row );
		$arr[] = $row;
	}
	return $arr;
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
function array_merge_unique( $arr1, $arr2 )
{
	$arr_ret = null;
	if ( is_array($arr1) && count($arr1)  ) {
		$arr_ret = $arr1 ;

		if ( is_array($arr2) && count($arr2) ) {
			foreach ( $arr2 as $a ) 
			{
				$key_val = $a[ $this->_GMAP_KEY_NAME ] ;
				if ( ! isset( $arr_ret[ $key_val ] ) && $this->exist_gmap_item( $a ) ) {
					$arr_ret[ $this->_GMAP_KEY_NAME ] = $a ;
				}
			}
		}

	} elseif ( is_array($arr2) && count($arr2) ) {
		$arr_ret = $arr2;
	}

	return $arr_ret;
}

//---------------------------------------------------------
// sanitize
//---------------------------------------------------------
function sanitize_control_code( $str )
{
	$str = $this->str_replace_control_code( $str );
	$str = $this->str_replace_tab_code( $str );
	$str = $this->str_replace_return_code( $str );
	return $str;
}

// --- class end ---
}

?>