<?php
// $Id: catlist.php,v 1.2 2008/12/18 13:23:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-29 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_catlist
//=========================================================
class webphoto_inc_catlist extends webphoto_inc_handler
{
	var $_xoops_tree_handler;
	var $_table_cat ;
	var $_table_item ;

	var $_cfg_perm_cat_read  = 0 ;
	var $_cfg_perm_item_read = 0 ;

	var $_DIRNAME ;
	var $_MODULE_URL ;
	var $_MODULE_DIR ;
	var $_CATS_URL;

	var $_CAT_ORDER   = 'cat_weight ASC, cat_title ASC, cat_id ASC';
	var $_PREFIX_NAME = 'prefix' ;
	var $_PREFIX_MARK = '.' ;
	var $_PREFIX_BAR  = '--' ;

	var $_CAT_ID_NAME = 'cat_id';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_catlist()
{
	$this->webphoto_inc_handler();
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_inc_catlist();
	}
	return $instance;
}

//---------------------------------------------------------
// init
//---------------------------------------------------------
function init( $dirname )
{
	$this->_DIRNAME    = $dirname;
	$this->_MODULE_URL = XOOPS_URL       .'/modules/'.$dirname;
	$this->_MODULE_DIR = XOOPS_ROOT_PATH .'/modules/'.$dirname;

	$this->_table_cat  = $this->prefix_dirname( 'cat' ) ;
	$this->_table_item = $this->prefix_dirname( 'item' ) ;

	$this->_xoops_tree_handler = new XoopsTree( 
		$this->_table_cat, $this->_CAT_ID_NAME, 'cat_pid' ) ;
}

function set_uploads_path( $path )
{
	$this->_CATS_URL = XOOPS_URL . $path .'/categories' ;
}

function set_perm_cat_read( $val )
{
	$this->_cfg_perm_cat_read = intval($val) ;
}

function set_perm_item_read( $val )
{
	$this->_cfg_perm_item_read = intval($val) ;
}

//---------------------------------------------------------
// cat list
//---------------------------------------------------------
function calc_width( $cols )
{
	if ( $cols <= 0 ) {
		$cols = 1 ;
	}

	$width = intval( 100 / $cols ) - 1;
	if ( $width <= 0 ) {
		 $width = 1;
	}

	return array( $cols, $width );
}

function build_catlist( $parent_id, $flag_sub )
{
	$name_perm = '';
	if ( $this->_cfg_perm_cat_read == _C_WEBPHOTO_OPT_PERM_READ_NO_CAT ) {
		$name_perm = 'cat_perm_read';
	}

	$catlist = array() ;

	$rows = $this->_get_cat_rows_by_pid_order_perm( 
		$parent_id, $this->_CAT_ORDER, $name_perm  );

	if ( !is_array($rows) || !count($rows) ) {
		return array();
	}

	foreach( $rows as $row )
	{
		$arr = $this->build_cat_show( $row );

		$arr['photo_small_sum'] 
			= $this->_get_photo_count_by_cat_row( $row ) ;

		$arr['photo_total_sum'] 
			= $this->_get_photo_count_in_parent_all_children( $row );

		$arr['subcategories'] 
			= $this->_build_subcat( $row, $flag_sub ) ;

		$catlist[] = $arr;
	}

	return $catlist ;
}

function _build_subcat( $cat_row, $flag_shub )
{
	$subcat = array() ;

	if ( ! $flag_shub ) {
		return array();
	}

	$rows = $this->_get_cat_first_child_rows_perm( $cat_row ) ;
	if ( !is_array($rows) || !count($rows) ) {
		return array();
	}

	foreach( $rows as $row ) 
	{
		$arr = $this->build_cat_show( $row );

		$arr['photo_small_sum']  
			= $this->_get_photo_count_by_cat_row( $row ) ;

		$arr['photo_total_sum'] 
			= $this->_get_photo_count_in_parent_all_children( $row );

		$arr['number_of_subcat'] 
			= $this->_get_cat_count_first_child_perm( $row ) ;

		$subcat[] = $arr;
	}

	return $subcat;
}

// show main
function build_cat_show( $cat_row )
{
	$img_name = $cat_row['cat_img_name'] ;
	if ( $img_name ) {
		$url = $this->_CATS_URL .'/'. $img_name ;
	} else {
		$url = $this->_build_cat_img_path( $cat_row );
	}

	$show = $cat_row;
	$show['cat_title_s'] = $this->sanitize( $cat_row['cat_title'] ) ;
	$show['imgurl']      = $url ;
	$show['imgurl_s']    = $this->sanitize( $url ) ;

	return $show;
}

