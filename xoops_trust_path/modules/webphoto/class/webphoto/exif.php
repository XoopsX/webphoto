<?php
// $Id: exif.php,v 1.1 2009/01/24 07:13:12 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_exif
// wrapper for webphoto_lib_exif
//=========================================================
class webphoto_exif
{
	var $_exif_class;
	var $_utility_class;

	var $_row = null ;

	var $_GMAP_ZOOM = _C_WEBPHOTO_GMAP_ZOOM ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_exif()
{
	$this->_exif_class    =& webphoto_lib_exif::getInstance();
	$this->_utility_class =& webphoto_lib_utility::getInstance();
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_exif();
	}
	return $instance;
}

//---------------------------------------------------------
// exif
//---------------------------------------------------------
function build_row_exif( $row, $file )
{
	$flag = 1 ;
	$this->_row = $row;

	$info = $this->_exif_class->read_file( $file );
	if ( !is_array($info) ) {
		return 0 ; // no action
	}

	$datetime  = $this->exif_to_mysql_datetime( $info ) ;
	$equipment = $info['equipment'] ;
	$latitude  = $info['latitude'] ;
	$longitude = $info['longitude'] ;
	$exif      = $info['all_data'] ;

	if ( $datetime ) {
		$row['item_datetime'] = $datetime ;
	}
	if ( $equipment ) {
		$row['item_equipment'] = $equipment ;
	}
	if ( ( $latitude != 0 )||( $longitude != 0 ) ) {
		$row['item_gmap_latitude']  = $latitude ;
		$row['item_gmap_longitude'] = $longitude ;
		$row['item_gmap_zoom']      = $this->_GMAP_ZOOM ;
	}
	if ( $exif ) {
		$row['item_exif'] = $exif ;
		$flag = 2 ;
	}

	$this->_row = $row;
	return $flag ;
}

function exif_to_mysql_datetime( $exif )
{
	$datetime     = $exif['datetime'];
	$datetime_gnu = $exif['datetime_gnu'];

	if ( $datetime_gnu ) {
		return $datetime_gnu;
	}

	$time = $this->str_to_time( $datetime );
	if ( $time <= 0 ) { return false; }

	return $this->time_to_mysql_datetime( $time );
}

function get_row()
{
	return $this->_row ;
}

//---------------------------------------------------------
// utility class
//---------------------------------------------------------
function str_to_time( $str )
{
	return $this->_utility_class->str_to_time( $str ) ;
}

function time_to_mysql_datetime( $time )
{
	return $this->_utility_class->time_to_mysql_datetime( $time ) ;
}

// --- class end ---
}

?>