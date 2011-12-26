<?php
// $Id: exif.php,v 1.6 2011/12/26 06:51:31 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// webphoto_lib_mysql_utility
// 2010-10-01 K.OHWADA
// build_row_exif() -> get_exif()
// 2010-03-18 K.OHWADA
// nothing to do
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
	var $_mysql_utility_class ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_exif()
{
	$this->_exif_class    =& webphoto_lib_exif::getInstance();
	$this->_utility_class =& webphoto_lib_utility::getInstance();
	$this->_mysql_utility_class =& webphoto_lib_mysql_utility::getInstance();

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
function get_exif( $file )
{
	$info = $this->_exif_class->read_file( $file );
	if ( !is_array($info) ) {
		return null ; // no action
	}

	$info['datetime_mysql'] = $this->exif_to_mysql_datetime( $info ) ;
	return $info ;
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

//---------------------------------------------------------
// utility class
//---------------------------------------------------------
function str_to_time( $str )
{
	return $this->_utility_class->str_to_time( $str ) ;
}

function time_to_mysql_datetime( $time )
{
	return $this->_mysql_utility_class->time_to_mysql_datetime( $time ) ;
}

// --- class end ---
}

?>