<?php
// $Id: catlist.php,v 1.1 2008/11/30 10:37:07 ohwada Exp $

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

	var $_DIRNAME ;
	var $_MODULE_URL ;
	var $_MODULE_DIR ;
	var $_CATS_URL;

	var $_CAT_ORDER = 'cat_weight ASC, cat_title ASC';

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
		$this->_table_cat, 'cat_id', 'cat_pid' ) ;
}

function set_uploads_path( $path )
{
	$this->_CATS_URL = XOOPS_URL . $path .'/categories' ;
}

//---------------------------------------------------------
// public
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
	$catlist = array() ;

	$rows = $this->get_cat_rows_by_pid( $parent_id );
	if ( !is_array($rows) || !count($rows) ) {
		return array();
	}

	foreach( $rows as $row )
	{
		$cat_id = $row['cat_id'];

		$arr = $this->build_cat_show( $row );
		$arr['photo_small_sum'] 
			= $this->get_item_count_public_by_catid( $cat_id ) ;
		$arr['photo_total_sum'] 
			= $this->build_photo_total_in_parent_all_children( $cat_id ) ;
		$arr['subcategories'] 
			= $this->build_subcat( $cat_id, $flag_sub ) ;

		$catlist[] = $arr;
	}

	return $catlist ;
}

function build_cat_show( $cat_row )
{
	$img_name = $cat_row['cat_img_name'] ;
	if ( $img_name ) {
		$url = $this->_CATS_URL .'/'. $img_name ;
	} else {
		$url = $this->build_cat_img_path( $cat_row );
	}

	$show = $cat_row;
	$show['cat_title_s'] = $this->sanitize( $cat_row['cat_title'] ) ;
	$show['imgurl']      = $url ;
	$show['imgurl_s']    = $this->sanitize( $url ) ;

	return $show;
}

//---------------------------------------------------------
// private
//---------------------------------------------------------
function build_subcat( $parent_id, $flag_shub )
{
	$subcat = array() ;

	if ( ! $flag_shub ) {
		return array();
	}

	$rows = $this->get_cat_first_child( $parent_id ) ;
	if ( !is_array($rows) || !count($rows) ) {
		return array();
	}

	foreach( $rows as $row ) 
	{
		$cat_id = $row['cat_id'] ;

		$arr = $this->build_cat_show( $row );
		$arr['photo_small_sum']  
			= $this->get_item_count_public_by_catid( $cat_id ) ;
		$arr['photo_total_sum'] 
			= $this->build_photo_total_in_parent_all_children( $cat_id ) ;
		$arr['number_of_subcat'] 
			= count( $this->get_cat_first_child_id( $cat_id ) ) ;

		$subcat[] = $arr;
	}

	return $subcat;
}

function build_photo_total_in_parent_all_children( $cat_id )
{
	$catid_arr = $this->get_cat_all_child_id( $cat_id ) ;
	array_push( $catid_arr , $cat_id ) ;
	return $this->get_item_count_public_by_catid_array( $catid_arr ) ;
}

//---------------------------------------------------------
// cat handler
//---------------------------------------------------------
function get_cat_first_child( $sel_id )
{
	return $this->_xoops_tree_handler->getFirstChild( $sel_id, $this->_CAT_ORDER ) ;
}

function get_cat_first_child_id( $sel_id )
{
	return $this->_xoops_tree_handler->getFirstChildId( $sel_id ) ;
}

function get_cat_all_child_id( $sel_id=0, $order="", $parray = array() )
{
	return $this->_xoops_tree_handler->getAllChildId( $sel_id, $order, $parray );
}

function get_cat_rows_by_pid( $pid, $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '. $this->_table_cat ;
	$sql .= ' WHERE cat_pid='.$pid;
	$sql .= ' ORDER BY '. $this->_CAT_ORDER;
	return $this->get_rows_by_sql( $sql, $limit, $offset ) ;
}

function build_cat_img_path( $cat_row )
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

function check_http_null( $str )
{
	if ( ($str == '') || ($str == 'http://') || ($str == 'https://') ) {
		return true;
	}
	return false;
}

function check_http_start( $str )
{
	if ( preg_match("|^https?://|", $str) ) {
		return true;	// include HTTP
	}
	return false;
}

function add_slash_to_head( $str )
{
// ord : the ASCII value of the first character of string
// 0x2f slash

	if( ord( $str ) != 0x2f ) {
		$str = "/". $str;
	}
	return $str;
}

//---------------------------------------------------------
// item handler
//---------------------------------------------------------
function get_item_count_public_by_catid( $cat_id )
{
	$where  = ' item_status > 0 ';
	$where .= ' AND item_cat_id='.intval($cat_id);

	return $this->get_item_count_by_where( $where ) ;
}

function get_item_count_public_by_catid_array( $catid_array )
{
	$where  = ' item_status > 0 ';
	$where .= ' AND item_cat_id IN ( ' ;
	foreach( $catid_array as $id ) {
		$where .= intval($id) .', ';
	}

// 0 means to belong no category
	$where .= ' 0 )';

	return $this->get_item_count_by_where( $where ) ;
}

function get_item_count_by_where( $where )
{
	$sql = 'SELECT COUNT(*) FROM '. $this->_table_item ;
	$sql .= ' WHERE '. $where;
	return $this->get_count_by_sql( $sql );
}

// --- class end ---
}

?>