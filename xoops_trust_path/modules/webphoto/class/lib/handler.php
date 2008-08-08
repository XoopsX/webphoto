<?php
// $Id: handler.php,v 1.2 2008/08/08 04:36:09 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-01 K.OHWADA
// added force in query()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_lib_handler
//=========================================================
class webphoto_lib_handler extends webphoto_lib_error
{
	var $_DIRNAME;

	var $_db;
	var $_table;
	var $_id_name;
	var $_pid_name;

	var $_id          = 0;
	var $_xoops_uid   = 0;
	var $_cached      = array();
	var $_flag_cached = false;

	var $_DEBUG_SQL   = false;
	var $_DEBUG_ERROR = false;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_handler( $dirname=null )
{
	$this->webphoto_lib_error();
	$this->_db =& Database::getInstance();

	$this->_DIRNAME = $dirname;
}

function set_table_prefix_dirname( $name )
{
	$this->set_table( $this->prefix_dirname( $name ) );
}

function set_table_prefix( $name )
{
	$this->set_table( $this->db_prefix( $name ) );
}

function set_table( $val )
{
	$this->_table = $val;
}

function get_table()
{
	return $this->_table;
}

function set_id_name( $val )
{
	$this->_id_name = $val;
}

function get_id_name()
{
	return $this->_id_name;
}

function set_pid_name( $val )
{
	$this->_pid_name = $val;
}

function get_pid_name()
{
	return $this->_pid_name;
}

function get_id()
{
	return $this->_id;
}

function prefix_dirname( $name )
{
	return $this->db_prefix( $this->_DIRNAME .'_'. $name );
}

function db_prefix( $name )
{
	return $this->_db->prefix( $name );
}

function set_debug_sql_by_const_name( $name )
{
	$name = strtoupper( $name );
	if ( defined($name) ) {
		$this->set_debug_sql( constant($name) );
	}
}

function set_debug_error_by_const_name( $name )
{
	$name = strtoupper( $name );
	if ( defined($name) ) {
		$this->set_debug_error( constant($name) );
	}
}

function set_debug_sql( $val )
{
	$this->_DEBUG_SQL = (bool)$val;
}

function set_debug_error( $val )
{
	$this->_DEBUG_ERROR = (bool)$val;
}

//---------------------------------------------------------
// insert
//---------------------------------------------------------
function insert( &$row )
{
	// dummy
}

//---------------------------------------------------------
// update
//---------------------------------------------------------
function update( &$row )
{
	// dummy
}

//---------------------------------------------------------
// delete
//---------------------------------------------------------
function delete( $row, $force=false )
{
	return $this->delete_by_id( $this->get_id_from_row( $row ), $force );
}

function delete_by_id( $id, $force=false )
{
	$sql  = 'DELETE FROM '. $this->_table;
	$sql .= ' WHERE '. $this->_id_name .'='. intval($id);
	return $this->query( $sql, 0, 0, $force );
}

function delete_by_id_array( $id_array )
{
	if ( !is_array($id_array) || !count($id_array) ) {
		return true;	// no action
	}

	$in   = implode( ',', $id_array );
	$sql  = 'DELETE FROM '. $this->_table;
	$sql .= ' WHERE '. $this->_id_name .' IN ('. $in .')';
	return $this->query( $sql );
}

function get_id_from_row( $row )
{
	if ( isset( $row[ $this->_id_name ] ) ) {
		$this->_id = $row[ $this->_id_name ];
		return $this->_id;
	}
	return null;
}

function truncate_table()
{
	$sql = 'TRUNCATE TABLE '.$this->_table;
	return $this->query( $sql );
}

//---------------------------------------------------------
// count
//---------------------------------------------------------
function exists_record()
{
	if ( $this->get_count_all() > 0 ) {
		return true;
	}
	return false;
}

function get_count_all()
{
	$sql  = 'SELECT COUNT(*) FROM '.$this->_table;
	return $this->get_count_by_sql( $sql );
}

function get_count_by_where( $where )
{
	$sql = 'SELECT COUNT(*) FROM '.$this->_table;
	$sql .= ' WHERE '. $where;
	return $this->get_count_by_sql( $sql );
}

//---------------------------------------------------------
// row
//---------------------------------------------------------
function get_row_by_id( $id )
{
	$sql  = 'SELECT * FROM '.$this->_table;
	$sql .= ' WHERE '. $this->_id_name .'='. intval($id);
	return $this->get_row_by_sql( $sql );
}

//---------------------------------------------------------
// rows
//---------------------------------------------------------
function get_rows_all_asc( $limit=0, $offset=0, $key=null )
{
	$sql  = 'SELECT * FROM '.$this->_table;
	$sql .= ' ORDER BY '. $this->_id_name .' ASC';
	return $this->get_rows_by_sql( $sql, $limit, $offset, $key );
}

function get_rows_all_desc( $limit=0, $offset=0, $key=null )
{
	$sql  = 'SELECT * FROM '.$this->_table;
	$sql .= ' ORDER BY '. $this->_id_name .' DESC';
	return $this->get_rows_by_sql( $sql, $limit, $offset, $key );
}

function get_rows_by_where( $where, $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '.$this->_table;
	$sql .= ' WHERE '. $where;
	$sql .= ' ORDER BY '. $this->_id_name .' ASC';
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

function get_rows_by_orderby( $orderby, $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '.$this->_table;
	$sql .= ' ORDER BY '. $orderby;
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

function get_rows_by_where_orderby( $where, $orderby, $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '.$this->_table;
	$sql .= ' WHERE '. $where;
	$sql .= ' ORDER BY '. $orderby;
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

function get_rows_by_groupby_orderby( $groupby, $orderby, $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '. $this->_table;
	$sql .= ' GROUP BY '.$groupby;
	$sql .= ' ORDER BY '.$orderby;
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

//---------------------------------------------------------
// id array
//---------------------------------------------------------
function get_id_array_by_where( $where, $limit=0, $offset=0 )
{
	$sql  = 'SELECT '. $this->_id_name .' FROM '.$this->_table;
	$sql .= ' WHERE '. $where;
	$sql .= ' ORDER BY '. $this->_id_name .' ASC';
	return $this->get_first_rows_by_sql( $sql, $limit, $offset );
}

function get_id_array_by_where_orderby( $where, $orderby, $limit=0, $offset=0 )
{
	$sql  = 'SELECT '. $this->_id_name .' FROM '.$this->_table;
	$sql .= ' WHERE '. $where;
	$sql .= ' ORDER BY '. $orderby;
	return $this->get_first_rows_by_sql( $sql, $limit, $offset );
}

//---------------------------------------------------------
// cached
//---------------------------------------------------------
function get_cached_row_by_id( $id )
{
	if ( isset( $this->_cached[ $id ] ) ) {
		return  $this->_cached[ $id ];
	}

	$row = $this->get_row_by_id( $id );
	if ( is_array($row) ) {
		$this->_cached [$id ] = $row;
		return $row;
	}

	return null;
}

function get_cached_value_by_id_name( $id, $name, $flag_sanitize=false )
{
	$row = $this->get_cached_row_by_id( $id );
	if ( isset( $row[ $name ] ) ) {
		$val = $row[ $name ];
		if ( $flag_sanitize ) {
			$val = $this->sanitize( $val );
		}
		return $val;
	}
	return null;
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
function get_count_by_sql( $sql )
{
	return intval( $this->get_first_row_by_sql( $sql ) );
}

function get_first_row_by_sql( $sql )
{
	$res = $this->query($sql);
	if ( !$res ) { return false; }

	$row = $this->_db->fetchRow( $res );
	if ( isset( $row[0] ) ) {
		return $row[0];
	}

	return false;
}

function get_row_by_sql( $sql )
{
	$res = $this->query( $sql );
	if ( !$res ) { return false; }

	$row = $this->_db->fetchArray($res);
	return $row; 
}

function get_rows_by_sql( $sql, $limit=0, $offset=0, $key=null )
{
	$arr = array();

	$res = $this->query( $sql, $limit, $offset );
	if ( !$res ) { return false; }

	while ( $row = $this->_db->fetchArray($res) ) 
	{
		if ( $key && isset( $row[ $key ] ) ) {
			$arr[ $row[ $key ] ] = $row;
		} else {
			$arr[] = $row;
		}
	}
	return $arr; 
}

function get_first_rows_by_sql( $sql, $limit=0, $offset=0 )
{
	$res = $this->query( $sql, $limit, $offset );
	if ( !$res ) { return false; }

	$arr = array();

	while ( $row = $this->_db->fetchRow($res) ) {
		$arr[] = $row[0];
	}
	return $arr;
}

function query( $sql, $limit=0, $offset=0, $force=false )
{
	if ( $force ) {
		return $this->queryF( $sql, $limit, $offset );
	}

	if ( $this->_DEBUG_SQL ) {
		echo $this->sanitize( $sql ) .': limit='. $limit .' :offset='. $offset. "<br />\n";
	}

	$res = $this->_db->query( $sql, intval($limit), intval($offset) );
	if ( !$res ) {
		$error = $this->_db->error();
		if ( empty($error) ) {
			$error = 'Database update not allowed during processing of a GET request';
		}
		$this->set_error( $error );

		if ( $this->_DEBUG_ERROR ) {
			echo $this->highlight( $this->sanitize( $error ) )."<br />\n";
		}
	}
	return $res;
}

function queryF( $sql, $limit=0, $offset=0 )
{
	if ( $this->_DEBUG_SQL ) {
		echo $this->sanitize( $sql ) .': limit='. $limit .' :offset='. $offset. "<br />\n";
	}

	$res = $this->_db->queryF( $sql, intval($limit), intval($offset) );
	if ( !$res ) {
		$error = $this->_db->error();
		$this->set_error( $error );

		if ( $this->_DEBUG_ERROR ) {
			echo $this->highlight( $this->sanitize( $error ) )."<br />\n";
		}
	}
	return $res;
}

function quote( $str )
{
	$str = "'". addslashes($str) ."'";
	return $str;
}

//----- class end -----
}

?>