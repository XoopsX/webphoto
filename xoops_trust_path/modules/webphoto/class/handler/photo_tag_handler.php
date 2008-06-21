<?php
// $Id: photo_tag_handler.php,v 1.1 2008/06/21 12:22:25 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_photo_tag_handler
//=========================================================
class webphoto_photo_tag_handler extends webphoto_lib_handler
{
	var $_photo_table;
	var $_tag_table;
	var $_p2t_table;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_photo_tag_handler( $dirname )
{
	$this->webphoto_lib_handler( $dirname );
	$this->_photo_table = $this->prefix_dirname( 'photo' );
	$this->_tag_table   = $this->prefix_dirname( 'tag' );
	$this->_p2t_table   = $this->prefix_dirname( 'p2t' );

	$constpref = strtoupper( '_P_' . $dirname. '_' ) ;
	$this->set_debug_sql_by_const_name(   $constpref.'DEBUG_SQL' );
	$this->set_debug_error_by_const_name( $constpref.'DEBUG_ERROR' );

}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_photo_tag_handler( $dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// count
//---------------------------------------------------------
function get_photo_count_public_by_tag( $tag_name, $limit=0, $offset=0 )
{
	$where  = 'p.photo_status > 0';
	$where .= ' AND t.tag_name='.$this->quote($tag_name);
	return $this->get_photo_count_by_where( $where );
}

function get_photo_count_by_where( $where )
{
	$sql  = 'SELECT COUNT(DISTINCT p.photo_id) ';
	$sql .= ' FROM '. $this->_p2t_table .' p2t ';
	$sql .= ' INNER JOIN '. $this->_photo_table .' p ';
	$sql .= ' ON p.photo_id = p2t.p2t_photo_id ';
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
	$sql  = 'SELECT DISTINCT p.photo_id ';
	$sql .= ' FROM '. $this->_p2t_table .' p2t ';
	$sql .= ' INNER JOIN '. $this->_photo_table .' p ';
	$sql .= ' ON p.photo_id = p2t.p2t_photo_id ';
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
	$where  = 'p.photo_status > 0';
	$where .= ' AND t.tag_name='.$this->quote($tag_name);
	$orderby = 'p.photo_time_update DESC, p.photo_id DESC';

	return $this->get_photo_id_array_by_where_orderby( $where, $orderby, $limit, $offset );
}

function get_photo_id_array_public_latest_by_tag_orderby( $tag_name, $orderby, $limit=0, $offset=0 )
{
	$where  = 'p.photo_status > 0';
	$where .= ' AND t.tag_name='.$this->quote($tag_name);
	return $this->get_photo_id_array_by_where_orderby( $where, $orderby, $limit, $offset );
}

function get_photo_id_array_by_where_orderby( $where, $orderby, $limit=0, $offset=0 )
{
	$sql  = 'SELECT DISTINCT p.photo_id ';
	$sql .= ' FROM '. $this->_p2t_table .' p2t ';
	$sql .= ' INNER JOIN '. $this->_photo_table .' p ';
	$sql .= ' ON p.photo_id = p2t.p2t_photo_id ';
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
	$sql .= ' INNER JOIN '. $this->_photo_table .' p ';
	$sql .= ' ON p.photo_id = p2t.p2t_photo_id ';
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