function _build_cat_img_path( $cat_row )
{
	$img_path = $cat_row['cat_img_path'] ;
	if ( $this->check_http_null( $img_path ) ) {
		$url = '' ;
	} elseif ( $this->check_http_start( $img_path ) ) {
		$url = $img_path;
	} else {
		$url = XOOPS_URL . $this->add_slash_to_head( $img_path );
	}
	return $url;
}

function _get_photo_count_in_parent_all_children( $cat_row )
{
	$id_arr = $this->get_cat_parent_all_child_id_by_row( $cat_row );
	return $this->_get_item_count_by_catid_array( $id_arr ) ;
}

function _get_photo_count_by_cat_row( $cat_row )
{
	if ( $this->_cfg_perm_cat_read != _C_WEBPHOTO_OPT_PERM_READ_ALL ) {
		if ( ! $this->_check_cat_perm_by_cat_row( $cat_row ) ) {
			return 0 ;
		}
	}

	return $this->_get_item_count_by_catid( $cat_row[ $this->_CAT_ID_NAME ] ) ;
}

//---------------------------------------------------------
// cat tree
//---------------------------------------------------------
function get_cat_all_tree_array()
{
	$name_perm = '';
	if ( $this->_cfg_perm_cat_read == _C_WEBPHOTO_OPT_PERM_READ_NO_CAT ) {
		$name_perm = 'cat_perm_read';
	}

	return $this->get_cat_all_tree_array_perm( 
		$this->_CAT_ORDER, $name_perm ) ;
}

function get_cat_parent_all_child_id_by_id( $cat_id )
{
	$cat_row = $this->_get_cat_row_by_id( $cat_id ) ;
	return $this->get_cat_parent_all_child_id_by_row( $cat_row );
}

function get_cat_parent_all_child_id_by_row( $cat_row )
{
	$cat_id = $cat_row[ $this->_CAT_ID_NAME ] ;

	$name_perm = '';
	if ( $this->_cfg_perm_cat_read == _C_WEBPHOTO_OPT_PERM_READ_NO_CAT ) {
		$name_perm = 'cat_perm_read';
	}

	$tree_arr = $this->_get_cat_child_tree_array_recusible( 
		$cat_id, $this->_CAT_ORDER, $name_perm );

	array_push( $tree_arr, $cat_row ) ;

	$id_arr = array();

	if ( is_array($tree_arr) && count($tree_arr) ) {
		foreach( $tree_arr as $row )
		{
			if (( $this->_cfg_perm_cat_read == _C_WEBPHOTO_OPT_PERM_READ_ALL ) ||
			    ( $this->_check_cat_perm_by_cat_row( $row ) )) {

				$id_arr[] = $row[ $this->_CAT_ID_NAME ] ;
			}
		}
	}

	return $id_arr ;
}

function get_cat_all_child_tree_array( $cat_id=0 )
{
	$name_perm = '';
	if ( $this->_cfg_perm_cat_read == _C_WEBPHOTO_OPT_PERM_READ_NO_CAT ) {
		$name_perm = 'cat_perm_read';
	}

	return $this->_get_cat_child_tree_array_recusible( 
		$cat_id, $this->_CAT_ORDER, $name_perm );
}

// XoopsTree::makeMySelBox
function get_cat_all_tree_array_perm( $order, $name_perm=null )
{
	$pid_rows = $this->_get_cat_rows_by_pid_order_perm( 0, $order, $name_perm );
	if ( !is_array($pid_rows) ) {
		return false;
	}

	$tree = array();
	foreach ( $pid_rows as $row )
	{
		$catid = $row[ $this->_CAT_ID_NAME ];
		$row[ $this->_PREFIX_NAME ] = '';

		$tree[] = $row;

		$child_arr = $this->_get_cat_child_tree_array_recusible( $catid, $order, $name_perm );
		foreach ( $child_arr as $child ) {
			$tree[] = $child;
		}
	}

	return $tree;
}

//---------------------------------------------------------
// cat handler
//---------------------------------------------------------
function get_cat_row_by_catid_perm( $cat_id )
{
	$cat_row = $this->_get_cat_row_by_id( $cat_id ) ;
	if ( !is_array($cat_row) ) {
		return false ;
	}
	if ( ! $this->_check_cat_perm_by_cat_row( $cat_row ) ) {
		return false;
	}
	return $cat_row ;
}

