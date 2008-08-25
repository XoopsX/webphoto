<?php
// $Id: place.php,v 1.4 2008/08/25 19:28:05 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// 2008-07-01 K.OHWADA
// used set_mode()
// decode_str() -> decode_uri_str()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_place
//=========================================================
class webphoto_main_place extends webphoto_show_list
{
	var $_search_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_place( $dirname , $trust_dirname )
{
	$this->webphoto_show_list( $dirname , $trust_dirname );
	$this->set_mode( 'place' );

	$this->_search_class =& webphoto_lib_search::getInstance();
	$this->_search_class->set_is_japanese( $this->_is_japanese );
	$this->_search_class->set_flag_candidate( false );

	$this->init_preload();
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main_place( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// list
//---------------------------------------------------------
// overwrite
function list_get_photo_list()
{
	$groupby = 'item_place';
	$orderby = 'item_place ASC, item_id DESC';
	$list_rows = $this->_item_handler->get_rows_by_groupby_orderby( $groupby , $orderby );
	if ( !is_array($list_rows) || !count($list_rows) ) {
		return false;
	}

	$arr = array();
	foreach ( $list_rows as $row )
	{
		$place = $row['item_place'];

		$photo_row = null;

		$place_arr = $this->str_to_array( $place, ' ' );
		$place_str = $this->array_to_str( $place_arr, ' ' );

		if ( $place ) {
			$title = $place_str;
			$param = $this->_utility_class->encode_slash( $place_str );
			$where = $this->_item_handler->build_where_public_by_place_array( $place_arr );

		} else {
			$title = $this->get_constant('PLACE_NOT_SET');
			$param = _C_WEBPHOTO_PLACE_STR_NOT_SET;
			$where   = $this->_item_handler->build_where_public_by_place(
				_C_WEBPHOTO_PLACE_VALUE_NOT_SET );
		}

		$total = $this->_item_handler->get_count_by_where( $where );

		$photo_rows = $this->_item_handler->get_rows_by_where_orderby(
			$where, $this->_PHOTO_LIST_ORDER, $this->_PHOTO_LIST_LIMIT );
		if ( isset($photo_rows[0]) ) {
			$photo_row = $photo_rows[0] ;
		}

		$arr[] = $this->list_build_photo_array(
			$title, $param, $total, $photo_row );
	}
	
	return $arr;
}

//---------------------------------------------------------
// detail list
//---------------------------------------------------------
// overwrite
function list_build_detail( $place_in )
{
	$rows    = null ;
	$limit   = $this->_MAX_PHOTOS;
	$start   = $this->pagenavi_calc_start( $limit );
	$orderby = $this->get_orderby_by_post();
	$where   = null;

	$place_in  = $this->decode_uri_str( $place_in );
	$place_arr = $this->_search_class->query_to_array( $place_in );
	$place     = $this->array_to_str( $place_arr, ' ' );
	$this->set_param_out( $place );

	$init_param = $this->list_build_init_param( true );

// if not set place
	if ( $place == _C_WEBPHOTO_PLACE_STR_NOT_SET ) {
		$title = $this->get_constant('PLACE_NOT_SET');
		$where = $this->_item_handler->build_where_public_by_place(
			_C_WEBPHOTO_PLACE_VALUE_NOT_SET );

// if set place
	} elseif ( is_array($place_arr) && count($place_arr) ) {
		$title = $this->get_constant('PHOTO_PLACE') .' : '. $place ;
		$where = $this->_item_handler->build_where_public_by_place_array( $place_arr );
	}

	if ( $where ) {
		$total = $this->_item_handler->get_count_by_where( $where );
		if ( $total > 0 ) {
			$rows = $this->_item_handler->get_rows_by_where_orderby(
				$where, $orderby, $limit, $start );
		}
	}

	$param      = $this->list_build_detail_common( $title, $total, $rows );
	$navi_param = $this->list_build_navi( $total, $limit );

	$this->list_assign_xoops_header();

	$ret = array_merge( $param, $init_param, $navi_param );
	return $this->add_box_list( $ret );
}

// --- class end ---
}

?>