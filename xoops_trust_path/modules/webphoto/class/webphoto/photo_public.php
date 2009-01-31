<?php
// $Id: photo_public.php,v 1.4 2009/01/31 19:12:50 ohwada Exp $

//=========================================================
// webphoto module
// 2008-12-12 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-01-25 K.OHWADA
// remove catlist->set_perm_cat_read()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_photo_public
//=========================================================
class webphoto_photo_public
{
	var $_config_class;
	var $_item_handler ;
	var $_cat_handler ;
	var $_item_cat_handler ;
	var $_tagcloud_class;

	var $_cfg_perm_cat_read ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_photo_public( $dirname )
{
	$this->_config_class  =& webphoto_config::getInstance( $dirname );
	$this->_item_handler  =& webphoto_item_handler::getInstance( $dirname );
	$this->_cat_handler   =& webphoto_cat_handler::getInstance( $dirname );

	$this->_cfg_perm_cat_read = $this->_config_class->get_by_name( 'perm_cat_read' );
	$cfg_perm_item_read       = $this->_config_class->get_by_name( 'perm_item_read' );
	$cfg_use_pathinfo         = $this->_config_class->get_by_name( 'use_pathinfo' );
	$cfg_uploads_path         = $this->_config_class->get_uploads_path();

	$this->_item_cat_handler =& webphoto_item_cat_handler::getInstance( $dirname );
	$this->_item_cat_handler->set_perm_item_read( $cfg_perm_item_read );

	$this->_catlist_class  =& webphoto_inc_catlist::getSingleton( $dirname );
	$this->_tagcloud_class =& webphoto_inc_tagcloud::getSingleton( $dirname );
}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_photo_public( $dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// catlist class
//---------------------------------------------------------
function get_cat_row( $cat_id )
{
	return $this->_catlist_class->get_cat_row_by_catid_perm( $cat_id ) ;
}

function get_cat_all_tree_array()
{
	return $this->_catlist_class->get_cat_all_tree_array() ;
}

function get_rows_total_by_catid( $cat_id, $orderby, $limit=0, $offset=0, $flag_child=false )
{
	$rows        = null ; 
	$catid_array = $this->_catlist_class->get_cat_parent_all_child_id_by_id( $cat_id );
	$this_sum    = $this->get_count_by_catid( $cat_id );
	$total       = $this->get_count_by_catid_array( $catid_array );

	if ( $total > 0 ) {
		if ( $this_sum > 0 ) {
			$rows = $this->get_rows_by_catid_orderby( 
				$cat_id, $orderby, $limit, $offset );

		} elseif ( $flag_child ) {
			$rows = $this->get_rows_by_catid_array_orderby( 
				$catid_array, $orderby, $limit, $offset );
		}
	}

	return array( $rows, $total, $this_sum );
}

function _check_cat_perm_by_catid( $cat_id )
{
	return $this->_catlist_class->check_cat_perm_by_catid( $cat_id ) ;
}

function build_catlist( $parent_id, $flag_sub, $cols )
{
	$catlist = $this->_catlist_class->build_catlist( $parent_id, $flag_sub ) ;
	list( $cols, $width ) =
		$this->_catlist_class->calc_width( $cols ) ;

	return array( $catlist, $cols, $width );
}

function build_cat_show( $cat_row )
{
	return $this->_catlist_class->build_cat_show( $cat_row );
}

//---------------------------------------------------------
// count
//---------------------------------------------------------
function get_count_by_catid( $param )
{
	if ( $this->_cfg_perm_cat_read != _C_WEBPHOTO_OPT_PERM_READ_ALL ) {
		if ( ! $this->_check_cat_perm_by_catid( $param ) ) {
			return 0 ;
		}
	}

	return $this->_item_cat_handler->get_count_item_by_name_param( 'catid', $param );
}

function get_count()
{
	return $this->_get_count_by_name_param( 'public', null );
}

function get_count_imode()
{
	return $this->_get_count_by_name_param( 'imode', null );
}

function get_count_by_catid_array( $param )
{
	return $this->_get_count_by_name_param( 'catid_array', $param );
}

function get_count_by_like_datetime( $param )
{
	return $this->_get_count_by_name_param( 'like_datetime', $param );
}

function get_count_by_place( $param )
{
	return $this->_get_count_by_name_param( 'place', $param );
}

function get_count_by_place_array( $param )
{
	return $this->_get_count_by_name_param( 'place_array', $param );
}

function get_count_by_search( $param )
{
	return $this->_get_count_by_name_param( 'search', $param );
}

function get_count_by_uid( $param )
{
	return $this->_get_count_by_name_param( 'uid', $param );
}

function _get_count_by_name_param( $name, $param )
{
	if ( $this->_cfg_perm_cat_read == _C_WEBPHOTO_OPT_PERM_READ_ALL ) {
		return $this->_item_cat_handler->get_count_item_by_name_param( 
			$name, $param );

	} else {
		return $this->_item_cat_handler->get_count_item_cat_by_name_param( 
			$name, $param ) ;
	}
}

//---------------------------------------------------------
// rows
//---------------------------------------------------------
function get_rows_by_catid_orderby( $param, $orderby, $limit=0, $offset=0 )
{
	if ( $this->_cfg_perm_cat_read != _C_WEBPHOTO_OPT_PERM_READ_ALL ) {
		if ( ! $this->_check_cat_perm_by_catid( $param ) ) {
			return false ;
		}
	}

	return $this->_item_cat_handler->get_rows_item_by_name_param_orderby( 
		'catid', $param, $orderby, $limit, $offset );
}

function get_rows_by_orderby( $orderby, $limit=0, $offset=0 )
{
	return $this->_get_rows_by_name_param_orderby( 
		'public', null, $orderby, $limit, $offset ) ;
}

function get_rows_imode_by_orderby( $orderby, $limit=0, $offset=0 )
{
	return $this->_get_rows_by_name_param_orderby( 
		'imode', null, $orderby, $limit, $offset ) ;
}

function get_rows_photo_by_orderby( $orderby, $limit=0, $offset=0 )
{
	return $this->_get_rows_by_name_param_orderby( 
		'photo', null, $orderby, $limit, $offset ) ;
}

function get_rows_photo_by_catid_orderby( $param, $orderby, $limit=0, $offset=0 )
{
	return $this->_get_rows_by_name_param_orderby( 
		'photo_catid', $param, $orderby, $limit, $offset ) ;
}

function get_rows_by_catid_array_orderby( $param, $orderby, $limit=0, $offset=0 )
{
	return $this->_get_rows_by_name_param_orderby( 
		'catid_array', $param, $orderby, $limit, $offset ) ;
}

function get_rows_by_like_datetime_orderby( $param, $orderby, $limit=0, $offset=0 )
{
	return $this->_get_rows_by_name_param_orderby( 
		'like_datetime', $param, $orderby, $limit, $offset ) ;
}

function get_rows_by_place_orderby( $param, $orderby, $limit=0, $offset=0 )
{
	return $this->_get_rows_by_name_param_orderby( 
		'place', $param, $orderby, $limit, $offset ) ;
}

function get_rows_by_place_array_orderby( $param, $orderby, $limit=0, $offset=0 )
{
	return $this->_get_rows_by_name_param_orderby( 
		'place_array', $param, $orderby, $limit, $offset ) ;
}

function get_rows_by_uid_orderby( $param, $orderby, $limit=0, $offset=0 )
{
	return $this->_get_rows_by_name_param_orderby( 
		'uid', $param, $orderby, $limit, $offset ) ;
}

function get_rows_by_search_orderby( $param, $orderby, $limit=0, $offset=0 )
{
	return $this->_get_rows_by_name_param_orderby( 
		'search', $param, $orderby, $limit, $offset ) ;
}

function _get_rows_by_name_param_orderby( $name, $param, $orderby, $limit=0, $offset=0 )
{
	if ( $this->_cfg_perm_cat_read  == _C_WEBPHOTO_OPT_PERM_READ_ALL ) {
		return $this->_item_cat_handler->get_rows_item_by_name_param_orderby( 
			$name, $param, $orderby, $limit, $offset );

	} else {
		return $this->_item_cat_handler->get_rows_item_cat_by_name_param_orderby( 
			$name, $param, 
			$this->_item_cat_handler->convert_item_field( $orderby ), 
			$limit, $offset );
	}
}

//---------------------------------------------------------
// get id array
//---------------------------------------------------------
function get_id_array_by_catid_orderby( $param, $orderby, $limit=0, $offset=0 )
{
	return $this->_item_cat_handler->get_id_array_item_by_name_param_orderby(
		'catid', $param, $orderby, $limit, $offset ) ;
}

//---------------------------------------------------------
// tagcloud class
//---------------------------------------------------------
function build_tagcloud( $limit=0 )
{
	return $this->_tagcloud_class->build_tagcloud( $limit );
}

function build_tagcloud_by_rows( $rows )
{
	return $this->_tagcloud_class->build_tagcloud_by_rows( $rows );
}

function get_tag_rows( $limit=0, $offset=0 )
{
	return $this->_tagcloud_class->get_tag_rows( $limit, $offset );
}

function get_count_by_tag( $param )
{
	return $this->_tagcloud_class->get_item_count_by_tag( $param );
}

function get_rows_by_tag_orderby( $param, $orderby, $limit=0, $offset=0 )
{
	$rows   = null ;
	$id_arr = $this->_tagcloud_class->get_item_id_array_by_tag( 
		$param, $orderby, $limit, $offset );

	if ( is_array($id_arr) && count($id_arr) ) {
		$rows = $this->_item_handler->get_rows_from_id_array( $id_arr );
	}
	return $rows;
}

function get_first_row_by_tag_orderby( $param, $orderby, $limit=0, $offset=0 )
{
	$row    = null ;
	$id_arr = $this->_tagcloud_class->get_item_id_array_by_tag( 
		$param, $orderby, $limit, $offset );

	if ( isset( $id_arr[0] ) ) {
		$row = $this->_item_handler->get_row_by_id( $id_arr[0] );
	}
	return $row;
}

// --- class end ---
}

?>