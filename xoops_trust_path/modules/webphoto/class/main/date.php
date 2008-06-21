<?php
// $Id: date.php,v 1.1 2008/06/21 12:22:18 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_date
//=========================================================
class webphoto_main_date extends webphoto_show_list
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_date( $dirname , $trust_dirname )
{
	$this->webphoto_show_list( $dirname , $trust_dirname );
	$this->init_preload();
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main_date( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// list
//---------------------------------------------------------
// overwrite
function list_get_photo_list()
{
	$groupby = 'photo_datetime';
	$orderby = 'photo_datetime DESC, photo_id DESC';
	$list_rows = $this->_photo_handler->get_rows_by_groupby_orderby( $groupby , $orderby );
	if ( !is_array($list_rows) || !count($list_rows) ) {
		return false;
	}

	$year_month_arr_1 = array();
	$year_month_arr_2 = array();
	$id_arr  = array();
	$ret_arr = array();

// year month list
	foreach ( $list_rows as $row )
	{
		$year_month = $this->_utility_class->mysql_datetime_to_year_month( $row['photo_datetime'] );
		$year       = $this->_utility_class->mysql_datetime_to_year( $year_month );

// set year
		if ( !isset( $year_month_arr_1[ $year ] ) ) {
			$year_month_arr_1[ $year ]['type'] = 1;	// year type
		}

// set year month
		if ( !isset( $year_month_arr_1[ $year_month ] ) ) {
			$year_month_arr_1[ $year_month ]['type'] = 2;	// month type

// set total
			$where = $this->_photo_handler->build_where_public_by_like_datetime( $year_month );
			$total = $this->_photo_handler->get_count_by_where( $where );
			$year_month_arr_1[ $year_month ]['total'] = $total;

// get first row
			$photo_rows = $this->_photo_handler->get_rows_by_where_orderby(
				$where, $this->_PHOTO_LIST_DATE_ORDER, 1 );

// set row
			if ( isset( $photo_rows[0] ) ) {
				$row      = $photo_rows[0];
				$photo_id = $row['photo_id'];
				$year_month_arr_1[ $year_month ]['row'] = $row;

// set id array
				$id_arr[ $photo_id ] = true;
			}
		}
	}

// year month list for year type
	foreach ( $year_month_arr_1 as $year_month => $arr )
	{

// save orinal
		$year_month_arr_2[ $year_month ] = $arr;

// probably set total row already if month type
		if ( isset( $arr['type'] ) && ( $arr['type'] == 2 ) ) {
			continue;
		}

// set total
		$where = $this->_photo_handler->build_where_public_by_like_datetime( $year_month );
		$total = $this->_photo_handler->get_count_by_where( $where );
		$year_month_arr_2[ $year_month ]['total'] = $total;

// get all rows
		$photo_rows = $this->_photo_handler->get_rows_by_where_orderby(
			$where, $this->_PHOTO_LIST_DATE_ORDER );

		if ( !is_array($photo_rows) || !count($photo_rows) ) {
			continue;
		}

// search unused photo_id
		$flag = false;
		foreach ( $photo_rows as $row )
		{
			$photo_id = $row['photo_id'];

// found
			if ( !isset( $id_arr[ $photo_id ] ) ) {
				$flag = true;
				break;
			}
		}

// not found
		if ( !$flag ) {
			$row = $photo_rows[0];
		}

// set row
		$year_month_arr_2[ $year_month ]['row'] = $row;
	}

// photo list
	foreach ( $year_month_arr_2 as $year_month => $arr )
	{
		$total = 0;
		$row   = null;

// probably set total row already
		if ( isset( $arr['total'] ) && isset( $arr['row'] ) ) {
			$total = $arr['total'];
			$row   = $arr['row'];

// get new if not set
		} else {
			$where = $this->_photo_handler->build_where_public_by_like_datetime( $year_month );
			$total = $this->_photo_handler->get_count_by_where( $where );
			$photo_rows = $this->_photo_handler->get_rows_by_where_orderby(
				$where, $this->_PHOTO_LIST_DATE_ORDER, 1 );
			if ( isset( $photo_rows[0] ) ) {
				$row = $photo_rows[0];
			}
		}

		if ( $year_month == _C_WEBPHOTO_DATETIME_STR_NOT_SET ) {
			$title = $this->get_constant('DATE_NOT_SET');
			$param = _C_WEBPHOTO_DATETIME_STR_NOT_SET ;
		} else {
			$title = $year_month ;
			$param = $year_month ;
		}

		$ret_arr[] = $this->list_build_photo_array(
			$title, $param, $total, $row );
	}

	return $ret_arr;
}

//---------------------------------------------------------
// detail list
//---------------------------------------------------------
// overwrite
function list_build_detail( $datetime_in )
{
	$rows    = null ;
	$limit   = $this->_MAX_PHOTOS;
	$start   = $this->pagenavi_calc_start( $limit );
	$orderby = $this->get_orderby_by_post();

	$datetime = $this->_utility_class->mysql_datetime_to_day_or_month_or_year( $datetime_in );
	$this->set_param_out( $datetime );

	$init_param = $this->list_build_init_param( true );

	if ( $datetime == _C_WEBPHOTO_DATETIME_STR_NOT_SET ) {
		$title = $this->get_constant('DATE_NOT_SET') ;
	} else {
		$title = $this->get_constant('PHOTO_DATETIME') .' : '. $datetime ;
	}

	$where = $this->_photo_handler->build_where_public_by_like_datetime( $datetime );

	$total = $this->_photo_handler->get_count_by_where( $where );
	if ( $total > 0 ) {
		$rows = $this->_photo_handler->get_rows_by_where_orderby(
			$where, $orderby, $limit, $start );
	}

	$param = $this->list_build_detail_common( $title, $total, $rows );

	$navi_param = $this->list_build_navi( $total, $limit );

	$this->list_assign_xoops_header( $datetime );

	$ret = array_merge( $param, $init_param, $navi_param );
	return $this->add_box_list( $ret );
}

// --- class end ---
}

?>