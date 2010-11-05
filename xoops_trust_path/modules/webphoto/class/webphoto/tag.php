<?php
// $Id: tag.php,v 1.9 2010/11/05 17:00:04 ohwada Exp $

//=========================================================
// webphoto module
// 2010-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-03 K.OHWADA
// build_rows_for_rss()
// 2010-05-10 K.OHWADA
// build_total_for_detail()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_tag
//=========================================================
class webphoto_tag extends webphoto_base_this
{
	var $_tagcloud_class;

	var $_TAG_LIST_START = 0;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_tag( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_tagcloud_class 
		=& webphoto_inc_tagcloud::getSingleton( $dirname, $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_tag( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// list
//---------------------------------------------------------
function build_rows_for_list()
{
	$tag_list_limit  = $this->get_ini('tag_list_limit');
	$tag_photo_limit = $this->get_ini('tag_photo_limit');

	$tag_rows = $this->get_tag_rows( 
		$tag_list_limit, $this->_TAG_LIST_START );
	if ( !is_array($tag_rows) || !count($tag_rows) ) {
		return false;
	}

	$i   = 0;
	$arr = array();
	foreach ( $tag_rows as $row )
	{
		$tag_name  = $row['tag_name'];
		$total     = $row['photo_count'];

		$photo_row = $this->get_first_row_by_tag_orderby(
			$tag_name, $this->_PHOTO_LIST_UPDATE_ORDER, $this->_PHOTO_LIST_LIMIT );

		$arr[] = array( $tag_name, $tag_name, $total, $photo_row );

		$i ++;
		if ( $i > $tag_photo_limit ) {
			break;
		}
	}

	return $arr;
}

//---------------------------------------------------------
// detail
//---------------------------------------------------------
function build_total_for_detail( $tag_in )
{
	$tag_name = $this->decode_uri_str( $tag_in );

	$title = $this->build_title( $tag_name );
	$total = $this->get_count_by_tag( $tag_name );

	return array( $tag_name, $title, $total );
}

function build_rows_for_detail( $tag_name, $orderby, $limit=0, $start=0 )
{
	return $this->get_rows_by_tag_orderby( 
		$tag_name, $orderby, $limit, $start );
}

function build_title( $tag_name )
{
	$str = $this->get_constant('TITLE_TAGS') .' : '. $tag_name ;
	return $str;
}

//---------------------------------------------------------
// rss
//---------------------------------------------------------
function build_rows_for_rss( $tag_in, $orderby, $limit=0, $start=0 )
{
	$tag_name = $this->decode_uri_str( $tag_in );

	return $this->build_rows_for_detail( 
		$tag_name, $orderby, $limit, $start );
}

//---------------------------------------------------------
// tagcloud class
//---------------------------------------------------------
function get_count_by_tag( $param )
{
	return $this->_tagcloud_class->get_item_count_by_tag( $param );
}

function get_tag_rows( $limit=0, $offset=0 )
{
	return $this->_tagcloud_class->get_tag_rows( $limit, $offset );
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

// --- class end ---
}

?>