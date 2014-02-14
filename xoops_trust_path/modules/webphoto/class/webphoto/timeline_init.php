<?php
// $Id: timeline_init.php,v 1.2 2012/04/09 12:46:19 ohwada Exp $

//=========================================================
// webphoto module
// 2011-12-25 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2012-04-09 K.OHWADA
// Fatal error: Unsupported operand types
//---------------------------------------------------------

// === class begin ===
if( !class_exists('webphoto_timeline_init') ) 
{

//=========================================================
// class webphoto_timeline_init
//=========================================================
class webphoto_timeline_init
{
	var $_config_class;
	var $_timeline_class;

	var $_cfg_timeline_dirname;
	var $_init_timeline;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_timeline_init( $dirname )
{
	$this->_config_class   =& webphoto_config::getInstance( $dirname );
	$this->_timeline_class =& webphoto_inc_timeline::getSingleton( $dirname );

	$this->_cfg_timeline_dirname = 
		$this->_config_class->get_by_name('timeline_dirname');
	$this->_init_timeline = 
		$this->_timeline_class->init( $this->_cfg_timeline_dirname );
}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_timeline_init( $dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// webphoto_inc_timeline
//---------------------------------------------------------
function fetch_timeline( $mode, $unit, $date, $photos )
{
	return $this->_timeline_class->fetch_timeline( 
		$mode, $unit, $date, $photos );
}

//---------------------------------------------------------
// function
//---------------------------------------------------------
function get_timeline_dirname()
{
	return $this->_timeline_dirname;
}

function get_init()
{
	return $this->_init_timeline;
}

function get_scale_options( $flag_none=false )
{
	$arr1 = array(
		'none' => _WEBPHOTO_TIMELINE_OFF ,
	);

	$arr2 = $this->_timeline_class->get_scale_options();

// Fatal error: Unsupported operand types
	if ( !is_array($arr2) ) {
		return false;
	}
	
	if ( $flag_none ) {
		$arr = $arr1 + $arr2 ;
	} else {
		$arr = $arr2;
	}

	return $arr;
}

function scale_to_unit( $scale, $default='month' )
{
	if ( $scale == 0 ) {
		return $default;
	}

	$arr = $this->_timeline_class->get_int_unit_array();

	if ( isset( $arr[ $scale ] ) ) {
		return  $arr[ $scale ];
	}

	return $default;
}

function unit_to_scale( $unit, $default=6 )
{
	$arr1 = $this->_timeline_class->get_int_unit_array();
	$arr2 = array_flip( $arr1 );

	if ( isset( $arr2[ $unit ] ) ) {
		return  $arr2[ $unit ];
	}

	return $default;
}

function get_lang_param()
{
	$param = $this->_timeline_class->get_scale_options();
	if ( !is_array($param) ) {
		return false;
	}

	$arr   = array();
	foreach ( $param as $k => $v ) {
		$arr[ 'lang_timeline_unit_'.$k ] = $v;
	}
	return $arr;
}

// --- class end ---
}

// === class end ===
}

?>