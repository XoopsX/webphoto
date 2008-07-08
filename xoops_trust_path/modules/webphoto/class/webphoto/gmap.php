<?php
// $Id: gmap.php,v 1.2 2008/07/08 20:31:22 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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
	var $_preload_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_gmap( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_gicon_handler   =& webphoto_gicon_handler::getInstance($dirname);

	$this->_gmap_info_class =& webphoto_gmap_info::getInstance( $dirname , $trust_dirname );

	$this->_preload_class   =& webphoto_d3_preload::getInstance();
	$this->_preload_class->init( $dirname , $trust_dirname );
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

		$rows = $this->_photo_handler->get_rows_public_gmap_latest_by_catid_array(
			$catid_array, $limit, $offset );

	} else {
		$rows = $this->_photo_handler->get_rows_public_gmap_latest(
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

function _build_show_from_rows( $rows )
{
	$arr = array();
	foreach ( $rows as $row ) {
		$arr[] = $this->_build_show_from_single_row( $row );
	}
	return $arr;
}

function _build_show_from_single_row( $row )
{
	$show                   = $row;
	$show['gmap_latitude']  = floatval( $row['photo_gmap_latitude'] );
	$show['gmap_longitude'] = floatval( $row['photo_gmap_longitude'] ) ;
	$show['gmap_icon_id']   = intval( $this->_build_icon_id( $row ) );
	$show['gmap_info']      = $this->_build_gmap_info( $row );
	return $show;
}

function _build_gmap_info( $row )
{
	return $this->sanitize_control_code( 
		$this->_build_gmap_info_preload( $row ) );
}

function _build_gmap_info_preload( $row )
{
	if ( $this->_preload_class->exists_class( 'gmap_info' ) ) {
		return $this->_preload_class->exec_class_method(
			'gmap_info', 'build_info_extend', $row );
	}
	return $this->_gmap_info_class->build_info( $row );
}

function _build_icon_id( $row )
{
	if ( $row['photo_gicon_id'] > 0 ) {
		return $row['photo_gicon_id'];
	}
	return $this->_build_cat_gicon_id( $row );
}

function _build_cat_gicon_id( $row )
{
	return $this->_cat_handler->get_cached_value_by_id_name( $row['photo_cat_id'], 'cat_gicon_id' );
}

//---------------------------------------------------------
// photo
//---------------------------------------------------------
function build_show( $row )
{
	$cfg_gmap_apikey = $this->get_config_by_name('gmap_apikey');
	if ( empty($cfg_gmap_apikey) ) { return null; }

	if ( ! $this->exist_gmap( $row ) ) { return null; } 

	return $this->_build_show_from_single_row( $row );
}

//---------------------------------------------------------
// gmap location
//---------------------------------------------------------
function exist_gmap( $row )
{
	if ( ( $row['photo_gmap_latitude']  != 0 ) || 
	     ( $row['photo_gmap_longitude'] != 0 ) || 
	     ( $row['photo_gmap_zoom']      != 0 ) ) {
		return true;
	}
	return false;
}

function build_list_location( $photo_row, $limit=0, $offset=0 )
{
	$id  = $photo_row['photo_id'];
	$lat = $photo_row['photo_gmap_latitude'];
	$lon = $photo_row['photo_gmap_longitude'];

	$rows = $this->_photo_handler->get_rows_public_gmap_area( $id, $lat, $lon );
	if ( ! is_array($rows) ) { return null; }

	$arr = array();
	foreach ( $rows as $row )
	{
		$row['info']     = $this->_build_gmap_info( $row );
		$row['gicon_id'] = $this->_build_icon_id( $row );
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