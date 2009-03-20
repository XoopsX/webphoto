<?php
// $Id: timeline.php,v 1.3 2009/03/20 13:44:48 ohwada Exp $

//=========================================================
// webphoto module
// 2009-03-15 K.OHWADA
//=========================================================

// === class begin ===
if( !class_exists('webphoto_timeline') ) 
{

//=========================================================
// class webphoto_timeline
//=========================================================
class webphoto_timeline
{
	var $_timeline_class ;
	var $_utility_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_timeline()
{
	$this->_utility_class =& webphoto_lib_utility::getInstance();
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_timeline();
	}
	return $instance;
}

//---------------------------------------------------------
// timeline
//---------------------------------------------------------
function init( $timeline_dirname )
{
	$file = XOOPS_ROOT_PATH.'/modules/'. $timeline_dirname .'/include/api_timeline.php' ;
	if ( !file_exists($file) ) {
		return false;
	}

	include_once $file ;

	if ( !class_exists( 'timeline_compo_timeline' ) ) {
		return false;
	}

	$this->_timeline_class =& timeline_compo_timeline::getSingleton( $timeline_dirname );
	return true;
}

function fetch_timeline( $mode, $unit, $date, $photos )
{
	$ID     = 0;
	$events = array();

	foreach ( $photos as $photo ) {
		$event = $this->build_event( $photo );
		if ( is_array($event) ) {
			$events[] = $event ;
		}
	}

	switch ( $mode )
	{
		case 'painter':
			$this->_timeline_class->init_painter_events();
			$this->_timeline_class->set_band_unit( $unit );
			$this->_timeline_class->set_center_date( $date );
			$param = $this->_timeline_class->build_painter_events( $ID, $events );
			$js    = $this->_timeline_class->fetch_painter_events( $param );
			break;

		case 'simple':
		default:
			$this->_timeline_class->init_simple_events();
			$param = $this->_timeline_class->build_simple_events( $ID, $events );
			$js    = $this->_timeline_class->fetch_simple_events( $param );
			break;
	}

	$arr = array(
		'timeline_js'      => $js ,
		'timeline_element' => $param['element'] ,
	);
	return $arr;
}

function build_event( $photo )
{
	$start = $this->build_start( $photo );
	if ( empty($start) ) {
		return false;
	}

	$icon = $photo['small_url_s'];

	$image = '';
	if ( $photo['thumb_url_s'] ) {
		$image = $photo['thumb_url_s'] ;
	} elseif ( $icon ) {
		$image = $icon ;
	}

	$arr = array(
		'start' => $start ,
		'title' => $photo['title'] ,
		'link'  => $photo['photo_uri'],
		'image' => $image ,
		'icon'  => $icon ,
		'description' => $this->_timeline_class->build_summary( $photo['description_disp'] ) ,
	);
	return $arr;
}

function build_start( $photo )
{
	$time = 0 ;
	if ( $photo['datetime_unix'] > 0 ) {
		$time = $photo['datetime_unix'] ;
	} elseif ( $photo['time_create'] > 0 ) {
		$time = $photo['time_create'] ;
	}

	$start = false;
	if ( $time > 0 ) {
		$start = date( 'r', $time );
	}

	return $start;
}

// --- class end ---
}

// === class end ===
}

?>