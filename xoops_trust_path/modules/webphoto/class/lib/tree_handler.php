<?php
// $Id: tree_handler.php,v 1.3 2008/10/30 00:22:49 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-10-01 K.OHWADA
// used build_form_select_list()
// 2008-09-20 K.OHWADA
// Warning : Invalid argument supplied for foreach() 
// 2008-07-01 K.OHWADA
// change get_nice_path_from_id()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

include_once XOOPS_ROOT_PATH.'/class/xoopstree.php';

//=========================================================
// class webphoto_lib_tree_handler
//=========================================================
class webphoto_lib_tree_handler extends webphoto_lib_handler
{
	var $_xoops_tree_handler;

	var $_ORDER_DEFAULT = null;

	var $_PATH_SEPARATOR = ' : ' ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_tree_handler( $dirname )
{
	$this->webphoto_lib_handler( $dirname );
	$this->set_use_prefix( true );
}

function init_xoops_tree()
{
	$this->set_order_default( $this->_id_name );
	$this->_xoops_tree_handler = new XoopsTree( $this->_table, $this->_id_name , $this->_pid_name ) ;
}

function set_order_default( $val )
{
	$this->_ORDER_DEFAULT = $val;
}

//---------------------------------------------------------
// base on XoopsTree::getNicePathFromId 
//---------------------------------------------------------
function get_nice_path_from_id( $sel_id, $title_name, $func_url, $flag_short=false )
{
	$rows = $this->get_parent_path_array( $sel_id );
	if ( !is_array($rows)  || !count($rows) ) {
		return '';
	}

	$path  = '';
	$start = count($rows) - 1;

	for ( $i=$start; $i >= 0; $i-- )
	{
		$row   = $rows[$i];
		$id    = $row[ $this->_id_name ];
		$pid   = $row[ $this->_pid_name ];
		$title = $row[ $title_name ];
		
		if ( $flag_short ) {
			$url = $func_url . $id ;
		} else {
			$url = $func_url .'&amp;'. $this->_id_name .'='. $id ;
		}

		$path .= '<a href="'. $url .'">';
		$path .= $this->sanitize($title);
		$path .= '</a>';
		$path .= $this->_PATH_SEPARATOR ;
	}

	return $path;
}

// recursible function
function get_parent_path_array( $sel_id, $path_array=array() )
{
	$row = $this->get_cached_row_by_id( $sel_id );
	if ( !is_array($row) ) {
		return $path_array;
	}

	$path_array[] = $row;
	$pid = $row[ $this->_pid_name ];

// reached top 
	if ( $pid == 0 ) {
		return $path_array;
	}

// recursible call
	$path_array = $this->get_parent_path_array( $pid , $path_array );
	return $path_array;
}

function set_path_separator( $val )
{
	$this->_PATH_SEPARATOR = $val;
}

//---------------------------------------------------------
// base on XoopsTree::makeMySelBox 
//---------------------------------------------------------
function make_my_sel_box( $title_name, $order='', $preset_id=0, $none=0, $sel_name='', $onchange='' )
{
	return $this->build_sel_box(
		$this->get_all_tree_array( $order ), 
		$title_name, $preset_id, $none, $sel_name, $onchange );
}

function build_sel_box( $tree, $title_name, $preset_id=0, $none=0, $sel_name='', $onchange='' )
{
	return $this->build_form_select_list( $tree, $title_name, $preset_id, $none, $sel_name, $onchange );
}

function get_all_tree_array( $order='', $flag_perm=false )
{
	if ( empty($order) ) {
		$order = $this->_ORDER_DEFAULT;
	}

	$pid_rows = $this->get_rows_by_pid_order_with_perm( 0, $order, $flag_perm );
	if ( !is_array($pid_rows) ) {
		return false;
	}

	$tree = array();
	foreach ( $pid_rows as $row )
	{
		$catid = $row[ $this->_id_name ];
		$row['prefix'] = '';

		$tree[] = $row;

		$child_arr = $this->get_child_tree_array( $catid, $order, array(), '', $flag_perm );
		foreach ( $child_arr as $child ) {
			$tree[] = $child;
		}
	}

	return $tree;
}

//---------------------------------------------------------
// base on XoopsTree::getChildTreeArray 
//---------------------------------------------------------
// recursible function
function get_child_tree_array( $sel_id=0, $order='', $parray=array(), $r_prefix='', $flag_perm=false )
{
	$rows  = $this->get_rows_by_pid_order_with_perm( $sel_id, $order, $flag_perm );
	if ( !is_array($rows) || !count($rows) ) {
		return $parray;
	}

	foreach ( $rows as $row ) 
	{
// add dot
		$row['prefix'] = $r_prefix . $this->_PREFIX_MARK;

		array_push( $parray, $row );

// recursible call
		$new_sel_id = $row[ $this->_id_name ];
		$parray = $this->get_child_tree_array( $new_sel_id, $order, $parray, $row['prefix'], $flag_perm ) ;
	}

	return $parray;
}

function get_rows_by_pid_order_with_perm( $pid, $order='', $flag_perm=false, $limit=0, $offset=0 )
{
	$rows = $this->get_rows_by_pid_order( $pid, $order, $limit, $offset );
	if ( !is_array($rows) || !count($rows) ) {
		return false;
	}

	if ( $flag_perm ) {
		return $this->build_rows_with_perm( $rows );
	}

	return $rows;
}

function get_rows_by_pid_order( $pid, $order='', $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '. $this->_table;
	$sql .= ' WHERE '.$this->_pid_name.'='.$pid;
	if ( $order != '' ) {
		$sql .= ' ORDER BY '.$order;
	}
	return $this->get_rows_by_sql( $sql, $limit, $offset ) ;
}

//---------------------------------------------------------
// dummy for overwrite
//---------------------------------------------------------
function build_rows_with_perm( $rows )
{
	return $rows;
}

//---------------------------------------------------------
// tree handler
//---------------------------------------------------------
function get_all_child_id( $sel_id=0, $order="", $parray = array() )
{
	return $this->_xoops_tree_handler->getAllChildId( $sel_id, $order, $parray );
}

function get_first_child( $sel_id, $order="" )
{
	return $this->_xoops_tree_handler->getFirstChild( $sel_id, $order ) ;
}

function get_first_child_id( $sel_id )
{
	return $this->_xoops_tree_handler->getFirstChildId( $sel_id ) ;
}

// --- class end ---
}

?>