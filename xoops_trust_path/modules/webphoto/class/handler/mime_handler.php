<?php
// $Id: mime_handler.php,v 1.4 2008/08/27 23:05:57 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-24 K.OHWADA
// build_perms_array_to_str()
// 2008-07-01 K.OHWADA
// added mime_ffmpeg
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_mime_handler
//=========================================================
class webphoto_mime_handler extends webphoto_lib_handler
{
	var $_cached_ext_array = array();

	var $_SEPARATOR = '&';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_mime_handler( $dirname )
{
	$this->webphoto_lib_handler( $dirname );
	$this->set_table_prefix_dirname( 'mime' );
	$this->set_id_name( 'mime_id' );

	$constpref = strtoupper( '_P_' . $dirname. '_' ) ;
	$this->set_debug_sql_by_const_name(   $constpref.'DEBUG_SQL' );
	$this->set_debug_error_by_const_name( $constpref.'DEBUG_ERROR' );

}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_mime_handler( $dirname );
	}
	return $instance;
}


//---------------------------------------------------------
// create
//---------------------------------------------------------
function create( $flag_new= false )
{
	$time_create = 0;
	$time_update = 0;

	if ( $flag_new ) {
		$time = time();
		$time_create = $time;
		$time_update = $time;
	}

	$arr = array(
		'mime_id'        => 0,
		'mime_time_create'  => $time_create,
		'mime_time_update'  => $time_update,
		'mime_name'     => '',
		'mime_medium'   => '',
		'mime_ext'      => '',
		'mime_type'     => '',
		'mime_perms'    => '',
		'mime_ffmpeg'   => '',
	);

	return $arr;
}

//---------------------------------------------------------
// insert
//---------------------------------------------------------
function insert( $row )
{
	extract( $row ) ;

	$sql  = 'INSERT INTO '.$this->_table.' (';

	$sql .= 'mime_time_create, ';
	$sql .= 'mime_time_update, ';
	$sql .= 'mime_name, ';
	$sql .= 'mime_ext, ';
	$sql .= 'mime_medium, ';
	$sql .= 'mime_type, ';
	$sql .= 'mime_perms, ';
	$sql .= 'mime_ffmpeg ';

	$sql .= ') VALUES ( ';

	$sql .= intval($mime_time_create).', ';
	$sql .= intval($mime_time_update).', ';
	$sql .= $this->quote($mime_name).', ';
	$sql .= $this->quote($mime_ext).', ';
	$sql .= $this->quote($mime_medium).', ';
	$sql .= $this->quote($mime_type).', ';
	$sql .= $this->quote($mime_perms).', ';
	$sql .= $this->quote($mime_ffmpeg).' ';

	$sql .= ')';

	$ret = $this->query( $sql );
	if ( !$ret ) { return false; }

	return $this->_db->getInsertId();
}

//---------------------------------------------------------
// update
//---------------------------------------------------------
function update( $row )
{
	extract( $row ) ;

	$sql  = 'UPDATE '.$this->_table.' SET ';

	$sql .= 'mime_time_create='.intval($mime_time_create).', ';
	$sql .= 'mime_time_update='.intval($mime_time_update).', ';
	$sql .= 'mime_name='.$this->quote($mime_name).', ';
	$sql .= 'mime_ext='.$this->quote($mime_ext).', ';
	$sql .= 'mime_medium='.$this->quote($mime_medium).', ';
	$sql .= 'mime_type='.$this->quote($mime_type).', ';
	$sql .= 'mime_perms='.$this->quote($mime_perms).', ';
	$sql .= 'mime_ffmpeg='.$this->quote($mime_ffmpeg).' ';

	$sql .= 'WHERE mime_id='.intval($mime_id);

	return $this->query( $sql );
}

function update_admin_all( $mime_admin )
{
	$sql  = 'UPDATE '.$this->_table.' SET ';
	$sql .= 'mime_admin='.intval($mime_admin);

	return $this->query( $sql );
}

function update_user_all( $mime_user )
{
	$sql  = 'UPDATE '.$this->_table.' SET ';
	$sql .= 'mime_user='.intval($mime_user);

	return $this->query( $sql );
}

//---------------------------------------------------------
// get row
//---------------------------------------------------------
function get_row_by_ext( $ext )
{
	$sql  = 'SELECT * FROM '.$this->_table;
	$sql .= ' WHERE mime_ext='.$this->quote( $ext );
	return $this->get_row_by_sql( $sql );
}

function get_cached_row_by_ext( $ext )
{
	if ( isset( $this->_cached_ext_array[ $ext ] ) ) {
		return  $this->_cached_ext_array[ $ext ];
	}

	$row = $this->get_row_by_ext( $ext );
	if ( !is_array($row) ) {
		return false;
	}

	$this->_cached_ext_array[ $ext ] = $row ;
	return $row ;
}

//---------------------------------------------------------
// get rows
//---------------------------------------------------------
function get_rows_all_orderby_ext( $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '.$this->_table;
	$sql .= ' ORDER BY mime_ext ASC, mime_id ASC';
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

function get_rows_by_mygroups( $groups, $limit=0, $offset=0 )
{
	$arr = array();
	foreach ( $groups as $group ) {
		$like  = '%'. $this->_SEPARATOR . intval($group) . $this->_SEPARATOR . '%';
		$arr[] = 'mime_perms LIKE '. $this->quote($like) ;
	}
	$where = implode( ' OR ', $arr );
	return $this->get_rows_by_where( $where, $limit, $offset );
}

//---------------------------------------------------------
// build
//---------------------------------------------------------
function build_perms_array_to_str( $arr )
{
	if ( !is_array($arr) || !count($arr) ) {
		return null;
	}

// array -> &1&2&3&
	$utility_class =& webphoto_lib_utility::getInstance();
	$str = $utility_class->array_to_str( $arr, $this->_SEPARATOR );
	$ret = $this->build_perms_with_separetor( $str ) ;
	return $ret ;
}

function build_perms_with_separetor( $str )
{
// str -> &1&
	$ret = $this->_SEPARATOR . $str . $this->_SEPARATOR ;
	return $ret ;
}

function build_perms_row_to_array( $row )
{
	$utility_class =& webphoto_lib_utility::getInstance();
	return $utility_class->str_to_array( $row['mime_perms'], $this->_SEPARATOR );
}

// --- class end ---
}

?>