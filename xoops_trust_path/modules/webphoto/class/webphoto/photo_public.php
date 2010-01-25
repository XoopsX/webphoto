<?php
// $Id: photo_public.php,v 1.9 2010/01/25 10:03:07 ohwada Exp $

//=========================================================
// webphoto module
// 2008-12-12 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-01-10 K.OHWADA
// build_cat_path()
// 2009-11-11 K.OHWADA
// $trust_dirname
// 2009-09-06 K.OHWADA
// add ns ew in get_rows_by_gmap_area()
// 2009-05-17 K.OHWADA
// _cfg_cat_child
// 2009-04-10 K.OHWADA
// add $key in get_rows_by_orderby()
// 2009-01-25 K.OHWADA
// remove catlist->set_perm_cat_read()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_photo_public
//=========================================================
class webphoto_photo_public
{
	var $_config_class;
	var $_item_handler ;
	var $_cat_handler ;
	var $_item_cat_handler ;
	var $_tagcloud_class;

	var $_cfg_perm_cat_read ;
	var $_cfg_cat_child ;
	var $_cfg_cat_main_width ;
	var $_cfg_cat_sub_width ;
	var $_cfg_use_pathinfo ;

	var $_ORDERBY_ASC    = 'item_id ASC';
	var $_ORDERBY_LATEST = 'item_time_update DESC, item_id DESC';

// show
	var $_SHOW_CAT_SUB      = true;
	var $_SHOW_CAT_MAIN_IMG = true;
	var $_SHOW_CAT_SUB_IMG  = true;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_photo_public( $dirname, $trust_dirname )
{
	$this->_cat_handler   
		=& webphoto_cat_handler::getInstance( $dirname, $trust_dirname );
	$this->_item_handler  
		=& webphoto_item_handler::getInstance( $dirname, $trust_dirname );
	$this->_item_cat_handler 
		=& webphoto_item_cat_handler::getInstance( $dirname, $trust_dirname );
	$this->_catlist_class  
		=& webphoto_inc_catlist::getSingleton( $dirname, $trust_dirname );
	$this->_tagcloud_class 
		=& webphoto_inc_tagcloud::getSingleton( $dirname, $trust_dirname );

	$this->_config_class   =& webphoto_config::getInstance( $dirname );

	$this->_cfg_perm_cat_read  = $this->_config_class->get_by_name( 'perm_cat_read' );
	$this->_cfg_cat_child      = $this->_config_class->get_by_name( 'cat_child' );
	$this->_cfg_cat_main_width = $this->_config_class->get_by_name('cat_main_width');
	$this->_cfg_cat_sub_width  = $this->_config_class->get_by_name('cat_sub_width');
	$this->_cfg_use_pathinfo   = $this->_config_class->get_by_name('use_pathinfo');
	$cfg_perm_item_read        = $this->_config_class->get_by_name( 'perm_item_read' );

	$this->_item_cat_handler->set_perm_item_read( $cfg_perm_item_read );
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_photo_public( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// cat path
//---------------------------------------------------------
function build_cat_path( $cat_id )
{
	$rows = $this->_cat_handler->get_parent_path_array( $cat_id );
	if ( !is_array($rows) || !count($rows) ) {
		return false;
	}

	$arr   = array();
	$count = count($rows);
	$last  = $count - 1;

	for ( $i = $last ; $i >= 0; $i-- ) {
		$arr[] = $this->build_cat_show( $rows[ $i ] );
	}

	$ret = array();
	$ret['list']  = $arr;
	$ret['first'] = $arr[ 0 ];
	$ret['last']  = $arr[ $last ];

	return $ret;
}

//---------------------------------------------------------
// catlist
//---------------------------------------------------------
function build_catlist_for_category( $cat_id, $cols, $delmita )
{
	$show = false ;

	list( $cats, $cols, $width ) =
		$this->build_catlist( $cat_id, $this->_SHOW_CAT_SUB, $cols ) ;

	if ( is_array($cats) && count($cats) ) {
		$show = true ;
	}

	$catlist = array(
		'cats'            => $cats ,
		'cols'            => $cols ,
		'width'           => $width ,
		'delmita'         => $delmita ,
		'show_sub'        => $this->_SHOW_CAT_SUB ,
		'show_main_img'   => $this->_SHOW_CAT_MAIN_IMG ,
		'show_sub_img'    => $this->_SHOW_CAT_SUB_IMG ,
		'main_width'      => $this->_cfg_cat_main_width ,
		'sub_width'       => $this->_cfg_cat_sub_width ,
	);

	$arr = array(
		'show_catlist'     => $show,
		'cfg_use_pathinfo' => $this->_cfg_use_pathinfo ,
		'catlist'          => $catlist,
	);

	return $arr ;
}

//---------------------------------------------------------
// catlist class
//---------------------------------------------------------
function get_cat_row( $cat_id )
{
	return $this->_catlist_class->get_cat_row_by_catid_perm( $cat_id ) ;
}

function get_cat_all_tree_array()
{
	return $this->_catlist_class->get_cat_all_tree_array() ;
}

function _check_cat_perm_by_catid( $cat_id )
{
	return $this->_catlist_class->check_cat_perm_by_catid( $cat_id ) ;
}

function build_catlist( $parent_id, $flag_sub, $cols )
{
	$catlist = $this->_catlist_class->build_catlist( $parent_id, $flag_sub ) ;
	list( $cols, $width ) =
		$this->_catlist_class->calc_width( $cols ) ;

	return array( $catlist, $cols, $width );
}

function build_cat_show( $cat_row )
{
	return $this->_catlist_class->build_cat_show( $cat_row );
}

//---------------------------------------------------------
// count
//---------------------------------------------------------
function get_count()
{
	return $this->get_count_by_name_param( 'public', null );
}

function get_count_imode()
{
	return $this->get_count_by_name_param( 'imode', null );
}

function get_count_by_catid_array( $param )
{
	return $this->get_count_by_name_param( 'catid_array', $param );
}

function get_count_by_like_datetime( $param )
{
	return $this->get_count_by_name_param( 'like_datetime', $param );
}

function get_count_by_place( $param )
{
	return $this->get_count_by_name_param( 'place', $param );
}

function get_count_by_place_array( $param )
{
	return $this->get_count_by_name_param( 'place_array', $param );
}

function get_count_by_search( $param )
{
	return $this->get_count_by_name_param( 'search', $param );
}

function get_count_by_uid( $param )
{
	return $this->get_count_by_name_param( 'uid', $param );
}

function get_count_by_name_param( $name, $param )
{
	if ( $this->_cfg_perm_cat_read == _C_WEBPHOTO_OPT_PERM_READ_ALL ) {
		return $this->_item_cat_handler->get_count_item_by_name_param( 
			$name, $param );

	} else {
		return $this->_item_cat_handler->get_count_item_cat_by_name_param( 
			$name, $param ) ;
	}
}

//---------------------------------------------------------
// rows
//---------------------------------------------------------
function get_rows_by_orderby( $orderby, $limit=0, $offset=0, $key=false )
{
	return $this->get_rows_by_name_param_orderby( 
		'public', null, $orderby, $limit, $offset, $key ) ;
}

function get_rows_imode_by_orderby( $orderby, $limit=0, $offset=0 )
{
	return $this->get_rows_by_name_param_orderby( 
		'imode', null, $orderby, $limit, $offset ) ;
}

function get_rows_photo_by_orderby( $orderby, $limit=0, $offset=0 )
{
	return $this->get_rows_by_name_param_orderby( 
		'photo', null, $orderby, $limit, $offset ) ;
}

function get_rows_photo_by_catid_orderby( $param, $orderby, $limit=0, $offset=0 )
{
	return $this->get_rows_by_name_param_orderby( 
		'photo_catid', $param, $orderby, $limit, $offset ) ;
}

function get_rows_by_catid_array_orderby( $param, $orderby, $limit=0, $offset=0 )
{
	return $this->get_rows_by_name_param_orderby( 
		'catid_array', $param, $orderby, $limit, $offset ) ;
}

function get_rows_by_like_datetime_orderby( $param, $orderby, $limit=0, $offset=0 )
{
	return $this->get_rows_by_name_param_orderby( 
		'like_datetime', $param, $orderby, $limit, $offset ) ;
}

function get_rows_by_place_orderby( $param, $orderby, $limit=0, $offset=0 )
{
	return $this->get_rows_by_name_param_orderby( 
		'place', $param, $orderby, $limit, $offset ) ;
}

function get_rows_by_place_array_orderby( $param, $orderby, $limit=0, $offset=0 )
{
	return $this->get_rows_by_name_param_orderby( 
		'place_array', $param, $orderby, $limit, $offset ) ;
}

function get_rows_by_uid_orderby( $param, $orderby, $limit=0, $offset=0 )
{
	return $this->get_rows_by_name_param_orderby( 
		'uid', $param, $orderby, $limit, $offset ) ;
}

function get_rows_by_search_orderby( $param, $orderby, $limit=0, $offset=0 )
{
	return $this->get_rows_by_name_param_orderby( 
		'search', $param, $orderby, $limit, $offset ) ;
}

function get_rows_by_gmap( $cat_id, $limit=0, $offset=0 )
{
	$cat_id = intval($cat_id);
	if ( $cat_id > 0 ) {
		$rows = $this->get_rows_by_gmap_catid(
			$cat_id, $limit, $offset );

	} else {
		$rows = $this->get_rows_by_gmap_latest(
			$limit, $offset );
	}

	return $rows ;
}

function get_rows_by_gmap_catid( $cat_id, $limit=0, $offset=0 )
{
	$catid_array = $this->_catlist_class->get_cat_parent_all_child_id_by_id( $cat_id ) ;

	return $this->get_rows_by_name_param_orderby( 
		'gmap_catid_array', $catid_array, $this->_ORDERBY_LATEST, $limit, $offset ) ;
}

function get_rows_by_gmap_latest( $limit=0, $offset=0, $key=false )
{
	return $this->get_rows_by_name_param_orderby( 
		'gmap_latest', null, $this->_ORDERBY_LATEST, $limit, $offset, $key ) ;
}

function get_rows_by_gmap_area( $id, $lat, $lon, $ns, $ew, $limit=0, $offset=0, $key=false )
{
	return $this->get_rows_by_name_param_orderby( 
		'gmap_area', array( $id, $lat, $lon, $ns, $ew ), $this->_ORDERBY_ASC, $limit, $offset, $key ) ;
}

function get_rows_by_name_param_orderby( $name, $param, $orderby, $limit=0, $offset=0, $key=false )
{
	$item_key = null;
	if ( $key ) {
		$item_key = 'item_id';
	}

	if ( $this->_cfg_perm_cat_read  == _C_WEBPHOTO_OPT_PERM_READ_ALL ) {
		return $this->_item_cat_handler->get_rows_item_by_name_param_orderby( 
			$name, $param, $orderby, $limit, $offset, $item_key );

	} else {
		return $this->_item_cat_handler->get_rows_item_cat_by_name_param_orderby( 
			$name, $param, 
			$this->_item_cat_handler->convert_item_field( $orderby ), 
			$limit, $offset, 
			$this->_item_cat_handler->convert_item_field( $item_key ) );
	}
}

//---------------------------------------------------------
// get id array
//---------------------------------------------------------
function get_id_array_by_catid_orderby( $param, $orderby, $limit=0, $offset=0 )
{
	return $this->_item_cat_handler->get_id_array_item_by_name_param_orderby(
		'catid', $param, $orderby, $limit, $offset ) ;
}

//---------------------------------------------------------
// tagcloud class
//---------------------------------------------------------
function build_tagcloud( $limit=0 )
{
	return $this->_tagcloud_class->build_tagcloud( $limit );
}

function build_tagcloud_by_rows( $rows )
{
	return $this->_tagcloud_class->build_tagcloud_by_rows( $rows );
}

function get_tag_rows( $limit=0, $offset=0 )
{
	return $this->_tagcloud_class->get_tag_rows( $limit, $offset );
}

function get_count_by_tag( $param )
{
	return $this->_tagcloud_class->get_item_count_by_tag( $param );
}

function get_rows_by_tag_orderby( $param, $orderby, $limit=0, $offset=0 )
{
	$rows   = null ;
	$id_arr = $this->_tagcloud_class->get_item_id_array_by_tag( 
		$param, $orderby, $limit, $offset );

	if ( is_array($id_arr) && count($id_arr) ) {
		$rows = $this->_item_handler->get_rows_from_id_array( $id_arr );
	}
	return $rows;
}

function get_first_row_by_tag_orderby( $param, $orderby, $limit=0, $offset=0 )
{
	$row    = null ;
	$id_arr = $this->_tagcloud_class->get_item_id_array_by_tag( 
		$param, $orderby, $limit, $offset );

	if ( isset( $id_arr[0] ) ) {
		$row = $this->_item_handler->get_row_by_id( $id_arr[0] );
	}
	return $row;
}

// --- class end ---
}

?>