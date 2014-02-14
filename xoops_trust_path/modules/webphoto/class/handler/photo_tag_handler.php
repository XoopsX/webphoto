<?php
// $Id: photo_tag_handler.php,v 1.3 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// webphoto_lib_handler -> webphoto_handler_base_ini
// 2008-08-24 K.OHWADA
// photo_table -> item_table
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_photo_tag_handler
//=========================================================
class webphoto_photo_tag_handler extends webphoto_handler_base_ini
{
	var $_item_table;
	var $_tag_table;
	var $_p2t_table;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_photo_tag_handler( $dirname, $trust_dirname )
{
	$this->webphoto_handler_base_ini( $dirname, $trust_dirname );
	$this->_item_table = $this->prefix_dirname( 'item' );
	$this->_tag_table  = $this->prefix_dirname( 'tag' );
	$this->_p2t_table  = $this->prefix_dirname( 'p2t' );

}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_photo_tag_handler( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// count
//---------------------------------------------------------
function get_photo_count_public_by_tag( $tag_name, $limit=0, $offset=0 )
{
	$where  = 'i.item_status > 0';
	$where .= ' AND t.tag_name='.$this->quote($tag_name);
	return $this->get_photo_count_by_where( $where );
}

function get_photo_count_by_where( $where )
{
	$sql  = 'SELECT COUNT(DISTINCT i.item_id) ';
	$sql .= ' FROM '. $this->_p2t_table .' p2t ';
	$sql .= ' INNER JOIN '. $this->_item_table .' i ';
	$sql .= ' ON i.item_id = p2t.p2t_photo_id ';
	$sql .= ' INNER JOIN '. $this->_tag_table .' t ';
	$sql .= ' ON t.tag_id = p2t.p2t_tag_id ';
	$sql .= ' WHERE '. $where;
	return $this->get_count_by_sql( $sql );
}

//---------------------------------------------------------
// rows
//---------------------------------------------------------
function get_photo_rows_by_where_orderby( $where, $orderby, $limit=0, $offset=0 )
{
	$sql  = 'SELECT DISTINCT i.item_id ';
	$sql .= ' FROM '. $this->_p2t_table .' p2t ';
	$sql .= ' INNER JOIN '. $this->_item_table .' i ';
	$sql .= ' ON i.item_id = p2t.p2t_photo_id ';
	$sql .= ' INNER JOIN '. $this->_tag_table .' t ';
	$sql .= ' ON t.tag_id = p2t.p2t_tag_id ';
	$sql .= ' WHERE '. $where;
	$sql .= ' ORDER BY '. $orderby;

	return $this->get_first_rows_by_sql( $sql, $limit, $offset );
}

function get_tag_rows_with_count( $key='tag_id', $limit=0, $offset=0 )
{
	$sql  = 'SELECT t.*, COUNT(*) AS photo_count ';
	$sql .= ' FROM '. $this->_tag_table.' t, ';
	$sql .= $this->_p2t_table .' p2t ';
	$sql .= ' WHERE t.tag_id = p2t.p2t_tag_id ';
	$sql .= ' GROUP BY tag_id ';
	$sql .= ' ORDER BY photo_count DESC';
	return $this->get_rows_by_sql( $sql, $limit, $offset, $key );
}

function __get_tag_rows_with_count( $key='tag_id', $limit=0, $offset=0 )
{
	$sql  = 'SELECT t.*, COUNT(*) AS photo_count ';
	$sql .= ' FROM '. $this->_tag_table.' t ';
	$sql .= ' LEFT JOIN '. $this->_p2t_table .' p2t ';
	$sql .= ' ON t.tag_id = p2t.p2t_tag_id ';
	$sql .= ' GROUP BY tag_id ';
	$sql .= ' ORDER BY photo_count DESC';
	return $this->get_rows_by_sql( $sql, $limit, $offset, $key );
}

//---------------------------------------------------------
// id array
//---------------------------------------------------------
function get_photo_id_array_public_latest_by_tag( $tag_name, $limit=0, $offset=0 )
{
	$where  = 'i.item_status > 0';
	$where .= ' AND t.tag_name='.$this->quote($tag_name);
	$orderby = 'i.item_time_update DESC, i.item_id DESC';

	return $this->get_photo_id_array_by_where_orderby( $where, $orderby, $limit, $offset );
}

function get_photo_id_array_public_latest_by_tag_orderby( $tag_name, $orderby, $limit=0, $offset=0 )
{
	$where  = 'i.item_status > 0';
	$where .= ' AND t.tag_name='.$this->quote($tag_name);
	return $this->get_photo_id_array_by_where_orderby( $where, $orderby, $limit, $offset );
}

function get_photo_id_array_by_where_orderby( $where, $orderby, $limit=0, $offset=0 )
{
	$sql  = 'SELECT DISTINCT i.item_id ';
	$sql .= ' FROM '. $this->_p2t_table .' p2t ';
	$sql .= ' INNER JOIN '. $this->_item_table .' i ';
	$sql .= ' ON i.item_id = p2t.p2t_photo_id ';
	$sql .= ' INNER JOIN '. $this->_tag_table .' t ';
	$sql .= ' ON t.tag_id = p2t.p2t_tag_id ';
	$sql .= ' WHERE '. $where;
	$sql .= ' ORDER BY '. $orderby;

	return $this->get_first_rows_by_sql( $sql, $limit, $offset );
}

function get_tag_id_array_by_where_orderby( $where, $orderby, $limit=0, $offset=0 )
{
	$sql  = 'SELECT DISTINCT t.tag_id ';
	$sql .= ' FROM '. $this->_p2t_table .' p2t ';
	$sql .= ' INNER JOIN '. $this->_item_table .' i ';
	$sql .= ' ON i.item_id = p2t.p2t_photo_id ';
	$sql .= ' INNER JOIN '. $this->_tag_table .' t ';
	$sql .= ' ON t.tag_id = p2t.p2t_tag_id ';
	$sql .= ' WHERE '. $where;
	$sql .= ' ORDER BY '. $orderby;

	return $this->get_first_rows_by_sql( $sql, $limit, $offset );
}

function get_tag_id_array_null($limit=0, $offset=0 )
{
	$sql = 'SELECT DISTINCT t.tag_id ';
	$sql .= ' FROM '. $this->_tag_table .' t ';
	$sql .= ' LEFT JOIN '. $this->_p2t_table .' p2t ';
	$sql .= ' ON t.tag_id = p2t.p2t_tag_id ';
	$sql .= ' WHERE p2t.pt2_tag_id IS NULL';
	$sql .= ' ORDER t.tag_id ASC';

	return $this->get_first_rows_by_sql( $sql, $limit, $offset );
}

// --- class end ---
}

?>