<?php
// $Id: timeline.php,v 1.9 2011/12/28 16:16:15 ohwada Exp $

//=========================================================
// webphoto module
// 2009-03-15 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// webphoto_lib_mysql_utility
// 2011-06-04 K.OHWADA
// remove build_uri()
// 2009-04-10 K.OHWADA
// check_exist()
//---------------------------------------------------------

// === class begin ===
if( !class_exists('webphoto_inc_timeline') ) 
{

//=========================================================
// class webphoto_inc_timeline
//=========================================================
class webphoto_inc_timeline
{
	var $_timeline_class ;
	var $_mysql_utility_class ;

	var $_init_timeline = false;

	var $_show_onload   = false;
	var $_show_onresize = false;
	var $_show_timeout  = false;
	var $_timeout = 1000;	// 1 sec

	var $_DIRNAME    ;
	var $_MODULE_URL ;
	var $_MODULE_DIR ;
	var $_IMAGE_EXTS ;

	var $_UNIT_DEFAULT = 'month';
	var $_DATE_DEFAULT = '';

	var $_UNIT_ARRAY = array('day','week','month','year','decade','century');

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_timeline( $dirname )
{
	$this->_DIRNAME    = $dirname ;
	$this->_MODULE_URL = XOOPS_URL       .'/modules/'. $dirname;
	$this->_MODULE_DIR = XOOPS_ROOT_PATH .'/modules/'. $dirname;

	$this->_IMAGE_EXTS = explode( '|', _C_WEBPHOTO_IMAGE_EXTS );

	$this->_mysql_utility_class =& webphoto_lib_mysql_utility::getInstance();
}

function &getSingleton( $dirname )
{
	static $singletons;
	if ( !isset( $singletons[ $dirname ] ) ) {
		$singletons[ $dirname ] = new webphoto_inc_timeline( $dirname );
	}
	return $singletons[ $dirname ];
}

//---------------------------------------------------------
// timeline
//---------------------------------------------------------
function init( $timeline_dirname )
{
	$check = $this->check_exist( $timeline_dirname );
	if ( ! $check ) {
		return false;
	}

	$this->_timeline_class =& timeline_compo_timeline::getSingleton( $timeline_dirname );
	$this->_init_timeline = true;

	return true;
}

function check_exist( $timeline_dirname )
{
	$file = XOOPS_ROOT_PATH.'/modules/'. $timeline_dirname .'/include/api_timeline.php' ;
	if ( !file_exists($file) ) {
		return false;
	}

	include_once $file ;

	return class_exists( 'timeline_compo_timeline' );
}

function fetch_timeline( $mode, $unit, $date, $photos )
{
	if ( ! $this->_init_timeline ) {
		return false;
	}

	$ID     = 0;
	$events = array();

	if ( empty($unit) ) {
		$unit = $this->_UNIT_DEFAULT;
	}

	if ( empty($date) ) {
		$date = $this->_DATE_DEFAULT;
	}

	foreach ( $photos as $photo ) {
		$event = $this->build_event( $photo );
		if ( is_array($event) ) {
			$events[] = $event ;
		}
	}

	switch ( $mode )
	{
		case 'painter':
			list( $element, $js ) = 
				$this->build_painter_events( $ID, $unit, $date, $events );
			break;

		case 'simple':
		default:
			list( $element, $js ) = 
				$this->build_simple_events( $ID, $unit, $date, $events );
			break;
	}

	$arr = array(
		'timeline_js'      => $js ,
		'timeline_element' => $element ,
	);
	return $arr;
}

//---------------------------------------------------------
// event
//---------------------------------------------------------
function build_event( $photo )
{
	$param = $this->build_start( $photo );
	if ( !is_array($param) ) {
		return false;
	}

	$param['title']       = $this->build_title( $photo ) ;
	$param['link']        = $this->build_link(  $photo ) ;
	$param['image']       = $this->build_image( $photo ) ;
	$param['icon']        = $this->build_icon(  $photo ) ;
	$param['description'] = $this->build_description( $photo ) ;

	return $param;
}

function build_start( $photo )
{
	if ( $photo['item_datetime'] ) {
		$param = $this->build_start_param( $photo['item_datetime'] );
		if ( is_array($param) ) {
			return $param;
		}
	}

	if ( $photo['item_time_create'] > 0 ) {
		$param = array(
			'start' => $this->unixtime_to_datetime( $photo['item_time_create'] )
		);
		return $param;
	}

	return false;
}

function build_start_param( $datetime )
{
	$p = $this->_mysql_utility_class->mysql_datetime_to_date_param( $datetime );
	if ( !is_array($p) ) {
		return false ;
	}

	$param = array(
		'start_year'   => $p['year'],
		'start_month'  => $p['month'],
		'start_day'    => $p['day'],
		'start_hour'   => $p['hour'],
		'start_minute' => $p['minute'],
		'start_second' => $p['second'],
	);

	return $param;
}

function build_title( $photo )
{
	return $this->sanitize( $photo['item_title'] ) ;
}

function build_description( $photo )
{
	return $this->escape_quotation( 
		$this->build_summary( $photo['description_disp'] ) ) ;
}

function build_link( $photo )
{
// no sanitize
	return $photo['photo_uri'] ;
}

function build_image( $photo )
{
// no sanitize
	if ( $photo['thumb_url'] ) {
		return $photo['thumb_url'] ;
	}
	return $this->build_icon( $photo );
}

function build_icon( $photo )
{
// no sanitize
	if ( $photo['small_url'] ) {
		return $photo['small_url'] ;
	} elseif ( $photo['icon_url'] ) {
		return $photo['icon_url'] ;
	}
	return null;
}

//---------------------------------------------------------
// timeline class
//---------------------------------------------------------
function build_painter_events( $id, $unit, $date, $events )
{
	$this->_timeline_class->init_painter_events();
	$this->_timeline_class->set_band_unit( $unit );
	$this->_timeline_class->set_center_date( $date );
	$this->_timeline_class->set_show_onload(   $this->_show_onload );
	$this->_timeline_class->set_show_onresize( $this->_show_onresize );
	$this->_timeline_class->set_show_timeout(  $this->_show_timeout );
	$this->_timeline_class->set_timeout( $this->_timeout );
	$param = $this->_timeline_class->build_painter_events( $id, $events );
	$js    = $this->_timeline_class->fetch_painter_events( $param );
	return array( $param['element'], $js );
}

function build_simple_events( $id, $unit, $date, $events )
{
	$this->_timeline_class->init_simple_events();
	$param = $this->_timeline_class->build_simple_events( $id, $events );
	$js    = $this->_timeline_class->fetch_simple_events( $param );
	return array( $param['element'], $js );
}

function build_summary( $str )
{
	return $this->_timeline_class->build_summary( $str ) ;
}

function unixtime_to_datetime( $time )
{
	return $this->_timeline_class->unixtime_to_datetime( $time );
}

function escape_quotation( $str )
{
	return $this->_timeline_class->escape_quotation( $str );
}

//---------------------------------------------------------
// options
//---------------------------------------------------------
function get_scale_options()
{
	if ( ! $this->_init_timeline ) {
		return false;
	}

	$lang = $this->_timeline_class->get_unit_lang_array();

	$arr = array();
	foreach ( $lang as $k => $v )
	{
		if( in_array( $k, $this->_UNIT_ARRAY ) ) {
			$arr[ $k ] = $v;
		}
	}
	return $arr;
}

function get_int_unit_array()
{
	if ( ! $this->_init_timeline ) {
		return array();
	}

	return $this->_timeline_class->get_int_unit_array();
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
function sanitize( $str )
{
	return htmlspecialchars( $str, ENT_QUOTES );
}

function is_image_ext( $ext )
{
	return $this->is_ext_in_array( $ext, $this->_IMAGE_EXTS );
}

function is_ext_in_array( $ext, $arr )
{
	if ( in_array( strtolower( $ext ) , $arr ) ) {
		return true;
	}
	return false ;
}

//---------------------------------------------------------
// set param
//---------------------------------------------------------
function set_show_onload( $val )
{
	$this->_show_onload = (bool)$val ;
}

function set_show_onresize( $val )
{
	$this->_show_onresize = (bool)$val ;
}

function set_show_timeout( $val )
{
	$this->_show_timeout = (bool)$val ;
}

function set_timeout( $val )
{
	$this->_timeout = intval($val) ;
}

// --- class end ---
}

// === class end ===
}

?>