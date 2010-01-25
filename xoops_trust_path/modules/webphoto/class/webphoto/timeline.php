<?php
// $Id: timeline.php,v 1.5 2010/01/25 10:03:07 ohwada Exp $

//=========================================================
// webphoto module
// 2009-03-15 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-01-10 K.OHWADA
// build_timeline_by_rows()
//---------------------------------------------------------

// === class begin ===
if( !class_exists('webphoto_timeline') ) 
{

//=========================================================
// class webphoto_timeline
//=========================================================
class webphoto_timeline extends webphoto_show_photo
{
	var $_public_class;
	var $_timeline_class;

	var $_init_timeline;

	var $_ORDERBY_LATEST = 'item_time_update DESC, item_id DESC';
	var $_ORDERBY_RANDOM = 'rand()';
	var $_OFFSET_ZERO = 0 ;
	var $_KEY_TRUE    = true ;
	var $_KEY_NAME    = 'item_id' ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_timeline( $dirname, $trust_dirname )
{
	$this->webphoto_show_photo( $dirname, $trust_dirname );

	$this->_public_class
		=& webphoto_photo_public::getInstance( $dirname, $trust_dirname  );

	$this->_timeline_class =& webphoto_inc_timeline::getSingleton( $dirname );

	$cfg_timeline_dirname  = $this->_config_class->get_by_name('timeline_dirname');
	$this->_init_timeline  = $this->_timeline_class->init( $cfg_timeline_dirname );
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_timeline( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// timeline class
//---------------------------------------------------------
function build_timeline_by_rows( $mode, $unit, $date, $rows )
{
	$latest = $this->_config_class->get_by_name('timeline_latest');
	$random = $this->_config_class->get_by_name('timeline_random');

	$latest_rows = $this->get_rows_by_orderby( $this->_ORDERBY_LATEST, $latest );
	$random_rows = $this->get_rows_by_orderby( $this->_ORDERBY_RANDOM, $random );

	$all_rows = $this->array_merge_unique( $random_rows, $latest_rows );
	$all_rows = $this->array_merge_unique( $all_rows,    $rows        );

	if ( is_array($all_rows) && count($all_rows)  ) {
		$photos = $this->build_photo_show_from_rows( $all_rows );
		return $this->build_timeline_by_photos( $mode, $unit, $date, $photos );
	}

	$arr = array(
		'show_timeline' => false ,
	);
	return $arr;
}

function build_timeline_by_photos( $mode, $unit, $date, $photos )
{
	$show    = false ;
	$js      = null ;
	$element = null;

	if ( $this->_init_timeline ) {
		$param = $this->_timeline_class->fetch_timeline( 
			'painter', $unit, $date, $photos );
		$js      = $param['timeline_js'] ;
		$element = $param['timeline_element'] ;
		$show    = true ;
	}

	$is_timeline_mode = $this->is_timeline_mode( $mode );

	$arr = array(
		'show_timeline'       => $show ,
		'show_timeline_large' => ! $is_timeline_mode ,
		'show_timeline_unit'  => $is_timeline_mode ,
		'timeline_class'      => $this->get_timeline_class( $mode ) ,
		'timeline_js'         => $js ,
		'timeline_element'    => $element ,
	);
	return $arr;
}

function get_timeline_class( $mode )
{
	if ( $this->is_timeline_mode( $mode ) ) {
		return 'webphoto_timeline_large';
	}
	return 'webphoto_timeline_normal';
}

function is_timeline_mode( $mode )
{
	if ( $mode == 'timeline' ) {
		return true;
	}
	return false;
}

function get_rows_by_orderby( $orderby, $limit )
{
	return $this->_public_class->get_rows_by_orderby( 
		 $orderby, $limit, $this->_OFFSET_ZERO, $this->_KEY_TRUE );
}

function build_photo_show_from_rows( $rows )
{
	$arr = array();
	foreach ( $rows as $row ) {
		$arr[] = $this->build_photo_show( $row ) ;
	}
	return $arr;
}

function array_merge_unique( $arr1, $arr2 )
{
	return $this->_utility_class->array_merge_unique( $arr1, $arr2, $this->_KEY_NAME );
}

// --- class end ---
}

// === class end ===
}

?>