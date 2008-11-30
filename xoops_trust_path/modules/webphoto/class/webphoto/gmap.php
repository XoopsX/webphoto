<?php
// $Id: gmap.php,v 1.4 2008/11/30 10:36:34 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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
	var $_gmap_info_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_gmap( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_gicon_handler   =& webphoto_gicon_handler::getInstance($dirname);

	$this->_gmap_info_class =& webphoto_gmap_info::getInstance( $dirname , $trust_dirname );

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
	$cfg_gmap_apikey = $this->get_config_by_name('gmap_apikey');
	if ( empty($cfg_gmap_apikey) ) { return null; }

	$cat_id = intval($cat_id);
	if ( $cat_id > 0 ) {
		$catid_array = $this->_cat_handler->get_all_child_id( $cat_id );
		array_push( $catid_array , $cat_id ) ;

		$rows = $this->_item_handler->get_rows_public_gmap_latest_by_catid_array(
			$catid_array, $limit, $offset );

	} else {
		$rows = $this->_item_handler->get_rows_public_gmap_latest(
			$limit, $offset );
	}

	if ( ! is_array($rows) ) { return null; }

	return $this->_build_show_from_rows( $rows );
}

function build_icon_list( $limit=0, $offset=0 )
{
	$cfg_gmap_apikey = $this->get_config_by_name('gmap_apikey');
	if ( empty($cfg_gmap_apikey) ) { return null; }

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
	return $this->_cat_handler->get_cached_value_by_id_name( $item_row['item_cat_id'], 'cat_gicon_id' );
}

//---------------------------------------------------------
// photo
//---------------------------------------------------------
function build_show( $item_row )
{
	$cfg_gmap_apikey = $this->get_config_by_name('gmap_apikey');
	if ( empty($cfg_gmap_apikey) ) { return null; }

	if ( ! $this->exist_gmap( $item_row ) ) { return null; } 

	return $this->_build_show_from_single_row( $item_row );
}

//---------------------------------------------------------
// gmap location
//---------------------------------------------------------
function exist_gmap( $item_row )
{
	if ( ( $item_row['item_gmap_latitude']  != 0 ) || 
	     ( $item_row['item_gmap_longitude'] != 0 ) || 
	     ( $item_row['item_gmap_zoom']      != 0 ) ) {
		return true;
	}
	return false;
}

function build_list_location( $item_row, $limit=0, $offset=0 )
{
	$id  = $item_row['item_id'];
	$lat = $item_row['item_gmap_latitude'];
	$lon = $item_row['item_gmap_longitude'];

	$gmap_rows = $this->_item_handler->get_rows_public_gmap_area( $id, $lat, $lon );
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