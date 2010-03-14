<?php
// $Id: exif.php,v 1.3 2010/03/14 17:14:27 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-03-14 K.OHWADA
// webphoto_lib_multibyte
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_exif
// wrapper for webphoto_lib_exif
//=========================================================
class webphoto_exif
{
	var $_exif_class;
	var $_utility_class;
	var $_multibyte_class;

	var $_GMAP_ZOOM = _C_WEBPHOTO_GMAP_ZOOM ;
	var $_EXIF_LENGTH = 65000 ; // 64KB

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_exif()
{
	$this->_exif_class      =& webphoto_lib_exif::getInstance();
	$this->_utility_class   =& webphoto_lib_utility::getInstance();
	$this->_multibyte_class =& webphoto_lib_multibyte::getInstance();
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

	$info = $this->_exif_class->read_file( $file );
	if ( !is_array($info) ) {
		return null ; // no action
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
		$row['item_exif'] = $this->format_exif( $exif ) ;
		$flag = 2 ;
	}

	$arr = array(
		'flag' => $flag,
		'row'  => $row,
	);
	return $arr ;
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

function format_exif( $str )
{
	if ( strlen($str) < $this->_EXIF_LENGTH ) {
		return $str ;
	}

	$str = $this->_multibyte_class->convert_encoding( $str, 'ASCII', 'UTF-8' );
	$str = substr( $str, 0, $this->_EXIF_LENGTH );
	return $str;
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