<?php
// $Id: date.php,v 1.5 2011/12/26 06:51:31 ohwada Exp $

//=========================================================
// webphoto module
// 2010-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// mysql_datetime_to_year()
// 2010-11-03 K.OHWADA
// build_rows_for_rss()
// 2010-05-10 K.OHWADA
// build_total_for_detail()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_date
//=========================================================
class webphoto_date extends webphoto_base_this
{
	var $_public_class;

	var $_PHOTO_LIST_DATE_ORDER = 'item_datetime DESC, item_id DESC';
	var $_PHOTO_LIST_DATE_GROUP = 'item_datetime';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_date( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_public_class
		=& webphoto_photo_public::getInstance( $dirname, $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_date( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// list
//---------------------------------------------------------
function build_rows_for_list()
{
	$list_rows = $this->_item_handler->get_rows_by_groupby_orderby( 
		$this->_PHOTO_LIST_DATE_GROUP , $this->_PHOTO_LIST_DATE_ORDER );
	if ( !is_array($list_rows) || !count($list_rows) ) {
		return false;
	}

	$year_month_arr_1 = array();
	$year_month_arr_2 = array();
	$id_arr   = array();
	$ret_arr  = array();

// year month list
	foreach ( $list_rows as $row )
	{
		$year_month = $this->mysql_datetime_to_year_month( $row['item_datetime'] );
		$year       = $this->mysql_datetime_to_year( $year_month );

// set year
		if ( !isset( $year_month_arr_1[ $year ] ) ) {
			$year_month_arr_1[ $year ]['type'] = 1;	// year type
		}

// set year month
		if ( !isset( $year_month_arr_1[ $year_month ] ) ) {
			$year_month_arr_1[ $year_month ]['type'] = 2;	// month type

// set total
			$total = $this->_public_class->get_count_by_like_datetime( $year_month );
			$year_month_arr_1[ $year_month ]['total'] = $total;

// get first row
			$photo_rows = $this->_public_class->get_rows_by_like_datetime_orderby( 
				$year_month, $this->_PHOTO_LIST_DATE_ORDER, 1 );

// set row
			if ( isset( $photo_rows[0] ) ) {
				$row      = $photo_rows[0];
				$photo_id = $row['item_id'];
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
		$total = $this->_public_class->get_count_by_like_datetime( $year_month );
		$year_month_arr_2[ $year_month ]['total'] = $total;

// get all rows
		$photo_rows = $this->_public_class->get_rows_by_like_datetime_orderby( 
			$year_month, $this->_PHOTO_LIST_DATE_ORDER );

		if ( !is_array($photo_rows) || !count($photo_rows) ) {
			continue;
		}

// search unused photo_id
		$flag = false;
		foreach ( $photo_rows as $row )
		{
			$photo_id = $row['item_id'];

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
			$total = $this->_public_class->get_count_by_like_datetime( $year_month );
			$photo_rows = $this->_public_class->get_rows_by_like_datetime_orderby( 
				$year_month, $this->_PHOTO_LIST_DATE_ORDER, 1 );

			if ( isset( $photo_rows[0] ) ) {
				$row =  $photo_rows[0];
			}
		}

		if ( $year_month == _C_WEBPHOTO_DATETIME_STR_NOT_SET ) {
			$title = $this->get_constant('DATE_NOT_SET');
			$param = _C_WEBPHOTO_DATETIME_STR_NOT_SET ;
		} else {
			$title = $year_month ;
			$param = $year_month ;
		}

		$ret_arr[] = array( $title, $param, $total, $row );
	}

	return $ret_arr;
}

//---------------------------------------------------------
// detail
//---------------------------------------------------------
function build_total_for_detail( $datetime_in )
{
	$datetime = $this->build_datetime_for_detail( $datetime_in );
	$title    = $this->build_title( $datetime );
	$total    = $this->_public_class->get_count_by_like_datetime( $datetime );

	return array( $title, $total, $datetime );
}

function build_datetime_for_detail( $datetime_in )
{
	$datetime = $this->decode_uri_str( $datetime_in );
	$datetime = $this->mysql_datetime_to_day_or_month_or_year( $datetime );
	return $datetime;
}

function build_rows_for_detail( $datetime, $orderby, $limit=0, $start=0 )
{
	return $this->_public_class->get_rows_by_like_datetime_orderby( 
		$datetime, $orderby, $limit, $start );
}

function build_title( $datetime )
{
	if ( $datetime == _C_WEBPHOTO_DATETIME_STR_NOT_SET ) {
		$str = $this->get_constant('DATE_NOT_SET') ;
	} else {
		$str = $this->get_constant('PHOTO_DATETIME') .' : '. $datetime ;
	}
	return $str;
}

//---------------------------------------------------------
// rss
//---------------------------------------------------------
function build_rows_for_rss( $datetime_in, $orderby, $limit=0, $start=0 )
{
	$datetime = $this->build_datetime_for_detail( $datetime_in );

	return $this->build_rows_for_detail( 
		$datetime, $orderby, $limit, $start );
}

// --- class end ---
}

?>