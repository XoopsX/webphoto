<?php
// $Id: timeline.php,v 1.3 2009/03/21 12:44:57 ohwada Exp $

//=========================================================
// webphoto module
// 2009-03-15 K.OHWADA
//=========================================================

// === class begin ===
if( !class_exists('webphoto_inc_timeline') ) 
{

//=========================================================
// class webphoto_inc_timeline
//=========================================================
class webphoto_inc_timeline
{
	var $_timeline_class ;

	var $_cfg_use_pathinfo;
	var $_cfg_timeline_scale;

	var $_DIRNAME    ;
	var $_MODULE_URL ;
	var $_MODULE_DIR ;
	var $_IMAGE_EXTS ;

	var $_UNIT_DEFAULT = '';
	var $_DATE_DEFAULT = '';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_timeline( $dirname )
{
	$this->_DIRNAME    = $dirname ;
	$this->_MODULE_URL = XOOPS_URL       .'/modules/'. $dirname;
	$this->_MODULE_DIR = XOOPS_ROOT_PATH .'/modules/'. $dirname;

	$this->_IMAGE_EXTS = explode( '|', _C_WEBPHOTO_IMAGE_EXTS );

	$config_handler =& webphoto_inc_config::getSingleton( $dirname );

	$this->_cfg_use_pathinfo   = $config_handler->get_by_name( 'use_pathinfo' );
	$this->_cfg_timeline_scale = $config_handler->get_by_name( 'timeline_scale' );

	$this->_UNIT_DEFAULT = $this->_cfg_timeline_scale ;
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
	$start = $this->build_start( $photo );
	if ( empty($start) ) {
		return false;
	}

	$arr = array(
		'start'       => $start ,
		'title'       => $this->build_title( $photo ) ,
		'link'        => $this->build_link(  $photo ) ,
		'image'       => $this->build_image( $photo ) ,
		'icon'        => $this->build_icon(  $photo ) ,
		'description' => $this->build_description( $photo ) ,
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

	return $this->unixtime_to_datetime( $time );
}

function build_title( $photo )
{
	return $this->sanitize( $photo['title'] ) ;
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
	return $photo['thumb_url'] ;
}

function build_icon( $photo )
{
// no sanitize
		return $photo['small_url'] ;
}

//---------------------------------------------------------
// timeline class
//---------------------------------------------------------
function build_painter_events( $id, $unit, $date, $events )
{
	$this->_timeline_class->init_painter_events();
	$this->_timeline_class->set_band_unit( $unit );
	$this->_timeline_class->set_center_date( $date );
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
// uri
//---------------------------------------------------------
function build_uri( $fct, $param )
{
	if ( $this->_cfg_use_pathinfo ) {
		$str = $this->_MODULE_URL .'/index.php/'. $fct .'/'. $param .'/';
	} else {
		$str = $this->_MODULE_URL .'/index.php?'. $fct .'photo&amp;p='. $param ;
	}
	return $str;
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

// --- class end ---
}

// === class end ===
}

?>