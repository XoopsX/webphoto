<?php
// $Id: p2t_handler.php,v 1.2 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// webphoto_lib_handler -> webphoto_handler_base_ini
//---------------------------------------------------------

//=========================================================
// class webphoto_p2t_handler
//=========================================================
class webphoto_p2t_handler extends webphoto_handler_base_ini
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_p2t_handler( $dirname, $trust_dirname )
{
	$this->webphoto_handler_base_ini( $dirname, $trust_dirname );
	$this->set_table_prefix_dirname( 'p2t' );
	$this->set_id_name( 'p2t_id' );

}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_p2t_handler( $dirname, $trust_dirname );
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
		'p2t_id'          => 0,
		'p2t_time_create' => $time_create,
		'p2t_time_update' => $time_update,
		'p2t_photo_id'    => 0,
		'p2t_tag_id'      => 0,
		'p2t_uid'         => 0,
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

	$sql .= 'p2t_time_create, ';
	$sql .= 'p2t_time_update, ';
	$sql .= 'p2t_photo_id, ';
	$sql .= 'p2t_tag_id, ';
	$sql .= 'p2t_uid ';

	$sql .= ') VALUES ( ';

	$sql .= intval($p2t_time_create).', ';
	$sql .= intval($p2t_time_update).', ';
	$sql .= intval($p2t_photo_id).', ';
	$sql .= intval($p2t_tag_id).', ';
	$sql .= intval($p2t_uid).' ';

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

	$sql .= 'p2t_time_create='.intval($p2t_time_create).', ';
	$sql .= 'p2t_time_update='.intval($p2t_time_update).', ';
	$sql .= 'p2t_photo_id='.intval($p2t_photo_id).', ';
	$sql .= 'p2t_tag_id='.intval($p2t_tag_id).', ';
	$sql .= 'p2t_uid='.intval($p2t_uid).' ';

	$sql .= 'WHERE p2t_id='.intval($p2t_id);

	return $this->query( $sql );
}

//---------------------------------------------------------
// delete
//---------------------------------------------------------
function delete_by_photoid( $photo_id )
{
	$sql  = 'DELETE FROM '. $this->_table;
	$sql .= ' WHERE p2t_photo_id='. intval($photo_id);
	return $this->query( $sql );
}

function delete_by_photoid_tagid_array( $photo_id, $tag_id_array )
{
	$where = $this->build_sql_tagid_in_array( $tag_id_array );
	if ( !$where ) {
		return true;	// no action
	}

	$sql  = 'DELETE FROM '. $this->_table;
	$sql .= ' WHERE p2t_photo_id='.intval($photo_id);
	$sql .= ' AND '.$where;
	return $this->query( $sql );
}

function delete_by_photoid_uid_tagid_array( $photo_id, $uid, $tag_id_array )
{
	$where = $this->build_sql_tagid_in_array( $tag_id_array );
	if ( !$where ) {
		return true;	// no action
	}

	$sql  = 'DELETE FROM '. $this->_table;
	$sql .= ' WHERE p2t_photo_id='.intval($photo_id);
	$sql .= ' AND p2t_uid='.intval($uid);
	$sql .= ' AND '.$where;
	return $this->query( $sql );
}

function build_sql_tagid_in_array( $tag_id_array )
{
	if ( !is_array($tag_id_array) || !count($tag_id_array) ) {
		return false;
	}

	$in  = implode( ',', $tag_id_array );
	$sql = 'p2t_tag_id IN ('. $in .')';
	return $sql;
}

//---------------------------------------------------------
// get count
//---------------------------------------------------------
function get_count_by_photoid_tagid( $photo_id, $tag_id )
{
	$where  = 'p2t_photo_id='.intval($photo_id);
	$where .= ' AND p2t_tag_id='.intval($tag_id);
	return $this->get_count_by_where( $where );
}

//---------------------------------------------------------
// get id array
//---------------------------------------------------------
function get_tag_id_array_by_photoid( $photo_id, $limit=0, $offset=0 )
{
	$sql  = 'SELECT p2t_tag_id FROM '.$this->_table;
	$sql .= ' WHERE p2t_photo_id='.intval($photo_id);
	$sql .= ' ORDER BY p2t_id ASC';
	return $this->get_first_rows_by_sql( $sql, $limit, $offset );
}

function get_tag_id_array_by_photoid_uid( $photo_id, $uid, $limit=0, $offset=0 )
{
	$sql  = 'SELECT p2t_tag_id FROM '.$this->_table;
	$sql .= ' WHERE p2t_photo_id='.intval($photo_id);
	$sql .= ' AND   p2t_uid='.intval($uid);
	$sql .= ' ORDER BY p2t_id ASC';
	return $this->get_first_rows_by_sql( $sql, $limit, $offset );
}

function get_tag_id_array_by_photoid_without_uid( $photo_id, $uid, $limit=0, $offset=0 )
{
	$sql  = 'SELECT p2t_tag_id FROM '.$this->_table;
	$sql .= ' WHERE p2t_photo_id='.intval($photo_id);
	$sql .= ' AND   p2t_uid <> '.intval($uid);
	$sql .= ' ORDER BY p2t_id ASC';
	return $this->get_first_rows_by_sql( $sql, $limit, $offset );
}

// --- class end ---
}

?>