function check_cat_perm_by_catid( $cat_id )
{
	$cat_row = $this->_get_cat_row_by_id( $cat_id ) ;
	if ( is_array($cat_row) ) {
		return $this->_check_cat_perm_by_cat_row( $cat_row );
	}
	return false;
}

function _check_cat_perm_by_cat_row( $cat_row, $name_perm='cat_perm_read' )
{
	return $this->check_perm_by_row_name_groups( $cat_row, $name_perm ) ;

}

// recursible function
// XoopsTree::getChildTreeArray
function _get_cat_child_tree_array_recusible( 
	$sel_id, $order, $name_perm=null, $parray=array(), $r_prefix='' )
{
	$rows  = $this->_get_cat_rows_by_pid_order_perm( $sel_id, $order, $name_perm );
	if ( !is_array($rows) || !count($rows) ) {
		return $parray;
	}

	foreach ( $rows as $row ) 
	{
// add mark
		$new_r_prefix = $r_prefix . $this->_PREFIX_MARK ;
		$row[ $this->_PREFIX_NAME ] = $new_r_prefix ;

		array_push( $parray, $row );

// recursible call
		$new_sel_id = $row[ $this->_CAT_ID_NAME ];
		$parray = $this->_get_cat_child_tree_array_recusible( 
			$new_sel_id, $order, $name_perm, $parray, $new_r_prefix ) ;
	}

	return $parray;
}

function _get_cat_rows_by_pid_order_perm( $pid, $order, $name_perm=null, $limit=0, $offset=0 )
{
	$rows = $this->_get_cat_rows_by_pid_order( $pid, $order, $limit, $offset );
	if ( !is_array($rows) || !count($rows) ) {
		return false;
	}

	if ( $name_perm ) {
		$arr = array();
		foreach ( $rows as $row ) 
		{
			if ( $this->_check_cat_perm_by_cat_row( $row, $name_perm ) ) {
				$arr[] = $row ;
			}
		}

	} else {
		$arr = $rows;
	}

	return $arr ;
}

function _get_cat_rows_by_pid_order( $pid, $order, $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '. $this->_table_cat ;
	$sql .= ' WHERE cat_pid='. $pid;
	$sql .= ' ORDER BY '.$order;

	return $this->get_rows_by_sql( $sql, $limit, $offset ) ;
}

function _get_cat_first_child_rows_perm( $cat_row )
{
	$rows = $this->_xoops_tree_handler->getFirstChild( 
		$cat_row[$this->_CAT_ID_NAME], $this->_CAT_ORDER ) ;

	if ( !is_array($rows) || !count($rows) ) {
		return array();
	}

	if ( $this->_cfg_perm_cat_read == _C_WEBPHOTO_OPT_PERM_READ_NO_CAT ) {
		$arr = array();
		foreach( $rows as $row ) 
		{
			if ( $this->_check_cat_perm_by_cat_row( $row ) ) {
				$arr[] = $row ;
			}
		}
	} else {
		$arr = $rows ;
	}

	return $rows;
}

function _get_cat_count_first_child_perm( $cat_row )
{
	$rows = $this->_get_cat_first_child_rows_perm( $cat_row );
	if ( is_array($rows) ) {
		return count($rows);
	} else {
		return 0;
	}
}

function _get_cat_row_by_id( $id )
{
	$sql  = 'SELECT * FROM '. $this->_table_cat ;
	$sql .= ' WHERE cat_id='.$id;
	return $this->get_row_by_sql( $sql ) ;
}

//---------------------------------------------------------
// item handler
//---------------------------------------------------------
function _get_item_count_by_catid( $cat_id )
{
	$where  = $this->build_where_public_with_item();
	$where .= ' AND item_cat_id='.intval( $cat_id );

	return $this->get_item_count_by_where( $where ) ;
}

function _get_item_count_by_catid_array( $catid_array )
{
	$where  = $this->build_where_public_with_item();

	$where .= ' AND item_cat_id IN ( ' ;
	foreach( $catid_array as $id ) {
		$where .= intval($id) .', ';
	}

// 0 means to belong no category
	$where .= ' 0 )';

	return $this->get_item_count_by_where( $where ) ;
}

// --- class end ---
}

?>