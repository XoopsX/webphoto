<?php
// $Id: mysql_utility.php,v 1.1 2011/12/26 06:52:14 ohwada Exp $

//=========================================================
// webphoto module
// 2011-12-25 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_lib_mysql_utility
//=========================================================
class webphoto_lib_mysql_utility
{
	var $_utility_class;

	var $_MYSQL_FMT_DATE     = 'Y-m-d';
	var $_MYSQL_FMT_DATETIME = 'Y-m-d H:i:s';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_mysql_utility()
{
	$this->_utility_class =& webphoto_lib_utility::getInstance();
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_mysql_utility();
	}
	return $instance;
}

//---------------------------------------------------------
// mysql date
//---------------------------------------------------------
function get_mysql_date_today()
{
	return date( $this->_MYSQL_FMT_DATE );
}

function time_to_mysql_datetime( $time )
{
	return date( $this->_MYSQL_FMT_DATETIME, $time );
}

function mysql_datetime_to_unixtime( $datetime )
{
	$p = $this->mysql_datetime_to_date_param( $datetime );
	if ( !is_array($p) ) {
		return false ;
	}

	$time = $this->date_param_to_unixtime( $p );

	return $time;
}

function mysql_datetime_to_date_param( $datetime )
{
	if ( empty($datetime) ) {
		return false ;
	}

// yyyy-mm-dd hh:mm:ss
	preg_match( "/(\d+)\-(\d+)\-(\d+)\s+(\d+):(\d+):(\d+)/", $datetime, $match );

	$year = 0;
	$mon  = 0;
	$day  = 0;
	$hour = 0;
	$min  = 0;
	$sec  = 0;

	if ( isset( $match[1] ) ) {
		$year = intval( $match[1] );
	}
	if ( isset( $match[2] ) ) {
		$mon = intval( $match[2] );
	}
	if ( isset( $match[3] ) ) {
		$day = intval( $match[3] );
	}
	if ( isset( $match[4] ) ) {
		$hour = intval( $match[4] );
	}
	if ( isset( $match[5] ) ) {
		$min = intval( $match[5] );
	}
	if ( isset( $match[6] ) ) {
		$sec = intval( $match[6] );
	}

	if ( $year == 0 ) {
		return false ;
	}
	if ( $mon == 0 ) {
		 $mon = 1;
	}
	if ( $day == 0 ) {
		 $day = 1;
	}

	$arr = array(
		'year'   => $year,
		'month'  => $mon,
		'day'    => $day,
		'hour'   => $hour,
		'minute' => $min,
		'second' => $sec,
	);

	return $arr;
}

function date_param_to_unixtime( $p )
{
	$time = mktime( $p['hour'], $p['minute'], $p['second'], $p['month'], $p['day'], $p['year'] );

	return $time;
}

function mysql_datetime_to_day_or_month_or_year( $datetime )
{
	$val = $this->mysql_datetime_to_year_month_day( $datetime );
	if ( empty($val) ) {
		$val = $this->mysql_datetime_to_year_month( $datetime );
	}
	if ( empty($val) ) {
		$val = $this->mysql_datetime_to_year( $datetime );
	}
	return $val;
}

function mysql_datetime_to_year_month_day( $datetime )
{
// like yyyy-mm-dd
	if ( preg_match( "/^(\d{4}\-\d{2}\-\d{2})/", $datetime, $match ) ) {

// yyyy-00-00 -> yyyy
		$str = str_replace('-00-00', '', $match[1] );

// yyyy-mm-00 -> yyyy-mm
		$str = str_replace('-00', '', $str );
		return $str;
	}
	return null;
}

function mysql_datetime_to_year_month( $datetime )
{
// like yyyy-mm
	if ( preg_match( "/^(\d{4}\-\d{2})/", $datetime, $match ) ) {

// yyyy-00 -> yyyy
		return str_replace('-00', '', $match[1] );
	}
	return null;
}

function mysql_datetime_to_year( $datetime )
{
// like yyyy
	if ( preg_match( "/^(\d{4})/", $datetime, $match ) ) {
		return $match[1];
	}
	return null;
}

