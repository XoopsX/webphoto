<?php
// $Id: gmap.php,v 1.10 2009/02/01 11:02:38 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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
function build_photo_list_by_catid( $cat_id, $limit=0, $offset=0 )
{
	if ( empty( $this->_cfg_gmap_apikey ) ) { return null; }

	$cat_id = intval($cat_id);
	if ( $cat_id > 0 ) {
		$rows = $this->get_rows_by_gmap_catid(
			$cat_id, $limit, $offset );

	} else {
		$rows = $this->get_rows_by_gmap_latest(
			$limit, $offset );
	}

	if ( ! is_array($rows) ) { return null; }

	return $this->_build_show_from_rows( $rows );
}

function build_icon_list( $limit=0, $offset=0 )
{
	if ( empty( $this->_cfg_gmap_apikey ) ) { return null; }

	$rows = $this->_gicon_handler->get_rows_all_asc( $limit, $offset );
	if ( ! is_array($rows) ) { return null; }

	return $rows;
}

function _build_show_from_rows( $item_rows )
{
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

function build_list_location( $item_row, $limit=0, $offset=0 )
{
	$id  = $item_row['item_id'];
	$lat = $item_row['item_gmap_latitude'];
	$lon = $item_row['item_gmap_longitude'];

	$gmap_rows = $this->get_rows_by_gmap_area( $id, $lat, $lon );
	if ( ! is_array($gmap_rows) ) {
		return null; 
	}

	$arr = array();
	foreach ( $gmap_rows as $gmap_row )
	{
		$row             = $gmap_row;
		$row['info']     = $this->_build_gmap_info( $gmap_row );
		$row['gicon_id'] = $this->_build_icon_id(   $gmap_row );
		$arr[] = $row;
	}
	return $arr;
}

//---------------------------------------------------------
// item_cat_handler
//---------------------------------------------------------
function get_rows_by_gmap_catid( $cat_id, $limit=0, $offset=0 )
{
	$catid_array = $this->_catlist_class->get_cat_parent_all_child_id_by_id( $cat_id ) ;

	return $this->get_rows_by_name_param_orderby( 
		'gmap_catid_array', $catid_array, $this->_GMAP_ORDERBY_LATEST, $limit, $offset ) ;
}

function get_rows_by_gmap_latest( $limit=0, $offset=0 )
{
	return $this->get_rows_by_name_param_orderby( 
		'gmap_latest', null, $this->_GMAP_ORDERBY_LATEST, $limit, $offset ) ;
}

function get_rows_by_gmap_area( $id, $lat, $lon, $limit=0, $offset=0 )
{
	return $this->get_rows_by_name_param_orderby( 
		'gmap_area', array( $id, $lat, $lon ), $this->_GMAP_ORDERBY_ASC, $limit, $offset ) ;
}

function get_rows_by_name_param_orderby( $name, $param, $orderby, $limit=0, $offset=0 )
{
	if ( $this->_cfg_perm_cat_read ) {
		return $this->_item_cat_handler->get_rows_item_cat_by_name_param_orderby( 
			$name, $param, 
			$this->_item_cat_handler->convert_item_field( $orderby ), 
			$limit, $offset );

	} else {
		return $this->_item_cat_handler->get_rows_item_by_name_param_orderby( 
			$name, $param, $orderby, $limit, $offset );
	}
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