<?php
// $Id: timeline.php,v 1.7 2011/12/28 16:16:15 ohwada Exp $

//=========================================================
// webphoto module
// 2009-03-15 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// build_rows_for_detail()
// 2010-01-10 K.OHWADA
// build_timeline_by_rows()
//---------------------------------------------------------

// === class begin ===
if( !class_exists('webphoto_timeline') ) 
{

//=========================================================
// class webphoto_timeline
//=========================================================
class webphoto_timeline extends webphoto_base_this
{
	var $_public_class;
	var $_catlist_class;
	var $_timeline_init_class;

	var $_cfg_timeline_scale;
	var $_cfg_timeline_latest;
	var $_cfg_timeline_random;

	var $_init_timeline;

	var $_ORDERBY_LATEST = 'item_time_update DESC, item_id DESC';
	var $_ORDERBY_RANDOM = 'rand()';
	var $_OFFSET_ZERO  = 0 ;
	var $_KEY_TRUE     = true ;
	var $_KEY_NAME     = 'item_id' ;
	var $_DATE_DEFAULT = '';

	var $_SHOW_FALSE = array( 'show_timeline' => false );

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_timeline( $dirname, $trust_dirname )
{
	$this->webphoto_base_this( $dirname, $trust_dirname );

	$this->_public_class
		=& webphoto_photo_public::getInstance( $dirname, $trust_dirname  );
	$this->_catlist_class   
		=& webphoto_inc_catlist::getSingleton( $dirname , $trust_dirname );

	$this->_image_class 
		=& webphoto_show_image::getInstance( $dirname, $trust_dirname );

	$this->_timeline_init_class =& webphoto_timeline_init::getInstance( $dirname );

	$this->_init_timeline = $this->_timeline_init_class->get_init();

	$this->_cfg_timeline_scale  = $this->_config_class->get_by_name('timeline_scale');
	$this->_cfg_timeline_latest = $this->_config_class->get_by_name('timeline_latest');
	$this->_cfg_timeline_random = $this->_config_class->get_by_name('timeline_random');

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
function build_for_detail( $param, $flag_large )
{
	if ( ! $this->_init_timeline ) {
		return $this->_SHOW_FALSE;
	}

	$rows = $param['rows'];

	$unit = $this->_post_class->get_get_text('unit');
	$date = $this->_post_class->get_get_text('date');

	if ( empty($unit) ) {
		$unit = $this->get_unit_default( $param );
	}
	if ( empty($date) ) {
		$date = $this->_DATE_DEFAULT;
	}

	$extra_rows = $this->build_rows_for_detail( $param );
	$all_rows   = $this->array_merge_unique( $extra_rows, $rows );

	$photos = $this->build_photos_from_rows( $all_rows );

	$param  = $this->build_timeline_from_photos(
		$unit, $date, $photos, $flag_large );
	return $param;
}

function get_lang_param()
{
	return $this->_timeline_init_class->get_lang_param();
}

//---------------------------------------------------------
// private
//---------------------------------------------------------
function get_unit_default( $param )
{
	$mode   = $param['mode'];
	$cat_id = $param['cat_id'];

	switch ($mode)
	{
		case 'category':
			$scale = $this->_cat_handler->get_cached_timeline_scale_by_id( $cat_id );
			$unit = $this->_timeline_init_class->scale_to_unit( $scale );
			break;

		default:
			$unit = $this->_cfg_timeline_scale;
			break;
	}

	return $unit;
}

function build_rows_for_detail( $param )
{
	$mode      = $param['mode'];
	$cat_id    = $param['cat_id'];
	$sub_mode  = $param['sub_mode'];
	$sub_param = $param['sub_param'];
	$sub_param_int = intval($sub_param);

	if (( $mode == 'category' )&&( $cat_id > 0 )) {
		$rows = $this->build_rows_for_detail_category( $cat_id );

	} elseif (( $sub_mode == 'category' )&&( $sub_param_int > 0 )) {
		$rows = $this->build_rows_for_detail_category( $sub_param_int );
	} else {
		$rows = $this->build_rows_for_detail_main();
	}

	return $rows;
}

function build_rows_for_detail_main()
{
	$latest_rows = $this->get_rows_by_orderby( 
		$this->_ORDERBY_LATEST, $this->_cfg_timeline_latest );

	$random_rows = $this->get_rows_by_orderby( 
		$this->_ORDERBY_RANDOM, $this->_cfg_timeline_random );

	$all_rows = $this->array_merge_unique( $random_rows, $latest_rows );
	return $all_rows ;
}

function build_rows_for_detail_category( $cat_id )
{
	$latest_rows = $this->get_rows_by_catid_orderby( 
		$cat_id, $this->_ORDERBY_LATEST, $this->_cfg_timeline_latest );

	$random_rows = $this->get_rows_by_catid_orderby( 
		$cat_id, $this->_ORDERBY_RANDOM, $this->_cfg_timeline_random );

	$all_rows = $this->array_merge_unique( $random_rows, $latest_rows );
	return $all_rows ;
}

//---------------------------------------------------------
// timeline
//---------------------------------------------------------
function build_timeline_from_photos( $unit, $date, $photos, $flag_large )
{
	$show    = false ;
	$js      = null ;
	$element = null;

	if ( $this->_init_timeline ) {
		$param = $this->_timeline_init_class->fetch_timeline( 
			'painter', $unit, $date, $photos );
		$js      = $param['timeline_js'] ;
		$element = $param['timeline_element'] ;
		$show    = true ;
	}

	$arr = array(
		'show_timeline'       => $show ,
		'show_timeline_large' => ! $flag_large ,
		'show_timeline_unit'  =>   $flag_large ,
		'timeline_class'      => $this->get_timeline_class( $flag_large ) ,
		'timeline_js'         => $js ,
		'timeline_element'    => $element ,
	);
	return $arr;
}

function get_timeline_class( $flag_large )
{
	if ( $flag_large ) {
		return 'webphoto_timeline_large';
	}
	return 'webphoto_timeline_normal';
}

//---------------------------------------------------------
// photos
//---------------------------------------------------------
function build_photos_from_rows( $rows )
{
	$arr = array();
	foreach ( $rows as $row ) {
		$arr[] = $this->build_photo_single( $row ) ;
	}
	return $arr;
}

function build_photo_single( $row )
{
	$item_id = $row['item_id'];

	$arr1 = array(
		'description_disp' => $this->_item_handler->build_show_description_disp( $row ),
		'photo_uri'        => $this->build_uri_photo( $item_id, false ), 
	);

	$arr2 = $this->_image_class->build_image_by_item_row( $row, true );

	$arr = $row + $arr1 + $arr2 ;
	return $arr;
}

//---------------------------------------------------------
// public class
//---------------------------------------------------------
function get_rows_by_orderby( $orderby, $limit )
{
	return $this->_public_class->get_rows_by_orderby( 
		 $orderby, $limit, $this->_OFFSET_ZERO, $this->_KEY_TRUE );
}

function get_rows_by_catid_orderby( $cat_id, $orderby, $limit=0, $offset=0 )
{
	$catid_array = $this->_catlist_class->get_cat_parent_all_child_id_by_id( $cat_id ) ;

	return $this->_public_class->get_rows_by_catid_array_orderby( 
		$catid_array, $orderby, $limit, $offset );
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
function array_merge_unique( $arr1, $arr2 )
{
	return $this->_utility_class->array_merge_unique( $arr1, $arr2, $this->_KEY_NAME );
}

// --- class end ---
}

// === class end ===
}

?>