function mysql_datetime_to_str( $date )
{
	$date = str_replace( '0000-00-00 00:00:00', '', $date );
	$date = str_replace(     '-00-00 00:00:00', '', $date );
	$date = str_replace(        '-00 00:00:00', '', $date );
	$date = str_replace(           ' 00:00:00', '', $date );
	$date = str_replace( '0000-00-00',          '', $date );
	$date = str_replace(     '-00-00',          '', $date );
	$date = str_replace(        '-00',          '', $date );

// BUG: 12:00:52 -> 12:52
// 01:02:00 -> 01:02 
// 01:00:00 -> 01:00
	$date = preg_replace( '/(.*\d+:\d+):00/', '$1', $date );

	if ( $date == ' ' ) {
		$date = '';
	}
	return $date;
}

function str_to_mysql_datetime( $str )
{
	$date = '';
	$time = '';

	$arr = $this->_utility_class->str_to_array( $str, ' ' );
	if ( isset($arr[0]) ) {
		$date = $this->str_to_mysql_date( $arr[0] );
	}
	if ( isset($arr[1]) ) {
		$time = $this->str_to_mysql_time( $arr[1] );
	}

	if ( $date && $time ) {
		$val = $date.' '.$time;
		return $val;

	} elseif ( $date ) {
		return $date;
	}

// Incorrect datetime value
	$null = '0000-00-00 00:00:00';
	return $null;
}

function str_to_mysql_date( $str )
{
// 2008-01-01
	$year  = 2008;
	$month = 01;
	$day   = 01;

// 0000-00-00
	$mysql_year  = '0000';
	$mysql_month = '00';
	$mysql_day   = '00';
	$mysql_hour  = '00';
	$mysql_min   = '00';
	$mysql_sec   = '00';

	$arr = explode('-', $str);

// ex) 2008-02-03
	if ( isset($arr[0]) && isset($arr[1]) && isset($arr[2]) ) {
		$year  = intval(trim($arr[0]));
		$month = intval(trim($arr[1]));
		$day   = intval(trim($arr[2]));
		$mysql_year  = $year;
		$mysql_month = $month;
		$mysql_day   = $day;

// ex) 2008-02 -> 2008-02-00
	} elseif ( isset($arr[0]) && isset($arr[1]) ) {
		$year  = intval(trim($arr[0]));
		$month = intval(trim($arr[1]));
		$mysql_year  = $year;
		$mysql_month = $month;

// ex) 2008 -> 2008-00-00
	} elseif ( isset($arr[0]) ) {
		$year  = intval(trim($arr[0]));
		$mysql_year  = $year;

	} else {
		return false;
	}

	if ( checkdate( $month, $day, $year ) ) {
		return $this->build_mysql_date( $mysql_year, $mysql_month, $mysql_day );
	}
	return false;
}

function str_to_mysql_time( $str )
{
// 0000-00-00
	$mysql_hour  = '00';
	$mysql_min   = '00';
	$mysql_sec   = '00';

	$arr = explode(':', $str);

// ex) 01:02:03
	if ( isset($arr[0]) && isset($arr[1]) && isset($arr[2]) ) {
		$mysql_hour = intval(trim($arr[0]));
		$mysql_min  = intval(trim($arr[1]));
		$mysql_sec  = intval(trim($arr[2]));

// ex) 01:02 -> 01:02:00
	} elseif ( isset($arr[0]) && isset($arr[1]) ) {
		$mysql_hour = intval(trim($arr[0]));
		$mysql_min  = intval(trim($arr[1]));

// ex) 01 -> 01:00:00
	} elseif ( isset($arr[0]) ) {
		$mysql_hour = intval(trim($arr[0]));

	} else {
		return false;
	}

	if ( $this->check_time( $mysql_hour, $mysql_min, $mysql_sec ) ) {
		return $this->build_mysql_time( $mysql_hour, $mysql_min, $mysql_sec );
	}
	return false;
}

function check_time( $hour, $min, $sec )
{
	$hour = intval($hour);
	$min  = intval($min);
	$sec  = intval($sec);

	if ( ( $hour >= 0 )&&( $hour <= 24 )&&
	     ( $min  >= 0 )&&( $min  <= 60 )&&
	     ( $sec  >= 0 )&&( $sec  <= 60 ) ) {
		return true;
	}
	return false;
}

function build_mysql_date( $year, $month, $day )
{
	$str = $year .'-'. $month .'-'. $day;
	return $str;
}

function build_mysql_time( $hour, $min, $sec )
{
	$str = $hour .':'. $min .':'. $sec;
	return $str;
}

// --- class end ---
}

?>